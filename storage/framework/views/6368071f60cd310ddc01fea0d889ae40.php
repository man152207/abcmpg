
<?php $__env->startSection('title','Reception | Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="row">
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">New Students (Today)</h6>
      <h2 class="m-0"><?php echo e($studentsToday); ?></h2>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">New Enrollments (Today)</h6>
      <h2 class="m-0"><?php echo e($enrollsToday); ?></h2>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">Paid Today</h6>
      <h2 class="m-0">Rs. <?php echo e(number_format($paidToday,2)); ?></h2>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">By Method (Today)</h6>
      <ul class="mb-0">
        <?php $__currentLoopData = $paymentsByMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m=>$t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><?php echo e(strtoupper($m)); ?>: Rs. <?php echo e(number_format($t,2)); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div></div></div>
  </div>
  <div class="mt-3">
    <canvas id="paymentsChart" height="200"></canvas>
  </div>
  <div class="mt-3">
    <h5>Top Dues</h5>
    <table class="table table-sm table-bordered">
      <thead><tr><th>Student</th><th>Batch</th><th>Due</th></tr></thead>
      <tbody>
        <?php $__currentLoopData = $dues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr>
            <td><?php echo e($due->student->full_name); ?></td>
            <td><?php echo e($due->batch->course->title); ?> - <?php echo e($due->batch->name); ?></td>
            <td>Rs. <?php echo e(number_format($due->due_amount,2)); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
    <a href="<?php echo e(route('recp.report.dues')); ?>" class="btn btn-info">Full Dues Report</a>
  </div>
  <div class="mt-3 d-flex gap-2">
    <a href="<?php echo e(route('recp.students.create')); ?>" class="btn btn-primary btn-sm">+ New Student</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('paymentsChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo json_encode(array_keys($chartData)); ?>,
      datasets: [{
        label: 'Payments',
        data: <?php echo json_encode(array_values($chartData)); ?>,
        borderColor: 'rgba(75, 192, 192, 1)',
        tension: 0.1
      }]
    },
    options: { scales: { y: { beginAtZero: true } } }
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/recp_dashboard.blade.php ENDPATH**/ ?>