<?php
use Carbon\Carbon;
use App\Models\Ad;

// Set the start date to the first day of the current month
$startDate = Carbon::now()->startOfMonth();
$endDate   = Carbon::today();

$rows = \App\Models\Ad::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
    ->selectRaw("created_at::date as d, SUM(COALESCE(\"USD\",0)) AS totalUSD, SUM(COALESCE(\"NRP\",0)) AS totalNRP")
    ->groupBy('d')
    ->orderBy('d')
    ->get();

$data = [];
for ($cursor = $startDate->copy(); $cursor->lte($endDate); $cursor->addDay()) {
    $key = $cursor->toDateString();
    $row = $rows->firstWhere('d', $key);

    $usd = (float) ($row->totalUSD ?? 0);
    $npr = (float) ($row->totalNRP ?? 0);

    $data[$cursor->format('M j, Y')] = [
        'totalUSD' => number_format($usd, 2, '.', ','),
        'totalNPR' => number_format($npr, 2, '.', ','),
    ];
}
?>

@extends('admin.layout.layout')
@section('title', 'Summaries | MPG Solution')

@section('content')

<style>
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
    }

    .container {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        display: flex;
        flex-wrap: nowrap;
        width: 100%;
        height: 100%;
    }

    .dashboard-section {
        flex-grow: 1;
        width: 50%;
        padding: 20px;
        box-sizing: border-box;
    }

    .header {
        background-color: #17a2b8;
        color: white;
        padding: 10px;
        text-align: center;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 10px;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e0e0e0;
    }

    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .pagination li {
        margin: 0 5px;
        list-style: none;
        cursor: pointer;
        padding: 5px 10px;
        border: 1px solid #17a2b8;
        color: #17a2b8;
        border-radius: 4px;
    }

    .pagination li.active {
        background-color: #17a2b8;
        color: white;
    }

    .pagination li.disabled {
        pointer-events: none;
        color: #ccc;
    }

    canvas {
        max-width: 100%;
        height: auto;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            flex-direction: column;
        }

        .dashboard-section {
            width: 100%;
        }
    }
</style>

<div class="container mt-5">
    <div class="dashboard-container">
        <!-- Daily Ad Revenue Report Section -->
        <div class="dashboard-section">
            <div class="header">
                <h3>Daily Summary</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total USD</th>
                        <th>Total NPR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $dataReversed = array_reverse($data); ?>
                    @foreach ($dataReversed as $date => $values)
                        <tr>
                            <td>{{ htmlspecialchars($date) }}</td>
                            <td>${{ htmlspecialchars($values['totalUSD']) }}</td>
                            <td>₨{{ htmlspecialchars($values['totalNPR']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <canvas id="dailyChart"></canvas>
        </div>
        <!-- Monthly Summary Section -->
        <div class="dashboard-section">
            <div class="header">
                <h3>Monthly Summary</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total USD</th>
                            <th>Total NPR</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlySummaries as $summary)
                            <tr>
                                <td>{{ $summary->monthYear }}</td>
                                <td>${{ $summary->totalUSD }}</td>
                                <td>₨{{ $summary->totalNRP }}</td>
                                <td>
                                    <a href="{{ route('admin.ads_summary.details', ['monthYear' => \Carbon\Carbon::createFromFormat('Y-m', $summary->monthYear)->format('F Y')]) }}" class="btn btn-primary">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $monthlySummaries->links('pagination::bootstrap-5') }}
            </div>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily summary chart
    var dailyCtx = document.getElementById('dailyChart').getContext('2d');
    var dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($dataReversed as $date => $values)
                    "{{ $date }}",
                @endforeach
            ],
            datasets: [{
                label: 'Total USD',
                data: [
                    @foreach($dataReversed as $date => $values)
                        {{ (float)str_replace(',', '', $values['totalUSD']) }},
                    @endforeach
                ],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                tension: 0.4
            }, {
                label: 'Total NPR',
                data: [
                    @foreach($dataReversed as $date => $values)
                        {{ (float)str_replace(',', '', $values['totalNPR']) }},
                    @endforeach
                ],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Daily Summary',
                    font: {
                        weight: 'bold',
                        size: 16
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly summary chart
    var monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($monthlySummaries->items() as $summary)
                    "{{ $summary->monthYear }}",
                @endforeach
            ],
            datasets: [{
                label: 'Total USD',
                data: [
                    @foreach($monthlySummaries->items() as $summary)
                        {{ (float)$summary->totalUSD }},
                    @endforeach
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderWidth: 1
            }, {
                label: 'Total NPR',
                data: [
                    @foreach($monthlySummaries->items() as $summary)
                        {{ (float)$summary->totalNRP }},
                    @endforeach
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₨' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Summary',
                    font: {
                        weight: 'bold',
                        size: 16
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

@endsection
