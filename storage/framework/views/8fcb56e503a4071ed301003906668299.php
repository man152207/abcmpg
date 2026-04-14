<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($cardName); ?> Records</title>
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
    <h1><?php echo e($cardName); ?> Records</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount (USD)</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($record->date); ?></td>
                    <td>$<?php echo e(number_format($record->amount_usd, 2)); ?></td>
                    <td><?php echo e($record->description); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/downloadable/card-records-pdf.blade.php ENDPATH**/ ?>