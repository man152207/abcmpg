@extends('admin.layout.layout')

@section('content')
<style>
    .table-container {
        display: flex;
        justify-content: space-between;
    }

    .table-responsive {
        width: 30%;
        /* Adjust the width as needed */
        margin-right: 10px;
        /* Add some margin between tables */
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Monthly Summary</h3>
        </div>
        <div class="card-body">
            <form action="{{route('search_credit')}}" method="get">
                @csrf
                <div class="input-group">
                    <input type="text" name="search" placeholder="Search by card number (enter correct card number)" class="form-control">
                    <div style="background-color: grey;" class="input-group-append">
                        <button type="submit" class="btn">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- <h3>Total summary</h3> -->
            <div class="table-container">
                <div class="table-responsive">
                    <h3>Total Credit summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySummaries as $summary)
                            <tr>
                                <td>{{ $summary->monthYear }}</td>
                                <td>{{ $summary->totalUSD }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination for each table can be added here as needed -->
            {{ @$monthlySummaries->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection