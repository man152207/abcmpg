
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
            <div class="smmx-toolbar">
                <div class="smmx-toolbar-left">
                    <h4><?php echo e($customer->name); ?> - Social Media Panel</h4>
                    <p>Single customer workspace with overview, package, monthly plan, work logs and report summary.</p>
                </div>
                <div class="d-flex flex-wrap" style="gap:10px;">
                    <a href="<?php echo e(route('admin.smmx.customers.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>

                    <?php if($onboarding): ?>
                        <a href="<?php echo e(route('admin.smmx.onboarding.edit', $onboarding->id)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i> Edit Overview
                        </a>
                    <?php endif; ?>

                    <?php if($deliverable): ?>
                        <a href="<?php echo e(route('admin.smmx.deliverables.edit', $deliverable->id)); ?>" class="btn btn-info">
                            <i class="fas fa-calendar-alt mr-1"></i> Update Monthly Plan
                        </a>
                    <?php endif; ?>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#workLogModal">
                        <i class="fas fa-plus mr-1"></i> Add Work Log
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="smmx-stat-grid">
                <div class="smmx-stat-card">
                    <div class="label">Package</div>
                    <div class="value" style="font-size:20px;"><?php echo e($package->name ?? '-'); ?></div>
                    <div class="icon"><i class="fas fa-box-open"></i></div>
                </div>

                <div class="smmx-stat-card">
                    <div class="label">Completion Rate</div>
                    <div class="value"><?php echo e($stats['completion_rate']); ?>%</div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>

                <div class="smmx-stat-card">
                    <div class="label">Pending Tasks</div>
                    <div class="value"><?php echo e($stats['pending_tasks']); ?></div>
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                </div>

                <div class="smmx-stat-card">
                    <div class="label">Completed Tasks</div>
                    <div class="value"><?php echo e($stats['completed_tasks']); ?></div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="card smmx-card-accent">
                        <div class="card-header">
                            <h3 class="card-title">Customer Overview & Requirements</h3>
                        </div>
                        <div class="card-body">
                            <div class="smmx-detail-list">
                                <div class="smmx-detail-item">
                                    <div class="key">Business Name</div>
                                    <div class="value"><?php echo e($onboarding->business_name ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Brand Name</div>
                                    <div class="value"><?php echo e($onboarding->brand_name ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Primary Goal</div>
                                    <div class="value"><?php echo e($onboarding->primary_goal ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Preferred Language</div>
                                    <div class="value"><?php echo e($onboarding->preferred_language ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Target Location</div>
                                    <div class="value"><?php echo e($onboarding->target_location ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Target Age Group</div>
                                    <div class="value"><?php echo e($onboarding->target_age_group ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Target Gender</div>
                                    <div class="value"><?php echo e($onboarding->target_gender ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Brand Colors</div>
                                    <div class="value"><?php echo e($onboarding->brand_colors ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Approval Contact</div>
                                    <div class="value"><?php echo e($onboarding->approval_contact ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Monthly Budget</div>
                                    <div class="value"><?php echo e($onboarding->monthly_budget ?? '-'); ?></div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group">
                                <label>Target Interests</label>
                                <div class="p-3 bg-light rounded"><?php echo e($onboarding->target_interests ?? '-'); ?></div>
                            </div>

                            <div class="form-group">
                                <label>Content Preferences</label>
                                <div class="p-3 bg-light rounded"><?php echo e($onboarding->content_preferences ?? '-'); ?></div>
                            </div>

                            <div class="form-group mb-0">
                                <label>Important Notes</label>
                                <div class="p-3 bg-light rounded"><?php echo e($onboarding->notes ?? '-'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card smmx-card-accent">
                        <div class="card-header">
                            <h3 class="card-title">Current Package & Monthly Plan</h3>
                        </div>
                        <div class="card-body">
                            <div class="smmx-detail-list">
                                <div class="smmx-detail-item">
                                    <div class="key">Assigned Package</div>
                                    <div class="value"><?php echo e($package->name ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Current Month</div>
                                    <div class="value"><?php echo e($deliverable ? $deliverable->report_month.'/'.$deliverable->report_year : '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Posts</div>
                                    <div class="value"><?php echo e($deliverable ? $deliverable->posts_completed.'/'.$deliverable->posts_planned : '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Graphics</div>
                                    <div class="value"><?php echo e($deliverable ? $deliverable->graphics_completed.'/'.$deliverable->graphics_planned : '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Reels</div>
                                    <div class="value"><?php echo e($deliverable ? $deliverable->reels_completed.'/'.$deliverable->reels_planned : '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Stories</div>
                                    <div class="value"><?php echo e($deliverable ? $deliverable->stories_completed.'/'.$deliverable->stories_planned : '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Ad Spend Planned</div>
                                    <div class="value"><?php echo e($deliverable->ad_spend_planned ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Ad Spend Used</div>
                                    <div class="value"><?php echo e($deliverable->ad_spend_used ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Approval Status</div>
                                    <div class="value"><?php echo e($deliverable->approval_status ?? '-'); ?></div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Report Status</div>
                                    <div class="value"><?php echo e(isset($deliverable) && $deliverable->report_sent ? 'Sent' : 'Pending'); ?></div>
                                </div>
                            </div>

                            <?php if($deliverable): ?>
                                <hr class="my-4">
                                <div class="smmx-progress-label">
                                    <span>Completion Progress</span>
                                    <span><?php echo e($deliverable->completion_rate); ?>%</span>
                                </div>
                                <div class="smmx-progress">
                                    <div class="smmx-progress-bar" data-width="<?php echo e($deliverable->completion_rate); ?>%" style="width: <?php echo e($deliverable->completion_rate); ?>%;"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card smmx-card-accent">
                        <div class="card-header">
                            <h3 class="card-title">Latest Report Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="smmx-detail-list">
                                <div class="smmx-detail-item">
                                    <div class="key">Reach</div>
                                    <div class="value"><?php echo e($report->total_reach ?? '-'); ?></div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Impressions</div>
                                    <div class="value"><?php echo e($report->total_impressions ?? '-'); ?></div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Leads</div>
                                    <div class="value"><?php echo e($report->total_leads ?? '-'); ?></div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Messages</div>
                                    <div class="value"><?php echo e($report->total_messages ?? '-'); ?></div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Best Performer</div>
                                    <div class="value"><?php echo e($report->best_performing_content ?? '-'); ?></div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Report Status</div>
                                    <div class="value"><?php echo e($report->report_status ?? '-'); ?></div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="form-group mb-0">
                                <label>Summary Remark</label>
                                <div class="p-3 bg-light rounded"><?php echo e($report->summary_remark ?? '-'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card smmx-card-accent">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Work Log / Activity Table</h3>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#workLogModal">
                        <i class="fas fa-plus mr-1"></i> Add Work Log
                    </button>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Link</th>
                                <th>Remark</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $workLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(optional($log->work_date)->format('Y-m-d')); ?></td>
                                    <td><?php echo e($log->work_type); ?></td>
                                    <td><strong><?php echo e($log->title); ?></strong></td>
                                    <td><?php echo e($log->description); ?></td>
                                    <td><?php echo e($log->quantity); ?></td>
                                    <td>
                                        <?php
                                            $statusClass = 'smmx-badge-dark';
                                            if ($log->status === 'done') $statusClass = 'smmx-badge-success';
                                            elseif ($log->status === 'pending') $statusClass = 'smmx-badge-danger';
                                            elseif ($log->status === 'in_progress') $statusClass = 'smmx-badge-primary';
                                            elseif ($log->status === 'waiting_approval') $statusClass = 'smmx-badge-warning';
                                        ?>
                                        <span class="smmx-badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $log->status))); ?></span>
                                    </td>
                                    <td><?php echo e($log->assigned_to); ?></td>
                                    <td>
                                        <?php if($log->asset_link): ?>
                                            <a href="<?php echo e($log->asset_link); ?>" target="_blank" data-toggle="tooltip" title="View Asset"><i class="fas fa-file"></i></a>
                                        <?php elseif($log->external_link): ?>
                                            <a href="<?php echo e($log->external_link); ?>" target="_blank" data-toggle="tooltip" title="Open Link"><i class="fas fa-external-link-alt"></i></a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($log->remark); ?></td>
                                    <td>
                                        <form action="<?php echo e(route('admin.smmx.customers.worklog.delete', [$customer->id, $log->id])); ?>" method="POST" onsubmit="return confirm('Delete this work log?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4">No work logs found for this customer.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white">
                    <?php echo e($workLogs->links()); ?>

                </div>
            </div>

        </div>
    </section>
</div>

<?php echo $__env->make('admin.smmx.customers.partials.worklog-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/customers/show.blade.php ENDPATH**/ ?>