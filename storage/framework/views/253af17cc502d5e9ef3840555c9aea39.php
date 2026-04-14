

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h3 class="mb-4">Edit Calendar Item</h3>

    <form action="<?php echo e(route('admin.smmx.calendar.update', $item->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('admin.smmx.calendar._form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <button type="submit" class="btn btn-primary">Update Item</button>
        <a href="<?php echo e(route('admin.smmx.calendar.index')); ?>" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/calendar/edit.blade.php ENDPATH**/ ?>