@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Meetings</span>
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">
                        Schedule New Meeting
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Project</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meetings as $meeting)
                                    <tr>
                                        <td>{{ $meeting->title }}</td>
                                        <td>
                                            <a href="{{ route('projects.show', $meeting->project) }}">
                                                {{ $meeting->project->name }}
                                            </a>
                                        </td>
                                        <td>{{ $meeting->meeting_date->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $meeting->status === 'completed' ? 'success' : ($meeting->status === 'cancelled' ? 'danger' : 'primary') }}">
                                                {{ ucfirst($meeting->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $meeting->creator->name }}</td>
                                        <td>
                                            <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-info btn-sm">
                                                View
                                            </a>
                                            @if($meeting->status === 'scheduled')
                                                <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-primary btn-sm">
                                                    Edit
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $meetings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
