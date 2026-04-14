

<?php $__env->startSection('content'); ?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="max-w-xl w-full p-6 bg-white rounded-md shadow-md mx-auto">

    <h2 class="text-2xl font-semibold text-center mb-6">User Registration</h2>

    <form method="POST" action="<?php echo e(route('admin.user.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required autofocus>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input id="password" type="password" name="password" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required autocomplete="new-password">
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
            <input id="phone" type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="mb-2">
            <div class="flex items-center justify-between">
                <label class="block text-gray-700 text-sm font-bold mb-2">Assign Departments</label>
                <div class="space-x-2">
                    <button type="button" id="btnSelAll" class="text-blue-600 text-xs underline">Select all</button>
                    <button type="button" id="btnClearAll" class="text-gray-600 text-xs underline">Clear</button>
                </div>
            </div>

            <?php $__errorArgs = ['departments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['departments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs italic mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="grid grid-cols-2 gap-2 border rounded-md p-3">
                <?php $oldDepts = collect(old('departments', []))->map(fn($v)=>(int)$v)->all(); ?>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="departments[]"
                               value="<?php echo e($dept->id); ?>"
                               class="h-4 w-4"
                               <?php echo e(in_array($dept->id, $oldDepts, true) ? 'checked' : ''); ?>>
                        <span><?php echo e($dept->name); ?></span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p class="text-xs text-gray-500 mt-1">You can also change departments later from “Edit Privilege”.</p>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800">
                Register
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const boxes = Array.from(document.querySelectorAll('input[name="departments[]"]'));
    const btnSelAll = document.getElementById('btnSelAll');
    const btnClear  = document.getElementById('btnClearAll');
    if (btnSelAll) btnSelAll.addEventListener('click', ()=> boxes.forEach(b=> b.checked=true));
    if (btnClear)  btnClear.addEventListener('click', ()=> boxes.forEach(b=> b.checked=false));
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/user/add.blade.php ENDPATH**/ ?>