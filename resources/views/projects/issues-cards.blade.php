@extends('layouts.app')

@section('content')
<!--  container-fluid -->
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">{{ $project->name }} - Issues</h2>
            <p class="text-muted">
                <a href="{{ route('projects.show', $project->id) }}">Project Details</a> |
                <a href="{{ route('issues.create', ['project_id' => $project->id]) }}">Create New Issue</a>
            </p>
        </div>
    </div>

    <!-- Filters -->
    <section class="hk-sec-wrapper">
        <div class="row">
            <div class="col-sm">
                <form action="{{ route('projects.issues.cards', $project->id) }}" method="GET" class="form-inline">
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
                        <a href="{{ route('projects.issues.cards', $project->id) }}" class="btn btn-light btn-sm ml-1">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Issues List -->
    <section class="hk-sec-wrapper">
        <h5 class="hk-sec-title">Issues</h5>
        <div class="row">
            @forelse($issues as $issue)
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('issues.update', $issue->id) }}" method="POST" class="issue-edit-form">
                                @csrf
                                @method('PUT')
                                <div class="row align-items-center">
                                    <!-- Priority and Status -->
                                    <div class="col-md-2">
                                        <div class="d-flex flex-column">
                                            <div class="mb-2">
                                                <select name="priority" class="form-control form-control-sm">
                                                    <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                                                    <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                                    <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                                                    <option value="critical" {{ $issue->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                                                </select>
                                            </div>
                                            <div>
                                                <select name="status" class="form-control form-control-sm">
                                                    <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="review" {{ $issue->status == 'review' ? 'selected' : '' }}>Review</option>
                                                    <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Title and Description -->
                                    <div class="col-md-4">
                                        <input type="text" name="title" class="form-control mb-2" value="{{ $issue->title }}" placeholder="Issue Title">
                                        <textarea name="description" class="form-control" rows="2" placeholder="Issue Description">{{ $issue->description }}</textarea>
                                    </div>

                                    <!-- Assignees -->
                                    <div class="col-md-3">
                                        <select name="assignees[]" class="form-control form-control-sm" multiple>
                                            <option value="">Select Assignees</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $issue->assignees->contains($user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-3 text-right">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fa fa-save"></i> Save
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewIssueModal{{ $issue->id }}">
                                                <i class="fa fa-eye"></i> View
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center">
                        No issues found for this project.
                    </div>
                </div>
            @endforelse
        </div>
        <div class="d-flex justify-content-end mt-20">
            {{ $issues->appends(request()->query())->links() }}
        </div>
    </section>
</div>

<!-- View Issue Modal -->
@foreach($issues as $issue)
<div class="modal fade" id="viewIssueModal{{ $issue->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $issue->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Description</h6>
                        <p>{{ $issue->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Details</h6>
                        <ul class="list-unstyled">
                            <li><strong>Status:</strong> {{ ucfirst($issue->status) }}</li>
                            <li><strong>Priority:</strong> {{ ucfirst($issue->priority) }}</li>
                            <li><strong>Created:</strong> {{ $issue->created_at->format('M d, Y') }}</li>
                            <li><strong>Updated:</strong> {{ $issue->updated_at->format('M d, Y') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('issues.edit', $issue->id) }}" class="btn btn-primary">Edit Issue</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
