<!-- resources/views/admin/customer/list.blade.php -->

 <!-- Assuming you have a layout file, adjust as needed -->

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <h1>Ad Account List</h1>
    <form action="<?php echo e(route('search_ad_account')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="input-group">
            <input type="text" name="search" placeholder="Search by customer name" class="form-control">
            <div style="background-color: grey;" class="input-group-append">
                <button type="submit" class="btn">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($account->name); ?></td>
                <td>
                    <a href="<?php echo e(url('/admin/dashboard/ad_account/edit/'. $account->id)); ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="<?php echo e(url('/admin/dashboard/ad_account/delete/'. $account->id)); ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <ul class="custom-pagination">
        <?php echo e(@$accounts->links('pagination::bootstrap-5')); ?>

    </ul>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/ad_account/list.blade.php ENDPATH**/ ?>