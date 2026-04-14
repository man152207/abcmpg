

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
            <h1>Onboarding Details</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><?php echo e($item->business_name); ?></h3></div>
                <div class="card-body">
                    <p><strong>Customer:</strong> <?php echo e($item->customer->name ?? '-'); ?></p>
                    <p><strong>Brand Name:</strong> <?php echo e($item->brand_name); ?></p>
                    <p><strong>Contact Person:</strong> <?php echo e($item->contact_person); ?></p>
                    <p><strong>Phone:</strong> <?php echo e($item->phone); ?></p>
                    <p><strong>Email:</strong> <?php echo e($item->email); ?></p>
                    <p><strong>Goal:</strong> <?php echo e($item->primary_goal); ?></p>
                    <p><strong>Target Location:</strong> <?php echo e($item->target_location); ?></p>
                    <p><strong>Target Interests:</strong> <?php echo e($item->target_interests); ?></p>
                    <p><strong>Content Preferences:</strong> <?php echo e($item->content_preferences); ?></p>
                    <p><strong>Approval Contact:</strong> <?php echo e($item->approval_contact); ?></p>
                    <p><strong>Notes:</strong> <?php echo e($item->notes); ?></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('admin.smmx.onboarding.edit', $item->id)); ?>" class="btn btn-warning">Edit</a>
                    <a href="<?php echo e(route('admin.smmx.onboarding.index')); ?>" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/onboarding/show.blade.php ENDPATH**/ ?>