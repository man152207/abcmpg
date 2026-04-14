

<?php $__env->startSection('title', '2FA Auth Code Logs | MPG Solution'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid mpg-layout">
        <div class="row mpg-layout">
            <div class="col-md-12 mpg-layout">
                <div class="card mpg-layout">
                    <div class="card-header mpg-layout">
                        <h3 class="card-title mpg-layout">Logs for Auth Code: <?php echo e($authCode->account_name); ?></h3>
                        <div class="card-tools mpg-layout">
                            <a href="<?php echo e(route('admin.2fa.index')); ?>" class="btn btn-info btn-sm mpg-layout">Back to Auth Codes</a>
                        </div>
                    </div>
                    <div class="card-body mpg-layout">
                        <table class="table table-bordered table-hover mpg-layout">
                            <thead>
                                <tr class="mpg-layout">
                                    <th>Admin</th>
                                    <th>Device</th>
                                    <th>Location</th>
                                    <th>Generated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="mpg-layout">
                                        <td><?php echo e($log->admin->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($log->device ?? 'N/A'); ?></td>
                                        <td><?php echo e($log->location ?? 'N/A'); ?></td>
                                        <td><?php echo e($log->generated_at ? $log->generated_at->format('Y-m-d H:i:s') : 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="mpg-layout">
                                        <td colspan="4" class="text-center">No logs found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="card-footer mpg-layout">
                            <?php echo e($logs->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/2fa_logs.blade.php ENDPATH**/ ?>