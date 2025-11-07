@extends('admin.layout.layout')
@section('title','Reception | Dashboard')
@section('content')
<div class="container-fluid p-3">
  <div class="row">
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">New Students (Today)</h6>
      <h2 class="m-0">{{ $studentsToday }}</h2>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">New Enrollments (Today)</h6>
      <h2 class="m-0">{{ $enrollsToday }}</h2>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">Paid Today</h6>
      <h2 class="m-0">Rs. {{ number_format($paidToday,2) }}</h2>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <h6 class="mb-1">By Method (Today)</h6>
      <ul class="mb-0">
        @foreach($paymentsByMethod as $m=>$t)
          <li>{{ strtoupper($m) }}: Rs. {{ number_format($t,2) }}</li>
        @endforeach
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
        @foreach($dues as $due)
          <tr>
            <td>{{ $due->student->full_name }}</td>
            <td>{{ $due->batch->course->title }} - {{ $due->batch->name }}</td>
            <td>Rs. {{ number_format($due->due_amount,2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <a href="{{ route('recp.report.dues') }}" class="btn btn-info">Full Dues Report</a>
  </div>
  <div class="mt-3 d-flex gap-2">
    <a href="{{ route('recp.students.create') }}" class="btn btn-primary btn-sm">+ New Student</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('paymentsChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode(array_keys($chartData)) !!},
      datasets: [{
        label: 'Payments',
        data: {!! json_encode(array_values($chartData)) !!},
        borderColor: 'rgba(75, 192, 192, 1)',
        tension: 0.1
      }]
    },
    options: { scales: { y: { beginAtZero: true } } }
  });
</script>
@endsection