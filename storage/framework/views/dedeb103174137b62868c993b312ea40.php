
<?php $__env->startSection('title', 'Invoice List'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Layout Base */
body, html {
    margin: 0;
    padding: 0;
    width: 100%;
}
.container {
    max-width: 100%;
    padding: 15px;
}

/* Summary Cards */
.top-summary-section {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: space-between;
    margin-bottom: 20px;
}
.top-summary-card {
    flex: 1;
    min-width: 200px;
    text-align: center;
    padding: 20px;
    color: white;
    border-radius: 10px;
    background-color: #2e4c72;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.top-summary-card h4 {
    font-size: 16px;
    margin-bottom: 10px;
}
.top-summary-card p {
    font-size: 20px;
    font-weight: bold;
}
.due-date {
    font-size: 14px;
    font-weight: normal;
    color: #ffd700;
}
.due-date span {
    color: red;
    font-weight: bold;
    font-size: 16px;
}

/* Profile Summary */
.custom-profile-card {
    background-color: #16a085;
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.custom-profile-card h3 {
    font-size: 24px;
    margin-bottom: 20px;
}
.custom-profile-card p {
    margin: 5px 0;
    font-size: 16px;
}

/* Invoice Section */
.invoice-section {
    margin-top: 0px;
    padding: 10px;
}
.invoice-section h3 {
    background-color: #3498db;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}
.invoice-section table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.invoice-section th, .invoice-section td {
    border: 1px solid #ddd;
    padding: 5px;
    text-align: left;
}
.invoice-section th {
    background-color: #f3f3f3;
    font-weight: bold;
}
.invoice-section .btn {
    margin-right: 5px;
}

/* Table Styling */
.table {
    width: 100%;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.table th, .table td {
    padding: 15px;
    text-align: center;
    word-wrap: break-word;
    border: 1px solid #ddd;
}
.table th {
    background-color: #16a085;
    color: #fff;
}
.table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Badge Styling */
.badge {
    display: inline-block;
    padding: 0.5em 0.75em;
    border-radius: 5px;
    font-size: 0.85rem;
    color: white;
    font-weight: bold;
}
.payment-status-pending { background-color: #ffc107; }
.payment-status-paused { background-color: #6c757d; }
.payment-status-fpy-received { background-color: #28a745; }
.payment-status-esewa-received { background-color: #007bff; }
.payment-status-baki { background-color: #17a2b8; }
.payment-status-paid { background-color: #28a745; }
.payment-status-refunded { background-color: #dc3545; }
.payment-status-cancelled { background-color: #343a40; }
.payment-status-overpaid { background-color: #6f42c1; }
.payment-status-pv-adjusted { background-color: #20c997; }

/* Button Styling */
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    border: 1px solid transparent;
    padding: 0.1rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: all 0.15s ease-in-out;
}

/* Responsive Tables */
@media (max-width: 768px) {
    .table th, .table td {
        padding: 10px;
        font-size: 14px;
    }
}
@media (max-width: 480px) {
    .table th, .table td {
        padding: 8px;
        font-size: 12px;
    }
}

</style>
<div class="container mt-4">
    <div class="invoice-section">
        <h3>All Receipts</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice Date</th>
                        <th>Ad Details</th>
                        <th>Total Amount (NPR)</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($ad->created_at ? $ad->created_at->format('F j, Y') : 'N/A'); ?></td>
                            <td><?php echo e($ad->Ad_Nature_Page ?? 'Ad Campaign'); ?></td>
                            <td>Rs. <?php echo e(number_format($ad->NRP, 2)); ?></td>
                            <td>
                                <span class="badge payment-status-<?php echo e(strtolower(str_replace(' ', '-', $ad->Payment))); ?>">
                                    <?php echo e($ad->Payment); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(url('/receipt/show/' . $ad->id)); ?>" class="btn btn-info btn-sm">View Invoice</a>
                                <a href="<?php echo e(url('/receipt/pdf_gen/' . $ad->id)); ?>" class="btn btn-success btn-sm">Download PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center">No invoices found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customerlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/auth/invoicelists.blade.php ENDPATH**/ ?>