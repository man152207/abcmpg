
<?php $__env->startSection('title','Reception | Dues Report'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card">
    <div class="card-body">
      <h5>Dues List</h5>
      <table class="table">
        <thead><tr><th>Student</th><th>Batch</th><th>Due Amount</th></tr></thead>
        <tbody>
          <?php $__currentLoopData = $dues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($due->student->full_name); ?></td>
              <td><?php echo e($due->batch->course->title); ?></td>
              <td>Rs. <?php echo e(number_format($due->due_amount,2)); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
      <a href="<?php echo e(route('recp.report.export.dues')); ?>" class="btn btn-success">Export Excel</a>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/reports/dues_list.blade.php ENDPATH**/ ?>