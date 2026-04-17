<?php

use App\Models\Ad;
use App\Models\Client;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$totalNRP = Ad::whereIn("Payment", ["Pending", "Paused", "Informed"])->sum(
    "NRP"
);

// Calculate the total advance for 'Baki'
$totalAdvance = Ad::where("Payment", "Baki")->sum("advance");

// Total To Be Received is the sum of both
$totalToBeReceived = $totalNRP + $totalAdvance;

// Format total To Be Received
$formattedTotalToBeReceived = number_format($totalToBeReceived, 2, ".", ",");

$startOfMonth = Carbon::now()->startOfMonth();
$endOfMonth = Carbon::now()->endOfMonth();
$customers = DB::select("select * from customers");
$paused_amounts = DB::table("balance_rejects")
    ->select("USD")
    ->get();
$paused_amount = 0.0;
foreach ($paused_amounts as $amount) {
    $paused_amount = $paused_amount + $amount->USD;
}
$incomes = Ad::whereBetween("created_at", [$startOfMonth, $endOfMonth])
    ->select("USD")
    ->get();
$expences = Client::whereBetween("created_at", [$startOfMonth, $endOfMonth])
    ->select("USD")
    ->get();
$rev = 0;
$expp = 0;
foreach ($incomes as $income) {
    $rev = $income["USD"] + $rev;
    // dd($income);
}
foreach ($expences as $exp) {
    $expp = $exp["USD"] + $expp;
}
$to_be_load = $rev - $expp;
?>
<?php
$totalUSDAllTime = Ad::sum('USD'); // Adjust the model and column names as needed
$totalNPRAllTime = Ad::sum('NRP'); // Adjust the model and column names as needed
$totalQuantityAllTime = Ad::sum('Quantity'); // Adjust the model and column names as needed
?>


@extends('admin.layout.layout')
@section('title', 'Daily Records | MPG Solution')
@section('content')

<meta name="current-admin" content="{{ auth('admin')->user()->name }}">
<script src="{{ asset('js/fetchCustomerRate.js') }}"></script>
<!-- jQuery (ONLY ONE) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Moment (ONLY ONE - daterangepicker needs it) -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<!-- DateRangePicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- Bootstrap 4.5.2 (ONLY ONE css + js) -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Font Awesome (Choose ONE) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<!-- Select2 (Choose ONE version) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<!-- Flatpickr (optional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    table {
      border-collapse: collapse;
      width: 100%;
      overflow-y: none !important;
    }
    
    th,
    td {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    
    th {
      background-color: #f2f2f2;
    }
    
    .horizontal-menu {
      display: none;
      position: absolute;
      background-color: #646564;
      box-shadow: 0 4px 10px rgb(23 43 20);
      padding: 0;
      margin-top: 5px;
      /* Adjust as needed */
      z-index: 100;
      white-space: nowrap;
      right: 0;/
    }
    
    .form-control {
      display: block;
      width: 100%;
      height: calc(1.5em + .75rem + 2px);
      padding: .375rem .75rem;
      font-size: 15px;
      font-weight: 400;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      border: 0px solid #ced4da;
      border-radius: .25rem;
      transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    
    .horizontal-menu .menu-item {
      display: inline-block;
      padding: 7px 10px;
      color: #ffffff;
      text-decoration: none;
      font-weight: 600;
      border: 1px outset #f9fffc8f;
    }
    
    .horizontal-menu .menu-item:hover {
      background-color: #093b7b;
    }
    
    /* Dropdown container styles */
    .dropdown {
      position: relative;
      display: inline-block;
    }
    
    /* Dropdown button styles */
    .dropdown-button {
      background-color: #3498db;
      color: #fff;
      padding: 8px 16px;
      border: none;
      cursor: pointer;
    }
    
    /* Dropdown content (hidden by default) */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }
    
    /* Dropdown items */
    .dropdown-item {
      padding: 12px 16px;
      display: block;
      color: #333;
      text-decoration: none;
    }
    
    /* Change color on hover */
    .dropdown-item:hover {
      background-color: #ddd;
    }
    
    @media screen and (max-width:700px) {
      .overflow-mobile {
        overflow-x: scroll;
      }
    }
    
    /* Style for the dropdown */
    .dropdown {
      position: relative;
      display: inline-block;
    }
    
    /* Style for the dropdown button */
    .dropdown-btn {
      background-color: gray;
      color: #fff;
      padding: 7px;
      border: none;
      cursor: pointer;
      border-radius: 3px;
    }
    
    /* Style for the dropdown content */
    .dropdown-menu {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }
    
    /* Style for dropdown items */
    .dropdown-item {
      padding: 12px 16px;
      display: block;
      text-decoration: none;
      color: #333;
    }
    
    /* Style for dropdown items on hover */
    .dropdown-item:hover {
      background-color: #3498db;
      color: #fff;
    }
    
    .body {
      font-family: Arial, sans-serif;
    }
    
    /* Rest of the CSS remains the same */
    
    /* Add styles for buttons and the popup */
    .btn {
      /* Button styles */
    }
    
    /* Add styles for buttons and the popup */
    .btn {
      /* Button styles */
    }
    
    .popup {
      /* Updated properties for the popup */
      display: none;
      position: fixed;
      left: 78% !important;
      top: 56% !important;
      transform: translate(-50%, -50%) scale(0);
      background-color: white;
      border-radius: 6px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.25);
      z-index: 1;
      width: 45%;
      height: 86%;
      padding: 10px;
      opacity: 0;
      transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }
    
    /* Add any additional styling needed for the popup's content */
    .popup-content {
      /* Styles for the content inside the popup */
    }
    
    
    textarea {
      width: 100%;
      height: 200px;
      margin-bottom: 10px;
    }
    
    .dropdown {
      position: relative;
    }
    
    .dropdown-content {
      display: none;
      position: absolute;
      z-index: 9999;
      top: 100%;
      /* Adjust this value to position it below the button */
      left: 0;
    }
    
    .trigger-close {
      font-size: 16px;
      border: solid 2px;
      padding: 13px;
      border-radius: 5px;
      margin-left: 7px;
      margin-top: 11px;
      background: #093b7b;
      color: white;
      font-weight: 800;
      border-color: #093b7b;
    }
    
    #marqueeTag {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: #17a2b8;
      color: #ffffff;
      padding: 6px 0;
      font-size: 18px;
      font-family: 'Arial', sans-serif;
      border-top: 0px solid #017f6a;
      box-shadow: 0 -2px 7px rgba(0, 0, 0, 0.5);
      z-index: 1000;
    }
    
    .dropdown {
      position: relative;
      display: inline-block;
    }
    
    .dropdown-btn-filter {
      background-color: #3498db;
      color: white;
      padding: 8px 16px;
      border: none;
      cursor: pointer;
      border-radius: 3px;
    }
    
    .dropdown-content-filter {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      z-index: 1;
      min-width: 160px;
      top: 100%;
      left: 0;
    }
    
    .dropdown-content-filter a {
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      color: #333;
    }
    
    .dropdown-content-filter a:hover {
      background-color: #ddd;
    }
    
    .show {
      display: block;
    }
    
    .dropdown {
      position: relative;
      display: inline-block;
    }
    
    .dropdown-btn-filter {
      margin-left: 5px;
      margin-top: 5px;
      padding: 11px !important;
      border-radius: 3px;
      font-weight: bold;
      background: #093b7b;
      border: none;
      color: white;
      cursor: pointer;
    }
    
    .dropdown-content-filter {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }
    
    .dropdown-content-filter a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    
    .dropdown-content-filter a:hover {
      background-color: #f1f1f1;
    }
    
    .dropdown-content-filter.show {
      display: block;
    }
    
    .customer-popup {
      display: none;
      position: fixed;
      /* Keep the popup absolutely positioned */
      background-color: #ffffff;
      border: 1px solid #ccc;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
      padding: 10px;
      z-index: 1000;
      width: 300px;
      border-radius: 8px;
      color: #270707;
      font-size: 12px;
      width: 20%;
      transition: transform 0.3s ease, top 0.3s ease, left 0.3s ease;
    }
    
    .customer-display-name {
      cursor: pointer;
      /* Change cursor to hand */
      position: relative;
      /* Ensure the element is positioned relative to allow proper alignment of the popup */
    }
    
    .popup-arrow {
      width: 0;
      height: 0;
      border-left: 10px solid transparent;
      border-right: 10px solid transparent;
      border-bottom: 10px solid #f9f9f9;
      position: absolute;
      top: -10px;
      /* Position the arrow above the popup */
      left: 20px;
      /* Adjust the arrow's position horizontally */
      z-index: 1001;
    }
    
    .profile-popup {
      background-color: #16a085;
      padding: 10px;
      border-radius: 8px;
      color: white;
    }
    
    /* Ensure the popup remains within the viewport */
    .customer-popup.bottom {
      top: 100%;
      /* Position the popup below the element */
      left: 0;
      /* Align the popup to the left of the element */
      margin-top: 10px;
      /* Space between the element and the popup */
    }
    
    .customer-popup.top {
      bottom: 100%;
      /* Position the popup above the element */
      left: 0;
      /* Align the popup to the left of the element */
      margin-bottom: 10px;
      /* Space between the element and the popup */
      transform: translateY(-10px);
      /* Slide the popup slightly upwards */
    }
    
    .customer-popup.left {
      right: 100%;
      /* Position the popup to the left of the element */
      top: 0;
      /* Align the popup to the top of the element */
      margin-right: 10px;
      /* Space between the element and the popup */
    }
    
    .customer-popup.right {
      left: 100%;
      /* Position the popup to the right of the element */
      top: 0;
      /* Align the popup to the top of the element */
      margin-left: 10px;
      /* Space between the element and the popup */
    }
    
    .d-flex {
      display: flex;
    }
    
    .align-items-center {
      align-items: center;
    }
    
    .justify-content-between {
      justify-content: space-between;
    }
    
    .ml-3 {
      margin-left: 1rem;
    }
    
    .mx-1 {
      margin-left: 0.25rem;
      margin-right: 0.25rem;
    }
    
    .button-div {
      display: flex;
      align-items: center;
    }
    
    .dropdown-btn-filter {
      padding: 10px;
      background-color: #c4a35a;
      color: white;
      border: none;
      cursor: pointer;
    }
    
    .dropdown-content-filter {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }
    
    .dropdown:hover .dropdown-content-filter {
      display: block;
    }
    
    .dropdown-content-filter a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }
    
    .dropdown-content-filter a:hover {
      background-color: #ddd;
    }
    
    .fa-link {
    font-weight: 600;
    float: inline-end;
    color: blanchedalmond;
    background: linear-gradient(45deg, pink, red, lightpink, hotpink);
    border-radius: 50%;
    padding: 4px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), inset 0 1px 4px rgba(255, 255, 255, 0.3);
    cursor: pointer;
    animation: pulse 2s infinite;
}

.fa-link:hover {
    transform: scale(1.2);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.3), inset 0 2px 8px rgba(255, 255, 255, 0.5);
}

    #date_range {
    width: 150px;
    padding: 5px;
    }
    
    .form-dropdown {
    position: absolute !important;
    background: #ffffff !important;
    border: 1px solid #cccccc !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 8px !important;
    z-index: 1000 !important;
    width: 356px;
    border-radius: 6px;
    transform: translate(10px, 5px);
    top: 181.273px !important;
    left: 429.947px !important;
}

.form-dropdown .form-control {
    margin-bottom: 10px;
}
    /* CSS for Ad Details Popup */
.ad-details-popup {
  font-size: 14px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  transition: opacity 0.3s ease-in-out;
  line-height: 1.5;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Hover Effect */
td:hover {
  background-color: rgba(0, 0, 0, 0.05);
  cursor: pointer;
}

    .fa-sticky-note {
        font-size: 10px;
        vertical-align: middle;
        color: #f39c12; /* Orange color for visibility */
        margin-right: 5px;
        transition: color 0.3s;
    }
    .fa-sticky-note:hover {
        color: #e67e22; /* Darker orange on hover */
    }
    /* ==== Add-on Modal – visual polish (CSS only) ==== */
#addonModal .modal-dialog {
  /* override .modal-sm for this specific modal */
  max-width: 760px;      /* cozy width */
  margin: 1.25rem auto;
}

#addonModal .modal-content {
  border: 0;
  border-radius: .6rem;
  overflow: hidden;
  box-shadow: 0 8px 30px rgba(0,0,0,.15);
}

/* Header */
#addonModal .modal-header {
  background: linear-gradient(135deg, #0d6efd, #0b5ed7);
  color: #fff;
  border-bottom: 0;
  padding: .6rem .9rem;
}
#addonModal .modal-title {
  font-weight: 600;
  letter-spacing: .2px;
}
#addonModal .close {
  color: #fff;
  opacity: .9;
  text-shadow: none;
}
#addonModal .close:hover { opacity: 1; }

/* Body */
#addonModal .modal-body {
  padding: 0;
}

/* Table */
#addonModal table.table {
  margin: 0;
  border-collapse: separate;
  border-spacing: 0;
}

#addonModal thead {
  position: sticky;
  top: 0;
  z-index: 2;
  background: #f8fafc; /* light */
  box-shadow: 0 1px 0 rgba(0,0,0,.05);
}
#addonModal thead th {
  font-weight: 600;
  font-size: .9rem;
  color: #334155;
  border-top: 0 !important;
  border-bottom: 1px solid #e5e7eb !important;
  white-space: nowrap;
  padding: .55rem .75rem;
}
    /* blink / pulse for status button */
#dropdownButton {
  animation: colorBlink 1s ease-in-out infinite;
}

#dropdownButton { animation: colorBlink 1s ease-in-out infinite; }

@keyframes colorBlink {
  0%, 100% { background-color: #3b3933; }
  50%      { background-color: #E6C87A; }
}

/* Column sizing (no JS – label only) */
#addonModal thead th:nth-child(1) { width: 44px; }    /* checkbox */
#addonModal thead th:nth-child(2) { min-width: 220px; }/* Service (Project) */
#addonModal thead th:nth-child(3) { width: 140px; }   /* Type (Project Type) */
#addonModal thead th:nth-child(4) { width: 110px; text-align: right; } /* Amt */
#addonModal thead th:nth-child(5) { width: 160px; }   /* Date */

#addonModal tbody td {
  vertical-align: middle;
  border-top: 1px solid #eef2f7;
  padding: .55rem .75rem;
  color: #1f2937;
}

/* Zebra + hover */
#addonModal tbody tr:nth-child(odd) { background: #fcfdff; }
#addonModal tbody tr:hover { background: #f1f5ff; }

/* Checkbox look */
#addonModal .addon-check,
#addonModal tbody input[type="checkbox"] {
  width: 16px;
  height: 16px;
}

/* Service (Project) text style */
#addonModal tbody td:nth-child(2) {
  font-weight: 500;
  color: #111827;
}

/* Type (Project Type) as badge-ish chip */
#addonModal tbody td:nth-child(3) {
  font-size: .85rem;
}
#addonModal tbody td:nth-child(3) .type-chip,
#addonModal tbody td:nth-child(3) span,
#addonModal tbody td:nth-child(3) .badge {
  display: inline-block;
  background: #e8f1ff;
  color: #0b5ed7;
  padding: .2rem .5rem;
  border-radius: .35rem;
  font-weight: 600;
  line-height: 1;
}

/* Amount right aligned, tabular numbers */
#addonModal tbody td:nth-child(4) {
  text-align: right;
  font-variant-numeric: tabular-nums;
  letter-spacing: .2px;
}

/* Date input sizing */
#addonModal tbody td:nth-child(5) input[type="date"] {
  max-width: 150px;
  width: 100%;
  height: calc(1.5em + .5rem + 2px);
  padding: .25rem .5rem;
  font-size: .9rem;
}

/* Footer */
#addonModal .modal-footer {
  background: #f8fafc;
  border-top: 1px solid #e5e7eb;
  padding: .6rem .9rem;
}
#addonModal #addonTotal {
  font-weight: 700;
  color: #0b5ed7;
}
#addonModal .btn-primary#applyAddon {
  padding: .375rem .9rem;
  font-weight: 600;
  border-radius: .45rem;
  box-shadow: 0 2px 0 rgba(11,94,215,.18);
}

/* Small screens: allow horizontal scroll, keep columns readable */
@media (max-width: 576px) {
  #addonModal .modal-dialog { max-width: 95vw; }
  #addonModal .table-responsive { overflow-x: auto; }
  #addonModal thead th:nth-child(2) { min-width: 180px; }
  #addonModal thead th:nth-child(3) { width: 120px; }
  #addonModal thead th:nth-child(5) { width: 150px; }
}

    </style>
