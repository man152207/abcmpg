@push('styles')
<link rel="stylesheet" href="{{ asset('assets/smmx/css/smmx.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/smmx/js/smmx.js') }}"></script>
<script>
    // Optional: enhance tooltips, etc.
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush

<div class="row mb-3">
    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['active_clients'] ?? 0 }}</h3>
                <p>Active Clients</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['deliverables'] ?? 0 }}</h3>
                <p>Total Deliverables</p>
            </div>
            <div class="icon"><i class="fas fa-tasks"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['reports_sent'] ?? 0 }}</h3>
                <p>Reports Sent</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($stats['total_spend'] ?? 0, 2) }}</h3>
                <p>Total Spend</p>
            </div>
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
    </div>
</div>