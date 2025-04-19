@extends('layouts.app')

@section('content')
<div class="container mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">
                Edit Comment
            </h2>
            <p class="text-muted">
                Issue: <a href="{{ route('projects.issues.show', [$project, $issue]) }}">#{{ $issue->id }} - {{ $issue->title }}</a>
            </p>
        </div>
        <div class="d-flex">
            <a href="{{ route('projects.issues.show', [$project, $issue]) }}" class="btn btn-secondary btn-sm">Back to Issue</a>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Edit Comment</h5>
                <div class="row">
                    <div class="col-sm">
                        <form method="POST" action="{{ route('projects.issues.comments.update', [$project->id, $issue->id, $comment->id]) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="description">Comment</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="5" required>{{ old('description', $comment->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update Comment</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
