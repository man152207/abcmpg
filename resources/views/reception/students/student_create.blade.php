@extends('admin.layout.layout')
@section('title','Reception | New Student')
@section('content')
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <form method="post" action="{{ route('recp.students.store') }}">
      @csrf
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input name="full_name" class="form-control @error('full_name') is-invalid @enderror" required>
          @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
          <label class="form-label">Phone *</label>
          <input name="phone" class="form-control @error('phone') is-invalid @enderror" required>
          @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <!-- Add other fields similarly with validation -->
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Save</button>
        <a class="btn btn-light" href="{{ route('recp.students.list') }}">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@if(session('success'))
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast show bg-success text-white" role="alert">
      <div class="toast-body">{{ session('success') }}</div>
    </div>
  </div>
@endif
@endsection