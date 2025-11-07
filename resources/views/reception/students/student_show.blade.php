@extends('admin.layout.layout')
@section('title','Reception | Student Details')
@section('content')
<div class="container-fluid p-3">
  <div class="card">
    <div class="card-body">
      <h5>{{ $student->full_name }} ({{ $student->phone }})</h5>
      <ul class="nav nav-tabs" id="studentTabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#info">Info</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#enrollments">Enrollments</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payments">Payments</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a></li>
      </ul>
      <div class="tab-content mt-3">
        <div class="tab-pane active" id="info">
          <!-- Student info fields -->
          <p>Email: {{ $student->email ?? 'N/A' }}</p>
          <!-- ... -->
        </div>
        <div class="tab-pane" id="enrollments">
          <table class="table">
            <!-- List enrollments -->
            @foreach($student->enrollments as $enroll)
              <tr>
                <td>{{ $enroll->batch->course->title }}</td>
                <td>Due: Rs. {{ number_format($enroll->due_amount,2) }}</td>
                <td><a href="{{ route('recp.payment.create', $enroll) }}">Pay</a></td>
              </tr>
            @endforeach
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
@endsection