<div class="dropdown">
  <div class="container-fluid" style="padding-left: 0px; padding-right: 0px;">

    <div class="card" style="width: 100%;">
      <div class="card-header" style="display: flex; align-items: center; padding: 0px; background-color: lightblue; box-shadow: 0px 6px 6px 0px grey;">

        <div class="heading-div" style="flex: 1; display: flex; align-items: center;">

          <!-- Paused Balance -->
          <h6 class="btn btn-primary" style="margin-left: 5px; margin-top: 5px; padding: 5px!important; border-radius: 3px; font-weight: bold; background: #093b7b; border-color: #093b7b;" data-toggle="tooltip" title="Paused Balance">
            ${{$paused_amount}} <i class="fa fa-pause" aria-hidden="true"></i>
          </h6>
            <h6 class="btn btn-primary" style="margin-left: 5px; margin-top: 5px; padding: 5px!important; border-radius: 3px; font-weight: bold; background: #646564; border-color: #646564;" data-toggle="tooltip" title="{{ $to_be_load < 0 ? 'Excessive USD Loads' : 'To Be Loaded' }}">
                ${{ number_format($to_be_load, 2) }} <i class="fa fa-upload" aria-hidden="true"></i>
            </h6>
          <!-- Receivable -->
          <h6 class="btn btn-primary" style="margin-left: 5px; margin-top: 5px; padding: 5px!important; border-radius: 3px; font-weight: bold; background: #646564; border-color: #646564;" data-toggle="tooltip" title="Receivable">
            Rs.{{$formattedTotalToBeReceived}} <i class="fa fa-clock-o" aria-hidden="true"></i>
          </h6>
          <!-- Daily Spend -->
          <button id="dailySpendButton" class="btn btn-primary" style="margin-left: 5px; padding: 5px; border-radius: 3px; font-weight: bold; background-color: #5a9c5a; border-color: #5a9c5a; cursor: pointer;">
            <i class="fa fa-calculator" aria-hidden="true"></i> Daily Spend:
          </button>

          <!-- Total Active Ads -->
          <button id="activeAdsButton" class="btn btn-primary" style="margin-left: 5px; padding: 5px; border-radius: 3px; font-weight: bold; background-color: #3a89c5; border-color: #3a89c5; cursor: pointer;" data-toggle="tooltip" title="Total Active Ads">
            <i class="fa fa-bullhorn" aria-hidden="true"></i> Active:
          </button>

          <!-- Actively Running Ads Budget -->
          <button id="ARABButton" class="btn btn-primary" style="margin-left: 5px; padding: 5px; border-radius: 3px; font-weight: bold; background-color: #8e44ad; border-color: #8e44ad; cursor: pointer;" data-toggle="tooltip" title="Total Actively Running Ads Budget">
            <i class="fa fa-dollar-sign" aria-hidden="true"></i> ARAB:
          </button>
          <!-- Dropdown for Status -->
          <div class="dropdown">
            <button id="dropdownButton" class="btn btn-primary" style="margin-left: 5px; padding: 5px; border-radius: 3px; font-weight: bold; background-color: #c4a35a; border-color: #c4a35a; cursor: pointer;">
              Status &#9660;
            </button>
            <div class="dropdown-content-filter" id="dropdownFilterMenu" style="display: none; position: absolute; background-color: #f1f1f1; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2); z-index: 1;">
              <a href="{{ route('ads.filterByStatus', ['status' => 'Pending Action']) }}">Pending Action</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'Pending']) }}">Pending</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'Paused']) }}">Paused</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'Paid']) }}">Paid</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'Refunded']) }}">Refunded</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'Cancelled']) }}">Cancelled</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'Overpaid']) }}">Overpaid</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'PV Adjusted']) }}">PV Adjusted</a>
              <a href="{{ route('ads.filterByStatus', ['status' => 'Informed']) }}">Informed</a>
              <a href="{{ route('ads.filterByCalculatedStatus', ['status' => 'Running']) }}">Running</a>
              <a href="{{ route('ads.filterByCalculatedStatus', ['status' => 'Ending today']) }}">Ending today</a>
              <a href="{{ route('ads.filterByCalculatedStatus', ['status' => 'Ending tomorrow']) }}">Ending tomorrow</a>
              <a href="{{ route('ads.filterByCalculatedStatus', ['status' => 'Ended']) }}">Ended</a>
            </div>
            <button class="btn btn-primary" style="margin-right: 5px; padding: 5px; border-radius: 3px; font-weight: bold; background-color: #c4a35a; border-color: #c4a35a; cursor: pointer;" onclick="window.location.href='{{ route('ads.filterByMonitoringStatus') }}'">
              <i class="fa fa-desktop" aria-hidden="true"></i>
            </button>
            <select onchange="if(this.value){ window.location =
        '{{ url('/admin/dashboard/ads/volume') }}/' + this.value }"
        class="form-control form-control-sm" style="width:auto; display:inline;">
    <option value="">All Volume</option>
    <option value="high" {{ request()->segment(5)=='high' ? 'selected' : '' }}>High (≥ 100 USD)</option>
    <option value="low"  {{ request()->segment(5)=='low'  ? 'selected' : '' }}>Low (≤ 20 USD)</option>
</select>

          </div>
        </div>
        <form action="{{ route('search_ad') }}" method="get" style="display: flex; align-items: center; gap: 20px;">
    @csrf
    <!-- Search by Name, Display Name, or Number -->
    <div style="flex: 1;position: relative;margin-right: 5px;">
        <input type="text" name="search_query" placeholder="Find Individual Records" class="form-control" style="padding-right: 35px;">
        <!-- Clickable Search Icon for Search Query -->
        <button type="submit" style="border: none; background: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
            <i class="fas fa-search" style="color: #aaa;"></i>
        </button>
    </div>
</form>
<form action="{{ route('search_ad') }}" method="get" style="display: flex; align-items: center; gap: 20px;">
    @csrf
    <!-- Date Range Picker Form -->
    <div style="flex: 1; position: relative;">
        <input type="text" name="date_range" id="date_range" class="form-control" placeholder="Select Date" style="padding-right: 35px;">
        <!-- Clickable Search Icon for Date Range -->
        <button type="submit" style="border: none; background: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
            <i class="fas fa-search" style="color: #aaa;"></i>
        </button>
    </div>
</form>
        <div class="dropdown" style="margin-left: 10px; position: relative;">
          <button id="optionButton" class="btn" style="background-color:#c4a35a!important;color: white;">Option</button>
          <div class="dropdown-menu" id="optionDropdown" style="display: none; position: absolute; background-color: #f1f1f1; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2); z-index: 1;">
            <a href="{{ route('ads.yesterday') }}" class="dropdown-item">Yesterday</a>
            <a href="{{ route('ads.this_day') }}" class="dropdown-item">Today</a>
            <a href="{{ route('ads.this_week') }}" class="dropdown-item">This Week</a>
            <a href="{{ route('ads.this_month') }}" class="dropdown-item">This Month</a>
          </div>
        </div>

        <div class="button-div" style="display: flex; align-items: center;">
          <div id="addnew_btn" style="display: block;">
            <button class="ml-3 btn sm btn-primary" style="color:white;" onclick="addRow()"><i class='fas fa-record-vinyl'></i> </button>
          </div>
          <button class="ml-3 btn sm btn-primary" style="height: 35px;" id="noteButton"><i class="fas fa-solid fa-book-open"></i></button>
        </div>

      </div>
    </div>
  </div>

  <div id="notePopup" class="popup">
    <div class="popup-content">
      <textarea id="noteInput" placeholder="Write your note..."></textarea>
      <table>
        <thead>
          <th style="min-width: 150px; max-width: 150px">Customer</th>
          <th>USD</th>
          <th>Remarks</th>
          <th>xyz</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php $x = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]; ?>
          <div id="myInputsContainer">
            @foreach($x as $item)
            <tr id="{{$item}}" class="items">
              <td style="padding: 2.5px;">
                <input name="customer[{{$item}}]" type="text">
              </td>
              <td style="padding: 2.5px;">
                <input name="USD[{{$item}}]" type="number" step="0.01">
              </td>
              <td style="padding: 2.5px;">
                <input name="Remarks[{{$item}}]" type="text">
              </td>
              <td style="padding: 2.5px;">
                <input name="xyz[{{$item}}]" type="text">
              </td>
              <td style="padding: 2.5px;">
                <button class="clear-button">Clear</button>
              </td>
            </tr>
            @endforeach
          </div>
        </tbody>
      </table>
      <button class="trigger-close" id="saveButton">Save Note</button>
      <span class="trigger-close" id="closeButton">Close Note</span>
    </div>
  </div>

  <div class="card-body" style="padding: 0; padding-top: 0px;">
    <div id="add_new" style="display: show;">
      <form id="newRecordForm" action="{{ route('storeAd') }}" method="POST">
        @csrf
        <table>
          <tbody>
            <tr id="ad__table" class="blinking-row">
  <td style="min-width: 200px; padding: 2.5px;">
<input class="form-control" type="text" id="customer" name="customer" placeholder="Customer WhatsApp Number" required onblur="fetchCustomerRate()" >
</td>
  <td style="min-width: 100px; padding: 2.5px;">
    <input class="form-control" type="text" id="USD" step="0.01" name="USD" placeholder="USD" required>
  </td>
  <td style="min-width: 100px; padding: 2.5px;">
    <input class="form-control" type="text" id="Rate" name="Rate" placeholder="Rate" readonly ondblclick="makeEditable(this)" >
  </td>
  <td style="min-width: 100px; padding: 2.5px;">
    <input class="form-control" type="text" id="NRP" name="NRP" placeholder="NRP" required>
  </td>
  
              <td style="min-width: 200px; padding: 2.5px;">
                  <form id="adAccountForm" method="POST" action="/saveAdAccount">
    @csrf
   <div style="display: flex; gap: 1px;"> 
   <!-- Manual input field --> 
   <input class="form-control" type="text" id="manualInput" placeholder="Admin" style="flex: 1;" />
    <!-- Datalist input field -->
        <input class="form-control" type="text" id="datalistInput" placeholder="Select Ad Account" list="adAccountOptions" style="flex: 1;max-width: 50%;" />
        <datalist id="adAccountOptions">
    @if(isset($storredAdAccounts) && $storredAdAccounts->isNotEmpty())
        @foreach($storredAdAccounts as $adAccount)
            <option value="{{ $adAccount->ad_account_name }}">{{ $adAccount->ad_account_name }}</option>
        @endforeach
    @endif
</datalist>

    </div>
    <!-- Hidden Ad_Account field that will be populated -->
    <input  class="form-control" type="text" id="Ad_Account" name="Ad_Account" placeholder="Ad Account" style=" display: none; "  required readonly />
</form>
    </td>      
    <td style="min-width: 100px; padding: 2.5px;">
                <select class="form-control" id="Payment" name="Payment" required onchange="toggleBakiField()">
                  <option value="Pending" selected>Pending</option>
                  <option value="Paused">Paused</option>
                  <option value="FPY Received">FPY Received</option>
                  <option value="eSewa Received">eSewa Received</option>
                  <option value="Baki">Baki</option>
                  <option value="Paid">Paid</option>
                  <option value="Refunded">Refunded</option>
                  <option value="Cancelled">Cancelled</option>
                  <option value="Overpaid">Overpaid</option>
                  <option value="PV Adjusted">PV Adjusted</option>
                  <option value="Informed">Informed</option>
                </select>
              </td>
              <td style="min-width: 50px; padding: 2.5px;">
                <input type="text" id="duration" name="Duration" class="form-control" placeholder="Duration" required>
              </td>
              <td style="min-width: 50px; padding: 2.5px;">
                <input type="text" class="form-control" id="Quantity" name="Quantity" placeholder="Quantity" required>
              </td>
              <td style="min-width: 100px; padding: 2.5px;">
                <select class="form-control" id="Status" name="Status" required>
                  <option value="New" selected>New</option>
                  <option value="Extend">Extend</option>
                  <option value="Both">Both</option>
                  <option value="On schedule">On schedule</option>
                </select>
              </td>
              <td style="min-width: 100px; padding: 2.5px;">
                <input type="text" class="form-control" id="bakifield" value="" name="advance" placeholder="Baki" disabled>
              </td>
              <td style="min-width: 150px; padding: 2.5px;">
                <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" placeholder="Ad Nature/Page" required>
              </td>
              <td style="min-width: 80px; padding: 2.5px;">
<button type="button"
        class="btn btn-sm btn-warning addon-btn-new"
        data-exist-types="[]">   {{-- ➜ new मा default empty --}}
  <i class="fa fa-plus-circle"></i>
</button>
              <input type="hidden" class="form-control" value="{{ auth('admin')->user()->name }}" id="admin" name="admin" required>

              <td style="padding: 2.5px; display: flex; justify-content: space-between;">
                <button class="btn btn-danger" style="margin-right: 10px;" onclick="close_()">Cancel</button>
                <button type="submit" class="btn btn-success" id="btn_submit">Submit</button>
              </td>
            </tr>
          </tbody>
        </table>

        <script>
          function toggleBakiField() {
            const paymentStatus = document.getElementById('Payment').value;
            const bakiField = document.getElementById('bakifield');
            if (paymentStatus === 'Overpaid' || paymentStatus === 'Baki' || paymentStatus === 'Refunded') {
              bakiField.disabled = false;
            } else {
              bakiField.disabled = true;
              bakiField.value = ''; // Clear the field if not needed
            }
          }
        </script>
      </form>
    </div>

    <div>

    </div>
    <div class="overflow-mobile">
      <table style="width: 100%!important;">
        <thead>
          <tr>
            <th style="min-width: 55px; background: #646564; color: white;">Date</th>
            <th style="min-width: 80px; background: #646564; color: white;">WhatsApp</th>
            <th style="min-width: 350px; background: #646564; color: white;">Customer Name</th>
            <th style="min-width: 50px; background: #646564; color: white;">USD</th>
            <th style="min-width: 50px; background: #646564; color: white;display: none;">Rate</th>
            <th style="min-width: 75px; background: #646564; color: white;">NRP</th>
            <th style="min-width: 150px; background: #646564; color: white;">Ad Account</th>
            <th style="min-width: 150px; background: #646564; color: white;">Payment Status</th>
            <th style="min-width: 10px; background: #646564; color: white;">Days</th>
            <th style="min-width: 10px; background: #646564; color: white;">Q.</th>
            <th style="min-width: 100px; background: #646564; color: white;">Status</th>
            <th style="min-width: 35px; background: #646564; color: white;">Baki</th>
            <th style="min-width: 120px; background: #646564; color: white;">Ad Nature/Page</th>
            <th style="min-width: 90px; background: #646564; color: white;">add-on</th>
            <th style="min-width: 60px; background: #646564; color: white;">Admin</th>
            <th style="background: #646564; color: white;">Done</th>
            <th style="background: #646564; color: white;">Task</th>
          </tr>
        </thead>
        <tbody>

          @foreach($ads as $ad)
          
          @if($ad->Payment == "Pending")
          <tr style="background-color: #f9a52c6e; color: #000206;">
            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
              @csrf
                  <input type="hidden" name="redirect_to" value="{{ url()->full() }}">

               <td style="padding: 2.5px; position: relative;" onmouseenter="showAdDetails(this, '{{ $ad->created_at }}')" onmouseleave="hideAdDetails(this)">   {{ $ad->created_at ? $ad->created_at->format('M j') : '' }} </td>
              <td style="padding: 2.5px;">
                @php
                $customer = $ad->customer()->first(); // Make sure $customer is defined
                $phoneNumber = $customer->phone ?? $customer->phone_2; // Check if customer exists before using its attributes
                @endphp

                @if($customer)
                <a href="https://wa.me/+977{{ $customer->phone }}?text={{ rawurlencode('
*Your ad campaign has been set up.*
The total cost is *Rs '. number_format($ad->NRP, 0, '.', ',') . '/-*.
eSewa payment: 9856000601
_Kindly make the payment within an hour to ensure smooth processing of your ad campaign._
_*Thank you.*_') }}" target="_blank" style="text-decoration: none; color: inherit;">
                 <strong id="phone-number" style="user-select: all; {{ $customer && $customer->requires_bill ? 'background-color: darkgreen; color: white; padding: 2px 6px; border-radius: 4px;' : '' }}"> {{ $customer->phone }} </strong>
                </a>

                @else
                <span>No Customer Info</span>
                @endif


              </td>

              <td style="padding: 2.5px;">
                <span class="customer-display-name" @if($customer) onmouseover="showPopup(event, '{{ $customer->id }}')" onmouseout="hidePopup()" onclick="goToDetails('{{ route('customer.details', ['id' => $customer->id]) }}')" @endif>
                  <strong>{{ $customer ? $customer->display_name : 'Unknown Customer' }}</strong>
