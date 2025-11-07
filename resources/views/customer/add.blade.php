@extends('admin.layout.layout')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header" style="background-color: #0d3569; color: #fff;">
            <h3 class="mb-0">Add Customer</h3>
        </div>
        <div class="card-body" style="background-color: #646564; color: #fff;">
            @if (session('status'))
            <div class="alert alert-warning" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <form method="post" action="{{ url('/admin/dashboard/customer/add') }}" class="form-custom-style">
                @csrf
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="display_name" class="form-label">Display Name</label>
                        <input type="text" class="form-control" id="display_name" name="display_name">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="col-md-1 align-self-end">
                        <button type="submit" class="btn btn-primary btn-block" style="background-color: #0d3569;">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    
</style>

@endsection
