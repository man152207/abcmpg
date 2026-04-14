<!-- resources/views/admin/customer/update.blade.php -->

 <!-- Assuming you have a layout file, adjust as needed -->

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>UPdate Ad Account</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo e(url('/admin/dashboard/ad_account/edit/'. $account->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo e($account->name); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Ad Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/ad_account/update.blade.php ENDPATH**/ ?>