@extends('admin.layout.layout')
@section('title', 'Card Management | MPG Solution')

@section('content')
<style>
    .table-container {
        display: flex;
        flex-wrap: wrap;
        /* Allow tables to wrap to the next line */
    }

    .table-responsive {
        width: 100%;
        /* Occupy full width on smaller screens */
        margin-bottom: 20px;
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

    h3 {
        margin-bottom: 10px;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Card Summary</h3>
        </div>
        <div class="card-body">
            <!-- <h3>Total summary</h3> -->
            <div class="table-container">
                <div class="table-responsive">
                    <h3>Total summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Total USD IN Cards</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $summary->totalUSD }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection