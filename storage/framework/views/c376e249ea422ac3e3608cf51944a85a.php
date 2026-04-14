<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', 'sans-serif';
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #333;
        }
        .receipt-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .receipt-header h1 {
            font-size: 28px;
            color: #4CAF50;
            margin: 0;
        }
        .receipt-header img {
            max-height: 60px;
        }
        .receipt-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .receipt-details div {
            width: 48%;
        }
        .receipt-details h3 {
            margin-bottom: 10px;
            color: #4CAF50;
            font-size: 18px;
        }
        .receipt-details p {
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
        .receipt-summary {
            text-align: right;
        }
        .receipt-summary table {
            width: auto;
            margin-left: auto;
        }
        .receipt-summary td {
            padding: 10px;
            font-size: 14px;
        }
        .receipt-summary .total {
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
        .btn {
            display: inline-block;
            background: #4CAF50;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #45a049;
        }
        .watermark {
    position: fixed;
    top: 14%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 50px;
    color: rgba(0, 0, 0, 0.08); /* Very light gray for a watermark effect */
    font-weight: bold;
    text-transform: uppercase;
    z-index: 1; /* Ensures it's above background but behind text */
    white-space: nowrap;
    pointer-events: none; /* Ensures it's non-interactive */
    user-select: none; /* Prevents selection of the text */
}

    </style>
</head>
<body>
    <div class="watermark">This is not an original bill</div>

    <div class="receipt-container">
        <div class="receipt-header">
            <div>
                <h1>Receipt</h1>
<div style="padding: 2.5px;">Date <?php echo e($ad->created_at ? $ad->created_at->format('F j, Y') : ''); ?></div>
            </div>
            <div>
<img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" style="max-height: 60px;">
            </div>
        </div>

        <div class="receipt-details">
            <div>
                <h3>From:</h3>
                <p><strong>MPG Solution Pvt. Ltd.</strong></p>
                <p>Pokhara 15, Kaski</p>
                <p>www.mpg.com.np</p>
                <p>info@adsmpg.com</p>
                <p>WhatsApp: +977 9856000601</p>
            </div>
            <div>
                <h3>To:</h3>
                <p><strong><?php echo e($customer->name); ?></strong></p>
                <p><?php echo e($customer->address); ?></p>
                <p><?php echo e($customer->email); ?></p>
                <p><?php echo e($customer->phone); ?><?php echo e($customer->phone_2 ? ', ' . $customer->phone_2 : ''); ?></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Service Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i = 1; $i <= $ad->Quantity; $i++): ?>
                <tr>
                    <td>Ad Service (Unit <?php echo e($i); ?>)</td>
                    <td>1</td>
                    <td>Rs <?php echo e(number_format($ad->NRP / $ad->Quantity, 2)); ?></td>
                    <td>Rs <?php echo e(number_format($ad->NRP / $ad->Quantity, 2)); ?></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div class="receipt-summary">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>Rs <?php echo e(number_format($ad->NRP, 2)); ?></td>
                </tr>
                <tr class="total">
                    <td>Total:</td>
                    <td>Rs <?php echo e(number_format($ad->NRP, 2)); ?></td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for choosing MPG Solution!</p>
            <p>If you have any questions, feel free to contact us at info@adsmpg.com</p>
        </div>

        <div style="text-align: center;">
            <a href="<?php echo e(url('/receipt/pdf_gen/' . $ad->id)); ?>" class="btn">Download Receipt</a>
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/downloadable/receipt.blade.php ENDPATH**/ ?>