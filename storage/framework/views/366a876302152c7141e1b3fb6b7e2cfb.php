

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
            <h1>Create Onboarding</h1>
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

            <form action="<?php echo e(route('admin.smmx.onboarding.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Basic Details</h3></div>
                    <div class="card-body">
                        <?php echo $__env->make('admin.smmx.partials.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>Business Name</label><input type="text" name="business_name" class="form-control" value="<?php echo e(old('business_name')); ?>" required></div>
                                <div class="form-group"><label>Brand Name</label><input type="text" name="brand_name" class="form-control" value="<?php echo e(old('brand_name')); ?>"></div>
                                <div class="form-group"><label>Contact Person</label><input type="text" name="contact_person" class="form-control" value="<?php echo e(old('contact_person')); ?>"></div>
                                <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>"></div>
                                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>"></div>
                                <div class="form-group"><label>Business Address</label><textarea name="business_address" class="form-control"><?php echo e(old('business_address')); ?></textarea></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>Facebook Link</label><input type="text" name="facebook_link" class="form-control" value="<?php echo e(old('facebook_link')); ?>"></div>
                                <div class="form-group"><label>Instagram Link</label><input type="text" name="instagram_link" class="form-control" value="<?php echo e(old('instagram_link')); ?>"></div>
                                <div class="form-group"><label>TikTok Link</label><input type="text" name="tiktok_link" class="form-control" value="<?php echo e(old('tiktok_link')); ?>"></div>
                                <div class="form-group"><label>Website Link</label><input type="text" name="website_link" class="form-control" value="<?php echo e(old('website_link')); ?>"></div>
                                <div class="form-group"><label>Page Access Status</label><input type="text" name="page_access_status" class="form-control" value="<?php echo e(old('page_access_status')); ?>"></div>
                                <div class="form-group"><label>Business Manager Status</label><input type="text" name="business_manager_status" class="form-control" value="<?php echo e(old('business_manager_status')); ?>"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>Primary Goal</label><input type="text" name="primary_goal" class="form-control" value="<?php echo e(old('primary_goal')); ?>"></div>
                                <div class="form-group"><label>Target Location</label><input type="text" name="target_location" class="form-control" value="<?php echo e(old('target_location')); ?>"></div>
                                <div class="form-group"><label>Target Age Group</label><input type="text" name="target_age_group" class="form-control" value="<?php echo e(old('target_age_group')); ?>"></div>
                                <div class="form-group"><label>Target Gender</label><input type="text" name="target_gender" class="form-control" value="<?php echo e(old('target_gender')); ?>"></div>
                                <div class="form-group"><label>Target Interests</label><textarea name="target_interests" class="form-control"><?php echo e(old('target_interests')); ?></textarea></div>
                                <div class="form-group"><label>Competitors</label><textarea name="competitors" class="form-control"><?php echo e(old('competitors')); ?></textarea></div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group"><label>Brand Colors</label><input type="text" name="brand_colors" class="form-control" value="<?php echo e(old('brand_colors')); ?>"></div>
                                <div class="form-group"><label>Preferred Language</label><input type="text" name="preferred_language" class="form-control" value="<?php echo e(old('preferred_language')); ?>"></div>
                                <div class="form-group"><label>Content Preferences</label><textarea name="content_preferences" class="form-control"><?php echo e(old('content_preferences')); ?></textarea></div>
                                <div class="form-group"><label>Monthly Budget</label><input type="text" name="monthly_budget" class="form-control" value="<?php echo e(old('monthly_budget')); ?>"></div>
                                <div class="form-group">
                                    <label><input type="checkbox" name="approval_required" value="1" <?php echo e(old('approval_required') ? 'checked' : ''); ?>> Approval Required</label>
                                </div>
                                <div class="form-group"><label>Approval Contact</label><input type="text" name="approval_contact" class="form-control" value="<?php echo e(old('approval_contact')); ?>"></div>
                                <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control"><?php echo e(old('notes')); ?></textarea></div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="draft">Draft</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Save Onboarding</button>
                        <a href="<?php echo e(route('admin.smmx.onboarding.index')); ?>" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/onboarding/create.blade.php ENDPATH**/ ?>