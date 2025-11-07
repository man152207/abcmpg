@extends('admin.layout.layout')

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
            <h3>Monthly Expences Summary</h3>
        </div>
        <div class="card-body">
            <!-- <h3>Total summary</h3> -->
            <div class="table-container" style="display:flex;">
                <div class="mr-5">
                    <h3>Card expences</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total USD</th>
                                <th>Total NRP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySummaries as $summary)
                            <tr>
                                <td>{{ $summary->monthYear }}</td>
                                <td>$ {{ $summary->totalUSD }}</td>
                                <td>Rs {{ $summary->totalNRP }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="ml-5">
                    <h3>Other Expences summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total NRP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyExp as $summary)
                            <tr>
                                <td>{{ $summary->monthYear }}</td>
                                <td>RS {{ $summary->totalAmt }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="ml-5">
                    <h3>Total Expences summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total NRP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySummaries as $msummary)
                            @foreach($monthlyExp as $summary)
                            @if($msummary->monthYear == $summary->monthYear)
                            <tr>
                                <td>{{ $summary->monthYear }}</td>
                                <td>RS {{ $summary->totalAmt + $msummary->totalNRP }}</td>
                            </tr>
                            @endif
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>



            <!-- Pagination for each table can be added here as needed -->
        </div>
    </div>
</div>

@endsection