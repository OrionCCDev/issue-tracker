<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Project;
use App\Models\Issue;
use App\Models\User;
use App\Models\ProjectChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with(['project', 'creator'])
            ->latest()
            ->paginate(10);

        return view('meetings.index', compact('meetings'));
    }

    public function create(Project $project)
    {
        $projectIssues = $project->issues()->whereNotIn('status', ['resolved', 'closed'])->get();
        $projectMembers = $project->members;

        return view('meetings.create', compact('project', 'projectIssues', 'projectMembers'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'meeting_date' => 'required|date|after:now',
            'attendees' => 'required|array',
            'attendees.*' => 'exists:users,id',
            'issues' => 'array',
            'issues.*' => 'exists:issues,id',
        ]);

        $meeting = $project->meetings()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'meeting_date' => $validated['meeting_date'],
            'created_by' => Auth::id(),
        ]);

        // Add attendees
        $meeting->attendees()->attach($validated['attendees']);

        // Add issues to be discussed
        if (isset($validated['issues'])) {
            foreach ($validated['issues'] as $issueId) {
                $issue = Issue::find($issueId);
                $meeting->discussedIssues()->attach($issueId, [
                    'status_before' => $issue->status,
                ]);
            }
        }

        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Meeting created successfully.');
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['project', 'creator', 'attendees', 'discussedIssues', 'projectChanges']);

        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $projectIssues = $meeting->project->issues()->whereNotIn('status', ['resolved', 'closed'])->get();
        $projectMembers = $meeting->project->members;

        return view('meetings.edit', compact('meeting', 'projectIssues', 'projectMembers'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'meeting_date' => 'required|date|after:now',
            'attendees' => 'required|array',
            'attendees.*' => 'exists:users,id',
            'issues' => 'array',
            'issues.*' => 'exists:issues,id',
        ]);

        $meeting->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'meeting_date' => $validated['meeting_date'],
        ]);

        // Sync attendees
        $meeting->attendees()->sync($validated['attendees']);

        // Sync issues
        if (isset($validated['issues'])) {
            $currentIssues = $meeting->discussedIssues()->pluck('issues.id')->toArray();
            $newIssues = array_diff($validated['issues'], $currentIssues);

            foreach ($newIssues as $issueId) {
                $issue = Issue::find($issueId);
                $meeting->discussedIssues()->attach($issueId, [
                    'status_before' => $issue->status,
                ]);
            }
        }

        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Meeting updated successfully.');
    }

    public function updateStatus(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $meeting->update($validated);

        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Meeting status updated successfully.');
    }

    public function updateIssueStatus(Request $request, Meeting $meeting, Issue $issue)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $meeting->discussedIssues()->updateExistingPivot($issue->id, [
            'status_after' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        // Update the issue status
        $issue->update(['status' => $validated['status']]);

        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Issue status updated successfully.');
    }

    public function recordProjectChange(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'field_name' => 'required|string',
            'old_value' => 'nullable|string',
            'new_value' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        ProjectChange::create([
            'meeting_id' => $meeting->id,
            'project_id' => $meeting->project_id,
            'field_name' => $validated['field_name'],
            'old_value' => $validated['old_value'],
            'new_value' => $validated['new_value'],
            'changed_by' => Auth::id(),
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Project change recorded successfully.');
    }
}
