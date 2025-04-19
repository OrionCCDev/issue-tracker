@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Schedule New Meeting</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('meetings.store', $project) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>
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
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description') }}</textarea>
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
                                <input id="meeting_date" type="datetime-local" class="form-control @error('meeting_date') is-invalid @enderror" name="meeting_date" value="{{ old('meeting_date') }}" required>
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
                                        <option value="{{ $member->id }}" {{ in_array($member->id, old('attendees', [])) ? 'selected' : '' }}>
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
                                        <option value="{{ $issue->id }}" {{ in_array($issue->id, old('issues', [])) ? 'selected' : '' }}>
                                            #{{ $issue->id }} - {{ $issue->title }}
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Schedule Meeting
                                </button>
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">
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
