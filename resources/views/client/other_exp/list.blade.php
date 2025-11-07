@extends('admin.layout.layout')
@section('title', 'Other Expenses | MPG Solution')
@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
        font-size: 14px;
    }

    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px 12px;
        word-wrap: break-word;
    }

    th {
        background-color: #f2f2f2;
        color: #123456;
        font-weight: 500;
    }

    th.date-col, td.date-col {
        width: 15%;
    }

    th.title-col, td.title-col {
        width: 25%;
    }

    th.amount-col, td.amount-col {
        width: 20%;
    }

    th.note-col, td.note-col {
        width: 30%;
    }

    th.action-col, td.action-col {
        width: 10%;
    }

    td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-header {
        background-color: #345678;
        color: white;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #007BFF;
        border-color: #007BFF;
    }

    .btn-danger {
        background-color: #FF4136;
        border-color: #FF4136;
    }

    .table tr {
        padding: 4px 6px;
    }

    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .page-link {
        color: #007BFF;
    }

    .page-item.active .page-link {
        background-color: #007BFF;
        border-color: #007BFF;
        color: white;
    }

    .page-item.disabled .page-link {
        color: #cccccc;
    }

    @media screen and (max-width: 768px) {
        .col-md-4, .col-md-8 {
            width: 100%;
        }
    }
</style>

<div class="container-fluid mt-5">
    <!-- Expense Creation Table -->
    <div class="card">
        <div class="card-header">
            <h4>Create Other Exp</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('exp.store') }}">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input class="form-control" type="date" name="date" required>
                            </td>
                            <td>
                                <input class="form-control" type="text" name="title" required>
                            </td>
                            <td>
                                <input class="form-control" type="number" step="0.01" name="amount" required>
                            </td>
                            <td>
                                <textarea class="form-control" name="note" cols="30" rows="1"></textarea>
                            </td>
                            <td>
                                <button class="btn btn-primary" type="submit">Save Exp</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <!-- Expense List Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Other Expenses Details</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('search_exp') }}" method="get">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="search" placeholder="Search by title" class="form-control">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Expense Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th class="date-col">Date</th>
                        <th class="title-col">Title</th>
                        <th class="amount-col">Amount</th>
                        <th class="note-col">Note</th>
                        <th class="action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exps as $exp)
                    <tr>
                        <td class="date-col">{{ $exp->date }}</td>
                        <td class="title-col">{{ $exp->title }}</td>
                        <td class="amount-col">{{ $exp->amount }}</td>
                        <td class="note-col">{{ $exp->note }}</td>
                        <td class="action-col">
                            <a href="{{ url('/admin/dashboard/exp/edit/'. $exp->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ url('/admin/dashboard/exp/delete/'. $exp->id) }}" method="get" style="display:inline;">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $exps->links('pagination::bootstrap-5') }}
            <div class="card mt-4">
    <div class="card-header">
        <h4>Monthly Expenses Summary</h4>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th class="date-col">Month</th>
                    <th class="amount-col">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlySummary as $summary)
                <tr>
                    <td class="date-col">{{ \Carbon\Carbon::createFromFormat('Y-m', $summary->month)->format('F Y') }}</td>
                    <td class="amount-col">Rs {{ number_format($summary->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
        {{ $monthlySummary->links('pagination::bootstrap-5') }}
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">
        <h4>Monthly Expenses Summary</h4>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th class="date-col">Month</th>
                    <th class="amount-col">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlySummary as $summary)
                <tr>
                    <td class="date-col">{{ \Carbon\Carbon::createFromFormat('Y-m', $summary->month)->format('F Y') }}</td>
                    <td class="amount-col">Rs {{ number_format($summary->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

        </div>
    </div>
</div>

@endsection
