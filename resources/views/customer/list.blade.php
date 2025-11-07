@extends('admin.layout.layout')
@section('title', 'Customer Management | MPG Solution')

@section('content')
<!-- Bootstrap CSS for styling and responsiveness -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    /* Custom Styles for a Compact Layout */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #e9ecef;
    }
    .card {
        border: none;
        border-radius: 10px;
        background-color: #ffffff;
        padding: 10px;
        margin-bottom: 10px;
    }
    .card-header2 {
        background-color: #093b7b;
        color: white;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px 10px 0 0;
    }
    .card-body2 {
        padding: 10px;
    }
    .btn {
        border-radius: 20px;
        font-weight: bold;
        padding: 5px 10px;
    }
    .btn-outline-secondary {
        border-radius: 20px;
    }
    .table-responsive {
        margin-top: 10px;
    }
    .table thead th {
        background-color: #093b7b;
        color: white;
        border: none;
        font-weight: bold;
        font-size: 16px;
        padding: 10px;
    }
    .table thead th, .table tbody td {
        padding: 10px;
        vertical-align: middle;
        font-size: 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .table tbody tr:nth-child(odd) {
        background-color: #d1ecf1;
    }
    .table tbody td {
        color: #495057;
    }
    .table tbody tr:hover {
        background-color: #cfe2f3;
    }
    .form-control {
        border-radius: 20px;
        background-color: #f1f1f1;
        padding: 5px 10px;
    }
    .input-group-text {
        border-radius: 20px;
        background-color: #093b7b;
        color: #ffffff;
        padding: 5px 10px;
    }
    .form-custom-style .form-group {
        margin-bottom: 10px;
    }
    .form-custom-style .form-label {
        color: #093b7b;
    }
    .form-custom-style .form-control {
        background-color: #ffffff;
        color: #495057;
    }
    .btn-primary {
        color: #fff;
        background-color: #093b7b;
        border-color: #093b7b;
    }
    .btn-primary:hover {
        background-color: #062c56;
        border-color: #062c56;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .view-profile-btn {
        display: inline-flex;
        align-items: center;
        padding: 5px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 3px;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .view-profile-btn:hover {
        background-color: #0056b3;
    }
    .view-profile-btn i {
        margin-right: 0;
    }
    .total-count-display {
        font-size: 16px;
        font-weight: bold;
        color: #ffffff;
        padding-right: 10px;
    }
    .profile-picture {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    /* Responsive Styles for Table Header and Search Bar */
    @media screen and (max-width: 768px) {
        .card-header2 h3 {
            font-size: 18px;
        }
        .btn {
            width: 100%;
            margin-top: 5px;
        }
        .table thead {
            display: none;
            width: 100%;
            overflow-x: auto;
        }
        .table thead th {
            display: inline-block;
            width: auto;
            font-size: 14px;
            text-align: left;
            white-space: nowrap;
            padding: 8px 5px;
        }
        .input-group {
            flex-direction: column;
            align-items: stretch;
        }
        .input-group-prepend, .input-group-append {
            width: 100%;
            display: flex;
        }
        .input-group-prepend select, .input-group-append button, .form-control {
            width: 100%;
            margin-bottom: 5px;
        }
        .table tbody tr {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        .table tbody td {
            display: block;
            text-align: left;
            font-size: 12px;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 8px 5px;
        }
        .profile-picture {
            width: 40px;
            height: 40px;
        }
        .table tbody td:nth-child(2) {
            font-size: 14px;
            font-weight: bold;
        }
        .table tbody td:last-child {
            display: flex;
            flex-direction: column;
        }
        .table tbody td:last-child a,
        .table tbody td:last-child form {
            margin-bottom: 5px;
            width: 100%;
        }
        .card-header2 .d-flex {
            flex-direction: column;
        }
        .total-count-display {
            font-size: 14px;
            margin-bottom: 10px;
        }
        #exportButton {
            width: 100%;
        }
    }
    .search-bar-container {
        display: flex;
        flex-wrap: nowrap;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .search-bar-container .input-group {
        display: flex;
        flex-wrap: nowrap;
        width: 100%;
    }
    .search-bar-container .input-group-prepend select,
    .search-bar-container .form-control,
    .search-bar-container .input-group-append button {
        border-radius: 20px;
        padding: 10px;
        font-size: 14px;
        margin-right: 5px;
    }
    .search-bar-container .form-control {
        flex: 2;
        min-width: 150px;
    }
    .search-bar-container .input-group-append button {
        flex-shrink: 0;
        background-color: #093b7b;
        color: #fff;
        border: none;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .search-bar-container .input-group-append button:hover {
        background-color: #062c56;
    }
    @media screen and (max-width: 576px) {
        .search-bar-container {
            flex-direction: column;
            align-items: stretch;
        }
        .search-bar-container .form-control,
        .search-bar-container .input-group-append button {
            margin-right: 0;
            margin-bottom: 5px;
            width: 100%;
        }
        .search-bar-container .form-control {
            min-width: unset;
        }
    }
    .clickonname {
        text-decoration: none;
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        color: #2c3e50;
        font-size: 16px;
        display: inline-block;
    }
    .clickonname:hover {
        color: #2980b9;
        text-decoration: none;
    }
    .clickonname:visited {
        color: #8e44ad;
        text-decoration: none;
    }
    .table thead th, .table tbody td {
        padding: 5px;
        vertical-align: middle;
        font-size: 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .daterangepicker-input {
        border-radius: 20px;
        padding: 5px 10px;
        width: 100%;
    }
</style>

<div class="container-fluid">
    <div class="card my-2">
        <div class="card-header2 d-flex justify-content-between align-items-center">
            <h3>Customers Dashboard</h3>
            <div class="d-flex align-items-center">
                <span id="totalCount" class="total-count-display">Customers Count: {{ $totalCustomers }}</span>
                <button id="exportButton" class="btn btn-success ml-2"><i class="fas fa-file-export"></i> Export</button>
            </div>
        </div>
        <div class="card-body2">
            <form action="{{ route('search_customer') }}" method="get" class="mb-2 form-inline">
                @csrf
                <div class="search-bar-container">
                    <div class="input-group w-100">
                        <input type="text" name="search" placeholder="Search by Name, Display Name, Phone, or Secondary Phone" class="form-control">
                        <input type="text" name="date_range" id="dateRangePicker" class="form-control daterangepicker-input" placeholder="Select Date Range">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                @php
                    use Carbon\Carbon;
                @endphp
                <table class="table">
                    <thead>
                      <tr>
                          <th></th>
                          <th>Name</th>
                          <th>Display Name</th>
                          <th>Added By</th>
                          <th>USD Rate</th>
                          <th>Email</th>
                          <th>Address</th>
                          <th>Phone</th>
                          <th>Secondary Phone</th>
                           <th>Created On</th>
                          <th></th>
                          <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        <!-- Add New Customer Form Row -->
                        <tr>
                            <form method="post" action="{{ url('/admin/dashboard/customer/add') }}">
                                @csrf
                                <td></td>
                                <td><input type="text" class="form-control" name="name" placeholder="Name" required></td>
                                <td><input type="text" class="form-control" name="display_name" placeholder="Display Name"></td>
                                    <td>
      @php
        // current admin name/id सुरक्षित fallback
        $defaultAdminId = optional($currentAdmin)->id;
        $defaultAdminName = optional($currentAdmin)->name ?? 'Unknown';
      @endphp

      <!-- देखिने भाग: dropdown बाट change गर्न मिल्ने -->
      <select name="created_by" class="form-control">
        @foreach($admins as $admin)
          <option value="{{ $admin->id }}"
            {{ (isset($defaultAdminId) && $defaultAdminId == $admin->id) ? 'selected' : '' }}>
            {{ $admin->name }}
          </option>
        @endforeach
      </select>

      <!-- चाहेमा: केवल देखाउन मात्र text (optional) -->
      <!-- <small class="text-muted d-block mt-1">Default: {{ $defaultAdminName }}</small> -->
    </td>

                                <td><input type="number" class="form-control" name="usd_rate" placeholder="USD Rate" value="170" step="0.01" required></td>
                                <td><input type="email" class="form-control" name="email" placeholder="Email" required></td>
                                <td><input type="text" class="form-control" name="address" placeholder="Address" required></td>
                                <td><input type="text" class="form-control" name="phone" placeholder="Phone" required></td>
                                <td><input type="text" class="form-control" name="phone_2" placeholder="Secondary Phone"></td>
                                
                                <td class="sumAmountColumn"></td>
                                <td></td>

                                <td><button type="submit" class="btn btn-primary btn-block">Add</button></td>
                            </form>
                        </tr>
                        <!-- Existing Customers -->
                        @foreach ($customers as $customer)
                        <tr>
                            <td><a href="{{ url('/admin/dashboard/customer/details/' . $customer->id) }}">
                                @if($customer->profile_picture)
                                    <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="{{ $customer->name }}" class="profile-picture">
                                @else
                                    <i class="fas fa-user-circle" style="font-size: 50px; color: rgba(0, 0, 0, 0.7);"></i>
                                @endif
                            </a></td>
                            <td>
                                <a href="{{ url('/admin/dashboard/customer/details/' . $customer->id) }}" class="clickonname">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.customer.impersonate', $customer->id) }}" style="text-decoration: none;" target="_blank">
                                    {{ $customer->display_name }}
                                </a>
                            </td>
                            <td>
  @if($customer->createdByAdmin)
      <span class="badge badge-info" title="Admin ID: {{ $customer->created_by }}">
          {{ $customer->createdByAdmin->name }}
      </span>
  @else
      <span class="text-muted">—</span>
  @endif
</td>


                            <td>{{ $customer->usd_rate ?? 170 }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->address }}</td>
                            <td>
                                <a href="https://wa.me/+977{{ $customer->phone }}?text={{ rawurlencode("
                                *Welcome to MPG Solution!*
                                We are delighted to inform you that you have been successfully integrated into our system.\n
                                As a valued customer of MPG Solution, you now have access to a range of services including digital marketing, advertisement management, and dedicated customer support. Our team is here to ensure that you receive the best possible service and support for your business needs.\n\n
                                For more information about our services and policies, please review the following:
                                - Terms and Conditions: https://mpg.com.np/terms-services/
                                - Privacy Policy: https://mpg.com.np/privacy-policy/\n
                                _Our service hours are 9 AM to 5 PM, Sunday to Friday._
                                Thank you for giving us the opportunity to serve you. We are excited to help your business grow with MPG Solution.
                                ") }}" target="_blank" style="text-decoration: none; color: inherit;">
                                    <strong id="phone-number" style="user-select: all;">{{ $customer->phone }}</strong>
                                </a>
                            </td>
                            <td>{{ $customer->phone_2 }}</td>
                            
                                                            <td>
        @if($customer->created_at)
            {{ \Carbon\Carbon::parse($customer->created_at)->format('Y-m-d') }}
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
                                <td></td>

                            <td>
                                <a href="{{ url('/admin/dashboard/customer/edit/' . $customer->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ url('/admin/dashboard/customer/delete/' . $customer->id) }}" method="get" style="display:inline;">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links('pagination::bootstrap-5', ['paginator' => $customers->appends(request()->query()), 'perPage' => 10]) }}
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportButton = document.getElementById('exportButton');
    if (exportButton) {
        exportButton.addEventListener('click', function() {
            window.location.href = '/export-customers';
        });
    }

    // Initialize Date Range Picker
    $('#dateRangePicker').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment().endOf('month')],
            'All Time': [moment().subtract(10, 'years'), moment()]
        }
    }, function(start, end, label) {
        $('#dateRangePicker').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    });

    // Clear date range picker input when reset
    $('#dateRangePicker').on('cancel.daterangepicker', function() {
        $(this).val('');
    });
});
</script>
@endsection