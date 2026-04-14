

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/smmx/css/smmx.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/smmx/js/smmx.js')); ?>"></script>
<script>
    // Optional: enhance tooltips, etc.
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php $__env->stopPush(); ?>

    <section class="content-header">
        <div class="container-fluid">
            <h1>Create Deliverable</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($error); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.smmx.deliverables.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Monthly Deliverable Entry</h3></div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <select name="customer_id" class="form-control" required>
                                        <option value="">Select Customer</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name ?? ('Customer #'.$customer->id)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Package</label>
                                    <select name="package_id" class="form-control">
                                        <option value="">Select Package</option>
                                        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($package->id); ?>"><?php echo e($package->name ?? ('Package #'.$package->id)); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Onboarding</label>
                                    <select name="onboarding_id" class="form-control">
                                        <option value="">Select Onboarding</option>
                                        <?php $__currentLoopData = $onboardings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $onboarding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($onboarding->id); ?>"><?php echo e($onboarding->business_name); ?> (#<?php echo e($onboarding->id); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Month</label><input type="number" min="1" max="12" name="report_month" class="form-control" required></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Year</label><input type="number" min="2000" max="2100" name="report_year" class="form-control" required></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Campaign Objective</label><input type="text" name="campaign_objective" class="form-control"></div></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="report_sent">Report Sent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Posts Planned</label><input type="number" name="posts_planned" class="form-control" value="0"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Posts Completed</label><input type="number" name="posts_completed" class="form-control" value="0"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Graphics Planned</label><input type="number" name="graphics_planned" class="form-control" value="0"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Graphics Completed</label><input type="number" name="graphics_completed" class="form-control" value="0"></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Reels Planned</label><input type="number" name="reels_planned" class="form-control" value="0"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Reels Completed</label><input type="number" name="reels_completed" class="form-control" value="0"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Stories Planned</label><input type="number" name="stories_planned" class="form-control" value="0"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Stories Completed</label><input type="number" name="stories_completed" class="form-control" value="0"></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Ad Spend Planned</label><input type="number" step="0.01" name="ad_spend_planned" class="form-control"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Ad Spend Used</label><input type="number" step="0.01" name="ad_spend_used" class="form-control"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Approval Status</label><input type="text" name="approval_status" class="form-control"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Report Sent</label><br><input type="checkbox" name="report_sent" value="1"></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Assigned Staff (one per line)</label><textarea name="assigned_staff_text" class="form-control"></textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Pending Items</label><textarea name="pending_items" class="form-control"></textarea></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><div class="form-group"><label>Canva Link</label><input type="text" name="canva_link" class="form-control"></div></div>
                            <div class="col-md-4"><div class="form-group"><label>Drive Link</label><input type="text" name="drive_link" class="form-control"></div></div>
                            <div class="col-md-4"><div class="form-group"><label>Final Link</label><input type="text" name="final_link" class="form-control"></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Planned Date</label><input type="date" name="planned_date" class="form-control"></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Published Date</label><input type="date" name="published_date" class="form-control"></div></div>
                        </div>

                        <div class="form-group"><label>Next Action</label><textarea name="next_action" class="form-control"></textarea></div>
                        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control"></textarea></div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Save Deliverable</button>
                        <a href="<?php echo e(route('admin.smmx.deliverables.index')); ?>" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/deliverables/create.blade.php ENDPATH**/ ?>