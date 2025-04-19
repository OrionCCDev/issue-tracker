<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="thead-light">
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Type</th>
                <th>Assigned To</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($issues as $issue)
                <tr>
                    <td>
                        <div class="editable" data-field="title" data-issue-id="{{ $issue->id }}">
                            <a href="{{ route('issues.show', $issue) }}" class="text-primary">
                                {{ $issue->title }}
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="editable" data-field="status" data-issue-id="{{ $issue->id }}">
                            <select class="form-control form-control-sm" onchange="updateIssueField(this, 'status')">
                                <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="review" {{ $issue->status == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="editable" data-field="priority" data-issue-id="{{ $issue->id }}">
                            <select class="form-control form-control-sm" onchange="updateIssueField(this, 'priority')">
                                <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ $issue->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="editable" data-field="type" data-issue-id="{{ $issue->id }}">
                            <select class="form-control form-control-sm" onchange="updateIssueField(this, 'type')">
                                <option value="bug" {{ $issue->type == 'bug' ? 'selected' : '' }}>Bug</option>
                                <option value="feature" {{ $issue->type == 'feature' ? 'selected' : '' }}>Feature</option>
                                <option value="task" {{ $issue->type == 'task' ? 'selected' : '' }}>Task</option>
                                <option value="improvement" {{ $issue->type == 'improvement' ? 'selected' : '' }}>Improvement</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="editable" data-field="assigned_to" data-issue-id="{{ $issue->id }}">
                            <select class="form-control form-control-sm" onchange="updateIssueField(this, 'assigned_to')">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        @if($issue->assignedTo)
                                            @if(is_object($issue->assignedTo))
                                                {{ $issue->assignedTo->id == $user->id ? 'selected' : '' }}
                                            @else
                                                {{ $issue->assignedTo == $user->id ? 'selected' : '' }}
                                            @endif
                                        @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('issues.show', $issue) }}" class="btn btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-danger delete-issue" data-issue-id="{{ $issue->id }}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No issues found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($issues, 'hasPages') && $issues->hasPages())
    <div class="mt-3">
        {{ $issues->links() }}
    </div>
@endif

@push('scripts')
<script>
function updateIssueField(element, field) {
    const issueId = element.closest('.editable').dataset.issueId;
    const value = element.value;

    // Show loading indicator
    const originalValue = element.value;
    element.disabled = true;

    // Send AJAX request to update the field
    fetch(`/issues/${issueId}/update-field`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            field: field,
            value: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            toastr.success('Field updated successfully');
        } else {
            // Revert the value and show error
            element.value = originalValue;
            toastr.error(data.message || 'Error updating field');
        }
    })
    .catch(error => {
        // Revert the value and show error
        element.value = originalValue;
        toastr.error('Error updating field');
    })
    .finally(() => {
        element.disabled = false;
    });
}
</script>
@endpush