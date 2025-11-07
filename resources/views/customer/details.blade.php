@extends('admin.layout.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #6366f1;
        --primary-50: #eef2ff;
        --secondary: #10b981;
        --accent: #f59e0b;
        --text: #374151;
        --text-light: #6b7280;
        --light-bg: #f9fafb;
        --card-bg: #ffffff;
        --border: #e5e7eb;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --radius: 12px;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--light-bg);
        color: var(--text);
        line-height: 1.6;
    }

    .customer-details {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        background-color: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 24px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: var(--shadow-hover);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .profile-card {
        position: relative;
        overflow: hidden;
        padding-top: 32px;
    }

    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .profile-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid var(--primary-light);
        margin-bottom: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-details {
        width: 100%;
    }

    .profile-name {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .profile-info {
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--text-light);
    }

    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
        margin-top: 20px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-primary {
        background-color: var(--primary);
        color: white;
    }

    .badge-success {
        background-color: var(--success);
        color: white;
    }

    .badge-warning {
        background-color: var(--warning);
        color: white;
    }

    .badge-danger {
        background-color: var(--danger);
        color: white;
    }

    .badge-gray {
        background-color: var(--text-light);
        color: white;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        font-size: 0.875rem;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
    }

    .btn-accent {
        background-color: var(--accent);
        color: white;
    }

    .btn-accent:hover {
        background-color: #eab308;
        transform: translateY(-2px);
    }

    .btn-success {
        background-color: var(--success);
        color: white;
    }

    .btn-success:hover {
        background-color: #0da271;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.75rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }

    .stat-box {
        background-color: var(--light-bg);
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-light);
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .summary-card {
        background-color: var(--light-bg);
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .summary-label {
        font-size: 0.875rem;
        color: var(--text-light);
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .table th {
        background-color: var(--light-bg);
        font-weight: 600;
        color: var(--text);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background-color: var(--card-bg);
        border-radius: var(--radius);
        padding: 24px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .accordion-item {
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .accordion-header {
        padding: 16px;
        background-color: var(--light-bg);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .accordion-content {
        padding: 0 16px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .accordion-content.active {
        padding: 16px;
        max-height: 300px;
    }

    .toggle-container {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--light-bg);
        padding: 12px 16px;
        border-radius: 8px;
    }

    .toggle-label {
        font-weight: 500;
        color: var(--text);
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: var(--success);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .package-item {
        display: flex;
        flex-direction: column;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }

    .package-item:hover {
        background-color: var(--light-bg);
    }

    .package-info {
        flex: 1;
    }

    .package-name {
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 4px;
    }

    .package-details {
        font-size: 0.875rem;
        color: var(--text-light);
    }

    .package-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .notes-filter {
        margin-bottom: 16px;
    }

    .notes-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .note-item {
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }

    .note-item:last-child {
        border-bottom: none;
    }

    .note-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .note-body {
        color: var(--text);
        margin-bottom: 4px;
    }

    .note-date {
        font-size: 0.75rem;
        color: var(--text-light);
    }

    @media (min-width: 768px) {
        .profile-content {
            flex-direction: row;
            align-items: flex-start;
            text-align: left;
        }
        
        .profile-image {
            margin-right: 24px;
            margin-bottom: 0;
        }
        
        .profile-details {
            flex: 1;
        }
        
        .profile-name,
        .profile-info {
            justify-content: flex-start;
        }
        
        .profile-actions {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        
        .package-item {
            flex-direction: row;
            align-items: center;
        }
        
        .package-actions {
            margin-top: 0;
        }
    }

    @media (max-width: 768px) {
        .customer-details {
            padding: 12px;
        }
        
        .card {
            padding: 16px;
        }
        
        .card-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
        
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="customer-details">
    <!-- Profile Card -->
    <div class="card profile-card animate__animated animate__fadeIn">
        <div class="profile-content">
            <div class="profile-image">
                @if($customer->profile_picture)
                    <img src="{{ asset('uploads/customers/' . $customer->profile_picture) }}" alt="Profile Picture">
                @else
                    <img src="{{ asset('uploads/customers/default.jpg') }}" alt="Default Profile Picture">
                @endif
            </div>
            <div class="profile-details">
                <div class="profile-name">
                    {{ $customer->name }}
                    <span class="badge badge-danger">VIP</span>
                    
                    @if($customer->requires_bill)
                        <span class="badge badge-primary requires-bill-badge">Requires Bill</span>
                    @else
                        <span class="badge badge-gray requires-bill-badge">No Bill</span>
                    @endif
                </div>
                
                @if(optional($customer->createdByAdmin)->name)
                    <div class="profile-info">
                        <span class="badge badge-danger text-sm">
                            Created by: <span class=" font-bold">{{ $customer->createdByAdmin->name }}</span>
                        </span>
                    </div>
                @else
                    <div class="profile-info">
                        <span class="badge badge-danger text-sm text-gray-500 italic">Created by: Unknown</span>
                    </div>
                @endif
                @if($customer->created_at)
    <p class="badge badge-danger mt-0.5 text-xs text-white/70">
        Created on: {{ $customer->created_at->format('F j, Y g:i A') }}
    </p>
@endif

                <div class="profile-info">
                    <i class="fas fa-building"></i> 
                    <a href="{{ route('admin.customer.impersonate', $customer->id) }}" target="_blank" class="hover:text-blue-500 transition">
                        {{ $customer->display_name }}
                    </a>
                </div>
                <div class="profile-info">
                    <i class="fas fa-envelope"></i> {{ $customer->email }}
                </div>
                <div class="profile-info">
                    <i class="fas fa-phone"></i> {{ $customer->phone }}
                </div>
                <div class="profile-info">
                    <i class="fas fa-map-marker-alt"></i> {{ $customer->address }}
                </div>
                
                <div class="profile-actions">
                    <a href="{{ route('admin.packages.index') }}?customer_id={{ $customer->id }}" class="btn btn-primary">
                        <i class="fas fa-boxes"></i> Assign Packages
                    </a>
                    
                    <div class="toggle-container">
                        <span class="toggle-label">This customer requires bill?</span>
                        <label class="toggle-switch">
                            <input id="requiresBillToggle" type="checkbox" {{ $customer->requires_bill ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span id="requiresBillLabel" class="text-sm">{{ $customer->requires_bill ? 'Yes' : 'No' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Packages -->
    <div id="assignedPackages" class="card animate__animated animate__fadeIn" style="{{ $customer->packages && $customer->packages->count() ? '' : 'display:none;' }}">
        <div class="card-header">
            <h3 class="card-title">Assigned Packages</h3>
        </div>
        <div id="assignedList" class="space-y-3">
            @foreach(($customer->packages ?? collect()) as $p)
                <div class="package-item">
                    <div class="package-info">
                        <div class="package-name">{{ $p->name }} {{ $p->code ? '(' . $p->code . ')' : '' }}</div>
                        <div class="package-details">
                            Status: <span class="font-medium">{{ $p->pivot->status ?? '-' }}</span> •
                            Start: {{ $p->pivot->start_date ?? '-' }} •
                            End: {{ $p->pivot->end_date ?? '-' }}
                        </div>
                    </div>
                    <div class="package-actions">
                        <button class="btn btn-primary btn-sm" data-act="update" data-id="{{ $p->id }}">Update</button>
                        <button class="btn btn-danger btn-sm" data-act="remove" data-id="{{ $p->id }}">Remove</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Historical Order Card -->
        <div class="card animate__animated animate__fadeInLeft lg:col-span-1">
            <div class="card-header">
                <h3 class="card-title">Historical Orders</h3>
            </div>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-label">Total Ordered (USD)</div>
                    <div class="stat-value">${{ number_format($totalUSDAllTime, 2) }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">USD Rate</div>
                    <div class="stat-value">{{ $customer->usd_rate ?? 170 }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Total Ordered (NPR)</div>
                    <div class="stat-value">Rs.{{ number_format($totalUSDAllTime * ($customer->usd_rate ?? 170), 2) }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Total Quantity</div>
                    <div class="stat-value">{{ $totalQuantityAllTime }}</div>
                </div>
            </div>
            <form action="{{ route('insights.fetchFromApi', $customer->id) }}" method="POST" class="form-group mt-4">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <label for="campaign_ids" class="block text-sm font-medium mb-2">Campaign IDs</label>
                <input type="text" name="campaign_ids" class="form-control" required placeholder="12022222...,12023333...">
                <button type="submit" class="btn btn-primary mt-3 w-full"><i class="fas fa-cloud-download-alt"></i> Fetch Insights</button>
            </form>
            <form action="{{ route('insights.fetchFromApi', $customer->id) }}" method="POST" class="form-group mt-4">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <button type="submit" class="btn btn-accent w-full"><i class="fas fa-chart-bar"></i> Recent Campaign Results</button>
            </form>
        </div>

        <!-- Notes Card -->
        <div class="card animate__animated animate__fadeInRight lg:col-span-2">
            <div class="card-header">
                <h3 class="card-title">Requirements & Suggestions</h3>
                <button class="btn btn-primary" id="add-note-btn"><i class="fas fa-plus"></i> Add Note</button>
            </div>
            <div class="notes-filter">
                <input id="filter-notes" class="form-control" placeholder="Search notes..." />
            </div>
            <div id="notes-list" class="notes-list"></div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid animate__animated animate__fadeInUp">
        <div class="summary-card">
            <div class="summary-label">My Order</div>
            <div class="summary-value">Rs.{{ number_format($myOrderAmount, 2) }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Quantity</div>
            <div class="summary-value">{{ $quantity }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Unpaid Invoice</div>
            <div class="summary-value">Rs.{{ number_format($dueAmount, 2) }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Paid Invoice</div>
            <div class="summary-value">Rs.{{ number_format($paidInvoice, 2) }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Due Amount</div>
            <div class="summary-value">Rs.{{ number_format($dueAmount, 2) }}</div>
        </div>
    </div>

    <!-- Monthly & Daily Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card animate__animated animate__fadeInLeft">
            <div class="card-header">
                <h3 class="card-title">Monthly Summary</h3>
            </div>
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Total USD</div>
                    <div class="summary-value">${{ number_format($totalUSDThisMonth, 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total NPR</div>
                    <div class="summary-value">Rs.{{ number_format($totalNPRThisMonth, 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total Quantity</div>
                    <div class="summary-value">{{ $totalQuantityThisMonth }}</div>
                </div>
            </div>
        </div>
        <div class="card animate__animated animate__fadeInRight">
            <div class="card-header">
                <h3 class="card-title">Daily Summary</h3>
            </div>
            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Total USD</div>
                    <div class="summary-value">${{ number_format($totalUSDThisToday, 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total NPR</div>
                    <div class="summary-value">Rs.{{ number_format($totalNPRThisToday, 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total Quantity</div>
                    <div class="summary-value">{{ $totalQuantityThisToday }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Month-wise Table -->
    <div class="card animate__animated animate__fadeIn">
        <div class="card-header">
            <h3 class="card-title">Month-wise Data</h3>
            <div class="flex gap-2">
                <button class="btn btn-primary" onclick="fetchMonthData({{ $startMonthOffset + 5 }})">Newer</button>
                @if($startMonthOffset > 0)
                    <button class="btn btn-primary" onclick="fetchMonthData({{ $startMonthOffset - 5 }})">Older</button>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table" id="monthWiseTable">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>USD Amount</th>
                        <th>NPR Amount</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    @foreach($previousMonthsData as $month => $data)
                        <tr>
                            <td>{{ $month }}</td>
                            <td>${{ number_format($data['usd'], 2) }}</td>
                            <td>Rs.{{ number_format($data['npr'], 2) }}</td>
                            <td>{{ $data['quantity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="form-group mt-6">
            <label for="financialYearRange" class="block text-sm font-medium mb-2">Select Financial Year Range</label>
            <input type="text" id="financialYearRange" class="form-control">
        </div>
        <div class="overflow-x-auto">
            <table class="table hidden" id="financialYearTable">
                <thead>
                    <tr>
                        <th>Financial Year</th>
                        <th>USD Amount</th>
                        <th>NPR Amount</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="financialYearTableBody"></tbody>
            </table>
        </div>
    </div>

    <!-- Receipts Section -->
    <div class="card animate__animated animate__fadeIn">
        <div class="card-header">
            <h3 class="card-title">All Receipts</h3>
        </div>
        <form method="GET" action="{{ route('customer.receipts.download', $customer->id) }}" class="form-group mb-6 flex gap-4">
            <input type="text" name="daterange" id="daterange" class="form-control" required />
            <button type="submit" class="btn btn-primary">Download</button>
        </form>
        <div class="overflow-x-auto">
            <table class="table" id="receiptsTable">
                <thead>
                    <tr>
                        <th>Invoice Date</th>
                        <th>Ad Details</th>
                        <th>Total Amount (NPR)</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="receiptsTableBody">
                    @foreach ($paginatedAds as $ad)
                        <tr>
                            <td>{{ $ad->created_at ? $ad->created_at->format('F j, Y') : 'N/A' }}</td>
                            <td>{{ $ad->Ad_Nature_Page ?? 'Ad Campaign' }}</td>
                            <td>Rs. {{ number_format($ad->NRP, 2) }}</td>
                            <td>
                                <span class="badge {{ $ad->Payment === 'Paid' ? 'badge-success' : 'badge-warning' }}">{{ $ad->Payment }}</span>
                            </td>
                            <td class="flex gap-2">
                                <a href="{{ url('/receipt/show/' . $ad->id) }}" class="btn btn-accent btn-sm">View</a>
                                <a href="{{ url('/receipt/pdf_gen/' . $ad->id) }}" class="btn btn-primary btn-sm">Download PDF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4" id="receiptsPagination">
            {{ $paginatedAds->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <!-- Update Customer Form -->
    <div class="card hidden animate__animated animate__fadeIn" id="updateCustomer">
        <div class="card-header">
            <h3 class="card-title">Update Customer</h3>
        </div>
        <form method="post" action="{{ url('/admin/dashboard/customer/edit/'. $customer->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="name" class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
                </div>
                <div class="form-group">
                    <label for="display_name" class="block text-sm font-medium mb-2">Display Name</label>
                    <input type="text" class="form-control" id="display_name" name="display_name" value="{{ $customer->display_name }}">
                </div>
                <div class="form-group">
                    <label for="usd_rate" class="block text-sm font-medium mb-2">USD Rate</label>
                    <input type="number" class="form-control" id="usd_rate" name="usd_rate" value="{{ $customer->usd_rate ?? 170 }}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}" required>
                </div>
                <div class="form-group">
                    <label for="address" class="block text-sm font-medium mb-2">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}" required>
                </div>
                <div class="form-group">
                    <label for="phone" class="block text-sm font-medium mb-2">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" required>
                </div>
                <div class="form-group">
                    <label for="requires_bill" class="block text-sm font-medium mb-2">Requires Bill?</label>
                    <input type="checkbox" id="requires_bill" name="requires_bill" value="1" {{ $customer->requires_bill ? 'checked' : '' }}>
                    <small class="text-gray-500">Default: No</small>
                </div>
            </div>
            <div class="form-group mb-4">
                <label for="profile_picture" class="block text-sm font-medium mb-2">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
            </div>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
    </div>

    <!-- Modal for Notes -->
    <div class="modal" id="note-modal">
        <div class="modal-content animate__animated animate__zoomIn">
            <h3 id="modal-title" class="card-title mb-4">Add New Note</h3>
            <div class="form-group">
                <select id="note-type" class="form-control">
                    <option value="requirement">Requirement</option>
                    <option value="suggestion">Suggestion</option>
                    <option value="post_caption">Post Caption</option>
                    <option value="greeting">Greeting Message</option>
                    <option value="faq">QNA</option>
                </select>
            </div>
            <div class="form-group">
                <select id="note-priority" class="form-control">
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="form-group">
                <textarea id="note-text" class="form-control" placeholder="Enter requirement or suggestion..." rows="6"></textarea>
            </div>
            <div class="flex gap-4">
                <button id="save-note" class="btn btn-primary">Save</button>
                <button id="cancel-note" class="btn btn-accent">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Toggle Update Customer Form
document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.shiftKey && event.key.toLowerCase() === 'q') {
        const updateCustomerSection = document.getElementById('updateCustomer');
        updateCustomerSection.classList.toggle('hidden');
    }
});

// Initialize Date Range Picker
$(function() {
    $('input[name="daterange"]').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last 3 Months': [moment().subtract(3, 'month').startOf('month'), moment()],
            'This Year': [moment().startOf('year'), moment()]
        }
    });

    $('#financialYearRange').daterangepicker({
        locale: { format: 'YYYY-MM-DD' },
        startDate: moment().startOf('year').subtract(1, 'year'),
        endDate: moment().endOf('year').subtract(1, 'year'),
        ranges: {
            'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            'Last 2 Years': [moment().subtract(2, 'year').startOf('year'), moment().endOf('year')]
        }
    });

    fetchFinancialYearData(
        $('#financialYearRange').data('daterangepicker').startDate.format('YYYY-MM-DD'),
        $('#financialYearRange').data('daterangepicker').endDate.format('YYYY-MM-DD')
    );

    $('#financialYearRange').on('apply.daterangepicker', function(ev, picker) {
        fetchFinancialYearData(
            picker.startDate.format('YYYY-MM-DD'),
            picker.endDate.format('YYYY-MM-DD')
        );
    });
});

// Fetch Month-wise Data
function fetchMonthData(offset) {
    $.ajax({
        url: '{{ url("/admin/dashboard/customer/details/" . $customer->id) }}/' + offset,
        method: 'GET',
        data: { _token: '{{ csrf_token() }}' },
        beforeSend: function() {
            $('#dataTableBody').html('<tr><td colspan="4" class="p-4 text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        },
        success: function(response) {
            $('#dataTableBody').html($(response).find('#dataTableBody').html());
            updateNavigation(offset);
        },
        error: function(xhr) {
            $('#dataTableBody').html('<tr><td colspan="4" class="p-4 text-center text-red-500">Error loading data</td></tr>');
        }
    });
}

// Update Navigation Buttons
function updateNavigation(offset) {
    const navContainer = $('.card-header').find('div');
    navContainer.empty();
    navContainer.append(`<button class="btn btn-primary" onclick="fetchMonthData(${offset + 5})">Newer</button>`);
    if (offset > 0) {
        navContainer.append(`<button class="btn btn-primary" onclick="fetchMonthData(${offset - 5})">Older</button>`);
    }
}

// Receipts Pagination
$(document).on('click', '#receiptsPagination a', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    $.ajax({
        url: url,
        method: 'GET',
        data: { _token: '{{ csrf_token() }}' },
        beforeSend: function() {
            $('#receiptsTableBody').html('<tr><td colspan="5" class="p-4 text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        },
        success: function(response) {
            $('#receiptsTableBody').html($(response).find('#receiptsTableBody').html());
            $('#receiptsPagination').html($(response).find('#receiptsPagination').html());
        },
        error: function(xhr) {
            $('#receiptsTableBody').html('<tr><td colspan="5" class="p-4 text-center text-red-500">Error loading data</td></tr>');
        }
    });
});

// Financial Year Data
function fetchFinancialYearData(startDate, endDate) {
    $.ajax({
        url: '{{ url("/admin/dashboard/customer/financial-year") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            customer_id: {{ $customer->id }},
            start_date: startDate,
            end_date: endDate
        },
        beforeSend: function() {
            $('#financialYearTableBody').html('<tr><td colspan="4" class="p-4 text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        },
        success: function(response) {
            populateFinancialYearTable(response.data, startDate, endDate);
        },
        error: function(xhr) {
            $('#financialYearTableBody').html('<tr><td colspan="4" class="p-4 text-center text-red-500">Error loading data</td></tr>');
        }
    });
}

function populateFinancialYearTable(data, startDate, endDate) {
    let tableBody = $('#financialYearTableBody');
    tableBody.empty();
    let yearRange = `${moment(startDate).format('YYYY')}-${moment(endDate).format('YYYY')}`;
    let newRow = `<tr>
        <td class="p-4">${yearRange}</td>
        <td class="p-4">$${parseFloat(data.usd).toFixed(2)}</td>
        <td class="p-4">Rs.${parseFloat(data.npr).toFixed(2)}</td>
        <td class="p-4">${data.quantity}</td>
    </tr>`;
    tableBody.append(newRow);
    $('#financialYearTable').removeClass('hidden');
}

// Notes Functionality
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function renderNotes(filterText = '') {
        $.ajax({
            url: '{{ route("customer.requirements", $customer->id) }}',
            method: 'GET',
            success: function(response) {
                const notes = response.requirements.filter(note =>
                    note.body.toLowerCase().includes(filterText.toLowerCase())
                );
                $('#notes-list').empty();

                if (notes.length === 0) {
                    $('#notes-list').html('<div class="text-center py-8 text-gray-500">No notes found</div>');
                    return;
                }

                notes.forEach((note, index) => {
                    const typeBadge = {
                        requirement: 'badge-primary',
                        suggestion: 'badge-accent',
                        post_caption: 'badge-success',
                        greeting: 'badge-warning',
                        faq: 'badge-gray'
                    }[note.note_type] || 'badge-gray';
                    
                    const priorityBadge = `badge-${note.priority === 'high' ? 'danger' : note.priority === 'medium' ? 'warning' : 'success'}`;
                    
                    const noteHtml = `
                        <div class="note-item">
                            <div class="note-header">
                                <span class="badge ${typeBadge}">${note.note_type}</span>
                                <span class="badge ${priorityBadge}">${note.priority}</span>
                            </div>
                            <div class="note-body">${note.body}</div>
                            <div class="note-date">Added: ${new Date(note.created_at).toLocaleString()}</div>
                            <div class="flex gap-2 mt-2">
                                <a href="{{ route('customer.requirement.detail', '') }}/${note.id}" class="btn btn-primary btn-sm" target="_blank"> View </a>
                                <button class="btn btn-accent btn-sm edit-note" data-id="${note.id}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-note" data-id="${note.id}">Delete</button>
                            </div>
                        </div>`;
                    $('#notes-list').append(noteHtml);
                });
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load notes.', 'error');
            }
        });
    }

    $('#add-note-btn').click(function() {
        $('#modal-title').text('Add New Note');
        $('#note-text').val('');
        $('#note-type').val('requirement');
        $('#note-priority').val('high');
        $('#save-note').data('editing', null);
        $('#note-modal').addClass('active');
    });

    $('#cancel-note').click(function() {
        $('#note-modal').removeClass('active');
    });

    $('#save-note').click(function() {
        const text = $('#note-text').val().trim();
        if (!text) {
            Swal.fire('Error', 'Please enter a note.', 'error');
            return;
        }

        const noteData = {
            body: text,
            note_type: $('#note-type').val(),
            priority: $('#note-priority').val()
        };

        const editingId = $(this).data('editing');
        const url = editingId
            ? '{{ route("customer.requirements.update", "") }}/' + editingId
            : '{{ route("customer.requirements.store", $customer->id) }}';
        const method = editingId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: noteData,
            success: function(response) {
                $('#note-modal').removeClass('active');
                renderNotes($('#filter-notes').val());
                Swal.fire('Success', editingId ? 'Note updated successfully.' : 'Note added successfully.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to save note.', 'error');
            }
        });
    });

    $(document).on('click', '.edit-note', function() {
        const noteId = $(this).data('id');
        $.ajax({
            url: '{{ route("customer.requirements.show", "") }}/' + noteId,
            method: 'GET',
            success: function(response) {
                const note = response.data;
                $('#modal-title').text('Edit Note');
                $('#note-text').val(note.body);
                $('#note-type').val(note.note_type);
                $('#note-priority').val(note.priority);
                $('#save-note').data('editing', note.id);
                $('#note-modal').addClass('active');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to load note.', 'error');
            }
        });
    });

    $(document).on('click', '.delete-note', function() {
        const noteId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This note will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("customer.requirements.delete", "") }}/' + noteId,
                    method: 'DELETE',
                    success: function(response) {
                        renderNotes($('#filter-notes').val());
                        Swal.fire('Deleted', 'Note deleted successfully.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete note.', 'error');
                    }
                });
            }
        });
    });

    $('#filter-notes').on('input', function() {
        renderNotes($(this).val());
    });

    renderNotes();
});

// Bill Requirement Toggle
document.addEventListener('DOMContentLoaded', function(){
    const toggle = document.getElementById('requiresBillToggle');
    const label  = document.getElementById('requiresBillLabel');

    if (!toggle) return;

    toggle.addEventListener('change', function(){
        const isChecked = toggle.checked ? 1 : 0;
        fetch('{{ route("admin.customers.requires_bill", $customer->id) }}', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ requires_bill: isChecked })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                label.textContent = res.requires_bill ? 'Yes' : 'No';
                
                // Update top badge
                const badge = document.querySelector('.requires-bill-badge');
                if (badge) {
                    if (res.requires_bill) {
                        badge.classList.remove('badge-gray');
                        badge.classList.add('badge-primary');
                        badge.textContent = 'Requires Bill';
                    } else {
                        badge.classList.remove('badge-primary');
                        badge.classList.add('badge-gray');
                        badge.textContent = 'No Bill';
                    }
                }
                
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Saved', 
                    text: 'Billing requirement updated successfully', 
                    timer: 1200, 
                    showConfirmButton: false 
                });
            } else {
                alert(res.message || 'Failed to update.');
                toggle.checked = !isChecked; // revert
            }
        })
        .catch(() => {
            alert('Network error.');
            toggle.checked = !isChecked; // revert
        });
    });
});

// Order Volume Badge
$(document).ready(function() {
    const totalUSDThisMonth = parseFloat('{{ $totalUSDThisMonth }}');
    let badgeClass = '';
    let hoverText = '';

    if (totalUSDThisMonth <= 150) {
        badgeClass = 'badge-success';
        hoverText = 'Low Order Volume';
    } else if (totalUSDThisMonth <= 250) {
        badgeClass = 'badge-warning';
        hoverText = 'Medium Order Volume';
    } else {
        badgeClass = 'badge-danger';
        hoverText = 'High Order Volume';
    }

    const orderVolumeElement = `<span class="badge ${badgeClass} ml-2" title="${hoverText}">${hoverText}</span>`;
    $('.profile-name').append(orderVolumeElement);
});

// Assigned Packages Functionality
(function () {
  const assignedWrap = document.getElementById('assignedPackages');
  const assignedList = document.getElementById('assignedList');

  function renderRow(pkg) {
    const row = document.createElement('div');
    row.className = 'package-item';
    row.dataset.pkgId = pkg.id;
    row.innerHTML = `
      <div class="package-info">
        <div class="package-name">${pkg.name} ${pkg.code ? '(' + pkg.code + ')' : ''}</div>
        <div class="package-details">
          Status: <span class="font-medium pkg-status">${pkg.pivot?.status || '-'}</span> •
          Start: <span class="pkg-start">${pkg.pivot?.start_date || '-'}</span> •
          End: <span class="pkg-end">${pkg.pivot?.end_date || '-'}</span>
        </div>
      </div>
      <div class="package-actions">
        <button class="btn btn-primary btn-sm" data-act="update" data-id="${pkg.id}">Update</button>
        <button class="btn btn-danger btn-sm" data-act="remove" data-id="${pkg.id}">Remove</button>
      </div>`;
    return row;
  }

  function loadAssigned() {
    const url = `{{ route('admin.customers.packages.list', $customer->id) }}?t=${Date.now()}`;
    fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
      .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then(res => {
        assignedList.innerHTML = '';
        const list = Array.isArray(res.data) ? res.data : [];
        if (!list.length) {
          assignedWrap.style.display = 'none';
          return;
        }
        assignedWrap.style.display = '';
        list.forEach(pkg => assignedList.appendChild(renderRow(pkg)));
      })
      .catch(err => {
        console.error('Failed to load assigned packages:', err);
        if (!assignedList.children.length) assignedWrap.style.display = 'none';
      });
  }

  // Initial load
  loadAssigned();

  // Event delegation for Update/Remove
  assignedList.addEventListener('click', (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;

    const id = btn.getAttribute('data-id');
    const act = btn.getAttribute('data-act');
    const row = btn.closest('[data-pkg-id]');

    if (act === 'remove') {
      if (!confirm('Remove this package?')) return;

      const url = `{{ route('admin.customers.packages.remove', [$customer->id, 0]) }}`.replace('/0', '/' + id);
      fetch(url, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
      })
        .then(r => {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          // Optimistic UI removal
          if (row) row.remove();
          if (!assignedList.children.length) assignedWrap.style.display = 'none';
          // Verify fresh
          loadAssigned();
        })
        .catch(() => alert('Failed to remove. Try again.'));
    }

    if (act === 'update') {
      const start = prompt('Start date (YYYY-MM-DD) — leave blank to keep');
      const end = prompt('End date (YYYY-MM-DD) — leave blank to keep');
      const status = prompt('Status [active|paused|completed]');
      if (!status) return;

      const url = `{{ route('admin.customers.packages.update', [$customer->id, 0]) }}`.replace('/0', '/' + id);
      fetch(url, {
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ start_date: start || null, end_date: end || null, status })
      })
        .then(r => {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          return r.json();
        })
        .then(() => {
          // Optimistic UI update
          if (row) {
            if (start) row.querySelector('.pkg-start').textContent = start;
            if (end) row.querySelector('.pkg-end').textContent = end;
            row.querySelector('.pkg-status').textContent = status;
          }
          // Verify fresh
          loadAssigned();
        })
        .catch(() => alert('Failed to update. Check dates/status.'));
    }
  });
})();
</script>

@endsection