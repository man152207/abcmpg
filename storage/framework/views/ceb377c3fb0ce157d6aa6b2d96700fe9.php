
<?php $__env->startSection('title','Reception | Enroll Student'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <h5 class="mb-2">Enroll: <?php echo e($student->full_name); ?> (<?php echo e($student->phone); ?>)</h5>
    <form method="post" action="<?php echo e(route('recp.enroll.store',$student)); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Batch *</label>
          <select name="batch_id" class="form-select" required>
            <option value="">-- Select Batch --</option>
            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->id); ?>">
                <?php echo e($b->course->title); ?> — <?php echo e($b->name); ?>

                <?php if($b->start_date): ?> (<?php echo e($b->start_date); ?> to <?php echo e($b->end_date); ?>) <?php endif; ?>
              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Enroll Date</label>
          <input type="date" name="enroll_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Fee (Agreed) *</label>
          <input name="fee_agreed" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Discount *</label>
          <input name="discount" class="form-control" value="0" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php $__currentLoopData = ['enrolled','completed','dropped']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($s); ?>"><?php echo e(ucfirst($s)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Save Enrollment</button>
        <a class="btn btn-light" href="<?php echo e(route('recp.students.list')); ?>">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/enrollments/enroll_student.blade.php ENDPATH**/ ?>