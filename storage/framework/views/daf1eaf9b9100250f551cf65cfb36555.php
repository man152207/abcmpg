

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
            <h1>Deliverable Details</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"><h3 class="card-title"><?php echo e($item->customer->name ?? '-'); ?> - <?php echo e($item->report_month); ?>/<?php echo e($item->report_year); ?></h3></div>
                <div class="card-body">
                    <p><strong>Posts:</strong> <?php echo e($item->posts_completed); ?>/<?php echo e($item->posts_planned); ?></p>
                    <p><strong>Graphics:</strong> <?php echo e($item->graphics_completed); ?>/<?php echo e($item->graphics_planned); ?></p>
                    <p><strong>Reels:</strong> <?php echo e($item->reels_completed); ?>/<?php echo e($item->reels_planned); ?></p>
                    <p><strong>Stories:</strong> <?php echo e($item->stories_completed); ?>/<?php echo e($item->stories_planned); ?></p>
                    <p><strong>Ad Spend:</strong> <?php echo e($item->ad_spend_used); ?>/<?php echo e($item->ad_spend_planned); ?></p>
                    <p><strong>Completion Rate:</strong> <?php echo e($item->completion_rate); ?>%</p>
                    <p><strong>Approval Status:</strong> <?php echo e($item->approval_status); ?></p>
                    <p><strong>Assigned Staff:</strong> <?php echo e(is_array($item->assigned_staff) ? implode(', ', $item->assigned_staff) : '-'); ?></p>
                    <p><strong>Canva Link:</strong> <?php echo e($item->asset_links['canva_link'] ?? '-'); ?></p>
                    <p><strong>Drive Link:</strong> <?php echo e($item->asset_links['drive_link'] ?? '-'); ?></p>
                    <p><strong>Final Link:</strong> <?php echo e($item->asset_links['final_link'] ?? '-'); ?></p>
                    <p><strong>Pending Items:</strong> <?php echo e($item->pending_items); ?></p>
                    <p><strong>Next Action:</strong> <?php echo e($item->next_action); ?></p>
                    <p><strong>Notes:</strong> <?php echo e($item->notes); ?></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('admin.smmx.deliverables.edit', $item->id)); ?>" class="btn btn-warning">Edit</a>
                    <a href="<?php echo e(route('admin.smmx.deliverables.index')); ?>" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/deliverables/show.blade.php ENDPATH**/ ?>