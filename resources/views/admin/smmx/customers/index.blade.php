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
            <div class="smmx-toolbar">
                <div class="smmx-toolbar-left">
                    <h4>Social Media Customers</h4>
                    <p>All customers under social media marketing service with package, progress, approvals and quick access.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="smmx-info-panel">
                <h5>How this works</h5>
                <p>Open a customer panel to see full requirements, assigned package, monthly plan, work logs and report summary in one place.</p>
            </div>

            <div class="card smmx-card-accent">
                <div class="card-header">
                    <h3 class="card-title">Customers List</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Brand / Business</th>
                                <th>Package</th>
                                <th>Goal</th>
                                <th>Progress</th>
                                <th>Pending Tasks</th>
                                <th>Approval</th>
                                <th>Report</th>
                                <th>Assigned Staff</th>
                                <th>Last Activity</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                        @if($item->phone)
                                            <div class="text-muted small">{{ $item->phone }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->brand_name }}</strong>
                                        <div class="text-muted small">{{ $item->business_name }}</div>
                                    </td>
                                    <td>{{ $item->package_name }}</td>
                                    <td>{{ $item->goal }}</td>
                                    <td>
                                        <strong>{{ $item->completion_rate }}%</strong>
                                        <div class="smmx-progress-wrap">
                                            <div class="smmx-progress">
                                                <div class="smmx-progress-bar" data-width="{{ $item->completion_rate }}%" style="width: {{ $item->completion_rate }}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="smmx-badge {{ $item->pending_logs > 0 ? 'smmx-badge-warning' : 'smmx-badge-success' }}">
                                            {{ $item->pending_logs }}
                                        </span>
                                    </td>
                                    <td>{{ $item->approval_status }}</td>
                                    <td>
                                        <span class="smmx-badge {{ strtolower($item->report_status) === 'sent' ? 'smmx-badge-success' : 'smmx-badge-danger' }}">
                                            {{ $item->report_status }}
                                        </span>
                                    </td>
                                    <td>{{ $item->assigned_staff }}</td>
                                    <td>{{ $item->last_activity }}</td>
                                    <td>
                                        <a href="{{ route('admin.smmx.customers.show', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-folder-open mr-1"></i> Open
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No social media customers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection