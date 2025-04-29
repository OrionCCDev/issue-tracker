@extends('layouts.app')

@section('content')
<!--  container-fluid -->
<div class=" container-fluid mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">Issues Management</h2>
        </div>
        <div class="d-flex">
            <form action="{{ route('issues.index') }}" method="GET" class="mr-10">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search issues..."
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
                    @foreach($projects as $project)
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
                <form action="{{ route('issues.index') }}" method="GET" class="form-inline">
                    <div class="form-group mb-2 mr-15">
                        <label for="project_filter" class="mr-2">Project:</label>
                        <select class="form-control form-control-sm" id="project_filter" name="project_id">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
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
                            <option value="">All Users</option>
                            <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-2 ml-15">
                        <button type="submit" class="btn btn-info btn-sm">Filter</button>
                        <a href="{{ route('issues.index') }}" class="btn btn-light btn-sm ml-1">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Issues List -->
    <section class="hk-sec-wrapper">
        <h5 class="hk-sec-title">
            @if(request('assigned_to') == Auth::id())
                @if(isset($viewMode) && $viewMode == 'split')
                    <span class="badge badge-info mr-2">Split View</span>
                @endif
                My Assigned Issues
            @else
                Issues List
            @endif
        </h5>
        <div class="row">
            <div class="col-sm">
                @if(isset($viewMode) && $viewMode == 'split')
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-15">My Assigned Issues</h6>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $assignedIssues = $issues->filter(function($issue) {
                                                return $issue->assignees->contains('id', Auth::id());
                                            });
                                        @endphp
                                        @forelse($assignedIssues as $issue)
                                        <tr>
                                            <td>
                                                <a href="{{ route('issues.show', $issue->id) }}">
                                                    {{ $issue->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('projects.show', ['project' => $issue->project_id]) }}">
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
                                                <a href="{{ route('issues.show', $issue->id) }}" class="btn btn-xs btn-primary">View</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No issues assigned to you</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-15">Project Issues</h6>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $projectIssues = $issues->reject(function($issue) {
                                                return $issue->assignees->contains('id', Auth::id());
                                            });
                                        @endphp
                                        @forelse($projectIssues as $issue)
                                        <tr>
                                            <td>
                                                <a href="{{ route('issues.show', $issue->id) }}">
                                                    {{ $issue->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('projects.show', ['project' => $issue->project_id]) }}">
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
                                                <a href="{{ route('issues.show', $issue->id) }}" class="btn btn-xs btn-primary">View</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No other project issues found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @else
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
                                    <th>Created</th>
                                    <th>Target Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues as $issue)
                                <tr>
                                    <td>
                                        <a href="{{ route('issues.show', $issue->id) }}">
                                            {{ $issue->title }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.show', ['project' => $issue->project_id]) }}">
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
                                        @if($issue->assignees->isNotEmpty())
                                            <div class="media align-items-center">
                                                <div class="media-img-wrap d-flex mr-10">
                                                    <div class="avatar avatar-xs">
                                                        <img src="{{ asset('storage/' . $issue->assignees->first()->image_path) }}"
                                                            alt="user" class="avatar-img rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <span class="d-block">{{ $issue->assignees->first()->name }}</span>
                                                    @if($issue->assignees->count() > 1)
                                                        <small class="text-muted">+{{ $issue->assignees->count() - 1 }} more</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $issue->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($issue->target_resolution_date)
                                            {{ \Carbon\Carbon::parse($issue->target_resolution_date)->format('d M Y') }}
                                            @if(\Carbon\Carbon::parse($issue->target_resolution_date)->isPast() && !in_array($issue->status, ['resolved', 'closed']))
                                                <span class="badge badge-danger">Overdue</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('issues.show', $issue->id) }}"
                                            class="btn btn-icon btn-icon-circle btn-info btn-icon-style-2 btn-sm">
                                            <span class="btn-icon-wrap"><i class="fa fa-eye"></i></span>
                                        </a>
                                        <a href="{{ route('issues.edit', $issue->id) }}"
                                            class="btn btn-icon btn-icon-circle btn-primary btn-icon-style-2 btn-sm">
                                            <span class="btn-icon-wrap"><i class="fa fa-pencil"></i></span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No issues found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                <div class="d-flex justify-content-end mt-20">
                    {{ $issues->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
<!-- / container-fluid -->

<!-- Toast Message -->
@if(session('success'))
@section('custom_js')
<script>
    $(document).ready(function() {
        $.toast({
            heading: 'Success',
            text: '{{ session("success") }}',
            position: 'top-right',
            loaderBg: '#7a5449',
            class: 'jq-toast-primary',
            hideAfter: 3500,
            stack: 6,
            showHideTransition: 'fade'
        });
    });
</script>
@endsection
@endif

@section('additional_js')
<script>
    $(document).ready(function() {
        // No extra JS needed for direct project selection
    });
</script>
@endsection
@endsection
