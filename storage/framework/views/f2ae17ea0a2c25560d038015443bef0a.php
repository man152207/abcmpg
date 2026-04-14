

<?php $__env->startSection('content'); ?>
<style>
    .table-container {
        display: flex;
        justify-content: space-between;
    }

    .table-responsive {
        width: 30%;
        /* Adjust the width as needed */
        margin-right: 10px;
        /* Add some margin between tables */
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Monthly Summary</h3>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('search_credit')); ?>" method="get">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <input type="text" name="search" placeholder="Search by card number (enter correct card number)" class="form-control">
                    <div style="background-color: grey;" class="input-group-append">
                        <button type="submit" class="btn">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- <h3>Total summary</h3> -->
            <div class="table-container">
                <div class="table-responsive">
                    <h3>Total Credit summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $monthlySummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($summary->monthYear); ?></td>
                                <td><?php echo e($summary->totalUSD); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination for each table can be added here as needed -->
            <?php echo e(@$monthlySummaries->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/card/credit/summary.blade.php ENDPATH**/ ?>