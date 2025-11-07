<!-- resources/views/admin/customer/update.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>UPdate Ad Account</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/admin/dashboard/ad_account/edit/'. $account->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $account->name }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Ad Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection