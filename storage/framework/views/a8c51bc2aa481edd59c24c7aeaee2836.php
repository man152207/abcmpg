

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/smmx/css/smmx.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/smmx/js/smmx.js')); ?>"></script>
<script>
    // Optional: enhance tooltips, etc.
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php $__env->stopPush(); ?>


    <section class="content-header">
        <div class="container-fluid">
            <h1>Monthly Report Details</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e($report->customer->name ?? '-'); ?> - <?php echo e($report->report_month); ?>/<?php echo e($report->report_year); ?></h3>
                </div>
                <div class="card-body">
                    <p><strong>Reach:</strong> <?php echo e($report->total_reach); ?></p>
                    <p><strong>Impressions:</strong> <?php echo e($report->total_impressions); ?></p>
                    <p><strong>Leads:</strong> <?php echo e($report->total_leads); ?></p>
                    <p><strong>Messages:</strong> <?php echo e($report->total_messages); ?></p>
                    <p><strong>Total Spend:</strong> <?php echo e($report->total_spend); ?></p>
                    <p><strong>Completion Rate:</strong> <?php echo e($report->completion_rate); ?>%</p>
                    <p><strong>Best Performing Content:</strong> <?php echo e($report->best_performing_content); ?></p>
                    <p><strong>Summary Remark:</strong> <?php echo e($report->summary_remark); ?></p>
                    <p><strong>Report Status:</strong> <?php echo e(ucfirst($report->report_status)); ?></p>
                    <p><strong>Sent At:</strong> <?php echo e($report->sent_at); ?></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('admin.smmx.reports.index')); ?>" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/reports/show.blade.php ENDPATH**/ ?>