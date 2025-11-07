@extends('admin.layout.layout')
@section('title','Reception | Edit Student')
@section('content')
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <form method="post" action="{{ route('recp.students.update',$student) }}">
      @csrf @method('PUT')
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input name="full_name" class="form-control" value="{{ $student->full_name }}" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Phone *</label>
          <input name="phone" class="form-control" value="{{ $student->phone }}" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ $student->email }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Guardian</label>
          <input name="guardian_name" class="form-control" value="{{ $student->guardian_name }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Address</label>
          <input name="address" class="form-control" value="{{ $student->address }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">DOB</label>
          <input type="date" name="dob" class="form-control" value="{{ $student->dob }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach(['active','inactive','completed','dropped'] as $s)
              <option value="{{ $s }}" @selected($student->status===$s)>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="2">{{ $student->remarks }}</textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Update</button>
        <a class="btn btn-light" href="{{ route('recp.students.list') }}">Back</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
