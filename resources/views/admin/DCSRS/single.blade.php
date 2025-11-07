@extends('admin.layout.layout')

@section('title', "Card Records - $cardName")

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@section('content')
<style>
/* General Styling */
body {
    font-family: 'Roboto', Arial, sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

h2 {
    color: #2c3e50;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

/* Container Styling */
.container {
    max-width: 100%;
    margin: 3px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    flex: 1;
}

/* Date Range Picker and Buttons */
.filter-section {
    display: flex;
    align-items: center;
    gap: 20px;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.daterange-picker {
    width: 300px;
    padding: 12px;
    border: 1px solid #dcdde1;
    border-radius: 8px;
    font-size: 14px;
    background-color: #ffffff;
}

.btn {
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-primary {
    background-color: #6a89cc;
    color: #ffffff;
    border: none;
}

.btn-primary:hover {
    background-color: #4a69bd;
}

.btn-success {
    background-color: #78e08f;
    color: #ffffff;
}

.btn-success:hover {
    background-color: #38a69d;
}

.btn-secondary {
    background-color: #dfe6e9;
    color: #2c3e50;
}

.btn-secondary:hover {
    background-color: #b2bec3;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #f4f7fa;
    font-size: 14px;
    color: #2c3e50;
}

table thead {
    background: #6a89cc;
    color: #ffffff;
}

table tbody tr:hover {
    background: #f9f9f9;
}
</style>

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
