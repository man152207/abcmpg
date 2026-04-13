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
<div class="card mb-3">
    <div class="card-body">
        <h5 class="mb-3">Quick Notes</h5>
        <p class="mb-0 text-muted">
            This module is intentionally separated from old ad/requirements modules using the unique <strong>smmx</strong> prefix.
        </p>
    </div>
</div>