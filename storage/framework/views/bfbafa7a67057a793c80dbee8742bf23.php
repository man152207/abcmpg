

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Edit User</h3>
        </div>
        <div class="card-body">
            <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

<form method="POST"
      action="<?php echo e(url('/admin/dashboard/user/update/' . $user->id)); ?>"
      enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    
    <div class="mb-3">
        <label class="form-label d-block">Current Photo</label>
        <?php
            use Illuminate\Support\Str;
            $img = $user->profile_picture
                ? (Str::startsWith($user->profile_picture, ['http://','https://'])
                    ? $user->profile_picture
                    : asset('storage/'.$user->profile_picture))
                : null;
        ?>
        <?php if($img): ?>
            <img src="<?php echo e($img); ?>" alt="Profile"
                 class="rounded"
                 style="height:90px;width:90px;object-fit:cover;border:1px solid #ddd;">
        <?php else: ?>
            <span class="text-muted">No image uploaded</span>
        <?php endif; ?>
    </div>

<div class="mb-3">
    <label class="form-label d-block">Departments</label>
    <?php $deps = $user->departments ?? collect(); ?>
    <?php $__empty_1 = true; $__currentLoopData = $deps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <span class="badge badge-info mr-1"><?php echo e($d->name); ?></span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <span class="text-muted">Not assigned</span>
    <?php endif; ?>
    <a href="<?php echo e(url('/admin/dashboard/user/privilege/' . $user->id)); ?>" class="btn btn-sm btn-warning ml-2">
        Edit Departments
    </a>
</div>

    
    <div class="mb-3">
        <label for="profile_picture" class="form-label">Upload New Photo</label>
        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
        <small class="text-muted d-block mt-1">JPG/PNG/WebP, max 2MB</small>
        <?php $__errorArgs = ['profile_picture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" name="password">
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" name="password_confirmation">
        <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone:</label>
        <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" required>
        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <button type="submit" class="btn btn-primary">Update User</button>
</form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/user/update.blade.php ENDPATH**/ ?>