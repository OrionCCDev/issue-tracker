@extends('layouts.app')

@section('content')
<!--    container-fluid-fluid -->
<div class="   container-fluid-fluid-fluid mt-xl-50 mt-sm-30 mt-15">
    <!-- Title -->
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">Dashboard</h2>
            <p>Welcome back, {{ Auth::user()->name }}!</p>
        </div>
    </div>
    <!-- /Title -->

    <!-- Role-specific Alert -->
    @if(in_array(Auth::user()->role, ['o-admin', 'cm', 'gm']))
    <div class="alert alert-info mb-20">
        <strong>Notice:</strong> You are viewing the complete organization dashboard.
    </div>
    @else
    <div class="alert alert-light mb-20">
        <strong>Notice:</strong> You are viewing your personal project dashboard.
    </div>
    @endif
    <!-- /Role-specific Alert -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="col-lg-3 col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-5">
                                <div>
                                    <span class="d-block font-15 text-dark font-weight-500">Projects</span>
                                </div>
                                <div>
                                    <span class="badge badge-primary">{{ $totalProjects ?? 0 }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="d-block display-4 text-dark mb-5">{{ $totalProjects ?? 0 }}</span>
                                <small class="d-block">Total active projects</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-5">
                                <div>
                                    <span class="d-block font-15 text-dark font-weight-500">Open Issues</span>
                                </div>
                                <div>
                                    <span class="badge badge-primary">{{ $openIssues ?? 0 }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="d-block display-4 text-dark mb-5">{{ $openIssues ?? 0 }}</span>
                                <small class="d-block">Waiting for action</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-5">
                                <div>
                                    <span class="d-block font-15 text-dark font-weight-500">In Progress</span>
                                </div>
                                <div>
                                    <span class="badge badge-info">{{ $inProgressIssues ?? 0 }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="d-block display-4 text-dark mb-5">{{ $inProgressIssues ?? 0 }}</span>
                                <small class="d-block">Being worked on</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-5">
                                <div>
                                    <span class="d-block font-15 text-dark font-weight-500">Assigned to Me</span>
                                </div>
                                <div>
                                    <span class="badge badge-primary">{{ $myAssignedIssuesCount ?? 0 }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="d-block display-4 text-dark mb-5">{{ $myAssignedIssuesCount ?? 0 }}</span>
                                <small class="d-block">Issues assigned to you</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-8">
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Issues by Status</h5>
                <div class="row">
                    <div class="col-sm">
                        <div id="issues_by_status_chart" style="height: 300px;"></div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-xl-4">
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Issues by Priority</h5>
                <div class="row">
                    <div class="col-sm">
                        <div id="issues_by_priority_chart" style="height: 300px;"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row mb-15">
                    <div class="col-sm-6">
                        @if(in_array(Auth::user()->role, ['o-admin', 'cm', 'gm']))
                            <h5 class="hk-sec-title">All Projects</h5>
                        @else
                            <h5 class="hk-sec-title">My Projects</h5>
                        @endif
                    </div>
                    @if(in_array(Auth::user()->role, ['pm', 'cm', 'gm']))
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> Create New Project
                        </a>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @forelse($myProjects as $project)

                        <div class="col-xl-4 col-md-6 mb-20">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
                                            <span class="badge badge-soft-primary ml-10">{{ $project->code }}</span>
                                        </h5>
                                        <p class="card-text">
                                            {{ \Illuminate\Support\Str::limit($project->description, 100) }}
                                        </p>
                                        <div class="d-flex justify-content-between mt-15">
                                            <div>
                                                <span class="text-muted">All Issues: </span>
                                                <span class="badge badge-primary">{{ $project->issues->count() }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted">Resolved: </span>
                                                <span class="badge badge-success">{{ $project->issues->whereIn('status', ['resolved', 'closed'])->count() }}</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-sm mt-10">
                                            @php
                                                $total = $project->issues->count();
                                                $completed = $project->issues->whereIn('status', ['resolved', 'closed'])->count();
                                                $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-10">
                                            <small class="text-muted">{{ $percentage }}% complete</small>
                                            <div>
                                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-xs btn-primary">View</a>
                                                <a href="{{ route('projects.issues.create', $project->id) }}" class="btn btn-xs btn-info">Add Issue</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    @empty
                        <div class="col-12">
                            <div class="alert alert-light">
                                You are not assigned to any projects yet.
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->

    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <section class="hk-sec-wrapper">
                <div class="row mb-15">
                    <div class="col-sm-6">
                        <h5 class="hk-sec-title">My Assigned Issues</h5>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('issues.index', ['assigned_to' => Auth::id()]) }}" class="btn btn-sm btn-outline-primary">
                            View All My Issues
                        </a>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($myAssignedIssues as $issue)
                                        <tr>
                                            <td>
                                                <a href="{{ route('projects.issues.show', [$issue->project_id, $issue->id]) }}">
                                                    {{ $issue->title }}
                                                </a>
                                            </td>
                                            <td>{{ $issue->project->name }}</td>
                                            <td>
                                                @switch($issue->status)
                                                    @case('open')
                                                        <span class="badge badge-primary">Open</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge badge-info">In Progress</span>
                                                        @break
                                                    @case('review')
                                                        <span class="badge badge-warning">Review</span>
                                                        @break
                                                    @case('resolved')
                                                        <span class="badge badge-success">Resolved</span>
                                                        @break
                                                    @case('closed')
                                                        <span class="badge badge-secondary">Closed</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-light">{{ $issue->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($issue->priority)
                                                    @case('low')
                                                        <span class="badge badge-soft-success">Low</span>
                                                        @break
                                                    @case('medium')
                                                        <span class="badge badge-soft-primary">Medium</span>
                                                        @break
                                                    @case('high')
                                                        <span class="badge badge-soft-warning">High</span>
                                                        @break
                                                    @case('critical')
                                                        <span class="badge badge-soft-danger">Critical</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-soft-secondary">{{ $issue->priority }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No issues assigned to you</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-3">
                            {{ $myAssignedIssues->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{--  <div class="col-12">
            <section class="hk-sec-wrapper">
                <div class="row mb-15">
                    <div class="col-sm-6">
                        <h5 class="hk-sec-title">Project Issues</h5>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('issues.my-issues') }}" class="btn btn-sm btn-outline-primary">
                            View All Project Issues
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Get project issues, excluding assigned issues to avoid duplication
                                            $myProjectIssues = collect();
                                            foreach($myProjects as $project) {
                                                $projectIssues = $project->issues()->latest()->take(3)->get();
                                                $myProjectIssues = $myProjectIssues->concat($projectIssues);
                                            }
                                            $myProjectIssues = $myProjectIssues->unique('id')->take(5);
                                        @endphp

                                        @forelse($myProjectIssues as $issue)
                                        <tr>
                                            <td>
                                                <a href="{{ route('projects.issues.show', [$issue->project_id, $issue->id]) }}">
                                                    {{ $issue->title }}
                                                </a>
                                            </td>
                                            <td>{{ $issue->project->name }}</td>
                                            <td>
                                                @switch($issue->status)
                                                    @case('open')
                                                        <span class="badge badge-primary">Open</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge badge-info">In Progress</span>
                                                        @break
                                                    @case('review')
                                                        <span class="badge badge-warning">Review</span>
                                                        @break
                                                    @case('resolved')
                                                        <span class="badge badge-success">Resolved</span>
                                                        @break
                                                    @case('closed')
                                                        <span class="badge badge-secondary">Closed</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-light">{{ $issue->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($issue->priority)
                                                    @case('low')
                                                        <span class="badge badge-soft-success">Low</span>
                                                        @break
                                                    @case('medium')
                                                        <span class="badge badge-soft-primary">Medium</span>
                                                        @break
                                                    @case('high')
                                                        <span class="badge badge-soft-warning">High</span>
                                                        @break
                                                    @case('critical')
                                                        <span class="badge badge-soft-danger">Critical</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-soft-secondary">{{ $issue->priority }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No project issues found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>  --}}
    </div>
    <!-- /Row -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-6">
            <section class="hk-sec-wrapper">
                <div class="row mb-15">
                    <div class="col-sm-6">
                        <h5 class="hk-sec-title">Recent Activity</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="activity-wrap">
                            @forelse($recentActivities as $activity)
                                <div class="activity-item">
                                    <div class="media">
                                        <div class="media-img-wrap">
                                            <div class="avatar avatar-sm">
                                                <img src="{{ asset('assets/images/users/' . $activity->user->image_path) }}"
                                                    alt="user" class="avatar-img rounded-circle">
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <div>
                                                <span class="font-weight-500 text-dark">{{ $activity->user->name }}</span>
                                                {{ $activity->description }}
                                                @if($activity->subject_type == 'App\Models\Issue')
                                                    <a href="{{ route('projects.issues.show', [$activity->subject->project_id, $activity->subject_id]) }}">
                                                        {{ $activity->subject->title }}
                                                    </a>
                                                @elseif($activity->subject_type == 'App\Models\Project')
                                                    <a href="{{ route('projects.show', $activity->subject_id) }}">
                                                        {{ $activity->subject->name }}
                                                    </a>
                                                @elseif($activity->subject_type == 'App\Models\Comment')
                                                    <a href="{{ route('projects.issues.show', [$activity->subject->issue->project_id, $activity->subject->issue_id]) }}">
                                                        {{ $activity->subject->issue->title }}
                                                    </a>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @empty
                                <div class="alert alert-light">No recent activity</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->


</div>
<!-- /   container-fluid-fluid-fluid -->

@section('custom_js')
<script>
    $(document).ready(function() {
        // Issues by Status Chart
        if ($('#issues_by_status_chart').length > 0) {
            Morris.Bar({
                element: 'issues_by_status_chart',
                data: [
                    { status: 'Open', count: {{ $issuesByStatus['open'] }} },
                    { status: 'In Progress', count: {{ $issuesByStatus['in_progress'] }} },
                    { status: 'Review', count: {{ $issuesByStatus['review'] }} },
                    { status: 'Resolved', count: {{ $issuesByStatus['resolved'] }} },
                    { status: 'Closed', count: {{ $issuesByStatus['closed'] }} }
                ],
                xkey: 'status',
                ykeys: ['count'],
                labels: ['Count'],
                barColors: ['#3e8ef7'],
                hideHover: 'auto',
                gridLineColor: '#eef0f2',
                resize: true
            });
        }

        // Issues by Priority Chart
        if ($('#issues_by_priority_chart').length > 0) {
            Morris.Donut({
                element: 'issues_by_priority_chart',
                data: [
                    { label: 'Low', value: {{ $issuesByPriority['low'] }} },
                    { label: 'Medium', value: {{ $issuesByPriority['medium'] }} },
                    { label: 'High', value: {{ $issuesByPriority['high'] }} },
                    { label: 'Critical', value: {{ $issuesByPriority['critical'] }} }
                ],
                colors: ['#22af47', '#3e8ef7', '#f2a654', '#f83f37'],
                resize: true,
                formatter: function(value) { return value + ' issues' }
            });
        }
    });
</script>
@endsection

@endsection
