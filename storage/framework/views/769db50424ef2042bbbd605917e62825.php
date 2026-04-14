<html>
<head><title>Payment Receipt</title></head>
<body>
  <h1>Payment Receipt #<?php echo e($payment->id); ?></h1>
  <p>Student: <?php echo e($payment->enrollment->student->full_name); ?></p>
  <p>Amount: Rs. <?php echo e(number_format($payment->amount,2)); ?></p>
  <p>Method: <?php echo e(strtoupper($payment->method)); ?></p>
  <!-- More details -->
</body>
</html><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/payments/receipt_pdf.blade.php ENDPATH**/ ?>