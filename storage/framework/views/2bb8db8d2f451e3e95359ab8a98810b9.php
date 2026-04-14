
<?php $__env->startSection('title', 'Profile Settings'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 30px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
        text-align: center;
        color: #2e4c72;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
        outline: none;
    }

    .form-group img {
        max-width: 100px;
        margin-top: 10px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-primary {
        background-color: #16a085;
        border: none;
        padding: 10px 20px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    .btn-primary:hover {
        background-color: #13876a;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
    }
</style>

<div class="container mt-5">
    <div class="form-container">
        <h2>Profile Settings</h2>
        <form action="<?php echo e(route('customer.updateProfile')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo e($customer->name); ?>" required>
            </div>

            <div class="form-group">
                <label for="display_name">Display Name:</label>
                <input type="text" name="display_name" id="display_name" class="form-control" value="<?php echo e($customer->display_name); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo e($customer->email); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo e($customer->phone); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone_2">Alternate Phone:</label>
                <input type="text" name="phone_2" id="phone_2" class="form-control" value="<?php echo e($customer->phone_2); ?>">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo e($customer->address); ?>" required>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                <?php if($customer->profile_picture): ?>
                    <img src="<?php echo e(asset('uploads/' . $customer->profile_picture)); ?>" alt="Profile Picture">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="facebook_url">Facebook URL:</label>
                <input type="url" name="facebook_url" id="facebook_url" class="form-control" value="<?php echo e($customer->facebook_url); ?>">
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customerlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/auth/profile_settings.blade.php ENDPATH**/ ?>