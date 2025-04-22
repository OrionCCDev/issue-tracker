@extends('layouts.app')

@section('content')
<div class=" container-fluid mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">
                Edit Issue: {{ $issue->title }}
            </h2>
            <p class="text-muted">
                Project: <a href="{{ route('projects.show', $issue->project_id) }}">{{ $issue->project->name }}</a>
            </p>
        </div>
        <div class="d-flex">
            <a href="{{ route('issues.show', $issue->id) }}" class="btn btn-secondary btn-sm">Back to Issue</a>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Edit Issue Details</h5>
                <div class="row">
                    <div class="col-sm">
                        <form method="POST" action="{{ route('issues.update', $issue->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $issue->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="5" required>{{ old('description', $issue->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                            <option value="open" {{ old('status', $issue->status) == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="in_progress" {{ old('status', $issue->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="review" {{ old('status', $issue->status) == 'review' ? 'selected' : '' }}>Review</option>
                                            <option value="resolved" {{ old('status', $issue->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="closed" {{ old('status', $issue->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select class="form-control @error('priority') is-invalid @enderror"
                                            id="priority" name="priority" required>
                                            <option value="low" {{ old('priority', $issue->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority', $issue->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority', $issue->priority) == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="critical" {{ old('priority', $issue->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="assigned_to">Assigned To</label>
                                        <select class="form-control @error('assigned_to') is-invalid @enderror"
                                            id="assigned_to" name="assigned_to[]" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ in_array($user->id, old('assigned_to', $issue->assignees->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                    {{ $user->name }}{{ $user->id == $issue->project->manager_id ? ' (Manager)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="target_resolution_date">Target Resolution Date</label>
                                        <input type="date" class="form-control @error('target_resolution_date') is-invalid @enderror"
                                            id="target_resolution_date" name="target_resolution_date"
                                            value="{{ old('target_resolution_date', $issue->target_resolution_date ? $issue->target_resolution_date->format('Y-m-d') : '') }}">
                                        @error('target_resolution_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                    id="notes" name="notes" rows="3">{{ old('notes', $issue->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Issue</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@section('custom_js')
<script>
    $(document).ready(function() {
        // Initialize select2 for multiple select
        $('#assigned_to').select2({
            placeholder: 'Select assignees',
            allowClear: true
        });
    });
</script>
@endsection
@endsection
