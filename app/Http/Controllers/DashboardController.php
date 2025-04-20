<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Issue;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->role;

        // Check if user has full access to all data
        $hasFullAccess = in_array($userRole, ['o-admin', 'cm', 'gm']);

        if ($hasFullAccess) {
            // Full access for admin, CM, and GM roles
            // Get total projects count
            $totalProjects = Project::count();

            // Get issues counts by status
            $openIssues = Issue::where('status', 'open')->count();
            $inProgressIssues = Issue::where('status', 'in_progress')->count();
            $resolvedIssues = Issue::where('status', 'resolved')
                ->where('updated_at', '>=', now()->subDays(30))
                ->count();

            // Count issues assigned to current user
            $myAssignedIssuesCount = Issue::whereHas('assignees', function($query) {
                    $query->where('users.id', Auth::id());
                })->count();

            // Get issues by status for the chart
            $issuesByStatus = [
                'open' => Issue::where('status', 'open')->count(),
                'in_progress' => Issue::where('status', 'in_progress')->count(),
                'review' => Issue::where('status', 'review')->count(),
                'resolved' => Issue::where('status', 'resolved')->count(),
                'closed' => Issue::where('status', 'closed')->count(),
            ];

            // Get issues by priority for the chart
            $issuesByPriority = [
                'low' => Issue::where('priority', 'low')->count(),
                'medium' => Issue::where('priority', 'medium')->count(),
                'high' => Issue::where('priority', 'high')->count(),
                'critical' => Issue::where('priority', 'critical')->count(),
            ];

            // Get all projects for admin views
            $myProjects = Project::with(['issues', 'members'])->get();

            // Get only issues assigned to this user
            $myAssignedIssues = Issue::whereHas('assignees', function($query) {
                    $query->where('users.id', Auth::id());
                })
                ->with('project')
                ->latest()
                ->take(5)
                ->get();

        } else {
            // Limited access for PM role - only see their projects and related issues
            // Get projects managed by this PM
            $managedProjectIds = $user->managedProjects()->pluck('id')->toArray();

            // Get projects where user is a member
            $memberProjectIds = $user->projects()->pluck('projects.id')->toArray();

            // Combine both arrays and get unique values
            $accessibleProjectIds = array_unique(array_merge($managedProjectIds, $memberProjectIds));

            // Get total projects count for this PM
            $totalProjects = count($accessibleProjectIds);

            // Get issues counts for this PM's projects
            $openIssues = Issue::whereIn('project_id', $accessibleProjectIds)
                ->where('status', 'open')
                ->count();

            $inProgressIssues = Issue::whereIn('project_id', $accessibleProjectIds)
                ->where('status', 'in_progress')
                ->count();

            $resolvedIssues = Issue::whereIn('project_id', $accessibleProjectIds)
                ->where('status', 'resolved')
                ->where('updated_at', '>=', now()->subDays(30))
                ->count();

            // Count issues assigned to current user
            $myAssignedIssuesCount = Issue::whereHas('assignees', function($query) {
                    $query->where('users.id', Auth::id());
                })->count();

            // Get issues by status for the chart (PM's projects only)
            $issuesByStatus = [
                'open' => Issue::whereIn('project_id', $accessibleProjectIds)->where('status', 'open')->count(),
                'in_progress' => Issue::whereIn('project_id', $accessibleProjectIds)->where('status', 'in_progress')->count(),
                'review' => Issue::whereIn('project_id', $accessibleProjectIds)->where('status', 'review')->count(),
                'resolved' => Issue::whereIn('project_id', $accessibleProjectIds)->where('status', 'resolved')->count(),
                'closed' => Issue::whereIn('project_id', $accessibleProjectIds)->where('status', 'closed')->count(),
            ];

            // Get issues by priority for the chart (PM's projects only)
            $issuesByPriority = [
                'low' => Issue::whereIn('project_id', $accessibleProjectIds)->where('priority', 'low')->count(),
                'medium' => Issue::whereIn('project_id', $accessibleProjectIds)->where('priority', 'medium')->count(),
                'high' => Issue::whereIn('project_id', $accessibleProjectIds)->where('priority', 'high')->count(),
                'critical' => Issue::whereIn('project_id', $accessibleProjectIds)->where('priority', 'critical')->count(),
            ];

            // Get PM's projects
            $myProjects = Project::whereIn('id', $accessibleProjectIds)
                ->with(['issues', 'members'])
                ->get();

            // Get only issues assigned to this PM and from their projects
            $myAssignedIssues = Issue::whereHas('assignees', function($query) {
                    $query->where('users.id', Auth::id());
                })
                ->with('project')
                ->latest()
                ->take(5)
                ->get();
        }

        // Get recent activities (filtered for PM role)
        if ($hasFullAccess) {
            $recentActivities = Activity::with(['user', 'subject'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            // For PM, only show activities related to their projects
            $recentActivities = Activity::with(['user', 'subject'])
                ->whereHasMorph('subject', 'App\Models\Project', function($query) use ($accessibleProjectIds) {
                    $query->whereIn('id', $accessibleProjectIds);
                })
                ->orWhereHasMorph('subject', 'App\Models\Issue', function($query) use ($accessibleProjectIds) {
                    $query->whereIn('project_id', $accessibleProjectIds);
                })
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'totalProjects',
            'openIssues',
            'inProgressIssues',
            'resolvedIssues',
            'issuesByStatus',
            'issuesByPriority',
            'myAssignedIssues',
            'myAssignedIssuesCount',
            'recentActivities',
            'myProjects'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
