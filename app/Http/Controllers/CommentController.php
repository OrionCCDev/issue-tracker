<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class CommentController extends Controller
{
    public function store(Request $request, Project $project, Issue $issue)
    {
        $request->validate([
            'description' => 'required|string'
        ]);

        $comment = $issue->comments()->create([
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        $comment->load('user');

        // Get all users to notify
        $usersToNotify = collect();

        // Get all admin users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'o-admin')->get());

        // Get all GM users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'gm')->get());

        // Get all CM users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'cm')->get());

        // Get project manager
        if ($project->manager_id != Auth::id()) {
            $usersToNotify->push($project->manager);
        }

        // Get all assignees
        $usersToNotify = $usersToNotify->merge($issue->assignees);

        // Get issue creator if different from comment author
        if ($issue->created_by != Auth::id()) {
            $creator = User::find($issue->created_by);
            if ($creator) {
                $usersToNotify->push($creator);
            }
        }

        // Remove duplicates and the comment author
        $usersToNotify = $usersToNotify->unique('id')->where('id', '!=', Auth::id());

        // Send notifications
        NotificationService::notifyMany(
            $usersToNotify,
            'new_comment',
            $issue,
            [
                'title' => 'New Comment on Issue',
                'message' => Auth::user()->name . ' commented on issue "' . $issue->title . '"',
                'url' => route('projects.issues.show', [$project->id, $issue->id]) . '#comments'
            ]
        );

        if ($request->ajax()) {
            return view('issues.comment-item', compact('comment'));
        }

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function edit(Project $project, Issue $issue, Comment $comment)
    {
        // Ensure the comment belongs to the issue
        if ($comment->issue_id !== $issue->id) {
            abort(404);
        }

        return view('comments.edit', compact('project', 'issue', 'comment'));
    }

    public function update(Request $request, Project $project, Issue $issue, Comment $comment)
    {
        // Check if the user is the comment author
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'description' => 'required|string'
        ]);

        $comment->update([
            'description' => $request->description
        ]);

        if ($request->ajax()) {
            return response()->json([
                'content' => $comment->description
            ]);
        }

        return redirect()->back()->with('success', 'Comment updated successfully');
    }

    public function destroy(Request $request, Project $project, Issue $issue, Comment $comment)
    {
        // Check if the user is the comment author
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}
