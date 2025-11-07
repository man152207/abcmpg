@extends('admin.layout.layout')
@section('title', 'Card Details | MPG Solution')

@section('content')
<style>
    /* Full-page layout styling */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        background-color: #f5f5f5;
    }

    .container {
        max-width: 100%;
        padding: 0;
        margin: 0;
    }

    .card {
        margin: 20px auto;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    .card-header {
        background-color: #007bff;
        color: #ffffff;
        padding: 20px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        font-size: 1.5rem;
        font-weight: 600;
        text-align: center;
    }

    .card-body {
        padding: 30px;
        font-size: 1rem;
        color: #333333;
    }

    .info-totals-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .card-info {
        flex: 1;
        margin-right: 20px;
    }

    .card-info p {
        margin: 10px 0;
        font-size: 1.1rem;
        color: #444444;
    }

    .totals {
        flex: 1;
        text-align: right;
    }

    .totals h5 {
        margin: 5px 0;
        font-size: 1.2rem;
        font-weight: bold;
        color: #333333;
    }

    .totals div {
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    /* Card Status Styling */
    .status {
        text-align: center;
        margin-top: 10px;
    }

    .status .badge {
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 20px;
    }

    .badge-active {
        background-color: #28a745;
        color: white;
    }

    .badge-suspended {
        background-color: #dc3545;
        color: white;
    }

    /* Table Styling */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th {
        background-color: #007bff;
        color: white;
        text-align: left;
        padding: 15px;
        font-weight: 600;
        font-size: 1rem;
    }

    .table td {
        padding: 12px;
        border: 1px solid #dddddd;
        font-size: 0.95rem;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .info-totals-container {
            flex-direction: column;
        }

        .totals {
            text-align: left;
            margin-top: 20px;
        }

        .table th,
        .table td {
            font-size: 0.85rem;
            padding: 10px;
        }
    }
</style>

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            Details for {{ $card->name }}
        </div>
        <div class="card-body">
            <div class="info-totals-container">
                <!-- Card Information Section -->
                <div class="card-info">
                    <h5>Card Information:</h5>
                    <p><strong>Name:</strong> {{ $card->name }}</p>
                    <p><strong>Card Number:</strong> {{ $card->card_number }}</p>
                    <p><strong>USD:</strong> ${{ number_format($card->USD, 2) }}</p>
                </div>
 <!-- Card Status Section -->
            <div class="status">
                <span class="badge {{ $card->status ? 'badge-active' : 'badge-suspended' }}">
                    {{ $card->status ? 'Active' : 'Suspended' }}
                </span>
            </div>

                <!-- Totals Section -->
                <div class="totals">
                    <h5>Total Transactions:</h5>
                    <div>Total USD: <span>${{ number_format($totalUSD, 2) }}</span></div>
                    <div>Total NRP: <span>{{ number_format($totalNRP, 2) }}</span></div>
                </div>
            </div>

           
            <hr>

            <h5>Associated Entries:</h5>
            @if($entries->isNotEmpty())
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client Name</th>
                            <th>Account</th>
                            <th>USD</th>
                            <th>Rate</th>
                            <th>NRP</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $entry)
                            <tr>
                                <td>{{ $entry->id }}</td>
                                <td>{{ $entry->name }}</td>
                                <td>{{ $entry->account }}</td>
                                <td>${{ number_format($entry->USD, 2) }}</td>
                                <td>{{ number_format($entry->Rate, 2) }}</td>
                                <td>{{ number_format($entry->NRP, 2) }}</td>
                                <td>{{ $entry->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No entries found for this card.</p>
            @endif
        </div>
    </div>
</div>
@endsection
