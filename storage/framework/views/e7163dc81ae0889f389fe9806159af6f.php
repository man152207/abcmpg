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

<div class="modal fade" id="workLogModal" tabindex="-1" role="dialog" aria-labelledby="workLogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="<?php echo e(route('admin.smmx.customers.worklog.store', $customer->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content" style="border-radius:16px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="workLogModalLabel">Add Work Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Work Date</label>
                                <input type="date" name="work_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Month</label>
                                <input type="number" name="report_month" class="form-control" value="<?php echo e(now()->month); ?>" min="1" max="12" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Year</label>
                                <input type="number" name="report_year" class="form-control" value="<?php echo e(now()->year); ?>" min="2000" max="2100" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Work Type</label>
                                <select name="work_type" class="form-control">
                                    <option value="post">Post</option>
                                    <option value="graphic">Graphic</option>
                                    <option value="reel">Reel</option>
                                    <option value="story">Story</option>
                                    <option value="ad_campaign">Ad Campaign</option>
                                    <option value="caption">Caption</option>
                                    <option value="report">Report</option>
                                    <option value="approval">Approval</option>
                                    <option value="meeting">Meeting</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="done">Done</option>
                                    <option value="waiting_approval">Waiting Approval</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Example: Premium house reel draft completed" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Write work details..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Assigned To</label>
                                <input type="text" name="assigned_to" class="form-control" placeholder="Designer / Ads Manager / Content Writer">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Asset Link</label>
                                <input type="text" name="asset_link" class="form-control" placeholder="Canva / Drive / file link">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>External Link</label>
                                <input type="text" name="external_link" class="form-control" placeholder="Published link / URL">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label>Remark</label>
                        <textarea name="remark" class="form-control" placeholder="Any note, client feedback, next action..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Save Work Log</button>
                </div>
            </div>
        </form>
    </div>
</div><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/smmx/customers/partials/worklog-modal.blade.php ENDPATH**/ ?>