@if($customer && isset($customerNoteCounts[$customer->id]) && $customerNoteCounts[$customer->id] > 0)
                                                <a href="{{ route('customer.details', ['id' => $customer->id]) }}#notes-list">
                                                    <i class="fas fa-sticky-note" data-toggle="tooltip" title="{{ $customerNoteCounts[$customer->id] }} note(s)"></i>
                                                </a>
                                            @endif
                  <span class="ad-status" data-created-at="{{ $ad->created_at }}" data-duration="{{ $ad->Duration }}">
                    <!-- Badge will be updated by JavaScript -->
                  </span>
                </span>

                @if($customer)
                <div class="customer-popup" id="popup-{{ $customer->id }}">
                  <div class="popup-arrow"></div>
                  <!-- Arrow pointing to the name -->
                  <div class="profile-popup">
                    <div style="display: flex; align-items: center;">
                      <div>
                        <h4>{{ $customer->name }}</h4>
                        <span class="form-group" style="margin-bottom: 0px;">
                        <button type="button"class="btn btn-info" style="width: 100%; font-weight: bold; cursor: default;"readonly>{{ $ad->Rate }}</button>
                        </span>
                        <span><strong>Name:</strong> {{ $customer->name }}</span>
                        <span><strong>Company:</strong> {{ $customer->display_name }}</span>
                        <span><strong>Email:</strong> {{ $customer->email }}</span>
                        <span><strong>Phone:</strong> {{ $customer->phone }}</span>
                        <span><strong>Address:</strong> {{ $customer->address }}</span>
                      </div>
                      <div style="margin-left: 10px;">
                        @if($customer->profile_picture)
                        <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @else
                        <img src="{{ asset('uploads/customers/default.jpg') }}" alt="Default Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                @endif
<!-- Unified Icon Trigger -->
<a href="javascript:void(0)" data-customer-id="{{ $customer->id }}" class="open-link-form" style="text-decoration: none; color: inherit; position: relative;">
    <i class="fa fa-link blink" aria-hidden="true" style="cursor: pointer;"></i>
</a>

<div id="form-container-{{ $customer->id }}" class="form-dropdown d-none" style="position: absolute; background: #fff; border: 1px solid #ccc; padding: 15px; z-index: 1000; min-width: 300px;">
    <!-- The dynamic form will be injected by JS -->
</div>
              </td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" oninput="calAmt({{$ad->id}})" class="form-control" onchange="calAmt({{$ad->id}})" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                </div>
              </td>
              <!-- Non-editable Rate Field -->
<td style="padding: 2.5px;display:none;">
    <div class="form-group" style="margin-bottom: 0px;">
        <input type="text" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" readonly>
 
    </div>
