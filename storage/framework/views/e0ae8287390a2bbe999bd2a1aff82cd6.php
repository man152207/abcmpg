

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
            <h1>SMMX Reports</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php echo $__env->make('admin.smmx.partials.stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Monthly Reports</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Month</th>
                                <th>Reach</th>
                                <th>Impressions</th>
                                <th>Leads</th>
                                <th>Messages</th>
                                <th>Spend</th>
                                <th>Completion</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($report->customer->name ?? '-'); ?></td>
                                    <td><?php echo e($report->report_month); ?>/<?php echo e($report->report_year); ?></td>
                                    <td><?php echo e($report->total_reach ?? 0); ?></td>
                                    <td><?php echo e($report->total_impressions ?? 0); ?></td>
                                    <td><?php echo e($report->total_leads ?? 0); ?></td>
                                    <td><?php echo e($report->total_messages ?? 0); ?></td>
                                    <td><?php echo e($report->total_spend ?? 0); ?></td>
                                    <td><?php echo e($report->completion_rate ?? 0); ?>%</td>
                                    <td><?php echo e(ucfirst($report->report_status)); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.smmx.reports.show', $report->id)); ?>" class="btn btn-info btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="10" class="text-center">No reports found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <?php echo e($reports->links()); ?>

                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/reports/index.blade.php ENDPATH**/ ?>