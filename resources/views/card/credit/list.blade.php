<!-- resources/views/admin/credit/list.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header" style="display: inline-flex;">
                <h3>Credit List</h3>
                <div>
                    <a class="btn btn-primary" href="{{route('credit.add')}}" style=" margin-left:80%;display: inline-flex;">AddNew</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('search_credit_list') }}" method="get">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="search">Card Number</label>
                            <input type="text" name="search" placeholder="Search by card number" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <div><br></div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-fw"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Card ID</th>
                            <th>Card Number</th>
                            <th>USD</th>
                            <th>By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($credits as $credit)
                        <tr>
                            <td>{{ $credit->id }}</td>
                            <td>{{ $credit->card_id }}</td>
                            <td>{{ $credit->card_number }}</td>
                            <td>{{ $credit->USD }}</td>
                            <td>{{ $credit->by }}</td>
                            <td>{{ $credit->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $credits->appends(request()->query())->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
@endsection