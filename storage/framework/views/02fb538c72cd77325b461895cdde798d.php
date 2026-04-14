
<?php $__env->startSection('title','Reception | New Document'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <h5 class="mb-2">Document for: <?php echo e($student->full_name); ?> (<?php echo e($student->phone); ?>)</h5>
    <form method="post" action="<?php echo e(route('recp.doc.store',$student)); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-2">
        <div class="col-md-4">
          <label class="form-label">Doc Type *</label>
          <input name="doc_type" class="form-control" placeholder="Recommendation / ID Copy / Form Fill" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Doc No</label>
          <input name="doc_no" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Issued At</label>
          <input type="date" name="issued_at" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Fee *</label>
          <input name="fee" class="form-control" value="0" required>
        </div>
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Save</button>
        <a class="btn btn-light" href="<?php echo e(route('recp.students.list')); ?>">Back</a>
      </div>
    </form>
  </div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/documents/new_document.blade.php ENDPATH**/ ?>