{{--  <div class="issue-card mb-3" data-issue-id="{{ $issue->id }}">
    <div class="card h-100 shadow-sm">
        <div class="card-body p-3">
            <style>
                .issue-card {
                    cursor: pointer;
                    transition: transform 0.2s ease-in-out;
                }
                .issue-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                }
                .issue-card .Priority-pdg {
                    top:0;
                    right: 0;
                }
                .issue-card .Status-pdg {
                    top:0;
                    left: 0;
                }
            </style>
            <!-- Priority badge (top left) -->
            <div class="position-absolute Priority-pdg top-0 start-0 mt-2 ml-2">
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

            <!-- Status badge (top right) -->
            <div class="position-absolute Status-pdg top-0 end-0 mt-2 mr-2">
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



            <!-- Center - description (truncated) -->
            <div class="mt-4 pt-3 mb-3 text-center">
                <p class="text-muted issue-description">
                    {{ \Illuminate\Support\Str::limit($issue->description, 100) }}
                </p>
            </div>
            <!-- Left side - assigned users images -->
            <div class="mt-4 pt-3 mb-3 text-center">
                <div class="float-left mr-3">
                    @if($issue->assignees->isNotEmpty())
                        <div class="avatar-group">
                            @foreach($issue->assignees->take(3) as $assignee)
                                <div class="avatar avatar-xs">
                                    <img src="{{ asset('storage/' . $assignee->image_path) }}"
                                        alt="{{ $assignee->name }}"
                                        class="avatar-img rounded-circle"
                                        data-toggle="tooltip"
                                        title="{{ $assignee->name }}">
                                </div>
                            @endforeach

                            @if($issue->assignees->count() > 3)
                                <div class="avatar avatar-xs">
                                    <span class="avatar-text rounded-circle bg-secondary"
                                        data-toggle="tooltip"
                                        title="{{ $issue->assignees->skip(3)->pluck('name')->join(', ') }}">
                                        +{{ $issue->assignees->count() - 3 }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="avatar avatar-xs">
                            <span class="avatar-text rounded-circle bg-light text-muted">
                                <i class="fa fa-user-o"></i>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            <br>
            <!-- Bottom - title (bold) -->
            <div class="mt-3 pt-2 border-top">
                <h6 class="mb-0 font-weight-bold">{{ $issue->title }}</h6>
            </div>
        </div>
    </div>
</div>  --}}
