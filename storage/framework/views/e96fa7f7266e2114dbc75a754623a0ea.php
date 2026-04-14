

<?php $__env->startSection('content'); ?>
<style>
    .table-container {
        display: flex;
        flex-wrap: wrap;
        /* Allow tables to wrap to the next line */
    }

    .table-responsive {
        width: 100%;
        /* Occupy full width on smaller screens */
        margin-bottom: 20px;
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

    h3 {
        margin-bottom: 10px;
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Monthly Expences Summary</h3>
        </div>
        <div class="card-body">
            <!-- <h3>Total summary</h3> -->
            <div class="table-container" style="display:flex;">
                <div class="mr-5">
                    <h3>Card expences</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total USD</th>
                                <th>Total NRP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $monthlySummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($summary->monthYear); ?></td>
                                <td>$ <?php echo e($summary->totalUSD); ?></td>
                                <td>Rs <?php echo e($summary->totalNRP); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                </div>
                <div class="ml-5">
                    <h3>Other Expences summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total NRP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $monthlyExp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($summary->monthYear); ?></td>
                                <td>RS <?php echo e($summary->totalAmt); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                </div>
                <div class="ml-5">
                    <h3>Total Expences summary</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total NRP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $monthlySummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msummary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $monthlyExp; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($msummary->monthYear == $summary->monthYear): ?>
                            <tr>
                                <td><?php echo e($summary->monthYear); ?></td>
                                <td>RS <?php echo e($summary->totalAmt + $msummary->totalNRP); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                </div>
            </div>



            <!-- Pagination for each table can be added here as needed -->
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/client/summary.blade.php ENDPATH**/ ?>