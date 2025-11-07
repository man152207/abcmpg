<?php

use App\Models\Customer;


$customer = Customer::where('phone', $ad->customer)->first();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eeeeee;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 0px;
            border: 1px solid #dddddd;
        }

        .header {
            background-color: #dce1e9;
            padding: 6px;
            text-align: center;
        }

        .header img {
            max-width: 180px;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content h2 {
            color: #0047ab;
        }

        .content p {
            line-height: 1.5;
            color: #333333;
        }

        .footer {
            background-color: #f2f2f2;
            padding: 6px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }

        .button {
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }

        .button:hover {
            background-color: #218838;
        }

        .highlight {
            color: #0047ab;
            font-weight: 700;
        }

        .cost {
            font-size: 20px;
            color: #ff5722;
            font-weight: 700;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="https://app.mpg.com.np/images/logo.png" alt="MPG Solution Private Limited Logo">
        </div>
        <div class="content">
            <h2>Payment Reminder</h2>
            <p>Dear Valued Client,{{ $customer->name }}</p>
            <p>This is a friendly reminder that payment is required to continue running your ad campaign. Please reload your account at your earliest convenience to ensure uninterrupted service.</p>
            <p class="cost">Rs {{$ad->NRP}}</p>
            <p>Payment can be made through <b>eSewa, Khalti, ImePay, Fonepay or Direct Deposit</b> details of which have been shared via WhatsApp.</p>
            <a href="https://wa.me/mpgsolution" style="color: white;" class="button">Contact Us on WhatsApp</a>
            <p>If you require any assistance, our team is available from 9 AM to 5 PM, Sunday to Friday. Please note that our office remains closed on designated red days in the calendar.</p>
            <div class="content">
                <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" style="color: white;" class="button" download="PaymentReceipt.pdf">Download Receipt as PDF</a>
            </div>
        </div>
        <div class="footer">
            <p>MPG Solution Private Limited</p>
            <p>Pokhara 15, Kaski, Nepal</p>
            <p>Email: <a href="mailto:support@mpg.com.np">support@mpg.com.np</a></p>
            <p>© 2017–{{ date('Y') }} MPG Solution Private Limited. All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>