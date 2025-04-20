<?php
// app/Http/Controllers/ProjectController.php
namespace App\Http\Controllers;

use App\Events\ProjectCreated;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class ProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin, GM and CM can see all projects
        if (in_array($user->role, ['o-admin', 'gm', 'cm'])) {
            $projects = Project::with('manager')
                ->withCount('issues')
                ->latest()
                ->paginate(10);
        }
        // PM can only see their own projects
        elseif ($user->role === 'pm') {
            $projects = Project::with('manager')
                ->where('manager_id', $user->id)
                ->withCount('issues')
                ->latest()
                ->paginate(10);
        }
        // Other roles can see projects they're members of
        else {
            $projects = $user->projects()
                ->with('manager')
                ->withCount('issues')
                ->latest()
                ->paginate(10);
        }

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $managers = User::where('role', 'pm')->get();
        return view('projects.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Information
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:projects',
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',

            // Status Information
            'variation_status' => 'nullable|string|max:255',
            'variation_number' => 'nullable|numeric',
            'eot_status' => 'nullable|string|max:255',
            'ncrs_status' => 'nullable|string|max:255',

            // Timeline Information
            'project_value' => 'nullable|numeric',
            'duration' => 'nullable|integer',
            'commencement_date' => 'nullable|date',
            'completion_date' => 'nullable|date',

            // Financial Information
            'total_billed' => 'nullable|numeric',
            'remaining_unbilled' => 'nullable|numeric',
            'planned_percentage' => 'nullable|numeric',
            'actual_percentage' => 'nullable|numeric',
            'variance_percentage' => 'nullable|numeric',

            // Time Tracking
            'time_elapsed' => 'nullable|string',
            'time_balance' => 'nullable|integer',

            // Current Invoice
            'current_invoice_status' => 'nullable|string|max:255',
            'current_invoice_value' => 'nullable|numeric',
            'current_invoice_month' => 'nullable|string|max:255',

            // Previous Invoice
            'previous_invoice_status' => 'nullable|string|max:255',
            'previous_invoice_value' => 'nullable|numeric',
            'previous_invoice_month' => 'nullable|string|max:255',

            // Expected Invoice
            'expected_invoice_date' => 'nullable|date',
            'expected_invoice_month' => 'nullable|string|max:255',
        ]);

        $project = Project::create($validated);
        $project->members()->attach($validated['manager_id']);

        // Refresh the model with relationships to ensure all data is loaded
        $project = Project::with('manager')->findOrFail($project->id);

        // Dispatch the project created event
        event(new ProjectCreated($project));

        // Get all users to notify
        $users = User::all();

        // Notify all users about the new project
        NotificationService::notifyMany(
            $users,
            'project_created',
            $project,
            [
                'title' => 'New Project Created',
                'message' => 'A new project "' . $project->name . '" has been created',
                'url' => route('projects.show', $project->id)
            ]
        );

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project, \Illuminate\Http\Request $request)
    {
        $user = Auth::user();

        // Check if PM has access to this project
        if ($user->role === 'pm' && $project->manager_id !== $user->id) {
            // Check if user is a member of this project
            if (!$project->members->contains($user)) {
                abort(403, 'You do not have access to this project.');
            }
        }

        $project->load([
            'manager',
            'issues' => function($query) {
                $query->latest();
            },
            'members'
        ]);

        $issuesByStatus = [
            'open' => $project->issues()->where('status', 'open')->count(),
            'in_progress' => $project->issues()->where('status', 'in_progress')->count(),
            'review' => $project->issues()->where('status', 'review')->count(),
            'resolved' => $project->issues()->where('status', 'resolved')->count(),
            'closed' => $project->issues()->where('status', 'closed')->count(),
        ];

        // Load issues for cards with filter parameters
        $query = $project->issues();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereDoesntHave('assignees');
            } else {
                $query->whereHas('assignees', function($q) use ($request) {
                    $q->where('users.id', $request->assigned_to);
                });
            }
        }

        $issues = $query->with(['assignees', 'comments.user'])->latest()->get();
        $users = \App\Models\User::all();
        $managers = \App\Models\User::where('role', 'pm')->get();

        return view('projects.show', compact('project', 'issuesByStatus', 'issues', 'users', 'managers'));
    }

    public function edit(Project $project)
    {
        $user = Auth::user();

        // Check if PM has access to this project
        if ($user->role === 'pm' && $project->manager_id !== $user->id) {
            abort(403, 'You do not have access to edit this project.');
        }

        $managers = User::where('role', 'pm')->get();
        return view('projects.edit', compact('project', 'managers'));
    }

    public function update(Request $request, Project $project)
    {
        $user = Auth::user();

        // Check if PM has access to this project
        if ($user->role === 'pm' && $project->manager_id !== $user->id) {
            abort(403, 'You do not have access to update this project.');
        }

        $validated = $request->validate([
            // Basic Information
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:projects,code,' . $project->id,
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',

            // Status Information
            'variation_status' => 'nullable|string|max:255',
            'variation_number' => 'nullable|numeric',
            'eot_status' => 'nullable|string|max:255',
            'ncrs_status' => 'nullable|string|max:255',

            // Timeline Information
            'project_value' => 'nullable|numeric',
            'duration' => 'nullable|integer',
            'commencement_date' => 'nullable|date',
            'completion_date' => 'nullable|date',

            // Financial Information
            'total_billed' => 'nullable|numeric',
            'remaining_unbilled' => 'nullable|numeric',
            'planned_percentage' => 'nullable|numeric',
            'actual_percentage' => 'nullable|numeric',
            'variance_percentage' => 'nullable|numeric',

            // Time Tracking
            'time_elapsed' => 'nullable|string',
            'time_balance' => 'nullable|integer',

            // Current Invoice
            'current_invoice_status' => 'nullable|string|max:255',
            'current_invoice_value' => 'nullable|numeric',
            'current_invoice_month' => 'nullable|string|max:255',

            // Previous Invoice
            'previous_invoice_status' => 'nullable|string|max:255',
            'previous_invoice_value' => 'nullable|numeric',
            'previous_invoice_month' => 'nullable|string|max:255',

            // Expected Invoice
            'expected_invoice_date' => 'nullable|date',
            'expected_invoice_month' => 'nullable|string|max:255',
        ]);

        // Update the project
        $project->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully'
            ]);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function members(Project $project)
    {
        $user = Auth::user();

        // Check if PM has access to this project
        if ($user->role === 'pm' && $project->manager_id !== $user->id) {
            abort(403, 'You do not have access to manage this project\'s members.');
        }

        $members = $project->members;
        $availableUsers = User::whereDoesntHave('projects', function($query) use ($project) {
            $query->where('project_id', $project->id);
        })->get();

        return view('projects.members', compact('project', 'members', 'availableUsers'));
    }

    public function addMember(Request $request, Project $project)
    {
        $user = Auth::user();

        // Check if PM has access to this project
        if ($user->role === 'pm' && $project->manager_id !== $user->id) {
            abort(403, 'You do not have access to add members to this project.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($project->members()->where('user_id', $validated['user_id'])->exists()) {
            return back()->with('error', 'User is already a member of this project.');
        }

        $project->members()->attach($validated['user_id']);
        return back()->with('success', 'Member added successfully.');
    }

    public function removeMember(Request $request, Project $project, User $user)
    {
        $currentUser = Auth::user();

        // Check if PM has access to this project
        if ($currentUser->role === 'pm' && $project->manager_id !== $currentUser->id) {
            abort(403, 'You do not have access to remove members from this project.');
        }

        if ($project->manager_id === $user->id) {
            return back()->with('error', 'Cannot remove the project manager.');
        }

        $project->members()->detach($user->id);
        return back()->with('success', 'Member removed successfully.');
    }

    // Method to render issues as a list (AJAX partial)
    public function issuesList(Project $project, Request $request)
    {
        $query = $project->issues();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereDoesntHave('assignees');
            } else {
                $query->whereHas('assignees', function($q) use ($request) {
                    $q->where('users.id', $request->assigned_to);
                });
            }
        }

        $issues = $query->with('assignees')->latest()->paginate(10);
        $users = User::all();

        return view('issues.index-partial', compact('project', 'issues', 'users'));
    }

    // Method to render issues as cards (AJAX partial)
    public function issuesCardsPartial(Project $project, Request $request)
    {
        $query = $project->issues();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereDoesntHave('assignees');
            } else {
                $query->whereHas('assignees', function($q) use ($request) {
                    $q->where('users.id', $request->assigned_to);
                });
            }
        }

        $issues = $query->with(['assignees', 'comments.user'])->latest()->get();
        $users = User::all();

        return view('projects.issues-cards-partial', compact('project', 'issues', 'users'));
    }

    // Method to render full issues cards page
    public function issuesCards(Project $project, Request $request)
    {
        $query = $project->issues();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereDoesntHave('assignees');
            } else {
                $query->whereHas('assignees', function($q) use ($request) {
                    $q->where('users.id', $request->assigned_to);
                });
            }
        }

        $issues = $query->with(['assignees', 'comments.user'])->latest()->get();
        $users = User::all();

        return view('projects.issues-cards', compact('project', 'issues', 'users'));
    }
}

