

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h3 class="mb-4">Generate AI Content Calendar</h3>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('admin.smmx.calendar.generate')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control" required>
                            <option value="">Select Customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Month</label>
                        <input type="number" name="report_month" class="form-control" min="1" max="12" value="<?php echo e(date('n')); ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Year</label>
                        <input type="number" name="report_year" class="form-control" min="2000" max="2100" value="<?php echo e(date('Y')); ?>" required>
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <div>
                            <input type="checkbox" name="replace_existing" value="1"> Replace Existing
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Generate Draft</button>
                <a href="<?php echo e(route('admin.smmx.calendar.index')); ?>" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/calendar/generate.blade.php ENDPATH**/ ?>