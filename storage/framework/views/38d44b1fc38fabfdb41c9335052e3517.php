
<?php $__env->startSection('title','Reception | Student Details'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card">
    <div class="card-body">
      <h5><?php echo e($student->full_name); ?> (<?php echo e($student->phone); ?>)</h5>
      <ul class="nav nav-tabs" id="studentTabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#info">Info</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#enrollments">Enrollments</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payments">Payments</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a></li>
      </ul>
      <div class="tab-content mt-3">
        <div class="tab-pane active" id="info">
          <!-- Student info fields -->
          <p>Email: <?php echo e($student->email ?? 'N/A'); ?></p>
          <!-- ... -->
        </div>
        <div class="tab-pane" id="enrollments">
          <table class="table">
            <!-- List enrollments -->
            <?php $__currentLoopData = $student->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($enroll->batch->course->title); ?></td>
                <td>Due: Rs. <?php echo e(number_format($enroll->due_amount,2)); ?></td>
                <td><a href="<?php echo e(route('recp.payment.create', $enroll)); ?>">Pay</a></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </table>
        </div>
        <div class="tab-pane" id="payments">
          <!-- Similar table for payments -->
        </div>
        <div class="tab-pane" id="documents">
          <!-- Similar table for documents, with download links -->
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/students/student_show.blade.php ENDPATH**/ ?>