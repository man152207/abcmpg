<?php

use App\Models\Customer;


$customer = Customer::where('phone', $ad->customer)->first();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Payment Notification</title>
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
            <h2>Refund Payment Initiated</h2>
            <p>Dear <?php echo e($customer->name); ?>,</p>
            <p>We have initiated a refund for your recent transaction with us. The refund amount, after the necessary deductions for the ad campaign, will be communicated to you via WhatsApp.</p>
            <p>This process may take a few hours. We appreciate your patience during this time.</p>
            <a href="https://wa.me/mpgsolution" class="button">Contact Us on WhatsApp</a>
            <p>If you require any assistance or have any questions, our team is available from 9 AM to 5 PM, Sunday to Friday. Please note that our office remains closed on designated red days in the calendar.</p>
        </div>
        <div class="footer">
            <p>MPG Solution Private Limited</p>
            <p>Pokhara 15, Kaski, Nepal</p>
            <p>Email: <a href="mailto:support@mpg.com.np">support@mpg.com.np</a></p>
            <p>© 2017–<?php echo e(date('Y')); ?> MPG Solution Private Limited. All Rights Reserved.</p>
        </div>
    </div>

</body>

</html><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/email/refund.blade.php ENDPATH**/ ?>