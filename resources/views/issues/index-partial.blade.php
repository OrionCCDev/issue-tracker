<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>Project Issues</h5>
    <a href="{{ route('projects.issues.create', $project) }}" class="btn btn-primary btn-sm">Add New Issue</a>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('projects.issues.list', $project->id) }}" method="GET" class="row">
            <div class="col-md-3 mb-2">
                <label for="status_filter">Status</label>
                <select class="form-control form-control-sm" id="status_filter" name="status">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label for="priority_filter">Priority</label>
                <select class="form-control form-control-sm" id="priority_filter" name="priority">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>

            <div class="col-md-3 mb-2">
                <label for="assigned_filter">Assigned To</label>
                <select class="form-control form-control-sm" id="assigned_filter" name="assigned_to">
                    <option value="">All Users</option>
                    <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-2 d-flex align-items-end">
                <button type="submit" class="btn btn-info btn-sm mr-1">Filter</button>
                <button type="button" class="btn btn-light btn-sm" id="reset-filter">Reset</button>
            </div>
        </form>
    </div>
</div>

<!-- Issues Table -->
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Title</th>
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
                                    <img src="{{ asset('assets/images/users/' . $issue->assignees->first()->image_path) }}"
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
                <td colspan="8" class="text-center">No issues found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {{ $issues->appends(request()->query())->links() }}
</div>

<script>
    $(document).ready(function() {
        // Reset filter button
        $('#reset-filter').click(function() {
            $('#issues').load("{{ route('projects.issues.list', $project->id) }}");
        });

        // Handle form submission via AJAX
        $('form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $('#issues').html(`
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);
            $.get($(this).attr('action'), formData, function(response) {
                $('#issues').html(response);
            });
        });
    });
</script>
