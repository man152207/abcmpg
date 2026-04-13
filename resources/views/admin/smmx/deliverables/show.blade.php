@extends('admin.layout.layout')

@section('content')
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

    <section class="content-header">
        <div class="container-fluid">
            <h1>Deliverable Details</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"><h3 class="card-title">{{ $item->customer->name ?? '-' }} - {{ $item->report_month }}/{{ $item->report_year }}</h3></div>
                <div class="card-body">
                    <p><strong>Posts:</strong> {{ $item->posts_completed }}/{{ $item->posts_planned }}</p>
                    <p><strong>Graphics:</strong> {{ $item->graphics_completed }}/{{ $item->graphics_planned }}</p>
                    <p><strong>Reels:</strong> {{ $item->reels_completed }}/{{ $item->reels_planned }}</p>
                    <p><strong>Stories:</strong> {{ $item->stories_completed }}/{{ $item->stories_planned }}</p>
                    <p><strong>Ad Spend:</strong> {{ $item->ad_spend_used }}/{{ $item->ad_spend_planned }}</p>
                    <p><strong>Completion Rate:</strong> {{ $item->completion_rate }}%</p>
                    <p><strong>Approval Status:</strong> {{ $item->approval_status }}</p>
                    <p><strong>Assigned Staff:</strong> {{ is_array($item->assigned_staff) ? implode(', ', $item->assigned_staff) : '-' }}</p>
                    <p><strong>Canva Link:</strong> {{ $item->asset_links['canva_link'] ?? '-' }}</p>
                    <p><strong>Drive Link:</strong> {{ $item->asset_links['drive_link'] ?? '-' }}</p>
                    <p><strong>Final Link:</strong> {{ $item->asset_links['final_link'] ?? '-' }}</p>
                    <p><strong>Pending Items:</strong> {{ $item->pending_items }}</p>
                    <p><strong>Next Action:</strong> {{ $item->next_action }}</p>
                    <p><strong>Notes:</strong> {{ $item->notes }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.smmx.deliverables.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('admin.smmx.deliverables.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection