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
<div class="card mb-3">
    <div class="card-body">
        <h5 class="mb-3">Quick Notes</h5>
        <p class="mb-0 text-muted">
            This module is intentionally separated from old ad/requirements modules using the unique <strong>smmx</strong> prefix.
        </p>
    </div>
</div><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/partials/filters.blade.php ENDPATH**/ ?>