</td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                    @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                    <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}> {{ $Payment }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                    @foreach(['New', 'Extend', 'Both', 'On schedule', 'Monitoring'] as $status)
                    <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}> {{ $status }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                @if($ad->advance == '')
                <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                  <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                @else
                <div class="form-group" id="{{$ad->id.'bakifield'}}">
                  <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                </div>
                @endif
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                </div>
              </td>
        @php
  $raw = $ad->add_on ?? [];
  if ($raw instanceof \Illuminate\Support\Collection) $raw = $raw->toArray();
  if (is_string($raw)) {
      $s = html_entity_decode($raw, ENT_QUOTES);
      $s = trim($s);
      if ((substr($s,0,1)==='"' && substr($s,-1)==='"') || (substr($s,0,1)==="'" && substr($s,-1)==="'")) $s = substr($s,1,-1);
      $decoded = json_decode($s, true);
      if (!is_array($decoded)) $decoded = json_decode(stripslashes($s), true);
      $raw = is_array($decoded) ? $decoded : [];
  }
  $arr = $raw['data'] ?? $raw;

  // --- name र type छुट्ट्याएर: name मा project_type नहाल्ने! ---
  $pairs = collect($arr)->map(function($x){
      if (is_object($x)) $x = (array)$x;
      $name = is_array($x)
          ? ($x['service_name'] ?? $x['project'] ?? $x['title'] ?? $x['name'] ?? null)
          : (is_string($x) ? $x : null);
      $type = is_array($x) ? ($x['project_type'] ?? $x['type'] ?? null) : null;
      return ($name || $type) ? ['name'=>$name, 'type'=>$type] : null;
  })->filter()->values();

  $hasAddons = $pairs->isNotEmpty();

  // --- Label/type: type मात्र देखाउने, type नभए name देखाउने ---
  $labels = $pairs->map(function($p){
      return $p['type'] ?: $p['name'];
  });

  $shown      = $labels->take(2)->implode(', ');
  $extraCount = max($labels->count() - 2, 0);
  $labelText  = $hasAddons ? ($extraCount > 0 ? ($shown.' +'.$extraCount) : $shown) : 'Add-on';
  $titleText  = $hasAddons ? $labels->implode(', ') : 'Add-on';

  $wa = (isset($customer) && $customer) ? ($customer->phone ?? '') : '';
    // अहिले सम्म सेव भएका project_type हरू (type नभए name) को सूची:
  $existingTypes = $pairs->map(function($p){ return $p['type'] ?: $p['name']; })
                         ->filter()->unique()->values()->all();
  $existingTypesJson = json_encode($existingTypes);

@endphp

<td style="min-width:90px; padding:2.5px;">
  <button type="button"
          class="btn btn-sm {{ $hasAddons ? 'btn-info' : 'btn-warning' }} addon-btn"
          data-customer="{{ $wa }}"
          data-ad-id="{{ $ad->id }}"
          data-exist-types='@json($existingTypes)'
          title="{{ $titleText }}"
          style="border:0; {{ $hasAddons ? '' : '' }}"
          {{ (!$wa) ? 'disabled' : '' }}>
    @if($hasAddons)
      {{ $labelText }}
    @else
      <i class="fa fa-plus-circle"></i> {{ $labelText }}
    @endif
  </button>
</td>


              <td style="padding: 2.5px;">
  <span class="admin-name"
        data-ad-id="{{ $ad->id }}"
        data-toggle="tooltip"
        data-html="true" 
        title="Clicks: 0">
    {{ $ad->admin }}
  </span>
</td>

              <td style="padding: 2.5px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i></button>
              </td>
            </form>
            <td style="padding: 2.5px;">
              <!-- Dropdown Trigger Button -->
              <div class="dropdown">
                <button class="dropdown-button" onclick="toggleDropdown_({{$ad->id}})"><i class="fas fa-tasks"></i></button>
                <div id="menu_{{$ad->id}}" class="horizontal-menu">
                  <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="menu-item">Delete Campaign</a>
                  <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="menu-item">View Invoice</a>
                  <a href="{{ route('admin.customer.impersonate', $customer->id) }}" class="menu-item" style="target:blank;">View Portal</a>
                  <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="menu-item">View PDF</a>
                  <a href="javascript:void(0);" onclick="sendEmail({{$ad->id}})" class="menu-item">Send Email</a>
                  @php
                  $status = $ad->calculateStatus($ad->created_at, $ad->Duration); @endphp
                  @if($status)
                  <a href="javascript:void(0);" onclick="sendReminder('{{ $ad->id }}', '{{ $ad->created_at ? $ad->created_at->format('Y-m-d H:i:s') : '' }}', '{{ addslashes($status) }}', '{{ $customer->phone }}', {{ $ad->Duration }})" class="menu-item">Send Reminder</a>
                  @else
                  <span>No Customer Info</span>
                  @endif
                </div>
              </div>
            </td>
          </tr>
          @elseif($ad->Payment == "Paused")
          <tr style="background-color: #c4a35a;">
            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
              @csrf
                  <input type="hidden" name="redirect_to" value="{{ url()->full() }}">

               <td style="padding: 2.5px; position: relative;" onmouseenter="showAdDetails(this, '{{ $ad->created_at }}')" onmouseleave="hideAdDetails(this)">   {{ $ad->created_at ? $ad->created_at->format('M j') : '' }} </td>
              <td style="padding: 2.5px;">
                @php
                $customer = $ad->customer()->first(); // Make sure $customer is defined
                $phoneNumber = $customer->phone ?? $customer->phone_2; // Check if customer exists before using its attributes
                @endphp

                @if($customer)
                <a href="https://wa.me/+977{{ $customer->phone }}?text={{ rawurlencode('
*Your ad campaign has been set up.*
The total cost is *Rs '. number_format($ad->NRP, 0, '.', ',') . '/-*.
eSewa payment: 9856000601
_Kindly make the payment within an hour to ensure smooth processing of your ad campaign._
_*Thank you.*_') }}" target="_blank" style="text-decoration: none; color: inherit;">
                 <strong id="phone-number" style="user-select: all; {{ $customer && $customer->requires_bill ? 'background-color: darkgreen; color: white; padding: 2px 6px; border-radius: 4px;' : '' }}"> {{ $customer->phone }} </strong>
                </a>
                @else
                <span>No Customer Info</span>
                @endif

              </td>

              <td style="padding: 2.5px;">
                <span class="customer-display-name" @if($customer) onmouseover="showPopup(event, '{{ $customer->id }}')" onmouseout="hidePopup()" onclick="goToDetails('{{ route('customer.details', ['id' => $customer->id]) }}')" @endif>

                  <strong>{{ $customer ? $customer->display_name : 'Unknown Customer' }}</strong>
@if($customer && isset($customerNoteCounts[$customer->id]) && $customerNoteCounts[$customer->id] > 0)
                                                <a href="{{ route('customer.details', ['id' => $customer->id]) }}#notes-list">
                                                    <i class="fas fa-sticky-note" data-toggle="tooltip" title="{{ $customerNoteCounts[$customer->id] }} note(s)"></i>
                                                </a>
                                            @endif
                  <span class="ad-status" data-created-at="{{ $ad->created_at }}" data-duration="{{ $ad->Duration }}">
                    <!-- Badge will be updated by JavaScript -->
                  </span>
                </span>

                @if($customer)
                <div class="customer-popup" id="popup-{{ $customer->id }}">
                  <div class="popup-arrow"></div>
                  <div class="profile-popup">
                    <div style="display: flex; align-items: center;">
                      <div>
                        <h4>{{ $customer->name }}</h4>
                        <span class="form-group" style="margin-bottom: 0px;">
                        <button type="button"class="btn btn-info" style="width: 100%; font-weight: bold; cursor: default;"readonly>{{ $ad->Rate }}</button>
                        </span>
                        <span><strong>Name:</strong> {{ $customer->name }}</span>
                        <span><strong>Company:</strong> {{ $customer->display_name }}</span>
                        <span><strong>Email:</strong> {{ $customer->email }}</span>
                        <span><strong>Phone:</strong> {{ $customer->phone }}</span>
                        <span><strong>Address:</strong> {{ $customer->address }}</span>
                      </div>
                      <div style="margin-left: 10px;">
                        @if($customer->profile_picture)
                        <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @else
                        <img src="{{ asset('uploads/customers/default.jpg') }}" alt="Default Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                @endif
<!-- Unified Icon Trigger -->
<a href="javascript:void(0)" data-customer-id="{{ $customer->id }}" class="open-link-form" style="text-decoration: none; color: inherit; position: relative;">
    <i class="fa fa-link blink" aria-hidden="true" style="cursor: pointer;"></i>
</a>

<div id="form-container-{{ $customer->id }}" class="form-dropdown d-none" style="position: absolute; background: #fff; border: 1px solid #ccc; padding: 15px; z-index: 1000; min-width: 300px;">
    <!-- The dynamic form will be injected by JS -->
</div>

              </td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" class="form-control" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                </div>
              </td>
              <!-- Non-editable Rate Field -->
<td style="padding: 2.5px;display:none;">
    <div class="form-group" style="margin-bottom: 0px;">
        <input type="text" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" readonly>
 
    </div>
</td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                    @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                    <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}> {{ $Payment }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                    @foreach(['New', 'Extend', 'Both', 'On schedule', 'Monitoring'] as $status)
                    <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}> {{ $status }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                @if($ad->advance == '')
                <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                  <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                @else
                <div class="form-group" id="{{$ad->id.'bakifield'}}">
                  <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                </div>
                @endif
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                </div>
              </td>
             @php
  $raw = $ad->add_on ?? [];
  if ($raw instanceof \Illuminate\Support\Collection) $raw = $raw->toArray();
  if (is_string($raw)) {
      $s = html_entity_decode($raw, ENT_QUOTES);
      $s = trim($s);
      if ((substr($s,0,1)==='"' && substr($s,-1)==='"') || (substr($s,0,1)==="'" && substr($s,-1)==="'")) $s = substr($s,1,-1);
      $decoded = json_decode($s, true);
      if (!is_array($decoded)) $decoded = json_decode(stripslashes($s), true);
      $raw = is_array($decoded) ? $decoded : [];
  }
  $arr = $raw['data'] ?? $raw;

  // --- name र type छुट्ट्याएर: name मा project_type नहाल्ने! ---
  $pairs = collect($arr)->map(function($x){
      if (is_object($x)) $x = (array)$x;
      $name = is_array($x)
          ? ($x['service_name'] ?? $x['project'] ?? $x['title'] ?? $x['name'] ?? null)
          : (is_string($x) ? $x : null);
      $type = is_array($x) ? ($x['project_type'] ?? $x['type'] ?? null) : null;
      return ($name || $type) ? ['name'=>$name, 'type'=>$type] : null;
  })->filter()->values();

  $hasAddons = $pairs->isNotEmpty();

  // --- Label/type: type मात्र देखाउने, type नभए name देखाउने ---
  $labels = $pairs->map(function($p){
      return $p['type'] ?: $p['name'];
  });

  $shown      = $labels->take(2)->implode(', ');
  $extraCount = max($labels->count() - 2, 0);
  $labelText  = $hasAddons ? ($extraCount > 0 ? ($shown.' +'.$extraCount) : $shown) : 'Add-on';
  $titleText  = $hasAddons ? $labels->implode(', ') : 'Add-on';

  $wa = (isset($customer) && $customer) ? ($customer->phone ?? '') : '';
    // अहिले सम्म सेव भएका project_type हरू (type नभए name) को सूची:
  $existingTypes = $pairs->map(function($p){ return $p['type'] ?: $p['name']; })
                         ->filter()->unique()->values()->all();
  $existingTypesJson = json_encode($existingTypes);

@endphp

<td style="min-width:90px; padding:2.5px;">
  <button type="button"
          class="btn btn-sm {{ $hasAddons ? 'btn-info' : 'btn-warning' }} addon-btn"
          data-customer="{{ $wa }}"
          data-ad-id="{{ $ad->id }}"
  data-exist-types='@json($existingTypes)'
          title="{{ $titleText }}"
          style="border:0; {{ $hasAddons ? '' : '' }}"
          {{ (!$wa) ? 'disabled' : '' }}>
    @if($hasAddons)
      {{ $labelText }}
    @else
      <i class="fa fa-plus-circle"></i> {{ $labelText }}
    @endif
  </button>
</td>

              <td style="padding: 2.5px;">
  <span class="admin-name"
        data-ad-id="{{ $ad->id }}"
        data-toggle="tooltip"
        data-html="true"    
        title="Clicks: 0">
    {{ $ad->admin }}
  </span>
</td>
<td style="padding: 2.5px;">
  <button type="submit" class="btn btn-primary">
    <i class="fas fa-check-square"></i>
  </button>
</td>
            </form>
            <td style="padding: 2.5px;">
              <div class="dropdown">
                <button class="dropdown-button" onclick="toggleDropdown_({{$ad->id}})"><i class="fas fa-tasks"></i></button>
                <div id="menu_{{$ad->id}}" class="horizontal-menu">
                  <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="menu-item">Delete Campaign</a>
                  <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="menu-item">View Invoice</a>
                  <a href="{{ route('admin.customer.impersonate', $customer->id) }}" class="menu-item" style="target:blank;">View Portal</a>
                  <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="menu-item">View PDF</a>
                  <a href="javascript:void(0);" onclick="sendEmail({{$ad->id}})" class="menu-item">Send Email</a>
                  @php
                  $status = $ad->calculateStatus($ad->created_at, $ad->Duration); @endphp
                  @if($status)
                  <a href="javascript:void(0);" onclick="sendReminder('{{ $ad->id }}', '{{ $ad->created_at ? $ad->created_at->format('Y-m-d H:i:s') : '' }}', '{{ addslashes($status) }}', '{{ $customer->phone }}', {{ $ad->Duration }})" class="menu-item">Send Reminder</a>
                  @else
                  <span>No Customer Info</span>
                  @endif
                </div>
              </div>
            </td>
          </tr>
          @elseif($ad->Payment == "Baki")
          <tr style="background-color: #c66b3d91; color: black;">
            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
              @csrf
                  <input type="hidden" name="redirect_to" value="{{ url()->full() }}">

               <td style="padding: 2.5px; position: relative;" onmouseenter="showAdDetails(this, '{{ $ad->created_at }}')" onmouseleave="hideAdDetails(this)">   {{ $ad->created_at ? $ad->created_at->format('M j') : '' }} </td>
              <td style="padding: 2.5px;">
                @php
                $customer = $ad->customer()->first(); // Make sure $customer is defined
                $phoneNumber = $customer->phone ?? $customer->phone_2; // Check if customer exists before using its attributes
                @endphp


                @if($customer)
                <a href="https://wa.me/+977{{ $customer->phone }}?text={{ rawurlencode('
*Your ad campaign has been set up.*
The total cost is *Rs '. number_format($ad->NRP, 0, '.', ',') . '/-*.
eSewa payment: 9856000601
_Kindly make the payment within an hour to ensure smooth processing of your ad campaign._
_*Thank you.*_') }}" target="_blank" style="text-decoration: none; color: inherit;">
                 <strong id="phone-number" style="user-select: all; {{ $customer && $customer->requires_bill ? 'background-color: darkgreen; color: white; padding: 2px 6px; border-radius: 4px;' : '' }}"> {{ $customer->phone }} </strong>
                </a>
                @else
                <span>No Customer Info</span>
                @endif


              </td>

              <td style="padding: 2.5px;">
                <span class="customer-display-name" @if($customer) onmouseover="showPopup(event, '{{ $customer->id }}')" onmouseout="hidePopup()" onclick="goToDetails('{{ route('customer.details', ['id' => $customer->id]) }}')" @endif>

                  <strong>{{ $customer ? $customer->display_name : 'Unknown Customer' }}</strong>
@if($customer && isset($customerNoteCounts[$customer->id]) && $customerNoteCounts[$customer->id] > 0)
                                                <a href="{{ route('customer.details', ['id' => $customer->id]) }}#notes-list">
                                                    <i class="fas fa-sticky-note" data-toggle="tooltip" title="{{ $customerNoteCounts[$customer->id] }} note(s)"></i>
                                                </a>
                                            @endif
                  <span class="ad-status" data-created-at="{{ $ad->created_at }}" data-duration="{{ $ad->Duration }}">
                    <!-- Badge will be updated by JavaScript -->
                  </span>
                </span>

                @if($customer)
                <div class="customer-popup" id="popup-{{ $customer->id }}">
                  <div class="popup-arrow"></div>
                  <div class="profile-popup">
                    <div style="display: flex; align-items: center;">
                      <div>
                        <h4>{{ $customer->name }}</h4>
                        <span class="form-group" style="margin-bottom: 0px;">
                        <button type="button"class="btn btn-info" style="width: 100%; font-weight: bold; cursor: default;"readonly>{{ $ad->Rate }}</button>
                        </span>
                        <span><strong>Name:</strong> {{ $customer->name }}</span>
                        <span><strong>Company:</strong> {{ $customer->display_name }}</span>
                        <span><strong>Email:</strong> {{ $customer->email }}</span>
                        <span><strong>Phone:</strong> {{ $customer->phone }}</span>
                        <span><strong>Address:</strong> {{ $customer->address }}</span>
                      </div>
                      <div style="margin-left: 10px;">
                        @if($customer->profile_picture)
                        <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @else
                        <img src="{{ asset('uploads/customers/default.jpg') }}" alt="Default Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                @endif
<!-- Unified Icon Trigger -->
<a href="javascript:void(0)" data-customer-id="{{ $customer->id }}" class="open-link-form" style="text-decoration: none; color: inherit; position: relative;">
    <i class="fa fa-link blink" aria-hidden="true" style="cursor: pointer;"></i>
</a>

<div id="form-container-{{ $customer->id }}" class="form-dropdown d-none" style="position: absolute; background: #fff; border: 1px solid #ccc; padding: 15px; z-index: 1000; min-width: 300px;">
    <!-- The dynamic form will be injected by JS -->
</div>
              </td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                </div>
              </td>
             <!-- Non-editable Rate Field -->
<td style="padding: 2.5px;display:none;">
    <div class="form-group" style="margin-bottom: 0px;">
        <input type="text" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" readonly>
 
    </div>
</td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                    @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                    <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}> {{ $Payment }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                    @foreach(['New', 'Extend', 'Both', 'On schedule', 'Monitoring'] as $status)
                    <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}> {{ $status }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                @if($ad->advance == '')
                <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                  <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                @else
                <div class="form-group" id="{{$ad->id.'bakifield'}}">
                  <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                </div>
                @endif
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                </div>
              </td>
          @php
  $raw = $ad->add_on ?? [];
  if ($raw instanceof \Illuminate\Support\Collection) $raw = $raw->toArray();
  if (is_string($raw)) {
      $s = html_entity_decode($raw, ENT_QUOTES);
      $s = trim($s);
      if ((substr($s,0,1)==='"' && substr($s,-1)==='"') || (substr($s,0,1)==="'" && substr($s,-1)==="'")) $s = substr($s,1,-1);
      $decoded = json_decode($s, true);
      if (!is_array($decoded)) $decoded = json_decode(stripslashes($s), true);
      $raw = is_array($decoded) ? $decoded : [];
  }
  $arr = $raw['data'] ?? $raw;

  // --- name र type छुट्ट्याएर: name मा project_type नहाल्ने! ---
  $pairs = collect($arr)->map(function($x){
      if (is_object($x)) $x = (array)$x;
      $name = is_array($x)
          ? ($x['service_name'] ?? $x['project'] ?? $x['title'] ?? $x['name'] ?? null)
          : (is_string($x) ? $x : null);
      $type = is_array($x) ? ($x['project_type'] ?? $x['type'] ?? null) : null;
      return ($name || $type) ? ['name'=>$name, 'type'=>$type] : null;
  })->filter()->values();

  $hasAddons = $pairs->isNotEmpty();

  // --- Label/type: type मात्र देखाउने, type नभए name देखाउने ---
  $labels = $pairs->map(function($p){
      return $p['type'] ?: $p['name'];
  });

  $shown      = $labels->take(2)->implode(', ');
  $extraCount = max($labels->count() - 2, 0);
  $labelText  = $hasAddons ? ($extraCount > 0 ? ($shown.' +'.$extraCount) : $shown) : 'Add-on';
  $titleText  = $hasAddons ? $labels->implode(', ') : 'Add-on';

  $wa = (isset($customer) && $customer) ? ($customer->phone ?? '') : '';
    // अहिले सम्म सेव भएका project_type हरू (type नभए name) को सूची:
  $existingTypes = $pairs->map(function($p){ return $p['type'] ?: $p['name']; })
                         ->filter()->unique()->values()->all();
  $existingTypesJson = json_encode($existingTypes);

@endphp

<td style="min-width:90px; padding:2.5px;">
  <button type="button"
          class="btn btn-sm {{ $hasAddons ? 'btn-info' : 'btn-warning' }} addon-btn"
          data-customer="{{ $wa }}"
          data-ad-id="{{ $ad->id }}"
  data-exist-types='@json($existingTypes)'
          title="{{ $titleText }}"
          style="border:0; {{ $hasAddons ? '' : '' }}"
          {{ (!$wa) ? 'disabled' : '' }}>
    @if($hasAddons)
      {{ $labelText }}
    @else
      <i class="fa fa-plus-circle"></i> {{ $labelText }}
    @endif
  </button>
</td>

              <td style="padding: 2.5px;">
  <span class="admin-name"
        data-ad-id="{{ $ad->id }}"
        data-toggle="tooltip"
        data-html="true"    
        title="Clicks: 0">
    {{ $ad->admin }}
  </span>
</td>

              <td style="padding: 2.5px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i></button>
              </td>
            </form>
            <td style="padding: 2.5px;">
              <div class="dropdown">
                <button class="dropdown-button" onclick="toggleDropdown_({{$ad->id}})"><i class="fas fa-tasks"></i></button>
                <div id="menu_{{$ad->id}}" class="horizontal-menu">
                  <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="menu-item">Delete Campaign</a>
                  <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="menu-item">View Invoice</a>
                <a href="{{ route('admin.customer.impersonate', $customer->id) }}" class="menu-item">View Portal</a>
                  <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="menu-item">View PDF</a>
                  <a href="javascript:void(0);" onclick="sendEmail({{$ad->id}})" class="menu-item">Send Email</a>
                  @php
                  $status = $ad->calculateStatus($ad->created_at, $ad->Duration); @endphp
                  @if($status)
                  <a href="javascript:void(0);" onclick="sendReminder('{{ $ad->id }}', '{{ $ad->created_at ? $ad->created_at->format('Y-m-d H:i:s') : '' }}', '{{ addslashes($status) }}', '{{ $customer->phone }}', {{ $ad->Duration }})" class="menu-item">Send Reminder</a>
                  @else
                  <span>No Customer Info</span>
                  @endif
                </div>
              </div>
            </td>
          </tr>
          @elseif($ad->Payment == "Overpaid")
          <tr style="background-color: #e5e5dc;">
            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
              @csrf
                  <input type="hidden" name="redirect_to" value="{{ url()->full() }}">

               <td style="padding: 2.5px; position: relative;" onmouseenter="showAdDetails(this, '{{ $ad->created_at }}')" onmouseleave="hideAdDetails(this)">   {{ $ad->created_at ? $ad->created_at->format('M j') : '' }} </td>
              <td style="padding: 2.5px;">
                @php
                $customer = $ad->customer()->first(); // Make sure $customer is defined
                $phoneNumber = $customer->phone ?? $customer->phone_2; // Check if customer exists before using its attributes
                @endphp


                @if($customer)
                <a href="https://wa.me/+977{{ $customer->phone }}?text={{ rawurlencode('
*Your ad campaign has been set up.*
The total cost is *Rs '. number_format($ad->NRP, 0, '.', ',') . '/-*.
eSewa payment: 9856000601
_Kindly make the payment within an hour to ensure smooth processing of your ad campaign._
_*Thank you.*_') }}" target="_blank" style="text-decoration: none; color: inherit;">
                 <strong id="phone-number" style="user-select: all; {{ $customer && $customer->requires_bill ? 'background-color: darkgreen; color: white; padding: 2px 6px; border-radius: 4px;' : '' }}"> {{ $customer->phone }} </strong>
                </a>

                @else
                <span>No Customer Info</span>
                @endif

              </td>

              <td style="padding: 2.5px;">
                <span class="customer-display-name" @if($customer) onmouseover="showPopup(event, '{{ $customer->id }}')" onmouseout="hidePopup()" onclick="goToDetails('{{ route('customer.details', ['id' => $customer->id]) }}')" @endif>

                  <strong>{{ $customer ? $customer->display_name : 'Unknown Customer' }}</strong>
@if($customer && isset($customerNoteCounts[$customer->id]) && $customerNoteCounts[$customer->id] > 0)
                                                <a href="{{ route('customer.details', ['id' => $customer->id]) }}#notes-list">
                                                    <i class="fas fa-sticky-note" data-toggle="tooltip" title="{{ $customerNoteCounts[$customer->id] }} note(s)"></i>
                                                </a>
                                            @endif
                  <span class="ad-status" data-created-at="{{ $ad->created_at }}" data-duration="{{ $ad->Duration }}">
                    <!-- Badge will be updated by JavaScript -->
                  </span>
                </span>

                @if($customer)
                <div class="customer-popup" id="popup-{{ $customer->id }}">
                  <div class="popup-arrow"></div>
                  <div class="profile-popup">
                    <div style="display: flex; align-items: center;">
                      <div>
                        <h4>{{ $customer->name }}</h4>
                        <span class="form-group" style="margin-bottom: 0px;">
                        <button type="button"class="btn btn-info" style="width: 100%; font-weight: bold; cursor: default;"readonly>{{ $ad->Rate }}</button>
                        </span>
                        <span><strong>Name:</strong> {{ $customer->name }}</span>
                        <span><strong>Company:</strong> {{ $customer->display_name }}</span>
                        <span><strong>Email:</strong> {{ $customer->email }}</span>
                        <span><strong>Phone:</strong> {{ $customer->phone }}</span>
                        <span><strong>Address:</strong> {{ $customer->address }}</span>
                      </div>
                      <div style="margin-left: 10px;">
                        @if($customer->profile_picture)
                        <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @else
                        <img src="{{ asset('uploads/customers/default.jpg') }}" alt="Default Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                @endif
<!-- Unified Icon Trigger -->
<a href="javascript:void(0)" data-customer-id="{{ $customer->id }}" class="open-link-form" style="text-decoration: none; color: inherit; position: relative;">
    <i class="fa fa-link blink" aria-hidden="true" style="cursor: pointer;"></i>
</a>

<div id="form-container-{{ $customer->id }}" class="form-dropdown d-none" style="position: absolute; background: #fff; border: 1px solid #ccc; padding: 15px; z-index: 1000; min-width: 300px;">
    <!-- The dynamic form will be injected by JS -->
</div>


              </td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" class="form-control" oninput="calAmt({{$ad->id}})" onclick="calAmt({{$ad->id}})" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                </div>
              </td>
              <!-- Non-editable Rate Field -->
<td style="padding: 2.5px;display:none;">
    <div class="form-group" style="margin-bottom: 0px;">
        <input type="text" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" readonly>
 
    </div>
</td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                    @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                    <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}> {{ $Payment }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                    @foreach(['New', 'Extend', 'Both', 'On schedule', 'Monitoring'] as $status)
                    <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}> {{ $status }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                @if($ad->advance == '')
                <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                  <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                @else
                <div class="form-group" id="{{$ad->id.'bakifield'}}">
                  <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                </div>
                @endif
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                </div>
              </td>
           @php
  $raw = $ad->add_on ?? [];
  if ($raw instanceof \Illuminate\Support\Collection) $raw = $raw->toArray();
  if (is_string($raw)) {
      $s = html_entity_decode($raw, ENT_QUOTES);
      $s = trim($s);
      if ((substr($s,0,1)==='"' && substr($s,-1)==='"') || (substr($s,0,1)==="'" && substr($s,-1)==="'")) $s = substr($s,1,-1);
      $decoded = json_decode($s, true);
      if (!is_array($decoded)) $decoded = json_decode(stripslashes($s), true);
      $raw = is_array($decoded) ? $decoded : [];
  }
  $arr = $raw['data'] ?? $raw;

  // --- name र type छुट्ट्याएर: name मा project_type नहाल्ने! ---
  $pairs = collect($arr)->map(function($x){
      if (is_object($x)) $x = (array)$x;
      $name = is_array($x)
          ? ($x['service_name'] ?? $x['project'] ?? $x['title'] ?? $x['name'] ?? null)
          : (is_string($x) ? $x : null);
      $type = is_array($x) ? ($x['project_type'] ?? $x['type'] ?? null) : null;
      return ($name || $type) ? ['name'=>$name, 'type'=>$type] : null;
  })->filter()->values();

  $hasAddons = $pairs->isNotEmpty();

  // --- Label/type: type मात्र देखाउने, type नभए name देखाउने ---
  $labels = $pairs->map(function($p){
      return $p['type'] ?: $p['name'];
  });

  $shown      = $labels->take(2)->implode(', ');
  $extraCount = max($labels->count() - 2, 0);
  $labelText  = $hasAddons ? ($extraCount > 0 ? ($shown.' +'.$extraCount) : $shown) : 'Add-on';
  $titleText  = $hasAddons ? $labels->implode(', ') : 'Add-on';

  $wa = (isset($customer) && $customer) ? ($customer->phone ?? '') : '';
    // अहिले सम्म सेव भएका project_type हरू (type नभए name) को सूची:
  $existingTypes = $pairs->map(function($p){ return $p['type'] ?: $p['name']; })
                         ->filter()->unique()->values()->all();
  $existingTypesJson = json_encode($existingTypes);

@endphp

<td style="min-width:90px; padding:2.5px;">
  <button type="button"
          class="btn btn-sm {{ $hasAddons ? 'btn-info' : 'btn-warning' }} addon-btn"
          data-customer="{{ $wa }}"
          data-ad-id="{{ $ad->id }}"
  data-exist-types='@json($existingTypes)'
          title="{{ $titleText }}"
          style="border:0; {{ $hasAddons ? '' : '' }}"
          {{ (!$wa) ? 'disabled' : '' }}>
    @if($hasAddons)
      {{ $labelText }}
    @else
      <i class="fa fa-plus-circle"></i> {{ $labelText }}
    @endif
  </button>
</td>

              <td style="padding: 2.5px;">
  <span class="admin-name"
        data-ad-id="{{ $ad->id }}"
        data-toggle="tooltip"
        data-html="true" 
        title="Clicks: 0">
    {{ $ad->admin }}
  </span>
</td>

              <td style="padding: 2.5px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i></button>
              </td>
            </form>
            <td style="padding: 2.5px;">
              <div class="dropdown">
                <button class="dropdown-button" onclick="toggleDropdown_({{$ad->id}})"><i class="fas fa-tasks"></i></button>
                <div id="menu_{{$ad->id}}" class="horizontal-menu">
                  <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="menu-item">Delete Campaign</a>
                  <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="menu-item">View Invoice</a>
                  <a href="{{ route('admin.customer.impersonate', $customer->id) }}" class="menu-item" style="target:blank;">View Portal</a>
                  <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="menu-item">View PDF</a>
                  <a href="javascript:void(0);" onclick="sendEmail({{$ad->id}})" class="menu-item">Send Email</a>
                  @php
                  $status = $ad->calculateStatus($ad->created_at, $ad->Duration); @endphp
                  @if($status)
                  <a href="javascript:void(0);" onclick="sendReminder('{{ $ad->id }}', '{{ $ad->created_at ? $ad->created_at->format('Y-m-d H:i:s') : '' }}', '{{ addslashes($status) }}', '{{ $customer->phone }}', {{ $ad->Duration }})" class="menu-item">Send Reminder</a>
                  @else
                  <span>No Customer Info</span>
                  @endif
                </div>
              </div>
            </td>
          </tr>
          @else
          <tr>
            <form action="{{ url('/admin/dashboard/ads/edit/'. $ad->id) }}" method="POST">
              @csrf
                  <input type="hidden" name="redirect_to" value="{{ url()->full() }}">

               <td style="padding: 2.5px; position: relative;" onmouseenter="showAdDetails(this, '{{ $ad->created_at }}')" onmouseleave="hideAdDetails(this)">   {{ $ad->created_at ? $ad->created_at->format('M j') : '' }} </td>
              <td style="padding: 2.5px;">
                @php
                $customer = $ad->customer()->first(); // Make sure $customer is defined
                $phoneNumber = $customer->phone ?? $customer->phone_2; // Check if customer exists before using its attributes
                @endphp


                @if($customer)
                <a href="https://wa.me/+977{{ $customer->phone }}?text={{ rawurlencode('
*Your ad campaign has been set up.*
The total cost is *Rs '. number_format($ad->NRP, 0, '.', ',') . '/-*.
eSewa payment: 9856000601
_Kindly make the payment within an hour to ensure smooth processing of your ad campaign._
_*Thank you.*_') }}" target="_blank" style="text-decoration: none; color: inherit;">
                 <strong id="phone-number" style="user-select: all; {{ $customer && $customer->requires_bill ? 'background-color: darkgreen; color: white; padding: 2px 6px; border-radius: 4px;' : '' }}"> {{ $customer->phone }} </strong>
                </a>

                @else
                <span>No Customer Info</span>
                @endif

              </td>

              <td style="padding: 2.5px;">
                <span class="customer-display-name" @if($customer) onmouseover="showPopup(event, '{{ $customer->id }}')" onmouseout="hidePopup()" onclick="goToDetails('{{ route('customer.details', ['id' => $customer->id]) }}')" @endif>

                  <strong>{{ $customer ? $customer->display_name : 'Unknown Customer' }}</strong>
@if($customer && isset($customerNoteCounts[$customer->id]) && $customerNoteCounts[$customer->id] > 0)
                                                <a href="{{ route('customer.details', ['id' => $customer->id]) }}#notes-list">
                                                    <i class="fas fa-sticky-note" data-toggle="tooltip" title="{{ $customerNoteCounts[$customer->id] }} note(s)"></i>
                                                </a>
                                            @endif
                  <span class="ad-status" data-created-at="{{ $ad->created_at }}" data-duration="{{ $ad->Duration }}">
                    <!-- Badge will be updated by JavaScript -->
                  </span>
                </span>

                @if($customer)
                <div class="customer-popup" id="popup-{{ $customer->id }}">
                  <div class="popup-arrow"></div>
                  <div class="profile-popup">
                    <div style="display: flex; align-items: center;">
                      <div>
                        <h4>{{ $customer->name }}</h4>
                        <span class="form-group" style="margin-bottom: 0px;">
                        <button type="button"class="btn btn-info" style="width: 100%; font-weight: bold; cursor: default;"readonly>{{ $ad->Rate }}</button>
                        </span>
                        <span><strong>Name:</strong> {{ $customer->name }}</span>
                        <span><strong>Company:</strong> {{ $customer->display_name }}</span>
                        <span><strong>Email:</strong> {{ $customer->email }}</span>
                        <span><strong>Phone:</strong> {{ $customer->phone }}</span>
                        <span><strong>Address:</strong> {{ $customer->address }}</span>
                      </div>
                      <div style="margin-left: 10px;">
                        @if($customer->profile_picture)
                        <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @else
                        <img src="{{ asset('uploads/customers/default.jpg') }}" alt="Default Profile Picture" style="width: 90px; height: 90px; border-radius: 50%;">
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                @endif
<!-- Unified Icon Trigger -->
<a href="javascript:void(0)" data-customer-id="{{ $customer->id }}" class="open-link-form" style="text-decoration: none; color: inherit; position: relative;">
    <i class="fa fa-link blink" aria-hidden="true" style="cursor: pointer;"></i>
</a>

<div id="form-container-{{ $customer->id }}" class="form-dropdown d-none" style="position: absolute; background: #fff; border: 1px solid #ccc; padding: 15px; z-index: 1000; min-width: 300px;">
    <!-- The dynamic form will be injected by JS -->
</div>
              </td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" oninput="calAmt({{$ad->id}})" onchange="calAmt({{$ad->id}})" class="form-control" id="{{$ad->id.'USD'}}" name="USD" value="{{ $ad->USD }}" required>
                </div>
              </td>
              <!-- Non-editable Rate Field -->
<td style="padding: 2.5px;display:none;">
    <div class="form-group" style="margin-bottom: 0px;">
        <input type="text" class="form-control" id="{{$ad->id.'Rate'}}" name="Rate" value="{{ $ad->Rate }}" readonly>
 
    </div>
</td>

              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" step="0.01" class="form-control" value="{{ $ad->NRP }}" id="{{$ad->id.'NRP'}}" name="NRP" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Ad_Account }}" id="Ad_Account" name="Ad_Account" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="{{$ad->id.'baki'}}" name="Payment" required onchange="togglebakiField('{{$ad->id}}baki')">
                    @foreach(['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki', 'Paid', 'Refunded', 'Cancelled', 'Overpaid', 'PV Adjusted', 'Informed'] as $Payment)
                    <option value="{{ $Payment }}" {{ @$ad->Payment == $Payment ? 'selected' : '' }}> {{ $Payment }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" id="Duration" name="Duration" class="form-control" value="{{ $ad->Duration}}" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" value="{{ $ad->Quantity }}" id="Quantity" name="Quantity" required>
                </div>
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                    @foreach(['New', 'Extend', 'Both', 'On schedule', 'Monitoring'] as $status)
                    <option value="{{ $status }}" {{ @$ad->Status == $status ? 'selected' : '' }}> {{ $status }}</option>
                    @endforeach
                  </select>
                </div>
              </td>
              <td style="padding: 2.5px;">
                @if($ad->advance == '')
                <div class="form-group" id="{{$ad->id.'bakifield'}}" style="display: none;">
                  <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                @else
                <div class="form-group" id="{{$ad->id.'bakifield'}}">
                  <input type="text" class="form-control" id="advanceAmount" value="{{$ad->advance}}" name="advance">
                </div>
                @endif
              </td>
              <td style="padding: 2.5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                  <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="{{ $ad->Ad_Nature_Page }}" required>
                </div>
              </td>
            @php
  $raw = $ad->add_on ?? [];
  if ($raw instanceof \Illuminate\Support\Collection) $raw = $raw->toArray();
  if (is_string($raw)) {
      $s = html_entity_decode($raw, ENT_QUOTES);
      $s = trim($s);
      if ((substr($s,0,1)==='"' && substr($s,-1)==='"') || (substr($s,0,1)==="'" && substr($s,-1)==="'")) $s = substr($s,1,-1);
      $decoded = json_decode($s, true);
      if (!is_array($decoded)) $decoded = json_decode(stripslashes($s), true);
      $raw = is_array($decoded) ? $decoded : [];
  }
  $arr = $raw['data'] ?? $raw;

  // --- name र type छुट्ट्याएर: name मा project_type नहाल्ने! ---
  $pairs = collect($arr)->map(function($x){
      if (is_object($x)) $x = (array)$x;
      $name = is_array($x)
          ? ($x['service_name'] ?? $x['project'] ?? $x['title'] ?? $x['name'] ?? null)
          : (is_string($x) ? $x : null);
      $type = is_array($x) ? ($x['project_type'] ?? $x['type'] ?? null) : null;
      return ($name || $type) ? ['name'=>$name, 'type'=>$type] : null;
  })->filter()->values();

  $hasAddons = $pairs->isNotEmpty();

  // --- Label/type: type मात्र देखाउने, type नभए name देखाउने ---
  $labels = $pairs->map(function($p){
      return $p['type'] ?: $p['name'];
  });

  $shown      = $labels->take(2)->implode(', ');
  $extraCount = max($labels->count() - 2, 0);
  $labelText  = $hasAddons ? ($extraCount > 0 ? ($shown.' +'.$extraCount) : $shown) : 'Add-on';
  $titleText  = $hasAddons ? $labels->implode(', ') : 'Add-on';

  $wa = (isset($customer) && $customer) ? ($customer->phone ?? '') : '';
    // अहिले सम्म सेव भएका project_type हरू (type नभए name) को सूची:
  $existingTypes = $pairs->map(function($p){ return $p['type'] ?: $p['name']; })
                         ->filter()->unique()->values()->all();
  $existingTypesJson = json_encode($existingTypes);

@endphp

<td style="min-width:90px; padding:2.5px;">
  <button type="button"
          class="btn btn-sm {{ $hasAddons ? 'btn-info' : 'btn-warning' }} addon-btn"
          data-customer="{{ $wa }}"
          data-ad-id="{{ $ad->id }}"
  data-exist-types='@json($existingTypes)'
          title="{{ $titleText }}"
          style="border:0; {{ $hasAddons ? '' : '' }}"
          {{ (!$wa) ? 'disabled' : '' }}>
    @if($hasAddons)
      {{ $labelText }}
    @else
      <i class="fa fa-plus-circle"></i> {{ $labelText }}
    @endif
  </button>
</td>


              <td style="padding: 2.5px;">
  <span class="admin-name"
        data-ad-id="{{ $ad->id }}"
        data-toggle="tooltip"
        data-html="true" 
        title="Clicks: 0">
    {{ $ad->admin }}
  </span>
</td>

              <td style="padding: 2.5px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i></button>
              </td>
            </form>
            <td style="padding: 2.5px;">
              <div class="dropdown">
                <button class="dropdown-button" onclick="toggleDropdown_({{$ad->id}})"><i class="fas fa-tasks"></i></button>
                <div id="menu_{{$ad->id}}" class="horizontal-menu">
                  <a href="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" class="menu-item">Delete Campaign</a>
                  <a href="{{ URL('/receipt/show/' . $ad->id) }}" class="menu-item">View Invoice</a>
                  <a href="{{ route('admin.customer.impersonate', $customer->id) }}" class="menu-item" style="target:blank;">View Portal</a>
                  <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="menu-item">View PDF</a>
                  <a href="javascript:void(0);" onclick="sendEmail({{$ad->id}})" class="menu-item">Send Email</a>
                  @php
                  $status = $ad->calculateStatus($ad->created_at, $ad->Duration); @endphp
                  @if($status)
                  <a href="javascript:void(0);" onclick="sendReminder('{{ $ad->id }}', '{{ $ad->created_at ? $ad->created_at->format('Y-m-d H:i:s') : '' }}', '{{ addslashes($status) }}', '{{ $customer->phone }}', {{ $ad->Duration }})" class="menu-item">Send Reminder</a>
                  @else
                  <span>No Customer Info</span>
                  @endif
                </div>
              </div>
            </td>
          </tr>
          @endif
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<marquee id="marqueeTag" scrollamount="10" loop="infinite"></marquee>
{{ $ads->withQueryString()->links('pagination::bootstrap-5') }}
</div>
<!-- Global Add-on Modal -->
<div class="modal fade" id="addonModal" tabindex="-1" role="dialog" aria-labelledby="addonModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addonModalLabel">Customer Add-ons</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead>
              <tr>
                <th style="width:40px;"></th>
                <th>Project Type</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody id="addonTableBody"></tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer d-flex justify-content-between">
        <div>Total: Rs. <strong id="addonTotal">0.00</strong></div>
        <button type="button" class="btn btn-primary" id="applyAddon">Apply</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery (full or slim works; we use slim since we use fetch) -->

<!-- ✅ Popper v1 for Bootstrap 4.x -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>

<!-- Bootstrap 4.5.2 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  // (optional) tooltips
  $(function(){ $('[data-toggle="tooltip"]').tooltip(); });
</script>

<script>
(function () {
  'use strict';

  // ---------- shared context ----------
  let ctx = { mode:null, adId:null, whatsapp:null, triggerCell:null, excludeTypes:[] };

  // ---------- tiny helpers ----------
  const n0   = v => (isNaN(parseFloat(v)) ? 0 : parseFloat(v));
  const f2   = v => (Number(v) || 0).toFixed(2);
  const byId = id => document.getElementById(id);
  const norm = s => (String(s||'').trim().toLowerCase());

  const fmtYMD = iso => {
    if (!iso) return '';
    try { return String(iso).slice(0, 10); } catch { return ''; }
  };
  const fmtHuman = iso => {
    if (!iso) return '';
    try {
      const d = new Date(iso);
      return d.toLocaleDateString('en-GB', { year:'numeric', month:'short', day:'2-digit' }); // e.g. 21 Aug 2025
    } catch { return ''; }
  };

  const hasJQ = () => (typeof window.$ === 'function');

  // safely read JSON from data-* attribute (if present)
  function parseJsonAttr(el, attrName){
    const raw = el.getAttribute(attrName);
    if (!raw) return [];
    try { 
      const v = JSON.parse(raw);
      return Array.isArray(v) ? v : [];
    } catch { return []; }
  }

  // ---------- GLOBAL HELPERS (outer scope!) ----------
  function collectTypesFromPayload(arr){
    // arr: [{service_name, project_type, type, name, ...}, ...]
    if (!Array.isArray(arr)) return [];
    return arr
      .map(x => (x.project_type || x.type || x.name || x.service_name || '').toString().trim())
      .filter(Boolean);
  }

  // Gather already-used add-on types across the whole page
  function collectGlobalExcludedTypes(){
    const set = new Set();

    // 1) from each existing row button's data-exist-types
    document.querySelectorAll('button.addon-btn').forEach(btn => {
      try {
        const arr = JSON.parse(btn.getAttribute('data-exist-types') || '[]'); // array of strings
        (arr || []).forEach(t => { if (t) set.add(String(t).toLowerCase()); });
      } catch {}
    });

    // 2) from all hidden inputs that already store selections
    document.querySelectorAll('input[name="addons_selected"]').forEach(hid => {
      if (!hid.value) return;
      try {
        const sel = JSON.parse(hid.value) || []; // [{...}]
        collectTypesFromPayload(sel).forEach(t => set.add(t.toLowerCase()));
      } catch {}
    });

    return Array.from(set);
  }

  // ---------- table renderer ----------
  function renderAddonRows(items, excludeTypes){
    const exSet = new Set((excludeTypes||[]).map(norm));
    const tbody = byId('addonTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    const list = Array.isArray(items) ? items.slice() : [];
    if (!list.length){
      tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">No add-ons</td></tr>`;
      const tot = byId('addonTotal'); if (tot) tot.textContent = '0.00';
      return;
    }

    // newest first
    list.sort((a,b) => new Date(b.date||0) - new Date(a.date||0));

    list.forEach(obj => {
      const name    = obj.service_name || obj.project || obj.title || obj.name || '';
      const type    = obj.project_type || obj.type   || obj.title || obj.name || '';
      const amt     = n0(obj.amount ?? 0);
      const rawDate = obj.date || '';
      const showDate = fmtHuman(rawDate) || '-';
      const ymd      = fmtYMD(rawDate);

      const isLocked = exSet.has(norm(type));

      const tr = document.createElement('tr');
      if (isLocked) tr.classList.add('text-muted');

      tr.innerHTML = `
        <td>
          <input type="checkbox"
                 class="addon-check"
                 value="${amt}"
                 data-name="${String(name).replace(/"/g,'&quot;')}"
                 data-type="${String(type).replace(/"/g,'&quot;')}"
                 data-date="${ymd}"
                 ${isLocked ? 'disabled checked title="Already applied"' : ''}
          />
        </td>
        <td>${type || ''}</td>
        <td>${f2(amt)}</td>
        <td>${showDate}</td>
      `;
      tbody.appendChild(tr);
    });

    // total updater (only enabled + checked)
    const recalc = () => {
      const total = Array.from(tbody.querySelectorAll('input.addon-check:enabled:checked'))
        .reduce((s, x) => s + n0(x.value), 0);
      const tot = byId('addonTotal'); if (tot) tot.textContent = f2(total);
    };
    tbody.querySelectorAll('input.addon-check').forEach(chk => {
      chk.addEventListener('change', recalc);
    });
    const tot = byId('addonTotal'); if (tot) tot.textContent = '0.00';
  }

  // ---------- loader ----------
  async function loadAddonsByWhatsapp(whatsapp, excludeTypes){
    if(!whatsapp){ alert('WhatsApp number required.'); return; }
    const clean = (''+whatsapp).replace(/\s+/g,'').replace(/^\+?977/,'');

    try{
      const res = await fetch(`/admin/get-customer-addons/${encodeURIComponent(clean)}`, {
        headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' },
        credentials: 'same-origin'
      });
      const ctype = res.headers.get('content-type') || '';
      if(!res.ok || !ctype.includes('application/json')){
        throw new Error(`Bad response: ${res.status} ${ctype}`);
      }
      const json  = await res.json();
      const items = Array.isArray(json) ? json : (json?.data || []);
      renderAddonRows(items, excludeTypes || []);

      if (hasJQ()) {
        $('#addonModal').modal('show');
      } else {
        // minimal fallback
        const m = byId('addonModal');
        if (m) m.style.display = 'block';
      }
    }catch(e){
      console.error(e);
      alert('Failed to load add-ons.');
    }
  }

  // ---------- click delegation ----------
  document.addEventListener('click', function(e){
    // NEW row button
    const newBtn = e.target.closest('.addon-btn-new');
    if (newBtn) {
      const waInput = byId('customer');
      const whatsapp = waInput ? waInput.value.trim() : '';

      // exclude types from page + from new form hidden selection (if any)
      let excludeTypes = collectGlobalExcludedTypes();

      const newForm = byId('newRecordForm');
      const hid = newForm ? newForm.querySelector('input[name="addons_selected"]') : null;
      if (hid && hid.value){
        try {
          const arr = JSON.parse(hid.value);
          collectTypesFromPayload(arr).forEach(t => excludeTypes.push(t.toLowerCase()));
        } catch {}
      }
      excludeTypes = [...new Set(excludeTypes)];

      ctx = { mode:'new', adId:'new', whatsapp, triggerCell: newBtn.closest('td'), excludeTypes };
      loadAddonsByWhatsapp(whatsapp, excludeTypes);
      return;
    }

    // EXISTING row button
    const btn = e.target.closest('.addon-btn');
    if (btn) {
      const adId = btn.dataset.adId;
      const whatsapp = (btn.dataset.customer || '').trim();

      let excludeTypes = [];
      try { excludeTypes = parseJsonAttr(btn, 'data-exist-types'); } catch {}

      const tr = btn.closest('tr');
      const formEl = tr ? tr.closest('form') : null;
      const hid = formEl ? formEl.querySelector('input[name="addons_selected"]') : null;
      if (hid && hid.value){
        try {
          const arr = JSON.parse(hid.value);
          const picked = collectTypesFromPayload(arr);
          excludeTypes = [...new Set([...(excludeTypes||[]), ...picked])];
        } catch {}
      }

      ctx = { mode:'existing', adId, whatsapp, triggerCell: btn.closest('td'), excludeTypes };
      if(!whatsapp){ alert('No WhatsApp number for this customer.'); return; }
      loadAddonsByWhatsapp(whatsapp, excludeTypes);
      return;
    }

    // APPLY button (modal footer)
    if (e.target && e.target.id === 'applyAddon') {
      const tbody = byId('addonTableBody');
      if (!tbody) return;

      const checks = Array.from(tbody.querySelectorAll('input.addon-check:enabled:checked'));
      if (!checks.length){ alert('Please select at least one add-on.'); return; }

      const payload = checks.map(chk => ({
        service_name: (chk.dataset.name || '').trim(),
        project_type: (chk.dataset.type || '').trim(),
        amount: parseFloat(chk.value) || 0,
        service_date: (chk.dataset.date || '').trim()
      }));

      const total = payload.reduce((s, x) => s + (x.amount || 0), 0);

      const types = payload.map(p => p.project_type).filter(Boolean);
      const shown = types.slice(0,2).join(', ');
      const extra = Math.max(types.length - 2, 0);
      const labelText = types.length ? (extra ? `${shown} +${extra}` : shown) : 'Selected';
      const titleText = types.join(', ');

      // find relevant form
      let formEl = null;
      if (ctx.mode === 'new') {
        formEl = byId('newRecordForm');
      } else {
        if (ctx.triggerCell) {
          const tr = ctx.triggerCell.closest('tr');
          if (tr) formEl = tr.closest('form');
        }
        if (!formEl) formEl = document.querySelector(`form[action*="/admin/dashboard/ads/edit/${ctx.adId}"]`);
      }

      if (formEl) {
        let hid = formEl.querySelector('input[name="addons_selected"]');
        if (!hid) {
          hid = document.createElement('input');
          hid.type = 'hidden';
          hid.name = 'addons_selected';
          formEl.appendChild(hid);
        }

        let prev = [];
        if (hid.value){
          try { prev = JSON.parse(hid.value) || []; } catch {}
        }
        const merged = [...prev, ...payload];
        hid.value = JSON.stringify(merged);
      }

      // NRP adjust
      if (ctx.mode === 'new'){
        const nrp = byId('NRP');
        if (nrp) nrp.value = ((parseFloat(nrp.value)||0) + total).toFixed(2);
      } else {
        let nrpInput = null;
        if (ctx.triggerCell) {
          const tr = ctx.triggerCell.closest('tr');
          if (tr) nrpInput = tr.querySelector('input[name="NRP"]');
        }
        if (!nrpInput) nrpInput = document.getElementById(`${ctx.adId}NRP`);
        if (!nrpInput) nrpInput = document.querySelector(`form[action*="/admin/dashboard/ads/edit/${ctx.adId}"] input[name="NRP"]`);
        if (nrpInput) nrpInput.value = ((parseFloat(nrpInput.value)||0) + total).toFixed(2);
      }

      // update badge in cell
      if (ctx.triggerCell){
        ctx.triggerCell.innerHTML = `<span class="badge badge-info" title="${titleText}">${labelText}</span>`;
      }

      // close modal
      if (hasJQ()) {
        $('#addonModal').modal('hide');
      } else {
        const m = byId('addonModal');
        if (m) m.style.display = 'none';
      }
      return;
    }
  });
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };

  function computeStatus(createdAtISO, durationDays) {
    const now = new Date();
    const createdAt = new Date(createdAtISO);
    if (Number.isNaN(createdAt.getTime())) return '';

    const endDate = new Date(createdAt);
    endDate.setDate(endDate.getDate() + (parseInt(durationDays, 10) || 0));
    const endTime = endDate.toLocaleTimeString([], timeOptions);

    const diffMs = now - endDate;
    const absMs  = Math.abs(diffMs);
    const dayMs  = 1000 * 60 * 60 * 24;
    const hrMs   = 1000 * 60 * 60;
    const minMs  = 1000 * 60;

    // ended (past)
    if (diffMs > 0) {
      const d = Math.floor(absMs / dayMs);
      if (d > 7) return '';                    // 7 दिनभन्दा पुरानो भए badge नदेखाउने
      if (d > 0) return `${d} day${d>1?'s':''} ago`;
      const h = Math.floor(absMs / hrMs);
      if (h > 0) return `${h} hour${h>1?'s':''} ago`;
      const m = Math.floor(absMs / minMs);
      return `${m} minute${m>1?'s':''} ago`;
    }

    // today / tomorrow / running
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const tomorrow = new Date(today); tomorrow.setDate(today.getDate() + 1);

    if (endDate.toDateString() === today.toDateString()) return `Ending today ${endTime}`;
    if (endDate.toDateString() === tomorrow.toDateString()) return `Ending tomorrow ${endTime}`;
    return 'Running';
  }

  function badgeClass(text) {
    if (!text) return null;
    if (text.includes('Running')) return 'badge-success';
    if (text.includes('Ending today') || text.includes('Ending tomorrow')) return 'badge-warning';
    return 'badge-danger';
  }

  function renderBadge(holder) {
    const createdAt = holder.getAttribute('data-created-at');
    const duration  = holder.getAttribute('data-duration');
    const text = computeStatus(createdAt, duration);

    holder.innerHTML = '';
    if (!text) return;

    const span = document.createElement('span');
    span.className = 'badge ' + (badgeClass(text) || 'badge-secondary');
    span.textContent = text;
    holder.appendChild(span);
  }

  // Initial render for all rows
  document.querySelectorAll('.ad-status').forEach(renderBadge);
});
</script>
<script>
(function(){
  /* =========================
   * A) Inject modern tooltip CSS
   * ========================= */
  (function injectCSS(){
    var css = `
      .tooltip.admin-tip{
        opacity:1 !important;
      }
      .tooltip.admin-tip .tooltip-inner{
        background:#0B1220;            /* slate-950-ish */
        color:#E5E7EB;                  /* text-slate-200 */
        padding:12px 14px;
        border-radius:12px;
        max-width:360px;
        text-align:left;
        box-shadow:0 12px 28px rgba(2,6,23,.45), 0 2px 6px rgba(2,6,23,.15);
        font-size:13px; line-height:1.25rem;
        border:1px solid rgba(148,163,184,.15);
      }
      .tooltip.admin-tip .tooltip-inner .head{
        display:flex; align-items:center; gap:8px;
        font-weight:700; letter-spacing:.2px;
      }
      .tooltip.admin-tip .tooltip-inner .pill{
        font-variant-numeric:tabular-nums;
        background:rgba(148,163,184,.18);
        color:#F8FAFC;
        padding:2px 8px; border-radius:999px; font-weight:700;
      }
      .tooltip.admin-tip .tooltip-inner hr{
        border:0; border-top:1px solid rgba(148,163,184,.25);
        margin:8px 0;
      }
      .tooltip.admin-tip .tooltip-inner .row{
        display:flex; align-items:center; justify-content:space-between;
        gap:12px; padding:2px 0;
      }
      .tooltip.admin-tip .tooltip-inner .name{
        white-space:normal; color:#F1F5F9; font-weight:600
      }
      .tooltip.admin-tip .tooltip-inner .count{
        font-variant-numeric:tabular-nums;
        background:rgba(148,163,184,.15);
        padding:2px 8px; border-radius:8px;
      }
      .tooltip.admin-tip .tooltip-inner .muted{
        color:#94A3B8; font-style:italic;
      }
      .tooltip.admin-tip.bs-tooltip-top .arrow::before,
      .tooltip.admin-tip.bs-tooltip-bottom .arrow::before,
      .tooltip.admin-tip.bs-tooltip-left .arrow::before,
      .tooltip.admin-tip.bs-tooltip-right .arrow::before{
        border-top-color:#0B1220!important;
        border-bottom-color:#0B1220!important;
        border-left-color:#0B1220!important;
        border-right-color:#0B1220!important;
      }
    `;
    var s=document.createElement('style'); s.type='text/css';
    s.appendChild(document.createTextNode(css)); document.head.appendChild(s);
  })();

  /* =========================
   * B) Storage helpers (localStorage)
   *    Structure: { [adId]: { total: n, by: { 'Admin A': x, 'Admin B': y } } }
   * ========================= */
  const STORE_KEY = 'adminClickDetails';
  function loadStore(){
    try { return JSON.parse(localStorage.getItem(STORE_KEY) || '{}'); }
    catch { return {}; }
  }
  function saveStore(obj){
    try { localStorage.setItem(STORE_KEY, JSON.stringify(obj || {})); } catch{}
  }

  /* =========================
   * C) Current admin resolver
   * ========================= */
  function getCurrentAdmin(){
    const m = document.querySelector('meta[name="current-admin"]');
    if (m && m.content) return m.content.trim();
    if (window.CURRENT_ADMIN && String(window.CURRENT_ADMIN).trim()) return String(window.CURRENT_ADMIN).trim();
    const hid = document.querySelector('input[name="admin"][type="hidden"]');
    if (hid && hid.value) return hid.value.trim();
    return 'Unknown';
  }

  /* =========================
   * D) adId resolver (robust)
   * ========================= */
  function getAdIdFromForm(form){
    let el = form.querySelector('[data-ad-id]');
    if (el && el.getAttribute('data-ad-id')) return el.getAttribute('data-ad-id');

    let hid = form.querySelector('input[name="id"]');
    if (hid && hid.value) return hid.value;

    if (form.action){
      const m = form.action.match(/(\d+)(?:\/?[^\/]*)?$/);
      if (m) return m[1];
    }
    return null;
  }

  /* =========================
   * E) Tooltip HTML renderer
   * ========================= */
  function escapeHtml(s){
    return String(s||'')
      .replace(/&/g,'&amp;').replace(/</g,'&lt;')
      .replace(/>/g,'&gt;').replace(/"/g,'&quot;')
      .replace(/'/g,'&#039;');
  }
  function renderTitleFor(adId, store){
    const entry = store[adId] || { total: 0, by: {} };
    const total = entry.total || 0;
    const by = entry.by || {};
    const names = Object.keys(by);
    const rows = names.length
      ? names.sort().map(n => `
          <div class="row">
            <div class="name">• ${escapeHtml(n)}</div>
            <div class="count">${by[n]}</div>
          </div>
        `).join('')
      : '<div class="muted">No clicks yet</div>';

    return `
      <div style="min-width:240px">
        <div class="head">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="opacity:.8">
            <path d="M12 12a5 5 0 1 0-5-5 5.006 5.006 0 0 0 5 5Zm0 2c-4.33 0-8 1.91-8 4.25V21h16v-2.75C20 15.91 16.33 14 12 14Z"/>
          </svg>
          Admin Clicks <span class="pill">${total}</span>
        </div>
        <hr/>
        ${rows}
      </div>
    `;
  }

  /* =========================
   * F) Bootstrap tooltip setup (HTML + custom template)
   * ========================= */
  function setTooltipHTML(el, html){
    el.setAttribute('title', html);
    el.setAttribute('data-original-title', html);
    try { $(el).tooltip('dispose'); } catch(e){}
    try {
      $(el).tooltip({
        html: true,
        container: 'body',
        boundary: 'window',
        template:
          '<div class="tooltip admin-tip" role="tooltip">'+
            '<div class="arrow"></div>'+
            '<div class="tooltip-inner"></div>'+
          '</div>'
      });
    } catch(e){}
  }

  /* =========================
   * G) Increment counters (per ad & per admin)
   * ========================= */
  function bump(adId, adminName){
    const store = loadStore();
    if (!store[adId]) store[adId] = { total: 0, by: {} };
    store[adId].total = (store[adId].total || 0) + 1;
    store[adId].by[adminName] = (store[adId].by[adminName] || 0) + 1;
    saveStore(store);
    return store;
  }

  /* =========================
   * H) Initialize one span
   * ========================= */
  function initAdminSpan(span){
    const adId = span.getAttribute('data-ad-id');
    if (!adId) return;
    span.setAttribute('data-toggle','tooltip');
    const html = renderTitleFor(adId, loadStore());
    setTooltipHTML(span, html);
  }

  /* =========================
   * I) Wire submit events (buttons + forms)
   * ========================= */
  function wireFormSubmits(){
    // Buttons (to catch before navigation)
    document.querySelectorAll('form button[type="submit"], form input[type="submit"]').forEach(btn=>{
      if (btn.__adminClicksBound) return;
      btn.__adminClicksBound = true;
      btn.addEventListener('click', function(){
        const form = this.closest('form');
        if (!form) return;
        handleFormSubmit(form);
      });
    });
    // Fallback: form submit (enter/AJAX)
    document.querySelectorAll('form').forEach(form=>{
      if (form.__adminClicksBound) return;
      form.__adminClicksBound = true;
      form.addEventListener('submit', function(){
        handleFormSubmit(form);
      });
    });
  }
  function handleFormSubmit(form){
    const adId = getAdIdFromForm(form);
    if(!adId) return;
    const adminName = getCurrentAdmin();
    const store = bump(adId, adminName);
    const span = document.querySelector('.admin-name[data-ad-id="'+adId+'"]');
    if (span){
      const html = renderTitleFor(adId, store);
      setTooltipHTML(span, html);
    }
  }

  /* =========================
   * J) Observe DOM changes (if rows/buttons load later)
   * ========================= */
  function observeMutations(){
    const mo = new MutationObserver(muts=>{
      let needWire = false;
      muts.forEach(m=>{
        m.addedNodes && m.addedNodes.forEach(node=>{
          if (!(node instanceof Element)) return;
          if (node.matches && node.matches('.admin-name')) initAdminSpan(node);
          node.querySelectorAll && node.querySelectorAll('.admin-name').forEach(initAdminSpan);
          if (node.matches && (node.matches('form') || node.querySelector('form') || node.querySelector('button[type="submit"]'))) needWire = true;
        });
      });
      if (needWire) wireFormSubmits();
    });
    mo.observe(document.body, {childList:true, subtree:true});
  }

  /* =========================
   * K) Boot
   * ========================= */
  document.addEventListener('DOMContentLoaded', function(){
    // Init all admin-name tooltips
    document.querySelectorAll('.admin-name').forEach(initAdminSpan);

    // Ensure Bootstrap tooltips base init (safe no-op if already done)
    try { $('[data-toggle="tooltip"]').tooltip(); } catch(e){}

    // Wire submit handlers
    wireFormSubmits();

    // Watch future changes
    observeMutations();
  });
})();
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const newRecordButton = document.getElementById('addnew_btn');
    newRecordButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(document.getElementById('newRecordForm'));
        fetch('{{ route('storeAd') }}', { // Replace with your server endpoint
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Success:', data);
            // Handle success here
        })
        .catch((error) => {
            console.error('Error:', error);
            // Handle errors here
        });
    });
});
</script>

