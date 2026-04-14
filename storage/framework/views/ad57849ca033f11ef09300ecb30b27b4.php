

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>
    body {
        background-color: #f7f8fc;
        font-family: 'Arial', sans-serif;
    }

    #updateCustomer {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
    <!-- Update Customer Section -->
    <div id="updateCustomer">
        <h3>Update Customer</h3>
        <form method="post" action="<?php echo e(url('/admin/dashboard/customer/edit/' . $customer->id)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo e($customer->name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="display_name" class="form-label">Display Name</label>
                <input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo e($customer->display_name); ?>" placeholder="Display Name">
            </div>
            <div class="mb-3">
    <label for="usd_rate" class="form-label">USD Rate</label>
    <input type="number" class="form-control" id="usd_rate" name="usd_rate" value="<?php echo e($customer->usd_rate ?? 170); ?>" step="0.01" required>
</div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo e($customer->email); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo e($customer->address); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e($customer->phone); ?>" required>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                
                <?php if($customer->profile_picture): ?>
                    <img src="<?php echo e(asset('uploads/customers/' . $customer->profile_picture)); ?>" alt="Profile Picture" style="max-width: 150px; margin-top: 10px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="yes" id="remove_profile_picture" name="remove_profile_picture">
                        <label class="form-check-label" for="remove_profile_picture">
                            Remove profile picture
                        </label>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/customer/update.blade.php ENDPATH**/ ?>