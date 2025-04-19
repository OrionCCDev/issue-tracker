@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Meeting Details</span>
                    <div>
                        @if($meeting->status === 'scheduled')
                            <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-primary btn-sm">
                                Edit Meeting
                            </a>
                        @endif
                        <a href="{{ route('projects.show', $meeting->project) }}" class="btn btn-secondary btn-sm">
                            Back to Project
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Meeting Information</h5>
                            <p><strong>Title:</strong> {{ $meeting->title }}</p>
                            <p><strong>Description:</strong> {{ $meeting->description }}</p>
                            <p><strong>Date:</strong> {{ $meeting->meeting_date->format('M d, Y H:i') }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge badge-{{ $meeting->status === 'completed' ? 'success' : ($meeting->status === 'cancelled' ? 'danger' : 'primary') }}">
                                    {{ ucfirst($meeting->status) }}
                                </span>
                            </p>
                            <p><strong>Created By:</strong> {{ $meeting->creator->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Project Information</h5>
                            <p><strong>Project:</strong>
                                <a href="{{ route('projects.show', $meeting->project) }}">
                                    {{ $meeting->project->name }}
                                </a>
                            </p>
                            <p><strong>Description:</strong> {{ $meeting->project->description }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Attendees</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Attendance Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meeting->attendees as $attendee)
                                            <tr>
                                                <td>{{ $attendee->name }}</td>
                                                <td>{{ $attendee->email }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $attendee->pivot->attendance_status === 'attended' ? 'success' : ($attendee->pivot->attendance_status === 'absent' ? 'danger' : 'primary') }}">
                                                        {{ ucfirst($attendee->pivot->attendance_status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Project Issues</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Issue</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Assigned To</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meeting->project->issues as $issue)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('projects.issues.show', [$meeting->project, $issue]) }}">
                                                        #{{ $issue->id }} - {{ $issue->title }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $issue->status === 'resolved' ? 'success' : ($issue->status === 'closed' ? 'secondary' : 'primary') }}">
                                                        {{ ucfirst($issue->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $issue->priority === 'high' ? 'danger' : ($issue->priority === 'medium' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($issue->priority) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($issue->assignees->isNotEmpty())
                                                        {{ $issue->assignees->first()->name }}
                                                    @else
                                                        Unassigned
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($meeting->status === 'in_progress')
                                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateIssueModal{{ $issue->id }}">
                                                            Update Issue
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Issues Discussed in Meeting</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Issue</th>
                                            <th>Status Before</th>
                                            <th>Status After</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meeting->discussedIssues as $issue)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('projects.issues.show', [$meeting->project, $issue]) }}">
                                                        #{{ $issue->id }} - {{ $issue->title }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        {{ ucfirst($issue->pivot->status_before) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($issue->pivot->status_after)
                                                        <span class="badge badge-success">
                                                            {{ ucfirst($issue->pivot->status_after) }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">Not Updated</span>
                                                    @endif
                                                </td>
                                                <td>{{ $issue->pivot->notes }}</td>
                                                <td>
                                                    @if($meeting->status === 'in_progress' && !$issue->pivot->status_after)
                                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#updateIssueStatusModal{{ $issue->id }}">
                                                            Update Status
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Project Changes</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Field</th>
                                            <th>Old Value</th>
                                            <th>New Value</th>
                                            <th>Changed By</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($meeting->projectChanges as $change)
                                            <tr>
                                                <td>{{ ucfirst($change->field_name) }}</td>
                                                <td>{{ $change->old_value }}</td>
                                                <td>{{ $change->new_value }}</td>
                                                <td>{{ $change->changer->name }}</td>
                                                <td>{{ $change->notes }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($meeting->status === 'scheduled' || $meeting->status === 'in_progress')
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Meeting Actions</h5>
                                <div class="d-flex">
                                    @if($meeting->status === 'scheduled')
                                        <form action="{{ route('meetings.update-status', $meeting) }}" method="POST" class="mr-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="btn btn-primary">
                                                Start Meeting
                                            </button>
                                        </form>
                                    @endif

                                    @if($meeting->status === 'in_progress')
                                        <form action="{{ route('meetings.update-status', $meeting) }}" method="POST" class="mr-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-success">
                                                Complete Meeting
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('meetings.update-status', $meeting) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-danger">
                                            Cancel Meeting
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($meeting->project->issues as $issue)
    @if($meeting->status === 'in_progress')
        <div class="modal fade" id="updateIssueModal{{ $issue->id }}" tabindex="-1" role="dialog" aria-labelledby="updateIssueModalLabel{{ $issue->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateIssueModalLabel{{ $issue->id }}">Update Issue</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('meetings.update-issue-status', [$meeting, $issue]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="open" {{ $issue->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $issue->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="review" {{ $issue->status === 'review' ? 'selected' : '' }}>Review</option>
                                    <option value="resolved" {{ $issue->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $issue->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority" required>
                                    <option value="low" {{ $issue->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $issue->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $issue->priority === 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assigned_to">Assign To</label>
                                <select class="form-control" id="assigned_to" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    @foreach($meeting->project->members as $member)
                                        <option value="{{ $member->id }}"
                                            {{ $issue->assignees->isNotEmpty() && $issue->assignees->first()->id === $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ $issue->notes }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Issue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@foreach($meeting->discussedIssues as $issue)
    @if($meeting->status === 'in_progress' && !$issue->pivot->status_after)
        <div class="modal fade" id="updateIssueStatusModal{{ $issue->id }}" tabindex="-1" role="dialog" aria-labelledby="updateIssueStatusModalLabel{{ $issue->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateIssueStatusModalLabel{{ $issue->id }}">Update Issue Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('meetings.update-issue-status', [$meeting, $issue]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="status">New Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="review">Review</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
