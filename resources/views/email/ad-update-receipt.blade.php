<!-- resources/views/emails/ad-receipt.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Receipt</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 5px;
        }

        h2 {
            color: #3498db;
        }

        p {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Updated Ad Receipt</h2>
        <?php

        use App\Models\Customer;


        $customer = Customer::where('phone', $ad->customer)->first();

        ?>

        <p>Dear {{ $customer->name }},</p>

        <p>Your ad has been Updated successfully. Below are the details:</p>

        <table>
            <tr>
                <th>Attribute</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Customer</td>
                <td>{{ $customer->name }}</td>
            </tr>
            <!-- <tr>
                <td>Amount (USD)</td>
                <td>${{ $ad->USD }}</td>
            </tr> -->
            <tr>
                <td>Amount (NRP)</td>
                <td>Rs {{ $ad->NRP }}</td>
            </tr>
            <!-- Add more rows for other details -->
        </table>

        <p>Thank you for choosing our services.</p>

        <div class="footer">
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>

</html>