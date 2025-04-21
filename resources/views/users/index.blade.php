@extends('layouts.app')

@section('content')
<!-- Container -->
<div class="container mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">User Management</h2>
        </div>
        <div class="d-flex">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Add New User</a>
        </div>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Users List</h5>
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                    @if($user->image_path)
                                                        <div class="media-img-wrap d-flex mr-10">
                                                            <div class="avatar avatar-xs">
                                                                <img src="{{ asset('storage/' . $user->image_path) }}" alt="{{ $user->name }}"
                                                                    class="avatar-img rounded-circle">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="media-img-wrap d-flex mr-10">
                                                            <div class="avatar avatar-xs">
                                                                <span class="avatar-text avatar-text-primary rounded-circle">
                                                                    {{ substr($user->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge
                                                        @if($user->role == 'o-admin') badge-purple
                                                        @elseif($user->role == 'gm') badge-danger
                                                        @elseif($user->role == 'pm') badge-success
                                                        @elseif($user->role == 'dm') badge-primary
                                                        @else badge-secondary
                                                        @endif">
                                                        {{ $user->role }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">View</a>
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                                                    <form method="POST" action="{{ route('users.reset-password', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to reset the password to Orion@123?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm">Reset Password</button>
                                                    </form>
                                                    @if(Auth::id() !== $user->id)
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="if(confirm('Are you sure you want to delete this user?')) {
                                                            document.getElementById('delete-form-{{ $user->id }}').submit();
                                                        }">
                                                        Delete
                                                    </button>
                                                    <form id="delete-form-{{ $user->id }}"
                                                        action="{{ route('users.destroy', $user) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-20">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->

<!-- Toast Message -->
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
