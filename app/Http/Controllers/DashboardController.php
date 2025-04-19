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
        // Get total projects count
        $totalProjects = Project::count();

        // Get issues counts by status
        $openIssues = Issue::where('status', 'open')->count();
        $inProgressIssues = Issue::where('status', 'in_progress')->count();
        $resolvedIssues = Issue::where('status', 'resolved')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

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

        // Get user's assigned issues - using the assignees relationship instead of direct column
        $myAssignedIssues = Issue::whereHas('assignees', function($query) {
                $query->where('users.id', Auth::id());
            })
            ->with('project')
            ->latest()
            ->take(5)
            ->get();

        // Get recent activities
        $recentActivities = Activity::with(['user', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        // Get user's projects
        $myProjects = Project::whereHas('members', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['issues', 'members'])
        ->get();

        return view('dashboard', compact(
            'totalProjects',
            'openIssues',
            'inProgressIssues',
            'resolvedIssues',
            'issuesByStatus',
            'issuesByPriority',
            'myAssignedIssues',
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
