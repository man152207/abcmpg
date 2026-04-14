<!-- resources/views/admin/credit/list.blade.php -->

 <!-- Assuming you have a layout file, adjust as needed -->

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header" style="display: inline-flex;">
                <h3>Credit List</h3>
                <div>
                    <a class="btn btn-primary" href="<?php echo e(route('credit.add')); ?>" style=" margin-left:80%;display: inline-flex;">AddNew</a>
                </div>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('search_credit_list')); ?>" method="get">
                    <?php echo csrf_field(); ?>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="search">Card Number</label>
                            <input type="text" name="search" placeholder="Search by card number" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <div><br></div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search fa-fw"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Card ID</th>
                            <th>Card Number</th>
                            <th>USD</th>
                            <th>By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $credits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($credit->id); ?></td>
                            <td><?php echo e($credit->card_id); ?></td>
                            <td><?php echo e($credit->card_number); ?></td>
                            <td><?php echo e($credit->USD); ?></td>
                            <td><?php echo e($credit->by); ?></td>
                            <td><?php echo e($credit->created_at); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($credits->appends(request()->query())->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/card/credit/list.blade.php ENDPATH**/ ?>