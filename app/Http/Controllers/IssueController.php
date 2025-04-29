<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Issue;
use App\Models\Project;
use App\Events\IssueCreated;
use App\Events\IssueUpdated;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\IssueHistory;

class IssueController extends Controller
{
    /**
     * Get the count of pending issues (issues that are open or in progress)
     */
    public static function getPendingIssuesCount()
    {
        return Issue::whereIn('status', ['open', 'in_progress'])->count();
    }

    /**
     * Get the count of unread issues
     */
    public static function getUnreadIssuesCount()
    {
        return Issue::where('is_read', false)->count();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Issue::query()->with(['creator', 'assignees', 'project']);
        $viewMode = $request->has('view') && $request->view === 'split' ? 'split' : 'standard';

        // GM and CM can see all issues
        if (in_array($user->role, ['gm', 'cm', 'o-admin'])) {
            // No additional filtering needed
        }
        // PMs can only see issues from their projects
        elseif ($user->role === 'pm') {
            // Get projects managed by this PM
            $managedProjectIds = $user->managedProjects()->pluck('id')->toArray();

            // Include projects they're a member of
            $memberProjectIds = $user->projects()->pluck('projects.id')->toArray();

            // Combine both arrays and get unique values
            $accessibleProjectIds = array_unique(array_merge($managedProjectIds, $memberProjectIds));

            $query->whereIn('project_id', $accessibleProjectIds);
        }
        // Other roles can only see issues from projects they're a member of
        else {
            $memberProjectIds = $user->projects()->pluck('projects.id')->toArray();
            $query->whereIn('project_id', $memberProjectIds);
        }

        // Apply filters if present
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('assigned_to') && $request->assigned_to) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereDoesntHave('assignees');
            } else {
                $query->whereHas('assignees', function($q) use ($request) {
                    $q->where('user_id', $request->assigned_to);
                });
            }
        }

        $issues = $query->latest()->paginate(15);

        // Get the appropriate list of projects based on user role
        if (in_array($user->role, ['gm', 'cm', 'o-admin'])) {
            $projects = Project::all();
        } elseif ($user->role === 'pm') {
            $managedProjects = $user->managedProjects()->get();
            $memberProjects = $user->projects()->get();
            $projects = $managedProjects->merge($memberProjects)->unique('id');
        } else {
            $projects = $user->projects()->get();
        }

        $users = User::all();

        return view('issues.index', compact('issues', 'projects', 'users', 'viewMode'));
    }

    public function projectIssues(Project $project)
    {
        return $this->index(request());
    }

    public function createProjectIssue(Project $project)
    {
        $project->load('manager');
        $members = User::all();
        return view('issues.create', compact('project', 'members'));
    }

    public function create(Request $request)
    {
        $project = null;

        // Check if project_id is provided as a query parameter
        if ($request->has('project')) {
            $project = Project::with('manager')->findOrFail($request->project);
        }

        $members = User::all();

        return view('issues.create', compact('project', 'members'));
    }

    public function store(Request $request, Project $project = null)
    {
        // Debug logging
        Log::info('Issue creation request data:', [
            'all_request_data' => $request->all(),
            'assigned_to' => $request->input('assigned_to'),
            'is_array' => is_array($request->input('assigned_to')),
            'count' => is_array($request->input('assigned_to')) ? count($request->input('assigned_to')) : 0
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => $project ? 'nullable' : 'required|exists:projects,id',
            'status' => 'required|in:open,in_progress,review,resolved,closed',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
            'target_resolution_date' => 'nullable|date',
            'actual_resolution_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Set project_id if a Project is injected
        if ($project) {
            $validated['project_id'] = $project->id;
        }

        // Create the issue with the creator
        $validated['created_by'] = Auth::id();
        $validated['is_read'] = false; // Mark as unread for notifications

        $issue = Issue::create($validated);

        // Load the project relationship
        $issue->load('project');

        // Sync assignees if there are any
        if (!empty($validated['assigned_to'])) {
            $issue->assignees()->sync($validated['assigned_to']);
        }

        // Get all users to notify
        $usersToNotify = collect();

        // Get all admin users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'o-admin')->get());

        // Get all GM users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'gm')->get());

        // Get all CM users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'cm')->get());

        // Get project manager
        $usersToNotify->push($issue->project->manager);

        // Get all assignable users for this issue
        $usersToNotify = $usersToNotify->merge($issue->assignees);

        // Remove duplicates and the issue creator
        $usersToNotify = $usersToNotify->unique('id')->where('id', '!=', Auth::id());

        // Send notifications
        NotificationService::notifyMany(
            $usersToNotify,
            'issue_created',
            $issue,
            [
                'title' => 'New Issue Created',
                'message' => Auth::user()->name . ' created a new issue "' . $issue->title . '"',
                'url' => route('projects.issues.show', [$issue->project_id, $issue->id])
            ]
        );

        // Create initial history entry
        IssueHistory::create([
            'issue_id' => $issue->id,
            'updated_by' => Auth::id(),
            'title' => $issue->title,
            'description' => $issue->description,
            'priority' => $issue->priority,
            'status' => $issue->status,
            'target_resolution_date' => $issue->target_resolution_date,
            'actual_resolution_date' => $issue->actual_resolution_date,
            'notes' => $issue->notes,
            'changes' => json_encode(['initial_creation' => true])
        ]);

        // Broadcast to all users through the event
        try {
            event(new IssueCreated($issue->load('creator', 'assignees')));
        } catch (\Exception $e) {
            Log::error('Failed to broadcast issue creation: ' . $e->getMessage());
        }

        if ($project) {
            return redirect()
                ->route('projects.issues.show', ['project' => $project, 'issue' => $issue->id])
                ->with('success', 'Issue created successfully.');
        } else {
            return redirect()
                ->route('issues.show', $issue->id)
                ->with('success', 'Issue created successfully.');
        }
    }

    public function getIssue(Issue $issue)
    {
        $issue->load(['assignees', 'project', 'comments.user']);

        return response()->json([
            'success' => true,
            'issue' => $issue
        ]);
    }

    public function storeProjectIssue(Request $request, Project $project)
    {
        // Debug logging
        Log::info('Issue creation via project route:', [
            'all_request_data' => $request->all(),
            'assigned_to' => $request->input('assigned_to'),
            'is_array' => is_array($request->input('assigned_to')),
            'count' => is_array($request->input('assigned_to')) ? count($request->input('assigned_to')) : 0
        ]);

        return $this->store($request, $project);
    }

    public function show($issueOrProject, $issue = null)
    {
        // If $issueOrProject is a Project and $issue is provided, we're in the project.issue.show route
        if ($issueOrProject instanceof Project && $issue) {
            $project = $issueOrProject;
            // $issue is the ID in this case, so we need to find the actual Issue
            $issue = Issue::findOrFail($issue);
        }
        // If $issueOrProject is an Issue, we're in the issue.show route
        elseif ($issueOrProject instanceof Issue) {
            $issue = $issueOrProject;
            $project = $issue->project;
        }
        // If $issueOrProject is an ID (string/int), we're in the issue.show route
        else {
            $issue = Issue::findOrFail($issueOrProject);
            $project = $issue->project;
        }

        // Mark the issue as read when viewed
        if (!$issue->is_read) {
            $issue->update(['is_read' => true]);
        }

        // Load comments with eager loading and debug
        try {
            $issue->load(['comments.user', 'creator', 'assignees', 'project.manager']);
            Log::info('Comments loaded for issue ' . $issue->id . ': ' . $issue->comments->count());
            Log::info('Comments data: ' . json_encode($issue->comments->toArray()));
            Log::info('Issue data: ' . json_encode($issue->toArray()));
            
            // Debug the issue ID and project ID
            Log::info('Issue ID: ' . $issue->id);
            Log::info('Project ID: ' . $issue->project_id);
            
            // Debug the comments relationship
            Log::info('Comments relationship exists: ' . ($issue->relationLoaded('comments') ? 'true' : 'false'));
            Log::info('Comments count from relationship: ' . $issue->comments()->count());
        } catch (\Exception $e) {
            Log::error('Error loading comments for issue ' . $issue->id . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return view('issues.show', compact('issue', 'project'));
    }

    public function edit(Issue $issue)
    {
        $users = User::all();
        $issue->load('project.manager');
        return view('issues.edit', compact('issue', 'users'));
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:open,in_progress,review,resolved,closed',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
            'target_resolution_date' => 'nullable|date',
            'actual_resolution_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Save previous assignees to compare
        $previousAssignees = $issue->assignees()->pluck('user_id')->toArray();

        // Track changes for history
        $changes = [];
        foreach ($validated as $key => $value) {
            // Skip assignees as they're handled separately
            if ($key === 'assigned_to') {
                continue;
            }

            // Special handling for date fields
            if (in_array($key, ['target_resolution_date', 'actual_resolution_date'])) {
                $oldValue = $issue->{$key};

                // Standardize empty values for comparison
                if (empty($oldValue) && empty($value)) {
                    // Both are empty, no change
                    continue;
                }

                // Format dates for comparison if they exist
                $oldFormatted = !empty($oldValue) ? ($oldValue instanceof \Carbon\Carbon ? $oldValue->format('Y-m-d') : date('Y-m-d', strtotime($oldValue))) : null;
                $newFormatted = !empty($value) ? date('Y-m-d', strtotime($value)) : null;

                if ($oldFormatted !== $newFormatted) {
                    $changes[$key] = [
                        'old' => $oldFormatted,
                        'new' => $newFormatted
                    ];
                }
            }
            // Regular field comparison
            else if ($issue->{$key} != $value) {
                $changes[$key] = [
                    'old' => $issue->{$key},
                    'new' => $value
                ];
            }
        }

        // Update the issue
        $issue->update($validated);

        // Sync assignees
        if (isset($validated['assigned_to'])) {
            $issue->assignees()->sync($validated['assigned_to']);
        }

        // Get all users to notify
        $usersToNotify = collect();

        // Get all admin users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'o-admin')->get());

        // Get all GM users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'gm')->get());

        // Get all CM users
        $usersToNotify = $usersToNotify->merge(User::where('role', 'cm')->get());

        // Get project manager
        $usersToNotify->push($issue->project->manager);

        // Get all assignable users for this issue
        $usersToNotify = $usersToNotify->merge($issue->assignees);

        // Remove duplicates and the issue updater
        $usersToNotify = $usersToNotify->unique('id')->where('id', '!=', Auth::id());

        // Send notifications
        NotificationService::notifyMany(
            $usersToNotify,
            'issue_updated',
            $issue,
            [
                'title' => 'Issue Updated',
                'message' => Auth::user()->name . ' updated issue "' . $issue->title . '"',
                'url' => route('projects.issues.show', [$issue->project_id, $issue->id])
            ]
        );

        // Create history entry if there are changes
        if (!empty($changes)) {
            IssueHistory::create([
                'issue_id' => $issue->id,
                'updated_by' => Auth::id(),
                'title' => $issue->title,
                'description' => $issue->description,
                'priority' => $issue->priority,
                'status' => $issue->status,
                'target_resolution_date' => $issue->target_resolution_date,
                'actual_resolution_date' => $issue->actual_resolution_date,
                'notes' => $issue->notes,
                'changes' => json_encode($changes)
            ]);
        }

        // Broadcast updates
        try {
            event(new IssueUpdated($issue->load('creator', 'assignees')));
        } catch (\Exception $e) {
            Log::error('Failed to broadcast issue update: ' . $e->getMessage());
        }

        return redirect()
            ->route('issues.show', $issue->id)
            ->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect()->route('issues.index')
            ->with('success', 'Issue deleted successfully.');
    }

    /**
     * Display issues from the authenticated user's projects
     */
    public function myIssues(Request $request)
    {
        $user = Auth::user();

        // Get projects the user is a member of
        $myProjects = $user->projects()->get();
        $myProjectIds = $myProjects->pluck('id')->toArray();

        // If user is a project manager, also include projects they manage
        if ($user->role === 'pm') {
            $managedProjects = $user->managedProjects()->get();
            $myProjects = $myProjects->merge($managedProjects)->unique('id');
            $myProjectIds = $myProjects->pluck('id')->toArray();
        }

        // Build query for issues from user's projects that are assigned to the user
        $query = Issue::query()->with(['creator', 'assignees', 'project'])
            ->whereIn('project_id', $myProjectIds)
            ->whereHas('assignees', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Apply filters if present
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Apply search if present
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $myIssues = $query->latest()->paginate(15);

        return view('issues.my-issues', compact('myIssues', 'myProjects'));
    }

    /**
     * Get issue details for modal display via AJAX
     */
    public function getIssueDetails(Issue $issue)
    {
        try {
            // Mark the issue as read when viewed in the modal
            if (!$issue->is_read) {
                $issue->update(['is_read' => true]);
            }

            $issue->load(['comments.user', 'assignees', 'project', 'history.updatedBy']);
            $users = User::all();
            return view('issues.details-modal', compact('issue', 'users'));
        } catch (\Exception $e) {
            Log::error('Error in getIssueDetails: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'error' => 'An error occurred while loading issue details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update issue via AJAX
     */
    public function ajaxUpdate(Request $request, Issue $issue)
    {
        try {
            // Get all input data
            $data = $request->all();

            // If status is set to resolved or closed and actual_resolution_date is not set, set it to today
            if (in_array($data['status'] ?? '', ['resolved', 'closed']) && empty($data['actual_resolution_date'])) {
                $data['actual_resolution_date'] = now()->toDateString();
            }

            // Prepare history data before updating the issue
            $oldData = $issue->toArray();
            $changedFields = [];

            foreach ($data as $key => $value) {
                // Special handling for date fields
                if (in_array($key, ['target_resolution_date', 'actual_resolution_date'])) {
                    $oldValue = $oldData[$key];

                    // Standardize empty values for comparison
                    if (empty($oldValue) && empty($value)) {
                        // Both are empty, no change
                        continue;
                    }

                    // Format dates for comparison if they exist
                    $oldFormatted = !empty($oldValue) ? date('Y-m-d', strtotime($oldValue)) : null;
                    $newFormatted = !empty($value) ? date('Y-m-d', strtotime($value)) : null;

                    if ($oldFormatted !== $newFormatted) {
                        $changedFields[$key] = [
                            'old' => $oldFormatted,
                            'new' => $newFormatted
                        ];
                    }
                }
                // Regular field comparison
                else if (array_key_exists($key, $oldData) && $oldData[$key] != $value) {
                    $changedFields[$key] = [
                        'old' => $oldData[$key],
                        'new' => $value
                    ];
                }
            }

            // Save history only if there are actual changes
            if (!empty($changedFields)) {
                // Create issue history record
                IssueHistory::create([
                    'issue_id' => $issue->id,
                    'updated_by' => Auth::id(),
                    'title' => $oldData['title'],
                    'description' => $oldData['description'],
                    'priority' => $oldData['priority'],
                    'status' => $oldData['status'],
                    'target_resolution_date' => $oldData['target_resolution_date'],
                    'actual_resolution_date' => $oldData['actual_resolution_date'],
                    'notes' => $oldData['notes'],
                    'changes' => json_encode($changedFields)
                ]);
            }

            // Update the issue
            $issue->update($data);

            // Update assignees if provided
            if (isset($data['assignees'])) {
                // Use empty array for sync if assignees is an empty string
                $assignees = $data['assignees'] === '' ? [] : $data['assignees'];
                $issue->assignees()->sync($assignees);
            }

            // Load relationships
            $issue->load(['comments.user', 'assignees', 'project', 'history.updatedBy']);

            // Get all users to notify
            $usersToNotify = collect();

            // Get all admin users
            $usersToNotify = $usersToNotify->merge(User::where('role', 'o-admin')->get());

            // Get all GM users
            $usersToNotify = $usersToNotify->merge(User::where('role', 'gm')->get());

            // Get all CM users
            $usersToNotify = $usersToNotify->merge(User::where('role', 'cm')->get());

            // Get project manager
            if ($issue->project && $issue->project->manager_id != Auth::id()) {
                $usersToNotify->push($issue->project->manager);
            }

            // Get all assignees
            $usersToNotify = $usersToNotify->merge($issue->assignees);

            // Remove duplicates and the issue updater
            $usersToNotify = $usersToNotify->unique('id')->where('id', '!=', Auth::id());

            // Send notifications only if there are changes
            if (!empty($changedFields)) {
                NotificationService::notifyMany(
                    $usersToNotify,
                    'issue_updated',
                    $issue,
                    [
                        'title' => 'Issue Updated',
                        'message' => Auth::user()->name . ' updated issue "' . $issue->title . '"',
                        'url' => route('projects.issues.show', [$issue->project_id, $issue->id])
                    ]
                );
            }

            // Broadcast the update event
            event(new IssueUpdated($issue, $changedFields));

            return response()->json([
                'success' => true,
                'message' => 'Issue updated successfully',
                'issue' => $issue
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating issue: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating issue: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all unread issues as read
     */
    public function markAllRead()
    {
        Issue::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Get comments for an issue via AJAX
     */
    public function getComments(Issue $issue)
    {
        try {
            $issue->load(['comments.user']);
            return view('issues.comments-partial', compact('issue'));
        } catch (\Exception $e) {
            Log::error('Error in getComments: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while loading comments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get history for an issue via AJAX
     */
    public function getHistory(Issue $issue)
    {
        try {
            $issue->load(['history.updatedBy']);
            return view('issues.history-partial', compact('issue'));
        } catch (\Exception $e) {
            Log::error('Error in getHistory: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while loading history: ' . $e->getMessage()
            ], 500);
        }
    }
}
