
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
            <div class="smmx-toolbar">
                <div class="smmx-toolbar-left">
                    <h4>Social Media Customers</h4>
                    <p>All customers under social media marketing service with package, progress, approvals and quick access.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <div class="smmx-info-panel">
                <h5>How this works</h5>
                <p>Open a customer panel to see full requirements, assigned package, monthly plan, work logs and report summary in one place.</p>
            </div>

            <div class="card smmx-card-accent">
                <div class="card-header">
                    <h3 class="card-title">Customers List</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Brand / Business</th>
                                <th>Package</th>
                                <th>Goal</th>
                                <th>Progress</th>
                                <th>Pending Tasks</th>
                                <th>Approval</th>
                                <th>Report</th>
                                <th>Assigned Staff</th>
                                <th>Last Activity</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($item->name); ?></strong>
                                        <?php if($item->phone): ?>
                                            <div class="text-muted small"><?php echo e($item->phone); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo e($item->brand_name); ?></strong>
                                        <div class="text-muted small"><?php echo e($item->business_name); ?></div>
                                    </td>
                                    <td><?php echo e($item->package_name); ?></td>
                                    <td><?php echo e($item->goal); ?></td>
                                    <td>
                                        <strong><?php echo e($item->completion_rate); ?>%</strong>
                                        <div class="smmx-progress-wrap">
                                            <div class="smmx-progress">
                                                <div class="smmx-progress-bar" data-width="<?php echo e($item->completion_rate); ?>%" style="width: <?php echo e($item->completion_rate); ?>%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="smmx-badge <?php echo e($item->pending_logs > 0 ? 'smmx-badge-warning' : 'smmx-badge-success'); ?>">
                                            <?php echo e($item->pending_logs); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($item->approval_status); ?></td>
                                    <td>
                                        <span class="smmx-badge <?php echo e(strtolower($item->report_status) === 'sent' ? 'smmx-badge-success' : 'smmx-badge-danger'); ?>">
                                            <?php echo e($item->report_status); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($item->assigned_staff); ?></td>
                                    <td><?php echo e($item->last_activity); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.smmx.customers.show', $item->id)); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-folder-open mr-1"></i> Open
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="11" class="text-center">No social media customers found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/customers/index.blade.php ENDPATH**/ ?>