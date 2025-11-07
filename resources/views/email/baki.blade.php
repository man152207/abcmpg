<?php

use App\Models\Customer;


$customer = Customer::where('phone', $ad->customer)->first();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partial Payment Notification</title>
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
            color: #ffc107;
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
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }

        .button:hover {
            background-color: #0069d9;
        }

        .highlight {
            color: #ffc107;
            font-weight: 700;
        }

        .amount {
            font-size: 18px;
            color: #333;
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
            <h2>Partial Payment Received</h2>
            <p>Dear {{ $customer->name }},</p>
            <p>We appreciate your recent payment towards the social media campaign. However, we have noticed that the amount received is not the full payment.</p>
            <p><strong>Total Cost Amount:</strong> <span class="amount">Rs {{ $ad->NRP }}</span></p>
            <p><strong>Received Amount:</strong> <span class="amount">Rs {{ $ad->NRP - $ad->advance }}</span></p>
            <p><strong>Due Amount:</strong> <span class="highlight">Rs {{ $ad->advance }}</span></p>
            <p>Please complete the remaining payment at your earliest convenience to ensure uninterrupted service for your campaign.</p>
            <a href="https://wa.me/mpgsolution" class="button">Contact Us on WhatsApp</a>
            <p>For any queries or assistance, our team is available from 9 AM to 5 PM, Sunday to Friday.</p>
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