<html>
<head><title>Payment Receipt</title></head>
<body>
  <h1>Payment Receipt #{{ $payment->id }}</h1>
  <p>Student: {{ $payment->enrollment->student->full_name }}</p>
  <p>Amount: Rs. {{ number_format($payment->amount,2) }}</p>
  <p>Method: {{ strtoupper($payment->method) }}</p>
  <!-- More details -->
</body>
</html>