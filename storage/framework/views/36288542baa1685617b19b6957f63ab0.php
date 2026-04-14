

<?php $__env->startSection('content'); ?>
<!-- Bootstrap CSS for styling and responsiveness -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo e(asset('css/custom.css')); ?>">

<style>
    /* Custom Styles for a Compact Layout */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #e9ecef;
    }
    .card {
        border: none;
        border-radius: 10px;
        background-color: #ffffff;
        padding: 10px;
        margin-bottom: 10px;
    }
    .card-header2 {
        background-color: #093b7b;
        color: white;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px 10px 0 0;
    }
    .card-body2 {
        padding: 10px;
    }
    .btn {
        border-radius: 20px;
        font-weight: bold;
        padding: 5px 10px;
    }
    .table-responsive {
        margin-top: 10px;
    }
    .table thead th {
        background-color: #093b7b;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
    }
    .table tbody td {
        padding: 10px;
        font-size: 14px;
    }
    .profile-picture {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>

<div class="container-fluid">
    <div class="card my-2">
        <div class="card-header2 d-flex justify-content-between align-items-center">
            <h3>Users Dashboard</h3>
            <div class="d-flex align-items-center">
                <!-- Total Users Count -->
                <span id="totalCount" class="total-count-display">Users Count: <?php echo e($users->total()); ?></span>
                <!-- Export Button -->
                <button id="exportButton" class="btn btn-success ml-2"><i class="fas fa-file-export"></i> Export</button>
            </div>
        </div>
        <div class="card-body2">
            <!-- Search Form -->
            <form action="<?php echo e(route('search_user')); ?>" method="get" class="mb-2 form-inline">
                <?php echo csrf_field(); ?>
                <div class="input-group w-100">
                    <input type="text" name="search" placeholder="Search by customer name" class="form-control">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Departments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                           <td>
    <a href="<?php echo e(route('admin.user.details', $user->id)); ?>">
        <?php
            $pp = $user->profile_picture;
            $img = $pp
                ? (\Illuminate\Support\Str::startsWith($pp, ['http://','https://'])
                    ? $pp
                    : asset('storage/'.$pp))
                : null;
        ?>

        <?php if($img): ?>
            <img src="<?php echo e($img); ?>" alt="<?php echo e($user->name); ?>" class="profile-picture">
        <?php else: ?>
            <i class="fas fa-user-circle" style="font-size: 50px; color: rgba(0, 0, 0, 0.7);"></i>
        <?php endif; ?>
    </a>
</td>

                            <td>
                                <a href="<?php echo e(route('admin.user.details', $user->id)); ?>" class="clickonname"><?php echo e($user->name); ?></a>
                            </td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <a href="https://wa.me/+977<?php echo e($user->phone); ?>" target="_blank" style="text-decoration: none; color: inherit;">
                                    <strong><?php echo e($user->phone); ?></strong>
                                </a>
                            </td>
                            <td style="max-width:280px;">
    <?php $deps = $user->departments ?? collect(); ?>
    <?php $__empty_1 = true; $__currentLoopData = $deps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <span class="badge badge-info mr-1 mb-1"><?php echo e($d->name); ?></span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <span class="text-muted">—</span>
    <?php endif; ?>
</td>

                            <td>
                                <a href="<?php echo e(route('admin.user.details', $user->id)); ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                
                                <?php if($user->email !== 'info@adsmpg.com'): ?>
                                    <form action="<?php echo e(route('admin.user.details', $user->id)); ?>" method="post" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </form>
                                    <a href="<?php echo e(route('admin.user.details', $user->id)); ?>" class="btn btn-warning btn-sm"><i class="fas fa-key"></i> Edit Privilege</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <?php echo e($users->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>

<script>
    document.getElementById('exportButton').addEventListener('click', function () {
        window.location.href = '/export-users'; // Adjust the URL if needed
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/user/list.blade.php ENDPATH**/ ?>