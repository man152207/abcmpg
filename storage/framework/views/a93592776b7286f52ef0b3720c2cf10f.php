

<?php $__env->startSection('title', 'Campaign Insights'); ?>

<?php $__env->startSection('content'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body, html {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}
.insight-table {
    overflow-x: auto;
    width: 100%;
    margin-top: 20px;
}
.table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    min-width: 1000px;
}
.table th, .table td {
    padding: 10px;
    font-size: 13px;
    text-align: center;
    border: 1px solid #ddd;
}
.table th {
    background-color: #16a085;
    color: white;
}
.table tr:nth-child(even) {
    background-color: #f9f9f9;
}
.left-align {
    text-align: left !important;
}
.badge {
    padding: 5px 8px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}
.badge-success { background: #28a745; }
.badge-primary { background: #007bff; }
.badge-warning { background: #ffc107; color: #212529; }
.badge-danger { background: #dc3545; }
.badge-info { background: #17a2b8; }
.badge-secondary { background: #6c757d; }
.btn-insight {
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 4px;
    color: #fff;
    border: none;
}
.btn-warning { background-color: #f7b928; }
.btn-warning:hover { background-color: #e0a824; }
.btn-danger { background-color: #e41e3f; }
.btn-danger:hover { background-color: #c81b36; }
.editable-td input {
    border: 1px solid #ccc;
    padding: 5px;
    font-size: 13px;
    max-width: 200px;
    width: 100%;
}
.alert-warning {
    background: #fff3cd;
    color: #856404;
    text-align: center;
    border-radius: 5px;
    padding: 12px;
    font-size: 14px;
}
</style>

<?php
    $customerId = auth('customer')->id();
    $results = \App\Models\CampaignInsight::where('customer_id', $customerId)->latest()->get();
?>

<div class="container-fluid">
    <h3 class="mb-4"> Meta Ad Campaign Insights</h3>

    <?php if($results->count()): ?>
    <div class="insight-table">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Campaign Name</th>
                    <th>Delivery</th>
                    <th>Budget</th>
                    <th>Results</th>
                    <th>Reach</th>
                    <th>Impressions</th>
                    <th>Cost/Result</th>
                    <th>Spent</th>
                    <th>Ends</th>
                    <th>Schedule</th>
                    <th>Duration</th>
                    <th>Quality</th>
                    <th>Engagement</th>
                    <th>Conversion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td class="left-align" data-id="<?php echo e($row->id); ?>" ondblclick="makeEditable(this)">
                        <?php echo e($row->campaign_name); ?>

                    </td>
                    <?php $status = strtolower($row->delivery); ?>
                    <td><span class="badge badge-<?php echo e($status === 'active' ? 'success' : 
                        ($status === 'completed' ? 'primary' : 
                        ($status === 'paused' ? 'warning' : 
                        ($status === 'in_review' ? 'info' : 
                        ($status === 'rejected' ? 'danger' : 'secondary'))))); ?>">
                        <?php echo e(ucfirst($row->delivery)); ?>

                    </span></td>
                    <td><?php echo e($row->budget); ?></td>
                    <td><?php echo e($row->results); ?></td>
                    <td><?php echo e($row->reach); ?></td>
                    <td><?php echo e($row->impressions); ?></td>
                    <td>$<?php echo e($row->cost_per_result ?? '0.00'); ?></td>
                    <td>$<?php echo e($row->spend); ?></td>
                    <td><?php echo e($row->ends); ?></td>
                    <td><?php echo e($row->schedule); ?></td>
                    <td><?php echo e($row->duration); ?></td>
                    <td><?php echo e($row->quality_rank); ?></td>
                    <td><?php echo e($row->engagement_rank); ?></td>
                    <td><?php echo e($row->conversion_rank); ?></td>
                    <td>
                        <div style="display: flex; gap: 4px; justify-content: center;">
<form action="<?php echo e(route('portal.insights.fetchFromApi', $customerId ?? 1)); ?>" method="POST" class="insight-form" id="insightForm">
    <?php echo csrf_field(); ?>
    <button class="btn btn-sm btn-warning btn-insight">Fetch</button>
</form>
                            <form action="<?php echo e(route('portal.insights.delete', $row->id)); ?>" method="POST" onsubmit="return confirm('Delete this insight?');">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button class="btn btn-sm btn-danger btn-insight">Delete</button>
</form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-warning mt-3">No campaign insights found.</div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customerlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/auth/adsinsights.blade.php ENDPATH**/ ?>