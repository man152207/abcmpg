<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #333;
        }
        .invoice-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            font-size: 28px;
            color: #4CAF50;
            margin: 0;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-details div {
            width: 48%;
        }
        .invoice-details h3 {
            margin-bottom: 10px;
            color: #4CAF50;
            font-size: 18px;
        }
        .invoice-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table thead th {
            background-color: #4CAF50;
            color: #fff;
            text-align: left;
            padding: 12px;
            font-size: 14px;
        }
        table tbody td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .invoice-summary {
            text-align: right;
        }
        .invoice-summary table {
            width: auto;
            margin-left: auto;
        }
        .invoice-summary td {
            padding: 10px;
            font-size: 14px;
        }
        .invoice-summary .total {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice</h1>
        </div>
        <div class="invoice-details">
            <div>
                <h3>From:</h3>
                <p>MPG Solution Pvt Ltd.<br>Pokhara 15, Kaski<br>www.mpg.com.np<br>mail@adsmpg.com<br>WhatsApp: +977 9856000601</p>
            </div>
            <div>
                <h3>To:</h3>
                <p>{{ $customer->name }}<br>{{ $customer->display_name }}<br>{{ $customer->email }}<br>{{ $customer->phone }}<br>{{ $customer->address }}</p>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Tax</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs {{ $item->quantity > 1 ? $item->amount / $item->quantity : $item->amount }}</td>
                    <td>N/A</td>
                    <td>Rs {{ $item->amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="invoice-summary">
            <table>
                <tr>
                    <td><strong>Description:</strong></td>
                    <td>{{ $invoice->description }}</td>
                </tr>
                <tr>
                    <td>Total Amount:</td>
                    <td>Rs {{ $invoiceItems->sum('amount') }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            Thank you for your business!
        </div>
    </div>
</body>
</html>