<script>
    function addRow() {

        // document.getElementById('ad__table').style.display = "block";
        document.getElementById('addnew_btn').style.display = "none";
        document.getElementById('add_new').style.display = "block";
        document.getElementById('btn_submit').style.display = "block";
    }

    function close_() {
        document.getElementById('addnew_btn').style.display = "block";
        document.getElementById('add_new').style.display = "none";
        document.getElementById('btn_submit').style.display = "none";
    }
    //     var newrow =
    //          '<td style="padding:2.5px;"><input class="form-control" type="number" id="displayname" name="customer"></td>' +
    //         '<td style="padding:2.5px;"><input class="form-control" type="number" id="customer" name="customer"></td>' +
    //         '<td style="padding:2.5px;"><input class="form-control" type="text" id="USD" step="0.01" name="USD"></td>' +
    //         '<td style="padding:2.5px;"><input class="form-control" type="text" id="Rate" step="0.01" name="Rate"></td>' +
    //         '<td style="padding:2.5px;"><input class="form-control" type="text" id="NRP" name="NRP"></td>' +
    //         '<td style="padding:2.5px;"><input class="form-control" type="text" id="Ad_Account" name="Ad_Account"></td>' +
    //         '<td style="padding:2.5px;"><select class="form-control" id="Payment" name="Payment" required onchange="toggleNewbakiField()">' +
    //         '<option value="Pending">Pending</option>' +
    //         '<option value="Paused">Paused</option>' +
    //         '<option value="FPY Received">FPY Received</option>' +
    //         '<option value="eSewa Received">eSewa Received</option>' +
    //         '<option value="Baki">Baki</option>' +
    //         '</select></td>' +
    //         '<td style="padding:2.5px;"><input type="text" id="duration" name="Duration" class="form-control" required></td>' +
    //         '<td style="padding:2.5px;"><input type="text" class="form-control" id="Quantity" name="Quantity" required></td>' +
    //         ' <td style="padding:2.5px;"><select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">' +
    //         '<option value="New">New</option>' +
    //         '<option value="Extend">Extend</option>' +
    //         '<option value="Both">Both</option>' +
    //         '</select></td>' +
    //         '<td style="padding:2.5px;"><input type="text" class="form-control" id="bakifield" value="" name="advance" style="display: none;">' +
    //         '</td>' +
    //         '<td style="padding:2.5px;"><input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" required></td>' +
    //         '<input type="hidden" class="form-control" value="{{ auth("admin")->user()->name }},({{ auth("admin")->user()->id }}) " id="admin" name="admin" required>';

    //     document.getElementById('ad__table').insertAdjacentHTML('afterbegin', newrow);

    // }

    // function smt() {
    //     document.getElementById('submit_table').submit();
    // }
