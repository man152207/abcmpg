<!-- resources/views/admin/customer/add.blade.php -->
@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>ADD Card</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/admin/dashboard/card/add') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" value="{{ old('card_number') }}" required>
                        @error('card_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="USD" class="form-label">USD</label>
                        <input type="number" step="0.01" class="form-control @error('USD') is-invalid @enderror" id="USD" name="USD" value="{{ old('USD') }}" required>
                        @error('USD')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Add Card</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection