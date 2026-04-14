
<?php $__env->startSection('title','Reception | Take Payment'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <h5 class="mb-2">
      Payment — <?php echo e($enrollment->student->full_name); ?>

      (<?php echo e($enrollment->batch->course->title); ?> / <?php echo e($enrollment->batch->name); ?>)
    </h5>
    <p class="mb-2">Due: <strong>Rs. <?php echo e(number_format($enrollment->due_amount,2)); ?></strong>
      | Paid: Rs. <?php echo e(number_format($enrollment->paid_total,2)); ?>

      | Fee: Rs. <?php echo e(number_format($enrollment->fee_agreed - $enrollment->discount,2)); ?>

    </p>
    <form method="post" action="<?php echo e(route('recp.payment.store',$enrollment)); ?>">
      <?php echo csrf_field(); ?>
      <div class="row g-2">
        <div class="col-md-3">
          <label class="form-label">Amount *</label>
          <input name="amount" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Method *</label>
          <select name="method" class="form-select" required>
            <?php $__currentLoopData = ['cash','esewa','khalti','bank','card','other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($m); ?>"><?php echo e(strtoupper($m)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Source Account</label>
          <input name="source_account" class="form-control" placeholder="eSewa no / Bank name">
        </div>
        <div class="col-md-3">
          <label class="form-label">Reference</label>
          <input name="reference" class="form-control" placeholder="Txn/Slip no">
        </div>
        <div class="col-md-3">
          <label class="form-label">Paid At</label>
          <input type="datetime-local" name="paid_at" class="form-control" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Note</label>
          <textarea name="note" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-success">Record Payment</button>
        <a class="btn btn-light" href="<?php echo e(route('recp.students.list')); ?>">Back</a>
      </div>
    </form>

    <hr>
    <h6>Previous Payments</h6>
    <div class="table-responsive">
      <table class="table table-sm table-bordered">
        <thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Ref</th><th>By</th></tr></thead>
        <tbody>
          <?php $__currentLoopData = $enrollment->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($p->paid_at); ?></td>
              <td>Rs. <?php echo e(number_format($p->amount,2)); ?></td>
              <td><?php echo e(strtoupper($p->method)); ?></td>
              <td><?php echo e($p->reference); ?></td>
              <td><?php echo e(optional($p->receiver)->name ?? '-'); ?></td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>

  </div></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/payments/take_payment.blade.php ENDPATH**/ ?>