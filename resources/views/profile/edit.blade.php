@extends('layouts.app')

@section('custom_css')
<style>
    .profile-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }
    .profile-card-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f1f2f3;
    }
    .profile-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #324148;
        margin-bottom: 5px;
    }
    .profile-card-subtitle {
        font-size: 14px;
        color: #5e7d8a;
    }
    .profile-card-body {
        padding: 25px;
    }
    .profile-form-group {
        margin-bottom: 20px;
    }
    .profile-form-label {
        font-weight: 500;
        margin-bottom: 10px;
        display: block;
    }
    .profile-avatar-container {
        text-align: center;
        margin-bottom: 25px;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f1f2f3;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .profile-btn {
        background-color: #0f5874;
        color: white;
        font-weight: 500;
        padding: 8px 20px;
        border-radius: 5px;
        border: none;
        transition: all 0.3s;
    }
    .profile-btn:hover {
        background-color: #0d4c65;
    }
    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
    @if (session('status') === 'profile-updated')
    <div class="alert alert-success" role="alert">
        Profile updated successfully!
    </div>
    @endif

    @if (session('status') === 'password-updated')
    <div class="alert alert-success" role="alert">
        Password updated successfully!
    </div>
    @endif

    <div class="row">
        <!-- Profile Information Card -->
        <div class="col-xl-6 col-md-12">
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="profile-card-title">Profile Information</h5>
                    <p class="profile-card-subtitle">Update your account's profile information and email address</p>
                </div>
                <div class="profile-card-body">
                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="profile-avatar-container">
                            @if(Auth::user()->image_path)
                                <img src="{{ asset('storage/' . Auth::user()->image_path) }}" alt="{{ Auth::user()->name }}" class="profile-avatar">
                            @else
                                <img src="{{ asset('assets/imgs/default_employee.png') }}" alt="{{ Auth::user()->name }}" class="profile-avatar">
                            @endif
                        </div>

                        <div class="profile-form-group">
                            <label for="image" class="profile-form-label">Profile Image</label>
                            <input id="image" name="image" type="file" class="form-control" accept="image/*">
                            @error('image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="name" class="profile-form-label">Name</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="email" class="profile-form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="profile-btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="col-xl-6 col-md-12">
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5 class="profile-card-title">Update Password</h5>
                    <p class="profile-card-subtitle">Ensure your account is using a secure password</p>
                </div>
                <div class="profile-card-body">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="profile-form-group">
                            <label for="current_password" class="profile-form-label">Current Password</label>
                            <input id="current_password" name="current_password" type="password" class="form-control" required>
                            @error('current_password', 'updatePassword')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="password" class="profile-form-label">New Password</label>
                            <input id="password" name="password" type="password" class="form-control" required>
                            @error('password', 'updatePassword')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="password_confirmation" class="profile-form-label">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="profile-btn">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview selected profile image before upload
        const imageInput = document.getElementById('image');
        const profileAvatar = document.querySelector('.profile-avatar');

        if (imageInput && profileAvatar) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileAvatar.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endsection
