@extends('admin.layout.layout')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Edit Profile</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('put')

                <div class="mb-3">
    <label for="profile_picture" class="form-label">Profile Picture:</label>
    <input type="file" class="form-control" name="profile_picture" accept="image/*">
    @if($admin->profile_picture)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $admin->profile_picture) }}" 
                 alt="Profile" width="120" height="120" style="object-fit:cover;border-radius:50%;">
        </div>
    @endif
    @error('profile_picture')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password">
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" name="password_confirmation">
                    @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $admin->phone) }}" required>
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection