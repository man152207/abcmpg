
<?php $__env->startSection('title','Reception | Enrollments'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead><tr><th>#</th><th>Student</th><th>Batch</th><th>Fee</th><th>Due</th><th>Actions</th></tr></thead>
          <tbody>
            <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($enroll->id); ?></td>
                <td><?php echo e($enroll->student->full_name); ?></td>
                <td><?php echo e($enroll->batch->course->title); ?> - <?php echo e($enroll->batch->name); ?></td>
                <td>Rs. <?php echo e(number_format($enroll->fee_agreed - $enroll->discount,2)); ?></td>
                <td>Rs. <?php echo e(number_format($enroll->due_amount,2)); ?></td>
                <td>
                  <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('recp.enroll.edit', $enroll)); ?>">Edit</a>
                  <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?php echo e($enroll->id); ?>)">Delete</button>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
        <?php echo e($enrollments->links()); ?>

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(id) {
    // Similar as above
  }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/enrollments/enrollments_list.blade.php ENDPATH**/ ?>