</script>
<script>
    function toggleNewbakiField() {
        var statusSelect = document.getElementById("Payment");
        var advanceField = document.getElementById("bakifield");
        var lists = ["Baki", "Refunded", "Overpaid"];
        if (lists.includes(statusSelect.value)) {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
        }
    }
</script>
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
function calAmt(adId){
  const usdInput  = document.getElementById(`${adId}USD`);
  const rateInput = document.getElementById(`${adId}Rate`);
  const nrpInput  = document.getElementById(`${adId}NRP`);
  if(!usdInput || !rateInput || !nrpInput) return;

  const toNum = v => {
    const s = String(v ?? '').replace(/,/g,'').trim();
    const n = parseFloat(s);
    return isNaN(n) ? 0 : n;
  };

  const autoCalc = () => {
    const usd  = toNum(usdInput.value);
    const rate = toNum(rateInput.value);
    nrpInput.value = (usd * rate).toFixed(2);
  };

  // 👉 call गर्दा नै update गर
  autoCalc();

  // 👉 भविष्यका परिवर्तनहरूमा पनि update होस्
  usdInput.addEventListener('input', autoCalc);
  rateInput.addEventListener('input', autoCalc);
}
</script>

<script>
    function togglebakiField(adId) {
        var statusSelect = document.getElementById(adId);
        var advanceField = document.getElementById(adId + 'field');
        var lists = ["Baki", "Refunded", "Overpaid"];
        if (lists.includes(statusSelect.value)) {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
        }
    }
