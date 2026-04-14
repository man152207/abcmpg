

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
            <h1>SMMX Onboarding</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <?php echo $__env->make('admin.smmx.partials.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Onboarding List</h3>
                    <a href="<?php echo e(route('admin.smmx.onboarding.create')); ?>" class="btn btn-primary btn-sm">Add New</a>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Business</th>
                                <th>Goal</th>
                                <th>Status</th>
                                <th width="220">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item->id); ?></td>
                                    <td><?php echo e($item->customer->name ?? '-'); ?></td>
                                    <td><?php echo e($item->business_name); ?></td>
                                    <td><?php echo e($item->primary_goal ?? '-'); ?></td>
                                    <td><?php echo e(ucfirst($item->status)); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.smmx.onboarding.show', $item->id)); ?>" class="btn btn-info btn-sm">View</a>
                                        <a href="<?php echo e(route('admin.smmx.onboarding.edit', $item->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="<?php echo e(route('admin.smmx.onboarding.destroy', $item->id)); ?>" method="POST" style="display:inline-block;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this onboarding?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No onboarding records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <?php echo e($items->links()); ?>

                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/onboarding/index.blade.php ENDPATH**/ ?>