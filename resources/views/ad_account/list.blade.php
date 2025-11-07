<!-- resources/views/admin/customer/list.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <h1>Ad Account List</h1>
    <form action="{{route('search_ad_account')}}" method="post">
        @csrf
        <div class="input-group">
            <input type="text" name="search" placeholder="Search by customer name" class="form-control">
            <div style="background-color: grey;" class="input-group-append">
                <button type="submit" class="btn">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
            <tr>
                <td>{{ $account->name }}</td>
                <td>
                    <a href="{{ url('/admin/dashboard/ad_account/edit/'. $account->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <a href="{{ url('/admin/dashboard/ad_account/delete/'. $account->id) }}" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <ul class="custom-pagination">
        {{ @$accounts->links('pagination::bootstrap-5') }}
    </ul>

</div>
@endsection