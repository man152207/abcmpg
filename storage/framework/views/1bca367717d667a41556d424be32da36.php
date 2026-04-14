<?php

use App\Models\Customer;


$customer = Customer::where('phone', $ad->customer)->first();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overpayment Notification</title>
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
            padding: 20px;
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

        .highlight {
            color: #0047ab;
            font-weight: 700;
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
            <h2>Notice of Overpayment</h2>
            <p>Dear <?php echo e($customer->name); ?>,</p>
            <p>We have noticed that you have paid more than the required amount for your recent campaign.</p>
            <p><span class="highlight">Your cost amount:</span>Rs <?php echo e($ad->NRP); ?></p>
            <p><span class="highlight">You paid:</span>Rs <?php echo e($ad->NRP + $ad->advance); ?></p>
            <p><span class="highlight">Overpaid amount:</span>Rs <?php echo e($ad->advance); ?></p>
            <p>If you wish, we can refund the overpaid amount. Alternatively, you can choose to apply this overpayment to your next campaign.</p>
            <a href="https://wa.me/mpgsolution" class="button">Contact Us on WhatsApp</a>
            <p>Please let us know your preference at your earliest convenience.</p>
        </div>
        <div class="footer">
            <p>MPG Solution Private Limited</p>
            <p>Pokhara 15, Kaski, Nepal</p>
            <p>Email: <a href="mailto:support@mpg.com.np">support@mpg.com.np</a></p>
            <p>© 2017–<?php echo e(date('Y')); ?> MPG Solution Private Limited. All Rights Reserved.</p>
        </div>
    </div>

</body>

</html><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/email/overpaid.blade.php ENDPATH**/ ?>