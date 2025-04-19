@extends('layouts.app')

@section('content')
<!--  container-fluid -->
<div class=" container-fluid mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">
                <span id="issue-title-display">{{ $issue->title }}</span>

            </h2>
            <p class="text-muted">
                Project: <a href="{{ route('projects.show', $issue->project_id) }}">{{ $issue->project->name }}</a>
            </p>
        </div>
        <div class="d-flex">
            <a href="{{ route('issues.edit', $issue->id) }}" class="btn btn-primary btn-sm mr-15">Edit Issue</a>
            <a href="{{ route('issues.index') }}" class="btn btn-secondary btn-sm">Back to Issues</a>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <!-- Issue Description -->
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Issue Information</h5>
                <div class="row">
                    <div class="col-sm">
                        <div class="card">
                            <div class="card-body">
                                <form id="issue-title-form" class="d-none" action="{{ route('issues.update', $issue->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="issue-title-input" name="title" value="{{ $issue->title }}" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        <button type="button" class="btn btn-secondary btn-sm" id="cancel-title-edit">Cancel</button>
                                    </div>
                                </form>

                                <div class="row mb-15">
                                    <div class="col-sm-6">
                                        <span class="mr-10">
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
                                        </span>
                                        <span>
                                            @switch($issue->priority)
                                                @case('low')
                                                    <span class="badge badge-soft-success">Low Priority</span>
                                                    @break
                                                @case('medium')
                                                    <span class="badge badge-soft-primary">Medium Priority</span>
                                                    @break
                                                @case('high')
                                                    <span class="badge badge-soft-warning">High Priority</span>
                                                    @break
                                                @case('critical')
                                                    <span class="badge badge-soft-danger">Critical Priority</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-soft-secondary">{{ $issue->priority }} Priority</span>
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <span class="text-muted">Created: {{ $issue->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="issue-description mb-25">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6>Description</h6>

                                    </div>
                                    <div id="description-display" class="card bg-light">
                                        <div class="card-body">
                                            {!! nl2br(e($issue->description)) !!}
                                        </div>
                                    </div>
                                    <form id="description-form" class="d-none" action="{{ route('issues.update', $issue->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <textarea class="form-control" id="description-input" name="description" rows="5" required>{{ $issue->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                            <button type="button" class="btn btn-secondary btn-sm" id="cancel-description-edit">Cancel</button>
                                        </div>
                                    </form>
                                </div>

                                @if($issue->notes)
                                <div class="issue-notes mb-25">
                                    <h6>Notes</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            {!! nl2br(e($issue->notes)) !!}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Comments Section -->
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">
                    Comments
                    <span class="badge badge-soft-primary">{{ $issue->comments->count() }}</span>
                </h5>
                <div class="row">
                    <div class="col-sm">
                        <!-- Add Comment Form -->
                        <div class="card mb-20">
                            <div class="card-body">
                                <form action="{{ route('projects.issues.comments.store', [$project->id, $issue->id]) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="content">Add Your Comment</label>
                                        <textarea class="form-control" id="content" name="description" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Post Comment</button>
                                </form>
                            </div>
                        </div>

                        <!-- Comments List -->
                        @forelse($issue->comments as $comment)
                            <div class="media mb-15">
                                <div class="media-img-wrap mr-15">
                                    <div class="avatar avatar-sm">
                                        <img src="{{ asset('storage/' . $comment->user->image_path) }}"
                                            alt="user" class="avatar-img rounded-circle">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="card">
                                        <div class="card-header bg-transparent">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    <span class="text-muted ml-10">
                                                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                                                    </span>
                                                </div>
                                                @if(Auth::id() == $comment->user_id)
                                                    <div class="dropdown">
                                                        <a href="#" class="btn btn-sm btn-icon btn-light" data-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="{{ route('projects.issues.comments.edit', [$project->id, $issue->id, $comment->id]) }}">
                                                                Edit
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            <form action="{{ route('projects.issues.comments.destroy', [$project->id, $issue->id, $comment->id]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {!! nl2br(e($comment->description)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light">
                                No comments yet. Be the first to comment!
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <div class="col-xl-4 col-lg-5">
            <!-- Issue Details -->
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Details</h5>
                <div class="row">
                    <div class="col-sm">
                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Created By:</span>
                                        <div class="media align-items-center">
                                            <div class="media-img-wrap d-flex mr-10">
                                                <div class="avatar avatar-xs">
                                                    <img src="{{ asset('storage/' . $issue->creator->image_path) }}"
                                                        alt="user" class="avatar-img rounded-circle">
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <span>{{ $issue->creator->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Assigned To:</span>
                                        @if($issue->assignees->isNotEmpty())
                                            <div class="d-flex flex-wrap">
                                                @foreach($issue->assignees as $assignee)
                                                    <div class="media align-items-center mr-15 mb-10">
                                                        <div class="media-img-wrap d-flex mr-10">
                                                            <div class="avatar avatar-xs">
                                                                <img src="{{ asset('storage/' . $assignee->image_path) }}"
                                                                    alt="user" class="avatar-img rounded-circle">
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <span>{{ $assignee->name }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="badge badge-light">Unassigned</span>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Status:</span>
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
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Priority:</span>
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
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Created:</span>
                                        <span>{{ $issue->created_at->format('d M Y') }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Target Resolution:</span>
                                        @if($issue->target_resolution_date)
                                            <span>
                                                {{ \Carbon\Carbon::parse($issue->target_resolution_date)->format('d M Y') }}
                                                @if(\Carbon\Carbon::parse($issue->target_resolution_date)->isPast() && !in_array($issue->status, ['resolved', 'closed']))
                                                    <span class="badge badge-danger ml-1">Overdue</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>Actual Resolution:</span>
                                        @if($issue->actual_resolution_date)
                                            <span>{{ \Carbon\Carbon::parse($issue->actual_resolution_date)->format('d M Y') }}</span>
                                        @else
                                            <span class="text-muted">Not resolved yet</span>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Update Form -->
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Quick Update</h5>
                <div class="row">
                    <div class="col-sm">
                        <form action="{{ route('projects.issues.update', [$project->id, $issue->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="review" {{ $issue->status == 'review' ? 'selected' : '' }}>Review</option>
                                    <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assigned_to">Assigned To</label>
                                <select class="form-control" id="assigned_to" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    @foreach($project->members as $member)
                                        <option value="{{ $member->id }}" {{ $issue->assigned_to == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ $issue->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Issue</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- / container-fluid -->

<!-- Toast Message -->
@if(session('success'))
@section('custom_js')
<script>
    $(document).ready(function() {
        // Title editing
        $('#edit-title-btn').click(function() {
            $('#issue-title-display').addClass('d-none');
            $('#issue-title-form').removeClass('d-none');
            $('#issue-title-input').focus();
        });

        $('#cancel-title-edit').click(function() {
            $('#issue-title-form').addClass('d-none');
            $('#issue-title-display').removeClass('d-none');
        });

        $('#issue-title-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = form.find('button[type="submit"]');
            submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#issue-title-display').text(response.title);
                    $('#issue-title-form').addClass('d-none');
                    $('#issue-title-display').removeClass('d-none');
                    $.toast({
                        heading: 'Success',
                        text: 'Title updated successfully',
                        position: 'top-right',
                        loaderBg: '#7a5449',
                        class: 'jq-toast-primary',
                        hideAfter: 3500,
                        stack: 6,
                        showHideTransition: 'fade'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error updating title:', error);
                    $.toast({
                        heading: 'Error',
                        text: 'Failed to update title. Please try again.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        class: 'jq-toast-danger',
                        hideAfter: 3500,
                        stack: 6,
                        showHideTransition: 'fade'
                    });
                },
                complete: function() {
                    submitButton.prop('disabled', false).text('Save');
                }
            });
        });

        // Description editing
        $('#edit-description-btn').click(function() {
            $('#description-display').addClass('d-none');
            $('#description-form').removeClass('d-none');
            $('#description-input').focus();
        });

        $('#cancel-description-edit').click(function() {
            $('#description-form').addClass('d-none');
            $('#description-display').removeClass('d-none');
        });

        $('#description-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = form.find('button[type="submit"]');
            submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#description-display .card-body').html(response.description.replace(/\n/g, '<br>'));
                    $('#description-form').addClass('d-none');
                    $('#description-display').removeClass('d-none');
                    $.toast({
                        heading: 'Success',
                        text: 'Description updated successfully',
                        position: 'top-right',
                        loaderBg: '#7a5449',
                        class: 'jq-toast-primary',
                        hideAfter: 3500,
                        stack: 6,
                        showHideTransition: 'fade'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error updating description:', error);
                    console.error('Response:', xhr.responseText);
                    $.toast({
                        heading: 'Error',
                        text: 'Failed to update description. Please try again.',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        class: 'jq-toast-danger',
                        hideAfter: 3500,
                        stack: 6,
                        showHideTransition: 'fade'
                    });
                },
                complete: function() {
                    submitButton.prop('disabled', false).text('Save');
                }
            });
        });

        // Existing toast message code
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
@endsection
