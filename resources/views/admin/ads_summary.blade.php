<?php
use App\Helpers\DbSql;
use Carbon\Carbon;
use App\Models\Ad;

// Set the start date to the first day of the current month
$startDate = Carbon::now()->startOfMonth();
$endDate   = Carbon::today();

$_dateD  = DbSql::dateOf('created_at');
$_sumUSD = DbSql::sumCoalesce('USD');
$_sumNRP = DbSql::sumCoalesce('NRP');

$rows = \App\Models\Ad::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
    ->selectRaw(DbSql::alias($_dateD, 'd') . ', ' . DbSql::alias($_sumUSD, 'totalUSD') . ', ' . DbSql::alias($_sumNRP, 'totalNRP'))
    ->groupByRaw($_dateD)
    ->orderByRaw($_dateD)
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
            <div class="table-responsive tbl-cards">
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
