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
            <h1>Edit Deliverable</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.smmx.deliverables.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header"><h3 class="card-title">Edit Monthly Deliverable</h3></div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <select name="customer_id" class="form-control" required>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $item->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name ?? ('Customer #'.$customer->id) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Package</label>
                                    <select name="package_id" class="form-control">
                                        <option value="">Select Package</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id }}" {{ $item->package_id == $package->id ? 'selected' : '' }}>
                                                {{ $package->name ?? ('Package #'.$package->id) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Onboarding</label>
                                    <select name="onboarding_id" class="form-control">
                                        <option value="">Select Onboarding</option>
                                        @foreach($onboardings as $onboarding)
                                            <option value="{{ $onboarding->id }}" {{ $item->onboarding_id == $onboarding->id ? 'selected' : '' }}>
                                                {{ $onboarding->business_name }} (#{{ $onboarding->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Month</label><input type="number" name="report_month" class="form-control" value="{{ $item->report_month }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Year</label><input type="number" name="report_year" class="form-control" value="{{ $item->report_year }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Campaign Objective</label><input type="text" name="campaign_objective" class="form-control" value="{{ $item->campaign_objective }}"></div></div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $item->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="report_sent" {{ $item->status == 'report_sent' ? 'selected' : '' }}>Report Sent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Posts Planned</label><input type="number" name="posts_planned" class="form-control" value="{{ $item->posts_planned }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Posts Completed</label><input type="number" name="posts_completed" class="form-control" value="{{ $item->posts_completed }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Graphics Planned</label><input type="number" name="graphics_planned" class="form-control" value="{{ $item->graphics_planned }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Graphics Completed</label><input type="number" name="graphics_completed" class="form-control" value="{{ $item->graphics_completed }}"></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Reels Planned</label><input type="number" name="reels_planned" class="form-control" value="{{ $item->reels_planned }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Reels Completed</label><input type="number" name="reels_completed" class="form-control" value="{{ $item->reels_completed }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Stories Planned</label><input type="number" name="stories_planned" class="form-control" value="{{ $item->stories_planned }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Stories Completed</label><input type="number" name="stories_completed" class="form-control" value="{{ $item->stories_completed }}"></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label>Ad Spend Planned</label><input type="number" step="0.01" name="ad_spend_planned" class="form-control" value="{{ $item->ad_spend_planned }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Ad Spend Used</label><input type="number" step="0.01" name="ad_spend_used" class="form-control" value="{{ $item->ad_spend_used }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Approval Status</label><input type="text" name="approval_status" class="form-control" value="{{ $item->approval_status }}"></div></div>
                            <div class="col-md-3"><div class="form-group"><label>Report Sent</label><br><input type="checkbox" name="report_sent" value="1" {{ $item->report_sent ? 'checked' : '' }}></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Assigned Staff (one per line)</label><textarea name="assigned_staff_text" class="form-control">{{ is_array($item->assigned_staff) ? implode("\n", $item->assigned_staff) : '' }}</textarea></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Pending Items</label><textarea name="pending_items" class="form-control">{{ $item->pending_items }}</textarea></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><div class="form-group"><label>Canva Link</label><input type="text" name="canva_link" class="form-control" value="{{ $item->asset_links['canva_link'] ?? '' }}"></div></div>
                            <div class="col-md-4"><div class="form-group"><label>Drive Link</label><input type="text" name="drive_link" class="form-control" value="{{ $item->asset_links['drive_link'] ?? '' }}"></div></div>
                            <div class="col-md-4"><div class="form-group"><label>Final Link</label><input type="text" name="final_link" class="form-control" value="{{ $item->asset_links['final_link'] ?? '' }}"></div></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Planned Date</label><input type="date" name="planned_date" class="form-control" value="{{ optional($item->planned_date)->format('Y-m-d') }}"></div></div>
                            <div class="col-md-6"><div class="form-group"><label>Published Date</label><input type="date" name="published_date" class="form-control" value="{{ optional($item->published_date)->format('Y-m-d') }}"></div></div>
                        </div>

                        <div class="form-group"><label>Next Action</label><textarea name="next_action" class="form-control">{{ $item->next_action }}</textarea></div>
                        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control">{{ $item->notes }}</textarea></div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Update Deliverable</button>
                        <a href="{{ route('admin.smmx.deliverables.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection