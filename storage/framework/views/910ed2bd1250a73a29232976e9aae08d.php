

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container">
    <h2>Create Other Exp</h2>

    <form method="post" action="<?php echo e(route('exp.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="date">Date</label>
            <input class="form-control" type="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="title">Title:</label>
            <input class="form-control" type="text" name="title" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input class="form-control" type="number" step="0.01" name="amount" required>
        </div>
        <div class="form-group">
            <label for="note">Note:</label>
            <textarea class="form-control" name="note" id="note" cols="30" rows="10"></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Save Exp</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/client/other_exp/add.blade.php ENDPATH**/ ?>