</script>
<script>
    // Function to toggle the dropdown
function toggleDropdown_(adId) {
    var menu = document.getElementById('menu_' + adId);
    if (menu) {
        var isMenuOpen = menu.style.display === "block";
        // First, close all open menus
        closeAllMenus();
        // Then, if the menu was not already open, open it
        if (!isMenuOpen) {
            menu.style.display = "block";
        }
    } else {
        console.error("Menu with id 'menu_" + adId + "' not found.");
    }
}

// Function to close all menus
function closeAllMenus() {
    var menus = document.querySelectorAll('.horizontal-menu');
    menus.forEach(function(menu) {
        menu.style.display = "none";
    });
}

// Event listener for closing the menu when clicking outside
document.addEventListener('click', function(event) {
    var isClickInsideMenu = event.target.closest('.horizontal-menu') || event.target.closest('.dropdown-button');
    if (!isClickInsideMenu) {
        closeAllMenus();
    }
});


</script>
<script>

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-button')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    }
</script>
<script>
    // Function to toggle the dropdown
    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }

 </script>
 <script>
     
function sendEmail(adId) {
    fetch('/admin/dashboard/send_email_ajax/' + adId, {
        method: 'GET', // or 'POST', depending on how you want to handle the request
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        alert('Email sent successfully'); // Show success message
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending email'); // Show error message
    });
}
</script>
<script>
    function sendEmail(adId) {
    fetch('/admin/dashboard/send_email_ajax/' + adId, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.success); // Display success message
        } else {
            alert(data.error); // Display error message
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending email: ' + error.message);
    });
}

</script>

    <script>
        $(function () {
    $('#date_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD'
        }
    });

    $('#date_range').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#date_range').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
});
    </script>
<script>
document.getElementById('noteButton').addEventListener('click', function(event) {
    var popup = document.getElementById('notePopup');
    
    var buttonRect = event.target.getBoundingClientRect();
    var buttonCenterX = buttonRect.left + buttonRect.width / 2;
    var buttonCenterY = buttonRect.top + buttonRect.height / 2;

    // Calculate the maximum allowed positions for the popup
    var maxX = window.innerWidth - popup.offsetWidth;
    var maxY = window.innerHeight - popup.offsetHeight;

    // Calculate the desired position for the popup
    var desiredX = buttonCenterX - popup.offsetWidth / 2;
    var desiredY = buttonCenterY - popup.offsetHeight / 2;

    // Ensure the popup stays within the screen boundaries
    var x = Math.min(Math.max(desiredX, 0), maxX);
    var y = Math.min(Math.max(desiredY, 0), maxY);

    popup.style.left = x + 'px';
    popup.style.top = y + 'px';
    popup.style.transform = 'translate(-50%, -50%) scale(0)';
    popup.style.display = 'block';

    setTimeout(function() {
        popup.style.opacity = '1';
        popup.style.transform = 'translate(-50%, -50%) scale(1)';
    }, 10);
});

document.getElementById('closeButton').addEventListener('click', function() {
    var popup = document.getElementById('notePopup');
    popup.style.opacity = '0';
    popup.style.transform = 'translate(-50%, -50%) scale(0)';
    setTimeout(function() {
        popup.style.display = 'none';
    }, 300);
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const dropdownButton = document.getElementById("dropdownButton");

    // Function to toggle the dropdown menu
    function toggleFilterDropdown(event) {
        const dropdown = document.getElementById("dropdownFilterMenu");
        dropdown.classList.toggle("show");
        event.stopPropagation();
    }

    // Function to update button text with the selected status
    function updateButtonText(event) {
        const selectedStatus = event.target.getAttribute("data-status");
        dropdownButton.innerHTML = selectedStatus + ' &#9660;';
        localStorage.setItem("selectedStatus", selectedStatus);
        const dropdown = document.getElementById("dropdownFilterMenu");
        dropdown.classList.remove("show");
    }

    // Event listener to toggle the dropdown on button click
    dropdownButton.addEventListener("click", toggleFilterDropdown);

    // Event listener for dropdown menu items
    document.querySelectorAll(".dropdown-content-filter a").forEach(function(element) {
        element.addEventListener("click", updateButtonText);
    });

    // Event listener to close the dropdown when clicking outside
    window.addEventListener("click", function(event) {
        const dropdown = document.getElementById("dropdownFilterMenu");
        if (!event.target.closest('.dropdown')) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    });

    // Retrieve and display the saved status or default to "Status"
    const savedStatus = localStorage.getItem("selectedStatus");
    const currentURL = window.location.pathname;
    if (currentURL.includes('admin/dashboard/ads_list')) {
        dropdownButton.innerHTML = 'Status &#9660;';
    } else if (savedStatus) {
        dropdownButton.innerHTML = savedStatus + ' &#9660;';
    }

    const noteButton = document.getElementById("noteButton");
    const notePopup = document.getElementById("notePopup");
    const closeButton = document.getElementById("closeButton");
    const saveButton = document.getElementById("saveButton");
    const noteInput = document.getElementById("noteInput");
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
    let noteData = null; // Variable to store fetched note data

    // Function to update the note data in the popup
    function updateNotePopup() {
        if (noteData) {
            noteInput.value = noteData.note.note;
            var id__ = 0;
            noteData.datas.forEach(item => {
                const row = document.getElementById(id__);
                if (row) {
                    row.querySelector('input[name^="customer"]').value = item.customer || '';
                    row.querySelector('input[name^="USD"]').value = item.USD || '';
                    row.querySelector('input[name^="Remarks"]').value = item.Remarks || '';
                    row.querySelector('input[name^="xyz"]').value = item.xyz || '';
                }
                id__ = id__ + 1;
            });
        }
    }

    // Function to update marquee content
    function updateMarqueeContent() {
        let marqueeContent = '';
        let hasData = false;

        if (noteData) {
            if (noteData.note.note.trim()) {
                marqueeContent += `Note: ${noteData.note.note} | `;
                hasData = true;
            }

            noteData.datas.forEach(item => {
                let itemContent = '';
                if (item.customer && item.customer.trim()) {
                    itemContent += `${item.customer}, `;
                    hasData = true;
                }
                if (item.USD && item.USD.trim()) {
                    itemContent += `${item.USD}, `;
                    hasData = true;
                }
                if (item.Remarks && item.Remarks.trim()) {
                    itemContent += `${item.Remarks}, `;
                    hasData = true;
                }
                if (item.xyz && item.xyz.trim()) {
                    itemContent += `${item.xyz}, `;
                    hasData = true;
                }
                
                // Trim trailing comma and space
                itemContent = itemContent.replace(/, $/, '');
                if (itemContent) {
                    marqueeContent += `${itemContent} | `;
                }
            });
        }

        // Update marquee tag content if there is data
        const marqueeTag = document.getElementById('marqueeTag');
        if (marqueeTag) {
            marqueeTag.innerHTML = hasData ? marqueeContent : '';
        }
    }

    // Pre-fetch the note data when the page loads
    fetch("/api/getNote")
        .then(response => response.json())
        .then(data => {
            noteData = data; // Store the fetched data
            updateMarqueeContent(); // Update the marquee with the fetched data
        })
        .catch(error => {
            console.error('Error fetching note:', error);
        });

    noteButton.addEventListener("click", function() {
        updateNotePopup(); // Update the popup with the pre-fetched data
        notePopup.style.display = "block"; // Show the note popup instantly
    });

    closeButton.addEventListener("click", function() {
        notePopup.style.display = "none"; // Close the popup
    });

    saveButton.addEventListener("click", function() {
        const noteText = noteInput.value;
        const inputRows = document.querySelectorAll('.items');
        const data = [];

        inputRows.forEach(function(row) {
            const rowData = {
                customer: row.querySelector('input[name^="customer"]').value,
                USD: row.querySelector('input[name^="USD"]').value,
                Remarks: row.querySelector('input[name^="Remarks"]').value,
                xyz: row.querySelector('input[name^="xyz"]').value,
            };

            data.push(rowData);
        });

        fetch("/api/saveNote", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                note: noteText,
                datas: data
            }),
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            noteData = data; // Update the note data
            updateMarqueeContent(); // Update the marquee content
            notePopup.style.display = "none"; // Close the popup after saving
            noteInput.value = ""; // Optionally clear the input
        });
    });

    // Add click event listeners to all Clear buttons
    const clearButtons = document.querySelectorAll('.clear-button');
    clearButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get the parent row of the clicked button
            const row = this.closest('.items');
            
            // Clear input fields in the row
            const inputFields = row.querySelectorAll('input');
            inputFields.forEach(input => {
                input.value = '';
            });
        });
    });
});

</script>
<script>
function goToDetails(url) {
    window.location.href = url;
}

function showPopup(event, id) {
    var popup = document.getElementById('popup-' + id);
    popup.style.display = 'block';
    popup.style.left = event.pageX + 'px';
    popup.style.top = (event.pageY + 20) + 'px'; // Adjust popup position
}

function hidePopup() {
    var popups = document.querySelectorAll('.customer-popup');
    popups.forEach(function(popup) {
        popup.style.display = 'none';
    });
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const calendarButton = document.getElementById('calendarButton');
    const dateInputPopup = document.getElementById('dateInputPopup');
    if (calendarButton && dateInputPopup) {
  calendarButton.addEventListener('click', function(e){ ... });
  document.addEventListener('click', function(e){ ... });
}

    calendarButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default action (form submission)

        const calendarButtonRect = calendarButton.getBoundingClientRect();

        // Position the popup directly below the calendar button
        dateInputPopup.style.top = (calendarButtonRect.bottom + window.scrollY) + 'px';
        dateInputPopup.style.left = (calendarButtonRect.left + window.scrollX) + 'px';

        // Toggle the display of the date input popup
        dateInputPopup.style.display = dateInputPopup.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', function(event) {
        // Check if the click is outside the calendar button and the popup
        if (!dateInputPopup.contains(event.target) && !calendarButton.contains(event.target)) {
            dateInputPopup.style.display = 'none'; // Hide the popup
        }
    });
});

</script>
<script>
    // Toggle the "Status" dropdown
