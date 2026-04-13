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
            <h1>Onboarding Details</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header"><h3 class="card-title">{{ $item->business_name }}</h3></div>
                <div class="card-body">
                    <p><strong>Customer:</strong> {{ $item->customer->name ?? '-' }}</p>
                    <p><strong>Brand Name:</strong> {{ $item->brand_name }}</p>
                    <p><strong>Contact Person:</strong> {{ $item->contact_person }}</p>
                    <p><strong>Phone:</strong> {{ $item->phone }}</p>
                    <p><strong>Email:</strong> {{ $item->email }}</p>
                    <p><strong>Goal:</strong> {{ $item->primary_goal }}</p>
                    <p><strong>Target Location:</strong> {{ $item->target_location }}</p>
                    <p><strong>Target Interests:</strong> {{ $item->target_interests }}</p>
                    <p><strong>Content Preferences:</strong> {{ $item->content_preferences }}</p>
                    <p><strong>Approval Contact:</strong> {{ $item->approval_contact }}</p>
                    <p><strong>Notes:</strong> {{ $item->notes }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.smmx.onboarding.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('admin.smmx.onboarding.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection