<!-- /home/mpgcomnp/app.mpg.com.np/resources/views/client/clientdetails.blade.php -->


<?php $__env->startSection('title', 'Client Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Client Details for: <?php echo e($clients->first()->name); ?></h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>USD</th>
                                <th>Rate</th>
                                <th>NRP</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($client->account); ?></td>
                                    <td><?php echo e($client->USD); ?></td>
                                    <td><?php echo e($client->Rate); ?></td>
                                    <td><?php echo e($client->NRP); ?></td>
                                    <td><?php echo e($client->created_at->format('Y-m-d')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f2f2f2; font-weight: bold;">
                                <td><strong>Total</strong></td>
                                <td><strong><?php echo e($totalUSD); ?></strong></td>
                                <td><strong><?php echo e($totalRate); ?></strong></td>
                                <td><strong><?php echo e($totalNRP); ?></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/client/clientdetails.blade.php ENDPATH**/ ?>