@extends('admin.layout.layout')
@section('title','Reception | Enroll Student')
@section('content')
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <h5 class="mb-2">Enroll: {{ $student->full_name }} ({{ $student->phone }})</h5>
    <form method="post" action="{{ route('recp.enroll.store',$student) }}">
      @csrf
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Batch *</label>
          <select name="batch_id" class="form-select" required>
            <option value="">-- Select Batch --</option>
            @foreach($batches as $b)
              <option value="{{ $b->id }}">
                {{ $b->course->title }} — {{ $b->name }}
                @if($b->start_date) ({{ $b->start_date }} to {{ $b->end_date }}) @endif
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Enroll Date</label>
          <input type="date" name="enroll_date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Fee (Agreed) *</label>
          <input name="fee_agreed" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Discount *</label>
          <input name="discount" class="form-control" value="0" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach(['enrolled','completed','dropped'] as $s)
              <option value="{{ $s }}">{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Save Enrollment</button>
        <a class="btn btn-light" href="{{ route('recp.students.list') }}">Cancel</a>
      </div>
    </form>
  </div></div>
</div>
@endsection
