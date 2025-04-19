<div class="row">
    <div class="col-md-12">
        <!-- Issue Details -->
        <div class="mb-4">
            <div class="row mb-3">
                <div class="col-6">
                    <span class="mr-2">
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
                <div class="col-6 text-right">
                    <span class="text-muted">Created: {{ $issue->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <h4 class="mb-3">{{ $issue->title }}</h4>

            <div class="mb-4">
                <h6>Description</h6>
                <div class="card bg-light">
                    <div class="card-body">
                        {!! nl2br(e($issue->description)) !!}
                    </div>
                </div>
            </div>

            @if($issue->target_resolution_date)
            <div class="mb-3">
                <strong>Target Date:</strong>
                {{ \Carbon\Carbon::parse($issue->target_resolution_date)->format('d M Y') }}
                @if(\Carbon\Carbon::parse($issue->target_resolution_date)->isPast() && !in_array($issue->status, ['resolved', 'closed']))
                    <span class="badge badge-danger">Overdue</span>
                @endif
            </div>
            @endif

            <div class="mb-3">
                <strong>Assignees:</strong>
                @if($issue->assignees->isNotEmpty())
                    <div class="d-flex align-items-center mt-2">
                        @foreach($issue->assignees as $assignee)
                            <div class="media align-items-center mr-3">
                                <div class="media-img-wrap d-flex mr-2">
                                    <div class="avatar avatar-xs">
                                        <img src="{{ asset('assets/images/users/' . $assignee->image_path) }}"
                                            alt="{{ $assignee->name }}" class="avatar-img rounded-circle">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <span class="d-block">{{ $assignee->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted">Unassigned</span>
                @endif
            </div>
        </div>

        <!-- Comments -->
        <div class="mt-4 issue-comments-section">
            <h5 class="mb-3">
                Comments
                <span class="badge badge-soft-primary">{{ $issue->comments->count() }}</span>
            </h5>

            @forelse($issue->comments as $comment)
                <div class="media mb-3">
                    <div class="media-img-wrap mr-3">
                        <div class="avatar avatar-sm">
                            <img src="{{ asset('assets/images/users/' . $comment->user->image_path) }}"
                                alt="user" class="avatar-img rounded-circle">
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="card">
                            <div class="card-header bg-transparent py-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        <span class="text-muted ml-2">
                                            <small>{{ $comment->created_at->diffForHumans() }}</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-2">
                                {!! nl2br(e($comment->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light">
                    No comments yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
