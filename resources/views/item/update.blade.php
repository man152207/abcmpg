<!-- resources/views/admin/customer/update.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Update Item</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/admin/dashboard/item/edit/'. $item->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}" required>
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" value="{{ $item->unit }}" required>
                        @error('unit')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="selling_price" class="form-label">Selling Price</label>
                        <input type="number" step="0.01" class="form-control" id="email" name="selling_price" value="{{ $item->selling_price }}" required>
                        @error('selling_price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Description</label>
                        <textarea type="text" class="form-control" id="description" name="description" required> {{ $item->description}}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection