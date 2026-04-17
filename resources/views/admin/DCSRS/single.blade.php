@extends('admin.layout.layout')

@section('title', "Card Records - $cardName")

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@section('content')
<div class="container">
    <h2>{{ $cardName }} - Records</h2>
    <a href="{{ route('daily-card-spends.index') }}" class="btn btn-secondary mb-3">Back to All Cards</a>

    <!-- Filter and Download Section -->
    <div class="filter-section">
        <!-- Date Range Picker -->
        <input type="text" id="daterange-picker" class="daterange-picker" placeholder="Select Date Range">
        
        <!-- Filter Button -->
        <button id="filter-btn" class="btn btn-primary">Filter</button>

        <!-- Download Button -->
        <button id="download-btn" class="btn btn-success">Download PDF</button>
    </div>

    <!-- Records Table -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount (USD)</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record->date }}</td>
                    <td>${{ number_format($record->amount_usd, 2) }}</td>
                    <td>{{ $record->description ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize Date Range Picker
        $('#daterange-picker').daterangepicker({
            locale: { format: 'YYYY-MM-DD' },
            startDate: "{{ request('start_date') ?? now()->startOfMonth()->format('YYYY-MM-DD') }}",
            endDate: "{{ request('end_date') ?? now()->format('YYYY-MM-DD') }}",
        });

        // Handle Filter Button
        $('#filter-btn').click(function () {
            const dateRange = $('#daterange-picker').val();
            const [startDate, endDate] = dateRange.split(' - ');
            const url = `{{ route('daily-card-spends.view', ['cardName' => $cardName]) }}?start_date=${startDate}&end_date=${endDate}`;
            window.location.href = url;
        });

        // Handle Download Button
        $('#download-btn').click(function () {
            const dateRange = $('#daterange-picker').val();
            const [startDate, endDate] = dateRange.split(' - ');
            const url = `{{ route('daily-card-spends.download', ['cardName' => $cardName]) }}?start_date=${startDate}&end_date=${endDate}`;
            window.location.href = url;
        });
    });
</script>
@endsection
