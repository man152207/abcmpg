<?php
    $assetLinks = old('asset_links', $item->asset_links ?? []);
?>

<div class="row">
    <div class="col-md-4 mb-3">
        <label>Customer</label>
        <select name="customer_id" class="form-control" required>
            <option value="">Select Customer</option>
            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($customer->id); ?>" <?php echo e(old('customer_id', $item->customer_id ?? '') == $customer->id ? 'selected' : ''); ?>>
                    <?php echo e($customer->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Onboarding</label>
        <select name="onboarding_id" class="form-control">
            <option value="">Select Onboarding</option>
            <?php $__currentLoopData = $onboardings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $onboarding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($onboarding->id); ?>" <?php echo e(old('onboarding_id', $item->onboarding_id ?? '') == $onboarding->id ? 'selected' : ''); ?>>
                    #<?php echo e($onboarding->id); ?> - <?php echo e($onboarding->business_name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Deliverable</label>
        <select name="deliverable_id" class="form-control">
            <option value="">Select Deliverable</option>
            <?php $__currentLoopData = $deliverables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deliverable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($deliverable->id); ?>" <?php echo e(old('deliverable_id', $item->deliverable_id ?? '') == $deliverable->id ? 'selected' : ''); ?>>
                    #<?php echo e($deliverable->id); ?> - <?php echo e(optional($deliverable->customer)->name); ?> (<?php echo e($deliverable->report_month); ?>/<?php echo e($deliverable->report_year); ?>)
                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-2 mb-3">
        <label>Month</label>
        <input type="number" name="report_month" class="form-control" min="1" max="12" value="<?php echo e(old('report_month', $item->report_month ?? date('n'))); ?>" required>
    </div>

    <div class="col-md-2 mb-3">
        <label>Year</label>
        <input type="number" name="report_year" class="form-control" min="2000" max="2100" value="<?php echo e(old('report_year', $item->report_year ?? date('Y'))); ?>" required>
    </div>

    <div class="col-md-4 mb-3">
        <label>Planned Date</label>
        <input type="date" name="planned_date" class="form-control" value="<?php echo e(old('planned_date', isset($item->planned_date) ? $item->planned_date->format('Y-m-d') : '')); ?>" required>
    </div>

    <div class="col-md-4 mb-3">
        <label>Published Date</label>
        <input type="date" name="published_date" class="form-control" value="<?php echo e(old('published_date', isset($item->published_date) ? $item->published_date->format('Y-m-d') : '')); ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label>Platform</label>
        <select name="platform" class="form-control" required>
            <?php $__currentLoopData = ['facebook', 'instagram', 'tiktok', 'all']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($platform); ?>" <?php echo e(old('platform', $item->platform ?? 'facebook') == $platform ? 'selected' : ''); ?>>
                    <?php echo e(ucfirst($platform)); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Content Type</label>
        <select name="content_type" class="form-control" required>
            <?php $__currentLoopData = ['post', 'reel', 'story', 'carousel', 'graphic']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type); ?>" <?php echo e(old('content_type', $item->content_type ?? 'post') == $type ? 'selected' : ''); ?>>
                    <?php echo e(ucfirst($type)); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Content Pillar</label>
        <select name="content_pillar" class="form-control">
            <option value="">Select Pillar</option>
            <?php $__currentLoopData = ['promotional', 'educational', 'engagement', 'social_proof', 'offer', 'festival', 'behind_the_scenes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pillar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pillar); ?>" <?php echo e(old('content_pillar', $item->content_pillar ?? '') == $pillar ? 'selected' : ''); ?>>
                    <?php echo e(ucwords(str_replace('_', ' ', $pillar))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-12 mb-3">
        <label>Title / Topic</label>
        <input type="text" name="title" class="form-control" value="<?php echo e(old('title', $item->title ?? '')); ?>" required>
    </div>

    <div class="col-md-12 mb-3">
        <label>Brief</label>
        <textarea name="brief" class="form-control" rows="3"><?php echo e(old('brief', $item->brief ?? '')); ?></textarea>
    </div>

    <div class="col-md-12 mb-3">
        <label>Caption Text</label>
        <textarea name="caption_text" class="form-control" rows="5"><?php echo e(old('caption_text', $item->caption_text ?? '')); ?></textarea>
    </div>

    <div class="col-md-12 mb-3">
        <label>Creative Brief</label>
        <textarea name="creative_brief" class="form-control" rows="4"><?php echo e(old('creative_brief', $item->creative_brief ?? '')); ?></textarea>
    </div>

    <div class="col-md-6 mb-3">
        <label>CTA Text</label>
        <input type="text" name="cta_text" class="form-control" value="<?php echo e(old('cta_text', $item->cta_text ?? '')); ?>">
    </div>

    <div class="col-md-6 mb-3">
        <label>Assigned To</label>
        <input type="text" name="assigned_to" class="form-control" value="<?php echo e(old('assigned_to', $item->assigned_to ?? '')); ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label>Design Status</label>
        <select name="design_status" class="form-control">
            <?php $__currentLoopData = ['pending', 'in_progress', 'done']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php echo e(old('design_status', $item->design_status ?? 'pending') == $status ? 'selected' : ''); ?>>
                    <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Approval Status</label>
        <select name="approval_status" class="form-control">
            <?php $__currentLoopData = ['pending', 'sent', 'approved', 'rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php echo e(old('approval_status', $item->approval_status ?? 'pending') == $status ? 'selected' : ''); ?>>
                    <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Publish Status</label>
        <select name="publish_status" class="form-control">
            <?php $__currentLoopData = ['planned', 'scheduled', 'published', 'skipped']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($status); ?>" <?php echo e(old('publish_status', $item->publish_status ?? 'planned') == $status ? 'selected' : ''); ?>>
                    <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Canva Link</label>
        <input type="text" name="canva_link" class="form-control" value="<?php echo e(old('canva_link', $assetLinks['canva_link'] ?? '')); ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label>Drive Link</label>
        <input type="text" name="drive_link" class="form-control" value="<?php echo e(old('drive_link', $assetLinks['drive_link'] ?? '')); ?>">
    </div>

    <div class="col-md-4 mb-3">
        <label>Final Link</label>
        <input type="text" name="final_link" class="form-control" value="<?php echo e(old('final_link', $assetLinks['final_link'] ?? '')); ?>">
    </div>

    <div class="col-md-12 mb-3">
        <label>Remarks</label>
        <textarea name="remarks" class="form-control" rows="3"><?php echo e(old('remarks', $item->remarks ?? '')); ?></textarea>
    </div>
</div><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/calendar/_form.blade.php ENDPATH**/ ?>