document.getElementById('dropdownButton').addEventListener('click', function() {
    const dropdownMenu = document.getElementById('dropdownFilterMenu');
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

// Toggle the "Option" dropdown
document.getElementById('optionButton').addEventListener('click', function() {
    const optionDropdown = document.getElementById('optionDropdown');
    optionDropdown.style.display = optionDropdown.style.display === 'block' ? 'none' : 'block';
});

// Close the dropdowns if the user clicks outside of them
document.addEventListener('click', function(event) {
    const statusButton = document.getElementById('dropdownButton');
    const optionButton = document.getElementById('optionButton');
    const isClickInsideStatus = statusButton.contains(event.target);
    const isClickInsideOption = optionButton.contains(event.target);

    if (!isClickInsideStatus) {
        document.getElementById('dropdownFilterMenu').style.display = 'none';
    }

    if (!isClickInsideOption) {
        document.getElementById('optionDropdown').style.display = 'none';
    }
});
</script>
<script>
    function sendReminder(adId, createdAt, status, customerPhone, duration) {
    if (!customerPhone || !/^\d+$/.test(customerPhone)) {
        alert("Customer phone number is missing or invalid.");
        return;
    }

    console.log("Ad ID:", adId);
    console.log("Raw Status:", status);
    console.log("Created At:", createdAt);
    console.log("Customer Phone:", customerPhone);
    console.log("Duration:", duration);

    const trimmedStatus = status.trim().toLowerCase();
    console.log("Trimmed Status:", trimmedStatus);

    const calculateEndDate = (createdAt, duration) => {
        return moment(createdAt).add(duration, 'days');
    };

    const endDate = calculateEndDate(createdAt, duration);
    const today = moment().startOf('day'); // Get the start of today
    const tomorrow = moment().add(1, 'days').startOf('day'); // Get the start of tomorrow

    const generateMessage = (trimmedStatus, createdAt) => {
        if (trimmedStatus.includes('running')) {
            return `*Reminder: Your ad is running and will end on ${endDate.format('MMMM Do YYYY, h:mm A')}.*\n\nDear Sir/Ma'am,\nYour ad campaign, created on ${moment(createdAt).format('MMMM Do YYYY, h:mm A')}, is currently running and will end on ${endDate.format('MMMM Do YYYY, h:mm A')}. Let us know if you'd like to extend or create a new campaign.\n\n*Thank you.*\nBest regards,\nMPG Team`;
        } else if (endDate.isSame(today, 'day')) { // Check if endDate is today
            return `*Reminder: Your ad ends today at ${endDate.format('h:mm A')}.*\n\nDear Sir/Ma'am,\nYour ad campaign, created on ${moment(createdAt).format('MMMM Do YYYY, h:mm A')}, ends today at ${endDate.format('h:mm A')}. Let us know if you'd like to extend or create a new campaign.\n\n*Thank you.*\nBest regards,\nMPG Team`;
        } else if (endDate.isSame(tomorrow, 'day')) { // Check if endDate is tomorrow
            return `*Reminder: Your ad ends tomorrow at ${endDate.format('h:mm A')}.*\n\nDear Sir/Ma'am,\nYour ad campaign, created on ${moment(createdAt).format('MMMM Do YYYY, h:mm A')}, ends tomorrow at ${endDate.format('h:mm A')}. Let us know if you'd like to extend or create a new campaign.\n\n*Thank you.*\nBest regards,\nMPG Team`;
        } else if (endDate.isBefore(today)) { // Check if endDate has passed
            return `*Reminder: Your ad ended on ${endDate.format('MMMM Do YYYY, h:mm A')}.*\n\nDear Sir/Ma'am,\nYour ad campaign, created on ${moment(createdAt).format('MMMM Do YYYY, h:mm A')}, ended on ${endDate.format('MMMM Do YYYY, h:mm A')}. Let us know if you'd like to start a new campaign.\n\n🌟 *Share Your Experience* 🌟\nHelp us improve by leaving a review: https://g.page/r/CQsP7NDI6PELEAI/review\n\n*Thank you.*\nBest regards,\nMPG Team`;
        } else {
            return `*Reminder: Status of your ad campaign.*\n\nDear Sir/Ma'am,\nYour ad campaign, created on ${moment(createdAt).format('MMMM Do YYYY, h:mm A')}, is active. Let us know if you'd like to extend or start a new campaign.\n\nThank you.\nBest regards,\nMPG Team`;
        }
    };

    const message = generateMessage(trimmedStatus, createdAt);

    console.log("Generated Message:", message);

    var whatsappUrl = `https://wa.me/+977${customerPhone}?text=${encodeURIComponent(message)}`;

    console.log("Generated WhatsApp URL:", whatsappUrl);
    window.open(whatsappUrl, '_blank');
}
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Function to calculate total daily spend for running ads
    function calculateTotalDailySpend() {
        let totalDailySpend = 0;

        // Loop through rows and calculate daily spend for running ads
        const rows = document.querySelectorAll("tr");
        rows.forEach(row => {
            const statusBadge = row.querySelector(".ad-status .badge");
            if (statusBadge && statusBadge.textContent.includes("Running")) {
                const usdInput = row.querySelector('input[id$="USD"]');
                const durationInput = row.querySelector('input[id$="Duration"]');

                const usd = parseFloat(usdInput.value) || 0;
                const duration = parseInt(durationInput.value) || 1; // Avoid division by 0

                if (duration > 0) {
                    totalDailySpend += usd / duration;
                }
            }
        });

        return totalDailySpend;
    }

    // Update the Daily Spend button dynamically
    const dailySpendButton = document.getElementById("dailySpendButton");
    if (dailySpendButton) {
        const totalDailySpend = calculateTotalDailySpend();
        dailySpendButton.innerHTML = `
            <i class="fa fa-calculator" aria-hidden="true"></i> Daily Spend: $${totalDailySpend.toFixed(2)}
        `;
    }
});

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Function to fetch and update Daily Spend
    async function updateDailySpend() {
        const dailySpendButton = document.getElementById("dailySpendButton");
        try {
            const response = await fetch('/calculate-daily-spend');
            const data = await response.json();
            const totalDailySpend = data.totalDailySpend || 0;
            dailySpendButton.innerHTML = `
                <i class="fa fa-calculator" aria-hidden="true"></i> Daily Spend: $${totalDailySpend.toFixed(2)}
            `;
        } catch (error) {
            console.error('Error fetching Daily Spend:', error);
            dailySpendButton.innerHTML = `
                <i class="fa fa-calculator" aria-hidden="true"></i> Daily Spend: Error
            `;
        }
    }

    // Function to fetch and update Active Ads
    async function updateActiveAds() {
        const activeAdsButton = document.getElementById("activeAdsButton");
        try {
            const response = await fetch('/calculate-active-ads');
            const data = await response.json();
            const totalActiveAds = data.totalActiveAds || 0;
            activeAdsButton.innerHTML = `
                <i class="fa fa-bullhorn" aria-hidden="true"></i> Active: ${totalActiveAds}
            `;
        } catch (error) {
            console.error('Error fetching Active:', error);
            activeAdsButton.innerHTML = `
                <i class="fa fa-bullhorn" aria-hidden="true"></i> Active: Error
            `;
        }
    }

    // Fetch values on page load
    updateDailySpend();
    updateActiveAds();

    // Optional: Set interval to refresh values periodically (e.g., every 60 seconds)
    setInterval(() => {
        updateDailySpend();
        updateActiveAds();
    }, 60000);
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const ARABButton = document.getElementById('ARABButton');

    // Function to fetch and update ARAB value
    async function fetchARAB() {
        try {
            const response = await fetch('/calculate-arab');
            const data = await response.json();

            if (data.totalARAB !== undefined) {
                ARABButton.innerHTML = `<i class="fa fa-dollar-sign" aria-hidden="true"></i> ARAB: $${parseFloat(data.totalARAB).toFixed(2)}`;
            } else {
                ARABButton.innerHTML = `<i class="fa fa-dollar-sign" aria-hidden="true"></i> ARAB: $0.00`;
            }
        } catch (error) {
            console.error('Error fetching ARAB:', error);
            ARABButton.innerHTML = `<i class="fa fa-dollar-sign" aria-hidden="true"></i> ARAB: Error`;
        }
    }

    // Fetch ARAB value on page load
    fetchARAB();

    // Optional: Set interval to update ARAB periodically (e.g., every 60 seconds)
    setInterval(fetchARAB, 60000);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.open-link-form');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            const customerId = this.dataset.customerId;
            const formContainer = document.getElementById(`form-container-${customerId}`);

            document.querySelectorAll('.form-dropdown').forEach(container => {
                if (container !== formContainer) container.classList.add('d-none');
            });

            if (formContainer.classList.contains('d-none')) {
                const rect = e.target.getBoundingClientRect();
                formContainer.style.top = `${rect.top + window.scrollY + rect.height}px`;
                formContainer.style.left = `${rect.left + window.scrollX}px`;

             formContainer.innerHTML = `
    <form action="{{ route('admin.link.store') }}" method="POST" class="ajax-link-form mb-2">
        @csrf
        <div class="d-flex justify-content-between mb-2">
            <button type="button" class="btn btn-success insert-row-btn btn-sm">Insert More</button>
            <button type="submit" class="btn btn-primary btn-sm send-now-btn">Send Now</button>
            <a href="/admin/link-store-room/${customerId}" class="btn btn-secondary btn-sm">Go To Link Room</a>
        </div>
        <div id="link-rows-${customerId}" class="link-rows mb-2">
            <div class="form-group">
                <input type="url" name="campaign_links[]" class="form-control dynamic-input" placeholder="Enter campaign link" required>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-danger btn-sm clear-all-btn">Clear All</button>
        </div>
        <input type="hidden" name="customer_id" value="${customerId}">
    </form>

    <!-- Campaign Insights Fetch Form -->
    <form action="/admin/insights/fetch/${customerId}" method="POST" class="mb-2">
        @csrf
        <input type="hidden" name="customer_id" value="${customerId}">
        <label style="font-weight: 600;">Fetch Insights (Campaign IDs)</label>
        <input type="text" name="campaign_ids" class="form-control mb-2" placeholder="120222...,120333...">
        <button type="submit" class="btn btn-info btn-sm w-100">
            <i class="fas fa-cloud-download-alt"></i> Fetch Insights
        </button>
    </form>
`;

formContainer.classList.remove('d-none');
                initializeDynamicInputs(); // Initialize new inputs
                initializeClearAllButtons(); // Initialize clear all functionality
            } else {
                formContainer.classList.add('d-none');
            }
        });
    });

    document.addEventListener('click', function (e) {
        const isDropdown = e.target.closest('.form-dropdown');
        const isTrigger = e.target.closest('.open-link-form');
        const isSendButton = e.target.closest('.ajax-link-form .btn-primary');

        if (!isDropdown && !isTrigger && !isSendButton) {
            document.querySelectorAll('.form-dropdown').forEach(container => container.classList.add('d-none'));
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('insert-row-btn')) {
            const customerId = e.target.closest('form').querySelector('input[name="customer_id"]').value;
            const linkRows = document.getElementById(`link-rows-${customerId}`);
            const newRow = document.createElement('div');
            newRow.classList.add('form-group', 'mb-2');
            newRow.innerHTML = `
                <input type="url" name="campaign_links[]" class="form-control dynamic-input" placeholder="Enter another campaign link" required>`;
            linkRows.appendChild(newRow);
            initializeDynamicInputs(); // Reinitialize inputs for new rows
        }
    });

    function initializeDynamicInputs() {
        document.querySelectorAll('.dynamic-input').forEach(input => {
            input.addEventListener('focus', function () {
                const clipboardText = navigator.clipboard.readText()
                    .then(text => {
                        const validUrls = extractValidUrls(text);
                        const uniqueUrls = getUniqueUrls(validUrls);
                        populateAndAppendLinks(uniqueUrls, this);
                    })
                    .catch(err => {
                        console.error('Error reading clipboard content:', err);
                    });
            });
        });
    }

    function extractValidUrls(text) {
        const urlRegex = /((https:\/\/|www\.)[^\s]+)/g;
        const matches = text.match(urlRegex) || [];
        return matches.filter(link => link.startsWith('https://') || link.startsWith('www.'));
    }

    function getUniqueUrls(urls) {
        const existingLinks = Array.from(document.querySelectorAll('.dynamic-input')).map(input => input.value.trim());
        return [...new Set(urls.filter(url => !existingLinks.includes(url.trim())))];
    }

    function populateAndAppendLinks(urls, input) {
        const customerId = input.closest('form').querySelector('input[name="customer_id"]').value;
        const linkRows = document.getElementById(`link-rows-${customerId}`);
        let currentInput = input;

        urls.forEach(url => {
            if (currentInput.value.trim() === '') {
                // Populate the first empty input field
                currentInput.value = url;
            } else {
                // Create new rows for remaining links
                const newRow = document.createElement('div');
                newRow.classList.add('form-group', 'mb-2');
                newRow.innerHTML = `<input type="url" name="campaign_links[]" class="form-control dynamic-input" value="${url}" required>`;
                linkRows.appendChild(newRow);
                currentInput = newRow.querySelector('.dynamic-input');
            }
        });

        initializeDynamicInputs(); // Ensure new inputs are also functional
    }

    document.addEventListener('submit', function (e) {
    if (e.target.classList.contains('ajax-link-form')) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const formContainer = form.closest('.form-dropdown'); // Get the form's parent container

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message || 'Links added successfully!'); // Show success message
                    clearForm(form); // Clear form inputs
                    formContainer.classList.add('d-none'); // Hide the form container
                } else {
                    showNotification(data.message || 'Failed to add links. Please try again.', true); // Show error message
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                showNotification('An error occurred. Please try again.', true); // Show error message
            });
    }
});
    function initializeClearAllButtons() {
        document.querySelectorAll('.clear-all-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = button.closest('form');
                clearForm(form); // Clear all input fields
            });
        });
    }

    function clearForm(form) {
        const linkRows = form.querySelector('.link-rows');
        linkRows.innerHTML = `
            <div class="form-group mb-2">
                <input type="url" name="campaign_links[]" class="form-control dynamic-input" placeholder="Enter campaign link" required>
            </div>`;
        initializeDynamicInputs(); // Reinitialize after clearing rows
    }

    function showNotification(message, isError = false) {
        const notification = document.createElement('div');
        notification.innerText = message;
        notification.style.position = 'fixed';
        notification.style.bottom = '40px';
        notification.style.right = '20px';
        notification.style.backgroundColor = isError ? '#dc3545' : '#28a745';
        notification.style.color = '#fff';
        notification.style.padding = '10px 20px';
        notification.style.borderRadius = '5px';
        notification.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        notification.style.zIndex = '10000';
        notification.style.fontSize = '14px';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 8000);
    }

    // Initialize inputs and clear all buttons on page load
    initializeDynamicInputs();
    initializeClearAllButtons();
});
</script>
<script>
function fetchCustomerRate() {
    const customerInput = document.getElementById('customer');
    const rateInput = document.getElementById('Rate');
    const errorMessage = document.getElementById('customer-error-message'); // Error message element

    // Clear any previous error messages
    if (errorMessage) {
        errorMessage.textContent = '';
    }

    if (customerInput.value.trim() === '') {
        if (errorMessage) {
            errorMessage.textContent = 'Please enter a valid customer phone number.';
        }
        return;
    }

    const fetchUrl = `/admin/customer/getRate?phone=${encodeURIComponent(customerInput.value.trim())}`;

    fetch(fetchUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                rateInput.value = data.rate; // Set the rate in the input field
            } else {
                rateInput.value = ''; // Clear the input field if no rate is found
                if (errorMessage) {
                    errorMessage.textContent = data.message || 'Rate not found for the entered customer.';
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            if (errorMessage) {
                errorMessage.textContent = 'An error occurred while fetching the rate.';
            }
        });
}
</script>
<script>
    // Function to make the input field editable on double-click
    function makeEditable(element) {
        element.removeAttribute('readonly'); // Remove the readonly attribute
        element.style.border = '2px solid #007bff'; // Optional: Highlight the input to indicate it's editable

        // Automatically focus the input field
        element.focus();

        // Revert to readonly mode when the user clicks outside or presses Enter
        element.addEventListener('blur', function() {
            element.setAttribute('readonly', true);
            element.style.border = ''; // Reset border style
        });

        element.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') { // If Enter key is pressed
                element.setAttribute('readonly', true);
                element.style.border = ''; // Reset border style
            }
        });
    }
</script>
<script>
    // Combine the values of the two inputs into the Ad_Account field
    function updateAdAccountField() {
        const manualInput = document.getElementById('manualInput').value.trim();
        const datalistInput = document.getElementById('datalistInput').value.trim();

        // Combine the values with "/" as a separator
        const combinedValue = `${manualInput}/${datalistInput}`;

        // Populate the Ad_Account field
        document.getElementById('Ad_Account').value = combinedValue;
    }

    // Attach event listeners to update the Ad_Account field on input change
    document.getElementById('manualInput').addEventListener('input', updateAdAccountField);
    document.getElementById('datalistInput').addEventListener('input', updateAdAccountField);
</script>
<script>
  function showAdDetails(element, createdAt) {
    const date = new Date(createdAt);
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', weekday: 'long' };
    const formattedDate = date.toLocaleDateString('en-US', options);

    const popup = document.createElement('div');
    popup.className = 'ad-details-popup';
    popup.innerHTML = `<strong>Created At:</strong> ${formattedDate}`;

    popup.style.position = 'absolute';
    popup.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
    popup.style.color = '#fff';
    popup.style.border = '2px solid #5a67d8';
    popup.style.padding = '12px 16px';
    popup.style.borderRadius = '8px';
    popup.style.boxShadow = '0 8px 16px rgba(0,0,0,0.25)';
    popup.style.fontFamily = 'Arial, sans-serif';
    popup.style.fontSize = '14px';
    popup.style.transition = 'opacity 0.3s ease-in-out';
    popup.style.zIndex = '1000';
    popup.style.width = '300px';

    element.appendChild(popup);
  }

  function hideAdDetails(element) {
    const popup = element.querySelector('.ad-details-popup');
    if (popup) {
      popup.style.opacity = '0';
      setTimeout(() => element.removeChild(popup), 300);
    }
  }
</script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: [new Date(), new Date()],
            onClose: function(selectedDates, dateStr, instance) {
                // Ensure the input is updated with the selected range in 'YYYY-MM-DD - YYYY-MM-DD' format
                if (selectedDates.length === 2) {
                    instance.element.value = selectedDates[0].toISOString().split('T')[0] + ' - ' + selectedDates[1].toISOString().split('T')[0];
                }
            }
        });
    });
    </script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection