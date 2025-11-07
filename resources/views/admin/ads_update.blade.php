<!-- resources/views/admin/customer/update.blade.php -->

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$customers = DB::select('select * from customers');
?>

@extends('admin.layout.layout')

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Update Ad</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="customer">Customer:</label>
                    <select class="form-control" id="customer" name="customer" required>
                        <option value="">Select Customer</option>
                        <!-- Add options dynamically from your database -->
                        <!-- Example: -->
                        @foreach($customers as $customer)
                        <option value="{{ $customer->phone }}" {{ $ad->customer == $customer->phone ? 'selected' : '' }}>
                            {{ $customer->phone }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="USD">USD:</label>
                    <input type="number" step="0.01" class="form-control" id="USD" name="USD" value="{{ $ad->USD }}" required>
                </div>

                <div class="form-group">
                    <label for="Rate">Rate:</label>
                    <input type="number" step="0.01" class="form-control" id="Rate" name="Rate" value="{{ $ad->Rate }}" required>
                </div>

                <div class="form-group">
                    <label for="NRP">NRP:</label>
                    <input type="number" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="NRP" name="NRP" required>
                </div>

                <div class="form-group">
                    <label for="Ad_Account">Ad Account:</label>
                    <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                </div>

                <div class="form-group">
                    <label>Payment Method:</label>
                    <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                        @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki'] as $Payment)
                        <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}>
                            {{ $Payment }}
                        </option>
                        @endforeach
                        <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                        <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                        <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                        <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Duration:</label>
                    <input type="number" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                </div>

                <div class="form-group">
                    <label for="Quantity">Quantity:</label>
                    <input type="number" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                </div>

                <div class="form-group">
                    <label for="start_date">Status:</label>
                    <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                        @foreach(['New', 'Extend', 'Both'] as $status)
                        <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        @endforeach
                        <!-- <option value="" {{ $ad->Status === '$status' ? 'selected' : '' }}>Select Status</option>
                        <option value="No Payment" {{ $ad->Status === 'No Payment' ? 'No Payment' : '' }}>No Payment</option>
                        <option value="Advance" {{ $ad->Status === 'Advance' ? 'Advance' : '' }}>Advance</option>
                        <option value="Paid" {{ $ad->Status === 'Paid' ? 'Paid' : '' }}>Paid</option> -->
                    </select>
                </div>

                @if($ad->advance == '')
                <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                    <label for="Quantity">Baki:</label>
                    <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                @else
                <div class="form-group" id="{{$ad->id.'bakifield'}}">
                    <label for="Quantity">Baki:</label>
                    <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                </div>
                @endif

                <div class="form-group">
                    <label for="Ad_Nature_Page">Ad Nature/Page:</label>
                    <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                </div>

                <div class="form-group">
                    <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }},({{ auth('admin')->user()->id }}) " id="admin" name="admin" required>
                </div>
                <!-- <div class="form-group">
                    <label for="Status">Is Complete?:</label>
                    <select name="is_complete" class="form-control" id="Status" name="Status" required>
                        <option value="0">NO</option>
                        <option value="1">YES</option>
                    </select>
                </div> -->
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var usdInput = document.getElementById('USD');
        var rateInput = document.getElementById('Rate');
        var nrpInput = document.getElementById('NRP');

        usdInput.addEventListener('input', calculateNRP);
        rateInput.addEventListener('input', calculateNRP);

        function calculateNRP() {
            var usd = parseFloat(usdInput.value) || 0;
            var rate = parseFloat(rateInput.value) || 0;
            var nrp = usd * rate;
            nrpInput.value = nrp.toFixed(2);
        }
    });
</script>
<script>
    function toggleAdvanceField() {
        var statusSelect = document.getElementById("Status");
        var advanceField = document.getElementById("advanceField");

        if (statusSelect.value === "Advance") {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
        }
    }
</script>
<script>
    function togglebakiField(adId) {
        var statusSelect = document.getElementById(adId);
        var advanceField = document.getElementById(adId + 'field');

        if (statusSelect.value === "Baki") {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
        }
    }
</script>
<script>
    // Add event listener to start date input
    document.getElementById('start_date').addEventListener('input', function() {
        validateDateRange();
    });

    // Add event listener to end date input
    document.getElementById('end_date').addEventListener('input', function() {
        validateDateRange();
    });

    function validateDateRange() {
        // Get the values of start date and end date
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;

        // Compare the dates
        if (startDate > endDate) {
            alert('End Date must be later than Start Date');
            // You can also reset the end date to the start date or take other actions
            document.getElementById('end_date').value = startDate;
        }
    }
</script>
<script src="{{asset('https://code.jquery.com/jquery-3.3.1.slim.min.js')}}"></script>
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js')}}"></script>
<script src="{{asset('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js')}}"></script>

@endsection

@section('js_')
<!-- Include Select2 CSS -->
<link href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css')}}" rel="stylesheet" />

<!-- Include jQuery (required for Select2) -->
<script src="{{asset('https://code.jquery.com/jquery-3.6.4.min.js')}}"></script>

<!-- Include Select2 JS -->
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#customer').select2({
            placeholder: 'Select Customer',
            allowClear: true,
            data: @json($customers),
            // minimumInputLength: 1 // Minimum characters to start a search
        });
    });
</script>

@endsection