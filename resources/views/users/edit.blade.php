@extends('layouts.app')

@section('content')
<!-- Container -->
<div class="container mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">Edit User: {{ $user->name }}</h2>
        </div>
        <div class="d-flex">
            <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm mr-2">View</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Back to Users</a>
        </div>
    </div>

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <div class="row">
                    <div class="col-sm">
                        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="name" class="col-md-2 col-form-label">Name</label>
                                <div class="col-md-10">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-2 col-form-label">Email</label>
                                <div class="col-md-10">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="role" class="col-md-2 col-form-label">Role</label>
                                <div class="col-md-10">
                                    <select id="role" name="role" class="form-control @error('role') is-invalid @enderror">
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ (old('role', $user->role) == $role) ? 'selected' : '' }}>
                                                {{ ucfirst($role) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image" class="col-md-2 col-form-label">Profile Image</label>
                                <div class="col-md-10">
                                    <div class="mb-3" id="imagePreviewContainer">
                                        <img id="imagePreview" src="{{ $user->image_path ? asset('storage/' . $user->image_path) : '' }}"
                                            alt="{{ $user->name }}" class="avatar-img rounded-circle"
                                            style="width: 100px; height: 100px; object-fit: cover; {{ $user->image_path ? '' : 'display: none;' }}">
                                        <p class="text-muted mt-1" id="currentImageText" {{ $user->image_path ? '' : 'style="display: none;"' }}>Current profile image</p>
                                    </div>

                                    <input id="image" type="file" class="form-control-file @error('image') is-invalid @enderror" name="image" accept="image/*" onchange="previewImage(this);">
                                    <small class="form-text text-muted">Leave empty to keep current image</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        Update User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->

@section('custom_js')
<script>
    function previewImage(input) {
        var preview = document.getElementById('imagePreview');
        var previewContainer = document.getElementById('imagePreviewContainer');
        var currentImageText = document.getElementById('currentImageText');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.setAttribute('src', e.target.result);
                preview.style.display = 'block';
                currentImageText.textContent = 'New image preview';
                currentImageText.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            // If image is reset/cleared, show original image if it exists
            var originalImage = "{{ $user->image_path ? asset('storage/' . $user->image_path) : '' }}";
            if (originalImage) {
                preview.setAttribute('src', originalImage);
                preview.style.display = 'block';
                currentImageText.textContent = 'Current profile image';
                currentImageText.style.display = 'block';
            } else {
                preview.style.display = 'none';
                currentImageText.style.display = 'none';
            }
        }
    }
</script>
@endsection
@endsection
