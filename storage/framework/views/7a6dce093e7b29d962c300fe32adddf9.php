
<?php $__env->startSection('title','Reception | Edit Student'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <form method="post" action="<?php echo e(route('recp.students.update',$student)); ?>">
      <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input name="full_name" class="form-control" value="<?php echo e($student->full_name); ?>" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Phone *</label>
          <input name="phone" class="form-control" value="<?php echo e($student->phone); ?>" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo e($student->email); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Guardian</label>
          <input name="guardian_name" class="form-control" value="<?php echo e($student->guardian_name); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Address</label>
          <input name="address" class="form-control" value="<?php echo e($student->address); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">DOB</label>
          <input type="date" name="dob" class="form-control" value="<?php echo e($student->dob); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php $__currentLoopData = ['active','inactive','completed','dropped']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($s); ?>" <?php if($student->status===$s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="2"><?php echo e($student->remarks); ?></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Update</button>
        <a class="btn btn-light" href="<?php echo e(route('recp.students.list')); ?>">Back</a>
      </div>
    </form>
  </div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/students/student_edit.blade.php ENDPATH**/ ?>