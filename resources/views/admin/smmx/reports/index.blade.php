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
            <h1>SMMX Reports</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.smmx.partials.stats')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Monthly Reports</h3>
                </div>
                <div class="card-body table-responsive tbl-cards">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Month</th>
                                <th>Reach</th>
                                <th>Impressions</th>
                                <th>Leads</th>
                                <th>Messages</th>
                                <th>Spend</th>
                                <th>Completion</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>{{ $report->customer->name ?? '-' }}</td>
                                    <td>{{ $report->report_month }}/{{ $report->report_year }}</td>
                                    <td>{{ $report->total_reach ?? 0 }}</td>
                                    <td>{{ $report->total_impressions ?? 0 }}</td>
                                    <td>{{ $report->total_leads ?? 0 }}</td>
                                    <td>{{ $report->total_messages ?? 0 }}</td>
                                    <td>{{ $report->total_spend ?? 0 }}</td>
                                    <td>{{ $report->completion_rate ?? 0 }}%</td>
                                    <td>{{ ucfirst($report->report_status) }}</td>
                                    <td>
                                        <a href="{{ route('admin.smmx.reports.show', $report->id) }}" class="btn btn-info btn-sm">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center">No reports found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection