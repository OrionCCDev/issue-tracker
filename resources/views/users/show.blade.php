@extends('layouts.app')

@section('content')
<!-- Container -->
<div class="container mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">User Details: {{ $user->name }}</h2>
        </div>
        <div class="d-flex">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm mr-2">Edit</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Back to Users</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <!-- User Profile Image -->
                    <div class="col-md-4 text-center">
                        @if($user->image_path)
                            <img src="{{ asset('storage/' . $user->image_path) }}" alt="{{ $user->name }}"
                                class="avatar-img rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="avatar avatar-xl mx-auto mb-3" style="width: 150px; height: 150px;">
                                <span class="avatar-text avatar-text-primary rounded-circle"
                                    style="width: 150px; height: 150px; line-height: 150px; font-size: 60px;">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif

                        <h4 class="mb-0">{{ $user->name }}</h4>
                        <span class="badge
                            @if($user->role == 'o-admin') badge-purple
                            @elseif($user->role == 'gm') badge-danger
                            @elseif($user->role == 'pm') badge-success
                            @elseif($user->role == 'dm') badge-primary
                            @else badge-secondary
                            @endif mt-2">
                            {{ $user->role }}
                        </span>

                        @if(Auth::id() === $user->id)
                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                Edit My Profile
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- User Details -->
                    <div class="col-md-8">
                        <h5 class="mb-3">User Information</h5>
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <th style="width: 150px;">Email:</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Joined:</th>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if(Auth::user()->role === 'o-admin' || Auth::user()->role === 'cm')
                        <div class="mt-3">
                            <form method="POST" action="{{ route('users.reset-password', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to reset the password to Orion@123?');">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Reset Password to Orion@123
                                </button>
                            </form>
                        </div>
                        @endif

                        @if($user->managedProjects->count() > 0)
                            <h5 class="mt-5 mb-3">Managed Projects</h5>
                            <div class="card-group">
                                @foreach($user->managedProjects as $project)
                                    <div class="card card-sm mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $project->name }}</h5>
                                            <p class="card-text">{{ $project->description }}</p>
                                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                                View Project
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($user->assignedIssues->count() > 0)
                            <h5 class="mt-5 mb-3">Assigned Issues</h5>
                            <div class="card-group">
                                @foreach($user->assignedIssues as $issue)
                                    <div class="card card-sm mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $issue->title }}</h5>
                                            <p class="card-text">{{ $issue->description }}</p>
                                            <a href="{{ route('issues.show', $issue) }}" class="btn btn-sm btn-outline-primary">
                                                View Issue
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->

@if(session('success'))
@section('custom_js')
<script>
    $(document).ready(function() {
        $.toast({
            heading: 'Success',
            text: '{{ session("success") }}',
            position: 'top-right',
            loaderBg: '#7a5449',
            class: 'jq-toast-primary',
            hideAfter: 3500,
            stack: 6,
            showHideTransition: 'fade'
        });
    });
</script>
@endsection
@endif
@endsection
