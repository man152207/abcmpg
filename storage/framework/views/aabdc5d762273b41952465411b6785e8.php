

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Create Other Exp</h2>

    <form method="post" action="<?php echo e(url('/admin/dashboard/exp/edit/'. $exp->id)); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="date">Date</label>
            <input class="form-control" value="<?php echo e($exp->date); ?>" type="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="customer">Title:</label>
            <input class="form-control" type="text" value="<?php echo e($exp->title); ?>" name="title" required>
        </div>
        <div class="form-group">
            <label for="customer">Amount:</label>
            <input class="form-control" type="number" value="<?php echo e($exp->amount); ?>" step="0.01" name="amount" required>
        </div>
        <div class="form-group">
            <label for="note">Note:</label>
            <textarea class="form-control" name="note" id="note" cols="30" rows="10"><?php echo e($exp->note); ?></textarea>
        </div>
        <button class="btn btn-primary" type="submit">update Exp</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/client/other_exp/update.blade.php ENDPATH**/ ?>