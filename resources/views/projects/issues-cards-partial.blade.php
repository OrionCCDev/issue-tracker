<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>Project Issues</h5>
    <a href="{{ route('projects.issues.create', $project) }}" class="btn btn-primary btn-sm">Add New Issue</a>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('projects.issues.cards.partial', $project->id) }}" method="GET" class="row">
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
                    @foreach($users as $user)
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

<!-- Issues Cards -->
<div class="row">
    @forelse($issues as $issue)
        <div class="col-12 mb-4">
            <div class="issue-card mb-3" data-issue-id="{{ $issue->id }}">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-4">
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
                            .editable-field {
                                cursor: pointer;
                                padding: 5px;
                                border-radius: 4px;
                                transition: background-color 0.2s;
                            }
                            .editable-field:hover {
                                background-color: #f8f9fa;
                            }
                            .editable-field:focus {
                                background-color: #fff;
                                border: 1px solid #80bdff;
                                outline: none;
                            }
                        </style>

                        <!-- Priority badge (top left) -->
                        <div class="position-absolute Priority-pdg top-0 start-0 mt-2 ml-2">
                            <select class="form-control form-control-sm editable-field" data-field="priority">
                                <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ $issue->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>

                        <!-- Status badge (top right) -->
                        <div class="position-absolute Status-pdg top-0 end-0 mt-2 mr-2">
                            <select class="form-control form-control-sm editable-field" data-field="status">
                                <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="review" {{ $issue->status == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <!-- Title (editable) -->
                        <div class="mt-4 mb-3">
                            <h5 class="editable-field" contenteditable="true" data-field="title">{{ $issue->title }}</h5>
                        </div>

                        <!-- Description (editable) -->
                        <div class="mb-3">
                            <p class="editable-field" contenteditable="true" data-field="description">{{ $issue->description }}</p>
                        </div>

                        <!-- Assigned Users -->
                        <div class="mb-3">
                            <label class="small text-muted">Assigned To:</label>
                            <select class="form-control form-control-sm editable-field" data-field="assignees" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $issue->assignees->contains($user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Comments section -->
                        <div class="border-top pt-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">
                                    <i class="fa fa-comments"></i> {{ $issue->comments->count() }} {{ Str::plural('comment', $issue->comments->count()) }}
                                </span>
                            </div>
                            @if($issue->comments->count() > 0)
                                <div class="comments-preview mt-2">
                                    <div class="avatar-group">
                                        @php
                                            $commenters = $issue->comments->pluck('user')->unique('id')->take(4);
                                        @endphp
                                        @foreach($commenters as $commenter)
                                            <div class="avatar avatar-xxs">
                                                <img src="{{ asset('storage/' . $commenter->image_path) }}"
                                                    alt="{{ $commenter->name }}"
                                                    class="avatar-img rounded-circle"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="{{ $commenter->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($issue->comments->isNotEmpty())
                                        <p class="small text-muted mt-1 mb-0">
                                            <strong>{{ $issue->comments->sortByDesc('created_at')->first()->user->name }}</strong>:
                                            {{ Str::limit($issue->comments->sortByDesc('created_at')->first()->description, 40) }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
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

<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle editable fields
        $('.editable-field').on('blur', function() {
            const field = $(this).data('field');
            const value = $(this).is('select') ? $(this).val() : $(this).text();
            const issueId = $(this).closest('.issue-card').data('issue-id');

            $.ajax({
                url: `/projects/issues/${issueId}/update`,
                method: 'PATCH',
                data: {
                    [field]: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('Issue updated successfully');
                },
                error: function(xhr) {
                    toastr.error('Error updating issue');
                }
            });
        });

        // Handle form submission via AJAX
        $('form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            $('#issues-cards').html(`
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);
            $.get($(this).attr('action'), formData, function(response) {
                $('#issues-cards').html(response);
            });
        });
    });
</script>
