@extends('admin.layout.layout')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Edit User</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

<form method="POST"
      action="{{ url('/admin/dashboard/user/update/' . $user->id) }}"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Current Photo Preview --}}
    <div class="mb-3">
        <label class="form-label d-block">Current Photo</label>
        @php
            use Illuminate\Support\Str;
            $img = $user->profile_picture
                ? (Str::startsWith($user->profile_picture, ['http://','https://'])
                    ? $user->profile_picture
                    : asset('storage/'.$user->profile_picture))
                : null;
        @endphp
        @if($img)
            <img src="{{ $img }}" alt="Profile"
                 class="rounded"
                 style="height:90px;width:90px;object-fit:cover;border:1px solid #ddd;">
        @else
            <span class="text-muted">No image uploaded</span>
        @endif
    </div>
{{-- Current Departments (read-only) --}}
<div class="mb-3">
    <label class="form-label d-block">Departments</label>
    @php $deps = $user->departments ?? collect(); @endphp
    @forelse($deps as $d)
        <span class="badge badge-info mr-1">{{ $d->name }}</span>
    @empty
        <span class="text-muted">Not assigned</span>
    @endforelse
    <a href="{{ url('/admin/dashboard/user/privilege/' . $user->id) }}" class="btn btn-sm btn-warning ml-2">
        Edit Departments
    </a>
</div>

    {{-- Upload New Photo --}}
    <div class="mb-3">
        <label for="profile_picture" class="form-label">Upload New Photo</label>
        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
        <small class="text-muted d-block mt-1">JPG/PNG/WebP, max 2MB</small>
        @error('profile_picture') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- बाकीका फील्डहरू जस्ताको तस्तै --}}
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" name="password">
        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" name="password_confirmation">
        @error('password_confirmation') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone:</label>
        <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" required>
        @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Update User</button>
</form>
        </div>
    </div>
</div>
@endsection