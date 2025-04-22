@extends('layouts.app')

@section('content')
<!--  container-fluid -->
<div class=" container-fluid mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">My Project Issues</h2>
        </div>
        <div class="d-flex">
            <form action="{{ route('issues.my-issues') }}" method="GET" class="mr-10">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search my issues..."
                        value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Create New Issue
                </button>
                <div class="dropdown-menu">
                    @foreach($myProjects as $project)
                        <a class="dropdown-item" href="{{ route('projects.issues.create', $project) }}">{{ $project->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <section class="hk-sec-wrapper">
        <div class="row">
            <div class="col-sm">
                <form action="{{ route('issues.my-issues') }}" method="GET" class="form-inline">
                    <div class="form-group mb-2 mr-15">
                        <label for="project_filter" class="mr-2">Project:</label>
                        <select class="form-control form-control-sm" id="project_filter" name="project_id">
                            <option value="">All My Projects</option>
                            @foreach($myProjects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 mr-15">
                        <label for="status_filter" class="mr-2">Status:</label>
                        <select class="form-control form-control-sm" id="status_filter" name="status">
                            <option value="">All Statuses</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="form-group mb-2 mr-15">
                        <label for="priority_filter" class="mr-2">Priority:</label>
                        <select class="form-control form-control-sm" id="priority_filter" name="priority">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="assigned_filter" class="mr-2">Assigned To:</label>
                        <select class="form-control form-control-sm" id="assigned_filter" name="assigned_to">
                            <option value="">All Issues</option>
                            <option value="{{ Auth::id() }}" {{ request('assigned_to') == Auth::id() ? 'selected' : '' }}>
                                Assigned to Me
                            </option>
                            <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                        </select>
                    </div>

                    <div class="form-group mb-2 ml-15">
                        <button type="submit" class="btn btn-info btn-sm">Filter</button>
                        <a href="{{ route('issues.my-issues') }}" class="btn btn-light btn-sm ml-1">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Issues List -->
    <section class="hk-sec-wrapper">
        <h5 class="hk-sec-title">My Project Issues</h5>
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
                                    <th>Assigned To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myIssues as $issue)
                                <tr>
                                    <td>
                                        <a href="{{ route('projects.issues.show', [$issue->project_id, $issue->id]) }}">
                                            {{ $issue->title }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.show', $issue->project_id) }}">
                                            {{ $issue->project->name }}
                                        </a>
                                    </td>
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
                                    <td>
                                        @if($issue->assignees->count() > 0)
                                            @foreach($issue->assignees as $assignee)
                                                <span class="badge badge-soft-dark">{{ $assignee->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge badge-soft-secondary">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.issues.show', [$issue->project_id, $issue->id]) }}" class="btn btn-xs btn-primary">View</a>
                                        <a href="{{ route('projects.issues.edit', [$issue->project_id, $issue->id]) }}" class="btn btn-xs btn-secondary">Edit</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No issues found in your projects</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-20">
                    {{ $myIssues->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /  container-fluid -->
@endsection
