@extends('admin.layout.layout')
@section('title','Reception | Dues Report')
@section('content')
<div class="container-fluid p-3">
  <div class="card">
    <div class="card-body">
      <h5>Dues List</h5>
      <table class="table">
        <thead><tr><th>Student</th><th>Batch</th><th>Due Amount</th></tr></thead>
        <tbody>
          @foreach($dues as $due)
            <tr>
              <td>{{ $due->student->full_name }}</td>
              <td>{{ $due->batch->course->title }}</td>
              <td>Rs. {{ number_format($due->due_amount,2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <a href="{{ route('recp.report.export.dues') }}" class="btn btn-success">Export Excel</a>
    </div>
  </div>
</div>
@endsection