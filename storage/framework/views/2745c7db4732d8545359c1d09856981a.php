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
    <h2>All Receipts for <?php echo e($customer->name); ?> (<?php echo e($customer->phone); ?>)</h2>
    <p>Date Range: <?php echo e($daterange); ?> | <strong>Total Spend: Rs <?php echo e(number_format($ads->sum('NRP'), 2)); ?></strong></p>
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
            <?php $__empty_1 = true; $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($ad->created_at->format('Y-m-d')); ?></td>
                    <td><?php echo e($ad->Ad_Nature_Page ?? 'Ad Campaign'); ?></td>
                    <td><?php echo e($ad->Quantity ?? 'N/A'); ?></td>
                    <td>Rs <?php echo e(number_format($ad->NRP, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5">No data available for selected date range.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/downloadable/all_receipts_pdf.blade.php ENDPATH**/ ?>