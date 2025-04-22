<!-- Issue edit form for modal display -->
<div class="row">
    <div class="col-md-12">
        <form id="issue-edit-modal-form" action="{{ route('issues.ajax-update', $issue->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <h5 class="mb-3">Edit Issue</h5>

                <!-- Title field -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $issue->title }}" required>
                </div>

                <!-- Status and Priority -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="review" {{ $issue->status == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select class="form-control" id="priority" name="priority" required>
                                <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ $issue->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="target_resolution_date">Target Resolution Date</label>
                            <input type="text" class="form-control" id="target_resolution_date" name="target_resolution_date"
                                value="{{ $issue->target_resolution_date ? $issue->target_resolution_date->format('Y-m-d') : '' }}"
                                placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}">
                            <small class="form-text text-muted">Format: YYYY-MM-DD</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="actual_resolution_date">Actual Resolution Date</label>
                            <input type="text" class="form-control" id="actual_resolution_date" name="actual_resolution_date"
                                value="{{ $issue->actual_resolution_date ? $issue->actual_resolution_date->format('Y-m-d') : '' }}"
                                placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}">
                            <small class="form-text text-muted">Format: YYYY-MM-DD</small>
                        </div>
                    </div>
                </div>

                <!-- Assignees -->
                <div class="form-group mt-3">
                    <label for="assigned_to">Assigned To</label>
                    <select class="form-control select2-modal" id="assigned_to" name="assigned_to[]" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ in_array($user->id, $issue->assignees->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div class="form-group mt-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5">{{ $issue->description }}</textarea>
                </div>

                <!-- Notes -->
                <div class="form-group mt-3">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ $issue->notes }}</textarea>
                </div>

                <div class="alert alert-success mt-3 d-none" id="update-success-message">
                    Issue updated successfully!
                </div>

                <div class="alert alert-danger mt-3 d-none" id="update-error-message">
                    Error updating issue. Please try again.
                </div>
            </div>
        </form>

        <hr class="my-4">

        <!-- Issue History Accordion -->
        <div class="mt-4 mb-3 issue-history-section">
            <h5 class="d-flex align-items-center">
                <i class="fa fa-history mr-2"></i> Issue History
            </h5>

            @php
                // Group history entries by date
                $historyByDate = $issue->history->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                })->sortKeysDesc(); // Sort by date descending (newest first)
            @endphp

            <div class="accordion" id="issueHistoryAccordion">
                @forelse($historyByDate as $date => $entries)
                    <div class="card mb-2">
                        <div class="card-header py-2" id="heading{{ \Illuminate\Support\Str::slug($date) }}">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center collapsed"
                                        type="button"
                                        data-toggle="collapse"
                                        data-target="#collapse{{ \Illuminate\Support\Str::slug($date) }}"
                                        aria-expanded="false"
                                        aria-controls="collapse{{ \Illuminate\Support\Str::slug($date) }}">
                                    <span>
                                        <i class="fa fa-calendar mr-2"></i>
                                        {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                        <span class="badge badge-primary ml-2">{{ count($entries) }}</span>
                                    </span>
                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                </button>
                            </h2>
                        </div>

                        <div id="collapse{{ \Illuminate\Support\Str::slug($date) }}"
                             class="collapse"
                             aria-labelledby="heading{{ \Illuminate\Support\Str::slug($date) }}"
                             data-parent="#issueHistoryAccordion">
                            <div class="card-body py-3">
                                @foreach($entries->sortByDesc('created_at') as $historyEntry)
                                    <div class="history-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="user-info">
                                                <span class="user-avatar">
                                                    {{ strtoupper(substr($historyEntry->updatedBy->name ?? 'System', 0, 1)) }}
                                                </span>
                                                <strong>{{ $historyEntry->updatedBy->name ?? 'System' }}</strong>
                                                <small class="text-muted ml-2">
                                                    <i class="fa fa-clock-o"></i> {{ $historyEntry->created_at->format('H:i') }}
                                                </small>
                                            </span>

                                            @if(isset(json_decode($historyEntry->changes, true)['initial_creation']))
                                                <span class="badge badge-success">
                                                    <i class="fa fa-plus-circle"></i> Created
                                                </span>
                                            @else
                                                <span class="badge badge-info">
                                                    <i class="fa fa-pencil"></i> Updated
                                                </span>
                                            @endif
                                        </div>

                                        <div class="history-content">
                                            @if(!empty($historyEntry->changes))
                                                @php
                                                    $changes = json_decode($historyEntry->changes, true);
                                                @endphp

                                                @if(isset($changes['initial_creation']) && $changes['initial_creation'])
                                                    <p class="mb-0 text-success">
                                                        <i class="fa fa-check-circle mr-1"></i>
                                                        Issue was created with the current values
                                                    </p>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered history-table">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Field</th>
                                                                    <th>From</th>
                                                                    <th>To</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($changes as $field => $change)
                                                                    <tr>
                                                                        <td class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $field)) }}</td>
                                                                        <td class="text-danger">
                                                                            @if(in_array($field, ['target_resolution_date', 'actual_resolution_date']) && !empty($change['old']))
                                                                                {{ \Carbon\Carbon::parse($change['old'])->format('Y-m-d') }}
                                                                            @else
                                                                                {{ is_array($change['old']) ? implode(', ', $change['old']) : ($change['old'] ?: '-') }}
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-success">
                                                                            @if(in_array($field, ['target_resolution_date', 'actual_resolution_date']) && !empty($change['new']))
                                                                                {{ \Carbon\Carbon::parse($change['new'])->format('Y-m-d') }}
                                                                            @else
                                                                                {{ is_array($change['new']) ? implode(', ', $change['new']) : ($change['new'] ?: '-') }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            @else
                                                <p class="text-muted">No changes recorded</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light">
                        <i class="fa fa-info-circle mr-1"></i>
                        No history records found for this issue.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize select2 for the assignees dropdown in the modal
        $('.select2-modal').select2({
            dropdownParent: $('#issueDetailModal'),
            placeholder: 'Select assignees',
            allowClear: true
        });

        // Toggle icon rotation for accordion
        $('.accordion .btn-link').on('click', function() {
            $(this).find('.toggle-icon').toggleClass('rotate');
        });
    });
</script>

<style>
    /* Styles for issue history accordion */
    #issueHistoryAccordion .card {
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.25rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    #issueHistoryAccordion .card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    #issueHistoryAccordion .card-header {
        background-color: #f8f9fa;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    #issueHistoryAccordion .btn-link {
        color: #3a3b45;
        text-decoration: none;
        font-weight: 500;
        padding: 0;
        width: 100%;
    }

    #issueHistoryAccordion .btn-link:hover {
        color: #4e73df;
    }

    #issueHistoryAccordion .toggle-icon {
        transition: transform 0.3s ease;
    }

    #issueHistoryAccordion .toggle-icon.rotate {
        transform: rotate(180deg);
    }

    .history-item {
        position: relative;
    }

    .user-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background-color: #4e73df;
        color: white;
        border-radius: 50%;
        font-size: 14px;
        font-weight: bold;
        margin-right: 8px;
    }

    .history-table {
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .history-table th {
        font-weight: 600;
        background-color: #f8f9fc;
    }

    .history-table td {
        vertical-align: middle;
    }
</style>
