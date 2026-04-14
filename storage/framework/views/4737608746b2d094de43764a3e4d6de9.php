

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Content Calendar</h3>
        <div>
            <a href="<?php echo e(route('admin.smmx.calendar.generate.form')); ?>" class="btn btn-success">Generate AI Draft</a>
            <a href="<?php echo e(route('admin.smmx.calendar.create')); ?>" class="btn btn-primary">Add Item</a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.smmx.calendar.index')); ?>">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <select name="customer_id" class="form-control">
                            <option value="">All Customers</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>" <?php echo e(request('customer_id') == $customer->id ? 'selected' : ''); ?>>
                                    <?php echo e($customer->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <input type="number" name="report_month" class="form-control" placeholder="Month" value="<?php echo e(request('report_month')); ?>">
                    </div>

                    <div class="col-md-2 mb-2">
                        <input type="number" name="report_year" class="form-control" placeholder="Year" value="<?php echo e(request('report_year')); ?>">
                    </div>

                    <div class="col-md-2 mb-2">
                        <select name="platform" class="form-control">
                            <option value="">All Platforms</option>
                            <?php $__currentLoopData = ['facebook', 'instagram', 'tiktok', 'all']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($platform); ?>" <?php echo e(request('platform') == $platform ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($platform)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <select name="publish_status" class="form-control">
                            <option value="">All Publish Status</option>
                            <?php $__currentLoopData = ['planned', 'scheduled', 'published', 'skipped']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php echo e(request('publish_status') == $status ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($status)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-1 mb-2">
                        <button class="btn btn-dark w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Copy Previous Month</div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.smmx.calendar.copy.previous')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <select name="customer_id" class="form-control" required>
                            <option value="">Select Customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <input type="number" name="from_month" class="form-control" placeholder="From M" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" name="from_year" class="form-control" placeholder="From Y" required>
                    </div>
                    <div class="col-md-1 mb-2">
                        <input type="number" name="to_month" class="form-control" placeholder="To M" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" name="to_year" class="form-control" placeholder="To Y" required>
                    </div>
                    <div class="col-md-2 mb-2 d-flex align-items-center">
                        <input type="checkbox" name="replace_existing" value="1" class="mr-2"> Replace Existing
                    </div>
                    <div class="col-md-1 mb-2">
                        <button type="submit" class="btn btn-warning w-100">Copy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Platform</th>
                        <th>Type</th>
                        <th>Pillar</th>
                        <th>Title</th>
                        <th>Design</th>
                        <th>Approval</th>
                        <th>Publish</th>
                        <th>AI</th>
                        <th width="140">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($item->planned_date?->format('Y-m-d')); ?></td>
                            <td><?php echo e(optional($item->customer)->name); ?></td>
                            <td><?php echo e(ucfirst($item->platform)); ?></td>
                            <td><?php echo e(ucfirst($item->content_type)); ?></td>
                            <td><?php echo e($item->content_pillar); ?></td>
                            <td><?php echo e($item->title); ?></td>
                            <td><?php echo e(ucfirst($item->design_status)); ?></td>
                            <td><?php echo e(ucfirst($item->approval_status)); ?></td>
                            <td><?php echo e(ucfirst($item->publish_status)); ?></td>
                            <td><?php echo e($item->ai_generated ? 'Yes' : 'No'); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.smmx.calendar.edit', $item->id)); ?>" class="btn btn-sm btn-primary">Edit</a>
                                <form action="<?php echo e(route('admin.smmx.calendar.destroy', $item->id)); ?>" method="POST" style="display:inline-block;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11" class="text-center">No calendar items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <?php echo e($items->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/calendar/index.blade.php ENDPATH**/ ?>