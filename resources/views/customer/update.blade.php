@extends('admin.layout.layout')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>
    body {
        background-color: #f7f8fc;
        font-family: 'Arial', sans-serif;
    }

    #updateCustomer {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
    <!-- Update Customer Section -->
    <div id="updateCustomer">
        <h3>Update Customer</h3>
        <form method="post" action="{{ url('/admin/dashboard/customer/edit/' . $customer->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
            </div>
            <div class="mb-3">
                <label for="display_name" class="form-label">Display Name</label>
                <input type="text" class="form-control" id="display_name" name="display_name" value="{{ $customer->display_name }}" placeholder="Display Name">
            </div>
            <div class="mb-3">
    <label for="usd_rate" class="form-label">USD Rate</label>
    <input type="number" class="form-control" id="usd_rate" name="usd_rate" value="{{ $customer->usd_rate ?? 170 }}" step="0.01" required>
</div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" required>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                
                @if($customer->profile_picture)
                    <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="Profile Picture" style="max-width: 150px; margin-top: 10px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="yes" id="remove_profile_picture" name="remove_profile_picture">
                        <label class="form-check-label" for="remove_profile_picture">
                            Remove profile picture
                        </label>
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>
</div>
@endsection
