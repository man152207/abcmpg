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

<?php
    $isEdit = isset($item);
?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($customer->id); ?>"
                        <?php echo e(old('customer_id', $item->customer_id ?? '') == $customer->id ? 'selected' : ''); ?>>
                        <?php echo e($customer->name ?? ('Customer #'.$customer->id)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Package</label>
            <select name="package_id" class="form-control">
                <option value="">Select Package</option>
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($package->id); ?>"
                        <?php echo e(old('package_id', $item->package_id ?? '') == $package->id ? 'selected' : ''); ?>>
                        <?php echo e($package->name ?? ('Package #'.$package->id)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
</div><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/partials/form.blade.php ENDPATH**/ ?>