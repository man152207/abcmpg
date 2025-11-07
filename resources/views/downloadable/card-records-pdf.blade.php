<!DOCTYPE html>
<html>
<head>
    <title>{{ $cardName }} Records</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>{{ $cardName }} Records</h1>
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
                    <td>{{ $record->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
