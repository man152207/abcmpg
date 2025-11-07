<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Receipts PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>All Receipts for {{ $customer->name }} ({{ $customer->phone }})</h2>
    <p>Date Range: {{ $daterange }} | <strong>Total Spend: Rs {{ number_format($ads->sum('NRP'), 2) }}</strong></p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Type of Campaigns</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ads as $index => $ad)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ad->created_at->format('Y-m-d') }}</td>
                    <td>{{ $ad->Ad_Nature_Page ?? 'Ad Campaign' }}</td>
                    <td>{{ $ad->Quantity ?? 'N/A' }}</td>
                    <td>Rs {{ number_format($ad->NRP, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No data available for selected date range.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
