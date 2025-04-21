@extends('layouts.app')

@section('content')
<div class="  container-fluid-fluid mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">
                Create New Issue
                @if(isset($project))
                    <span class="text-muted"> for {{ $project->name }}</span>
                @endif
            </h2>
        </div>
        <div class="d-flex">

            @if(isset($project))
                <a href="{{ route('projects.issues.index', ['project' => $project->id]) }}" class="btn btn-secondary btn-sm">
                    Back to Issues
                </a>
            @else
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">Back</a>

            @endif
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Issue Details</h5>
                <div class="row">
                    <div class="col-sm">
                        <form method="post" action="{{ isset($project) ? route('projects.issues.store', ['project' => $project->id]) : route('issues.store') }}">
                            @csrf

                            @if(!isset($project))
                            <div class="form-group">
                                <label for="project_id">Project</label>
                                <select name="project_id" id="project_id"
                                    class="form-control @error('project_id') is-invalid @enderror" required>
                                    <option value="">Select Project</option>
                                    @foreach($projects as $projectItem)
                                        <option value="{{ $projectItem->id }}" {{ old('project_id') == $projectItem->id ? 'selected' : '' }}>
                                            {{ $projectItem->name }} ({{ $projectItem->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="title">Issue Title</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select name="priority" id="priority"
                                            class="form-control @error('priority') is-invalid @enderror" required>
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status"
                                            class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="open" {{ old('status') == 'open' ? 'selected' : '' }} selected>Open</option>
                                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>Review</option>
                                            <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="assigned_to">Assign To</label>
                                <select name="assigned_to[]" id="assigned_to"
                                    class="form-control @error('assigned_to') is-invalid @enderror" multiple>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ in_array($member->id, old('assigned_to', [])) ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="target_resolution_date">Target Resolution Date</label>
                                        <input type="date" name="target_resolution_date" id="target_resolution_date"
                                            class="form-control @error('target_resolution_date') is-invalid @enderror"
                                            value="{{ old('target_resolution_date') }}">
                                        @error('target_resolution_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="actual_resolution_date">Actual Resolution Date</label>
                                        <input type="date" name="actual_resolution_date" id="actual_resolution_date"
                                            class="form-control @error('actual_resolution_date') is-invalid @enderror"
                                            value="{{ old('actual_resolution_date') }}">
                                        @error('actual_resolution_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes (Optional)</label>
                                <textarea name="notes" id="notes"
                                    class="form-control @error('notes') is-invalid @enderror"
                                    rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Create Issue
                                </button>
                                @if(isset($project))
                                    <a href="{{ route('projects.issues.index', ['project' => $project->id]) }}" class="btn btn-secondary ml-2">
                                        Cancel
                                    </a>
                                @else
                                    <a href="{{ route('issues.index') }}" class="btn btn-secondary ml-2">
                                        Cancel
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->
</div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        // If we're not in a specific project context, set up dynamic loading of members
        @if(!isset($project))
        $('#project_id').change(function() {
            var projectId = $(this).val();
            if (projectId) {
                $.ajax({
                    url: '/api/projects/' + projectId + '/members',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Unassigned</option>';
                        $.each(data, function(key, value) {
                            options += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#assigned_to').html(options);
                    }
                });
            } else {
                $('#assigned_to').html('<option value="">Unassigned</option>');
            }
        });
        @endif
    });
</script>
@endsection
