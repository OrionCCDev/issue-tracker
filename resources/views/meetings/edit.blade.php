@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Meeting</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('meetings.update', $meeting) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $meeting->title) }}" required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description', $meeting->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="meeting_date" class="col-md-4 col-form-label text-md-right">Meeting Date</label>
                            <div class="col-md-6">
                                <input id="meeting_date" type="datetime-local" class="form-control @error('meeting_date') is-invalid @enderror" name="meeting_date" value="{{ old('meeting_date', $meeting->meeting_date->format('Y-m-d\TH:i')) }}" required>
                                @error('meeting_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="attendees" class="col-md-4 col-form-label text-md-right">Attendees</label>
                            <div class="col-md-6">
                                <select id="attendees" class="form-control @error('attendees') is-invalid @enderror" name="attendees[]" multiple required>
                                    @foreach($projectMembers as $member)
                                        <option value="{{ $member->id }}" {{ in_array($member->id, old('attendees', $meeting->attendees->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('attendees')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="issues" class="col-md-4 col-form-label text-md-right">Issues to Discuss</label>
                            <div class="col-md-6">
                                <select id="issues" class="form-control @error('issues') is-invalid @enderror" name="issues[]" multiple>
                                    @foreach($projectIssues as $issue)
                                        <option value="{{ $issue->id }}" {{ in_array($issue->id, old('issues', $meeting->discussedIssues->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            #{{ $issue->id }} - {{ $issue->title }} ({{ ucfirst($issue->status) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('issues')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <h5>All Project Issues</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Issue</th>
                                                <th>Status</th>
                                                <th>Priority</th>
                                                <th>Assigned To</th>
                                                <th>Select for Discussion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projectIssues as $issue)
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
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="issue_{{ $issue->id }}" name="issues[]" value="{{ $issue->id }}" {{ in_array($issue->id, old('issues', $meeting->discussedIssues->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="issue_{{ $issue->id }}"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Meeting
                                </button>
                                <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
