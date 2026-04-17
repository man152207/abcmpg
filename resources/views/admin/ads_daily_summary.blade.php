@extends('admin.layout.layout')
@section('title', 'Daily Summary | MPG Solution')
@section('content')

<div class="container">
    <div class="header">
        <h3>Daily Summary for {{ $monthYear }}</h3>
    </div>
    <div class="table-responsive tbl-cards">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total USD</th>
                    <th>Total NPR</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailySummaries as $summary)
                    <tr>
                        <td>{{ $summary->day }}</td>
                        <td>${{ number_format($summary->totalUSD, 2) }}</td>
                        <td>₨{{ number_format($summary->totalNRP, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <canvas id="dailyChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily summary chart
    var dailyCtx = document.getElementById('dailyChart').getContext('2d');
    var dailyChart = new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($dailySummaries as $summary)
                    "{{ $summary->day }}",
                @endforeach
            ],
            datasets: [{
                label: 'Total USD',
                data: [
                    @foreach($dailySummaries as $summary)
                        {{ $summary->totalUSD }},
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
                    @foreach($dailySummaries as $summary)
                        {{ $summary->totalNRP }},
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
                        callback: function(value, index, values) {
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
</script>

@endsection
