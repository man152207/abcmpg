@extends('admin.layout.layout')
@section('title','Reception | Take Payment')
@section('content')
<div class="container-fluid p-3">
  <div class="card"><div class="card-body">
    <h5 class="mb-2">
      Payment — {{ $enrollment->student->full_name }}
      ({{ $enrollment->batch->course->title }} / {{ $enrollment->batch->name }})
    </h5>
    <p class="mb-2">Due: <strong>Rs. {{ number_format($enrollment->due_amount,2) }}</strong>
      | Paid: Rs. {{ number_format($enrollment->paid_total,2) }}
      | Fee: Rs. {{ number_format($enrollment->fee_agreed - $enrollment->discount,2) }}
    </p>
    <form method="post" action="{{ route('recp.payment.store',$enrollment) }}">
      @csrf
      <div class="row g-2">
        <div class="col-md-3">
          <label class="form-label">Amount *</label>
          <input name="amount" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Method *</label>
          <select name="method" class="form-select" required>
            @foreach(['cash','esewa','khalti','bank','card','other'] as $m)
              <option value="{{ $m }}">{{ strtoupper($m) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Source Account</label>
          <input name="source_account" class="form-control" placeholder="eSewa no / Bank name">
        </div>
        <div class="col-md-3">
          <label class="form-label">Reference</label>
          <input name="reference" class="form-control" placeholder="Txn/Slip no">
        </div>
        <div class="col-md-3">
          <label class="form-label">Paid At</label>
          <input type="datetime-local" name="paid_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
        </div>
        <div class="col-12">
          <label class="form-label">Note</label>
          <textarea name="note" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-success">Record Payment</button>
        <a class="btn btn-light" href="{{ route('recp.students.list') }}">Back</a>
      </div>
    </form>

    <hr>
    <h6>Previous Payments</h6>
    <div class="table-responsive">
      <table class="table table-sm table-bordered">
        <thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Ref</th><th>By</th></tr></thead>
        <tbody>
          @foreach($enrollment->payments as $p)
            <tr>
              <td>{{ $p->paid_at }}</td>
              <td>Rs. {{ number_format($p->amount,2) }}</td>
              <td>{{ strtoupper($p->method) }}</td>
              <td>{{ $p->reference }}</td>
              <td>{{ optional($p->receiver)->name ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div></div>
</div>
@endsection
