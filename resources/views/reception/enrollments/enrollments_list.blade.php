@extends('admin.layout.layout')
@section('title','Reception | Enrollments')
@section('content')
<div class="container-fluid p-3">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead><tr><th>#</th><th>Student</th><th>Batch</th><th>Fee</th><th>Due</th><th>Actions</th></tr></thead>
          <tbody>
            @foreach($enrollments as $enroll)
              <tr>
                <td>{{ $enroll->id }}</td>
                <td>{{ $enroll->student->full_name }}</td>
                <td>{{ $enroll->batch->course->title }} - {{ $enroll->batch->name }}</td>
                <td>Rs. {{ number_format($enroll->fee_agreed - $enroll->discount,2) }}</td>
                <td>Rs. {{ number_format($enroll->due_amount,2) }}</td>
                <td>
                  <a class="btn btn-outline-primary btn-sm" href="{{ route('recp.enroll.edit', $enroll) }}">Edit</a>
                  <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $enroll->id }})">Delete</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $enrollments->links() }}
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(id) {
    // Similar as above
  }
</script>
@endsection