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
            <h1>Monthly Report Details</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $report->customer->name ?? '-' }} - {{ $report->report_month }}/{{ $report->report_year }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>Reach:</strong> {{ $report->total_reach }}</p>
                    <p><strong>Impressions:</strong> {{ $report->total_impressions }}</p>
                    <p><strong>Leads:</strong> {{ $report->total_leads }}</p>
                    <p><strong>Messages:</strong> {{ $report->total_messages }}</p>
                    <p><strong>Total Spend:</strong> {{ $report->total_spend }}</p>
                    <p><strong>Completion Rate:</strong> {{ $report->completion_rate }}%</p>
                    <p><strong>Best Performing Content:</strong> {{ $report->best_performing_content }}</p>
                    <p><strong>Summary Remark:</strong> {{ $report->summary_remark }}</p>
                    <p><strong>Report Status:</strong> {{ ucfirst($report->report_status) }}</p>
                    <p><strong>Sent At:</strong> {{ $report->sent_at }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.smmx.reports.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection