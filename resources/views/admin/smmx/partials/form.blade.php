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

@php
    $isEdit = isset($item);
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id', $item->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name ?? ('Customer #'.$customer->id) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Package</label>
            <select name="package_id" class="form-control">
                <option value="">Select Package</option>
                @foreach($packages as $package)
                    <option value="{{ $package->id }}"
                        {{ old('package_id', $item->package_id ?? '') == $package->id ? 'selected' : '' }}>
                        {{ $package->name ?? ('Package #'.$package->id) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>