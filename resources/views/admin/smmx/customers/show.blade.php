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
                    <h4>{{ $customer->name }} - Social Media Panel</h4>
                    <p>Single customer workspace with overview, package, monthly plan, work logs and report summary.</p>
                </div>
                <div class="d-flex flex-wrap" style="gap:10px;">
                    <a href="{{ route('admin.smmx.customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>

                    @if($onboarding)
                        <a href="{{ route('admin.smmx.onboarding.edit', $onboarding->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i> Edit Overview
                        </a>
                    @endif

                    @if($deliverable)
                        <a href="{{ route('admin.smmx.deliverables.edit', $deliverable->id) }}" class="btn btn-info">
                            <i class="fas fa-calendar-alt mr-1"></i> Update Monthly Plan
                        </a>
                    @endif

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#workLogModal">
                        <i class="fas fa-plus mr-1"></i> Add Work Log
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="smmx-stat-grid">
                <div class="smmx-stat-card">
                    <div class="label">Package</div>
                    <div class="value" style="font-size:20px;">{{ $package->name ?? '-' }}</div>
                    <div class="icon"><i class="fas fa-box-open"></i></div>
                </div>

                <div class="smmx-stat-card">
                    <div class="label">Completion Rate</div>
                    <div class="value">{{ $stats['completion_rate'] }}%</div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>

                <div class="smmx-stat-card">
                    <div class="label">Pending Tasks</div>
                    <div class="value">{{ $stats['pending_tasks'] }}</div>
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                </div>

                <div class="smmx-stat-card">
                    <div class="label">Completed Tasks</div>
                    <div class="value">{{ $stats['completed_tasks'] }}</div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="card smmx-card-accent">
                        <div class="card-header">
                            <h3 class="card-title">Customer Overview & Requirements</h3>
                        </div>
                        <div class="card-body">
                            <div class="smmx-detail-list">
                                <div class="smmx-detail-item">
                                    <div class="key">Business Name</div>
                                    <div class="value">{{ $onboarding->business_name ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Brand Name</div>
                                    <div class="value">{{ $onboarding->brand_name ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Primary Goal</div>
                                    <div class="value">{{ $onboarding->primary_goal ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Preferred Language</div>
                                    <div class="value">{{ $onboarding->preferred_language ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Target Location</div>
                                    <div class="value">{{ $onboarding->target_location ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Target Age Group</div>
                                    <div class="value">{{ $onboarding->target_age_group ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Target Gender</div>
                                    <div class="value">{{ $onboarding->target_gender ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Brand Colors</div>
                                    <div class="value">{{ $onboarding->brand_colors ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Approval Contact</div>
                                    <div class="value">{{ $onboarding->approval_contact ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Monthly Budget</div>
                                    <div class="value">{{ $onboarding->monthly_budget ?? '-' }}</div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group">
                                <label>Target Interests</label>
                                <div class="p-3 bg-light rounded">{{ $onboarding->target_interests ?? '-' }}</div>
                            </div>

                            <div class="form-group">
                                <label>Content Preferences</label>
                                <div class="p-3 bg-light rounded">{{ $onboarding->content_preferences ?? '-' }}</div>
                            </div>

                            <div class="form-group mb-0">
                                <label>Important Notes</label>
                                <div class="p-3 bg-light rounded">{{ $onboarding->notes ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card smmx-card-accent">
                        <div class="card-header">
                            <h3 class="card-title">Current Package & Monthly Plan</h3>
                        </div>
                        <div class="card-body">
                            <div class="smmx-detail-list">
                                <div class="smmx-detail-item">
                                    <div class="key">Assigned Package</div>
                                    <div class="value">{{ $package->name ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Current Month</div>
                                    <div class="value">{{ $deliverable ? $deliverable->report_month.'/'.$deliverable->report_year : '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Posts</div>
                                    <div class="value">{{ $deliverable ? $deliverable->posts_completed.'/'.$deliverable->posts_planned : '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Graphics</div>
                                    <div class="value">{{ $deliverable ? $deliverable->graphics_completed.'/'.$deliverable->graphics_planned : '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Reels</div>
                                    <div class="value">{{ $deliverable ? $deliverable->reels_completed.'/'.$deliverable->reels_planned : '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Stories</div>
                                    <div class="value">{{ $deliverable ? $deliverable->stories_completed.'/'.$deliverable->stories_planned : '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Ad Spend Planned</div>
                                    <div class="value">{{ $deliverable->ad_spend_planned ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Ad Spend Used</div>
                                    <div class="value">{{ $deliverable->ad_spend_used ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Approval Status</div>
                                    <div class="value">{{ $deliverable->approval_status ?? '-' }}</div>
                                </div>

                                <div class="smmx-detail-item">
                                    <div class="key">Report Status</div>
                                    <div class="value">{{ isset($deliverable) && $deliverable->report_sent ? 'Sent' : 'Pending' }}</div>
                                </div>
                            </div>

                            @if($deliverable)
                                <hr class="my-4">
                                <div class="smmx-progress-label">
                                    <span>Completion Progress</span>
                                    <span>{{ $deliverable->completion_rate }}%</span>
                                </div>
                                <div class="smmx-progress">
                                    <div class="smmx-progress-bar" data-width="{{ $deliverable->completion_rate }}%" style="width: {{ $deliverable->completion_rate }}%;"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card smmx-card-accent">
                        <div class="card-header">
                            <h3 class="card-title">Latest Report Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="smmx-detail-list">
                                <div class="smmx-detail-item">
                                    <div class="key">Reach</div>
                                    <div class="value">{{ $report->total_reach ?? '-' }}</div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Impressions</div>
                                    <div class="value">{{ $report->total_impressions ?? '-' }}</div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Leads</div>
                                    <div class="value">{{ $report->total_leads ?? '-' }}</div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Messages</div>
                                    <div class="value">{{ $report->total_messages ?? '-' }}</div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Best Performer</div>
                                    <div class="value">{{ $report->best_performing_content ?? '-' }}</div>
                                </div>
                                <div class="smmx-detail-item">
                                    <div class="key">Report Status</div>
                                    <div class="value">{{ $report->report_status ?? '-' }}</div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="form-group mb-0">
                                <label>Summary Remark</label>
                                <div class="p-3 bg-light rounded">{{ $report->summary_remark ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card smmx-card-accent">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Work Log / Activity Table</h3>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#workLogModal">
                        <i class="fas fa-plus mr-1"></i> Add Work Log
                    </button>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Link</th>
                                <th>Remark</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workLogs as $log)
                                <tr>
                                    <td>{{ optional($log->work_date)->format('Y-m-d') }}</td>
                                    <td>{{ $log->work_type }}</td>
                                    <td><strong>{{ $log->title }}</strong></td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>
                                        @php
                                            $statusClass = 'smmx-badge-dark';
                                            if ($log->status === 'done') $statusClass = 'smmx-badge-success';
                                            elseif ($log->status === 'pending') $statusClass = 'smmx-badge-danger';
                                            elseif ($log->status === 'in_progress') $statusClass = 'smmx-badge-primary';
                                            elseif ($log->status === 'waiting_approval') $statusClass = 'smmx-badge-warning';
                                        @endphp
                                        <span class="smmx-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $log->status)) }}</span>
                                    </td>
                                    <td>{{ $log->assigned_to }}</td>
                                    <td>
                                        @if($log->asset_link)
                                            <a href="{{ $log->asset_link }}" target="_blank" data-toggle="tooltip" title="View Asset"><i class="fas fa-file"></i></a>
                                        @elseif($log->external_link)
                                            <a href="{{ $log->external_link }}" target="_blank" data-toggle="tooltip" title="Open Link"><i class="fas fa-external-link-alt"></i></a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $log->remark }}</td>
                                    <td>
                                        <form action="{{ route('admin.smmx.customers.worklog.delete', [$customer->id, $log->id]) }}" method="POST" onsubmit="return confirm('Delete this work log?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">No work logs found for this customer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white">
                    {{ $workLogs->links() }}
                </div>
            </div>

        </div>
    </section>
</div>

@include('admin.smmx.customers.partials.worklog-modal')

@endsection