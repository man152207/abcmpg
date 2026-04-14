
<?php $__env->startSection('title', 'Customer Management | MPG Solution'); ?>

<?php $__env->startSection('content'); ?>
<!-- Bootstrap CSS for styling and responsiveness -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    :root {
        --primary-color: #093b7b;
        --primary-light: rgba(9, 59, 123, 0.1);
        --secondary-color: #17a2b8;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --light-bg: #f8f9fa;
        --dark-text: #343a40;
        --border-color: #e0e6ef;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: #f8fafc;
        line-height: 1.5;
    }

    /* Header Styles - More Compact */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0d4fa0 100%);
        color: white;
        padding: 1rem 0;
        margin-bottom: 1rem;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 3px 15px rgba(9, 59, 123, 0.15);
    }

    .page-header h1 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
    }

    .page-header .subtitle {
        opacity: 0.9;
        font-size: 0.85rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        min-height: auto;
    }

    .header-left {
        flex: 1;
        min-width: 0;
    }

    /* Header Button - More Prominent */
    .btn-add-customer {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        color: var(--primary-color);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 0.6rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        height: fit-content;
    }

    .btn-add-customer:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border-color: white;
    }

    @media (max-width: 767.98px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .btn-add-customer {
            align-self: flex-start;
            width: auto;
        }
    }

    /* Stats Cards - More Compact */
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .stats-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .stats-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stats-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .stats-icon.primary {
        background: rgba(9, 59, 123, 0.1);
        color: var(--primary-color);
    }

    .stats-icon.success {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .stats-icon.info {
        background: rgba(23, 162, 184, 0.1);
        color: var(--secondary-color);
    }

    .stats-text {
        flex: 1;
        min-width: 0;
    }

    .stats-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-text);
        line-height: 1.2;
    }

    .stats-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-top: 0.125rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Export Button Card */
    .export-card {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-export {
        background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-weight: 600;
        font-size: 0.85rem;
        color: white;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.2);
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        color: white;
    }

    /* Search Container - More Compact */
    .search-container {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .search-row {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input-container {
        flex: 1;
        min-width: 250px;
    }

    .date-filter-container {
        width: 220px;
    }

    .search-button-container {
        width: auto;
    }

    .search-container .input-group-text {
        background: var(--light-bg);
        border: 1px solid #ced4da;
        border-right: none;
        padding: 0.5rem 0.75rem;
    }

    .search-container .form-control {
        border-left: none;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        height: calc(1.5em + 0.75rem + 2px);
    }

    .search-container .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        height: calc(1.5em + 0.75rem + 2px);
    }

    @media (max-width: 767.98px) {
        .search-row {
            flex-direction: column;
        }
        
        .search-input-container,
        .date-filter-container,
        .search-button-container {
            width: 100%;
        }
    }

    /* Main Content Card */
    .main-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header-custom {
        background: white;
        border-bottom: 2px solid var(--light-bg);
        padding: 0.875rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-custom h5 {
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
    }

    /* Table Styles - Slightly Larger Font */
    .customer-table {
        margin: 0;
        font-size: 0.95rem;
    }

    .customer-table thead th {
        background: #f8fafd;
        color: var(--primary-color);
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }

    .customer-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--border-color);
    }

    .customer-table tbody tr:hover {
        background: rgba(9, 59, 123, 0.03);
    }

    .customer-table tbody td {
        padding: 0.875rem 1rem;
        vertical-align: middle;
        color: var(--dark-text);
        border-top: none;
        line-height: 1.4;
    }

    /* Profile Image */
    .profile-img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .profile-img:hover {
        transform: scale(1.05);
        border-color: var(--primary-color);
    }

    /* Customer Info */
    .customer-info {
        display: flex;
        flex-direction: column;
    }

    .customer-name {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
        font-size: 1.1rem;
        margin-bottom: 0.125rem;
        line-height: 1.3;
    }

    .customer-name:hover {
        color: #0d4fa0;
        text-decoration: underline;
    }

    .customer-email {
        font-size: 0.8rem;
        color: #6c757d;
        word-break: break-all;
        line-height: 1.3;
        font-size: 15px;
    }

    /* Badges */
    .badge-admin {
        background: rgba(23, 162, 184, 0.1);
        color: var(--secondary-color);
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        border: 1px solid rgba(23, 162, 184, 0.2);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.375rem;
        flex-wrap: nowrap;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: white;
        color: #6c757d;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .btn-action:hover {
        background: var(--light-bg);
        color: var(--primary-color);
        border-color: var(--primary-light);
        transform: translateY(-1px);
    }

    .btn-action.view:hover {
        background: rgba(0, 123, 255, 0.1);
        color: #007bff;
        border-color: rgba(0, 123, 255, 0.2);
    }

    .btn-action.edit:hover {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border-color: rgba(40, 167, 69, 0.2);
    }

    .btn-action.delete:hover {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
        border-color: rgba(220, 53, 69, 0.2);
    }

    /* Dropdown Menu */
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 0.375rem;
        min-width: 160px;
    }

    .dropdown-item {
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.85rem;
        color: var(--dark-text);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }

    .dropdown-item i {
        width: 18px;
        margin-right: 0.5rem;
        font-size: 0.85rem;
        text-align: center;
    }

    .dropdown-item:hover {
        background: rgba(9, 59, 123, 0.08);
        color: var(--primary-color);
    }

    .dropdown-item.text-danger:hover {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    /* Mobile Card View */
    @media (max-width: 767.98px) {
        .mobile-customer-card {
            background: white;
            border-radius: 10px;
            padding: 0.875rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .mobile-customer-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .mobile-card-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .mobile-profile-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.75rem;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .mobile-customer-info {
            flex: 1;
        }

        .mobile-customer-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1rem;
            margin-bottom: 0.125rem;
            text-decoration: none;
            display: block;
        }

        .mobile-customer-name:hover {
            text-decoration: underline;
        }

        .mobile-meta-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin: 0.75rem 0;
        }

        .mobile-meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 0.125rem;
            font-weight: 500;
        }

        .meta-value {
            font-size: 0.875rem;
            color: var(--dark-text);
            font-weight: 500;
        }

        .mobile-actions {
            display: flex;
            gap: 0.375rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-color);
        }

        .mobile-actions .btn {
            flex: 1;
            font-size: 0.8rem;
            padding: 0.375rem;
        }

        .page-header {
            padding: 0.75rem 0;
            margin-bottom: 0.75rem;
        }

        .page-header h1 {
            font-size: 1.25rem;
        }
    }

    /* WhatsApp Link */
    .whatsapp-link {
        color: #25D366;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
        font-size: 17px;
    }

    .whatsapp-link:hover {
        color: #128C7E;
        text-decoration: underline;
    }

    .whatsapp-link i {
        margin-right: 0.375rem;
        font-size: 1rem;
    }

    /* Impersonate Link */
    .impersonate-link {
        color: var(--secondary-color);
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 1rem;
    }

    .impersonate-link:hover {
        color: #138496;
        text-decoration: underline;
    }

    .impersonate-link i {
        margin-left: 0.25rem;
        font-size: 0.75rem;
    }

    /* Date Range Picker */
    .daterangepicker-input {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem;
        background: white;
        font-size: 0.9rem;
    }

    /* Pagination */
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .pagination .page-link {
        color: var(--primary-color);
        border-radius: 6px;
        margin: 0 2px;
        border: 1px solid var(--border-color);
        padding: 0.375rem 0.75rem;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .pagination .page-link:hover {
        background-color: var(--light-bg);
        color: var(--primary-color);
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0d4fa0 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        border-bottom: none;
        padding: 1rem;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
        font-size: 1.25rem;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #e0e6ef;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(9, 59, 123, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.375rem;
        font-size: 0.9rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        color: #dee2e6;
    }

    .empty-state h4 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .empty-state p {
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid rgba(9, 59, 123, 0.1);
        border-radius: 50%;
        border-top-color: var(--primary-color);
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive Table Container */
    .table-responsive-custom {
        overflow-x: auto;
        border-radius: 0 0 12px 12px;
    }

    /* Action Dropdown Fix */
    .dropdown-toggle::after {
        display: none;
    }

    /* Status Badge */
    .status-badge {
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    /* Action Separator */
    .action-separator {
        height: 1px;
        background: var(--border-color);
        margin: 0.375rem 0;
    }

    /* Utility Classes */
    .text-ellipsis {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 280px;
    }

    .nowrap {
        white-space: nowrap;
    }

    /* Compact spacing between sections */
    .compact-section {
        margin-bottom: 0.75rem;
    }
</style>

<div class="page-header">
    <div class="container-fluid">
        <div class="header-content">
            <div class="header-left">
                <h1><i class="fas fa-users mr-2"></i>Customer Management</h1>
                <div class="subtitle">Manage all customer accounts and information</div>
            </div>
            <button class="btn btn-add-customer" data-toggle="modal" data-target="#addCustomerModal">
                <i class="fas fa-plus mr-2"></i>Add New Customer
            </button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Stats Section - More Compact -->
    <div class="row compact-section">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-text">
                        <div class="stats-number"><?php echo e($totalCustomers); ?></div>
                        <div class="stats-label">Total Customers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-icon success">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stats-text">
                        <div class="stats-number"><?php echo e($customers->count()); ?></div>
                        <div class="stats-label">On This Page</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-content">
                    <div class="stats-icon info">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stats-text">
                        <div class="stats-number"><?php echo e($admins->count()); ?></div>
                        <div class="stats-label">Administrators</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="export-card">
                <button id="exportButton" class="btn btn-export">
                    <i class="fas fa-file-export mr-2"></i>Export Customers
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section - More Compact -->
    <div class="search-container compact-section">
        <form action="<?php echo e(route('search_customer')); ?>" method="get" id="searchForm">
            <?php echo csrf_field(); ?>
            <div class="search-row">
                <div class="search-input-container">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" 
                               placeholder="Search customers..." 
                               value="<?php echo e(request()->get('search')); ?>">
                    </div>
                </div>
                
                <div class="date-filter-container">
                    <div class="input-group">
                        <input type="text" name="date_range" id="dateRangePicker" 
                               class="form-control daterangepicker-input" 
                               placeholder="Date range" 
                               value="<?php echo e(request()->get('date_range')); ?>">
                        <div class="input-group-append">
                            <a href="<?php echo e(url('/admin/dashboard/customer')); ?>" class="btn btn-outline-secondary" 
                               type="button" title="Clear all filters">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="search-button-container">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-card">
        <div class="card-header-custom">
            <h5><i class="fas fa-list mr-2"></i>Customer List</h5>
            <div class="text-muted small">
                <span class="d-none d-md-inline">Showing </span><?php echo e($customers->firstItem() ?? 0); ?>-<?php echo e($customers->lastItem() ?? 0); ?> of <?php echo e($customers->total()); ?>

            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="d-none d-md-block">
            <div class="table-responsive-custom">
                <table class="table table-hover customer-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;" class="ps-4">Customer</th>
                            <th style="width: 70px;" >Contact Information</th>
                            <th style="width: 90px;" class="text-center nowrap">USD Rate</th>
                            <th style="width: 120px;">Phone</th>
                            <th style="width: 100px;">Created</th>
                            <th style="width: 100px;">Added By</th>
                            <th style="width: 90px;" class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <!-- Customer Profile -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <a href="<?php echo e(url('/admin/dashboard/customer/details/' . $customer->id)); ?>">
                                        <?php if($customer->profile_picture): ?>
                                            <img src="<?php echo e(asset('uploads/customers/' . $customer->profile_picture)); ?>" 
                                                 alt="<?php echo e($customer->name); ?>" 
                                                 class="profile-img">
                                        <?php else: ?>
                                            <div class="profile-img bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    <div class="ml-2">
                                        <a href="<?php echo e(url('/admin/dashboard/customer/details/' . $customer->id)); ?>" 
                                           class="customer-name text-ellipsis d-block">
                                            <?php echo e($customer->name); ?>

                                        </a>
                                        <div class="small text-muted">
                                            ID: <?php echo e($customer->id); ?>

                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Information -->
                            <td>
                                <div class="customer-info">
                                    <div class="d-flex align-items-center mb-1">
                                        <a href="<?php echo e(route('admin.customer.impersonate', $customer->id)); ?>" 
                                           class="impersonate-link" target="_blank" title="Impersonate this customer">
                                            <?php echo e($customer->display_name); ?>

                                            <i class="fas fa-external-link-alt ml-1"></i>
                                        </a>
                                    </div>
                                    <div class="customer-email text-ellipsis" title="<?php echo e($customer->email); ?>">
                                        <i class="fas fa-envelope fa-xs mr-1"></i><?php echo e($customer->email); ?>

                                    </div>
                                    <div class="small text-muted mt-1 text-ellipsis" title="<?php echo e($customer->address); ?>">
                                        <i class="fas fa-map-marker-alt fa-xs mr-1"></i><?php echo e(Str::limit($customer->address, 25)); ?>

                                    </div>
                                </div>
                            </td>
                            <td class="text-center nowrap">
  <?php echo e($customer->usd_rate ?? 170); ?>

</td>
                                                       <!-- Phone -->
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="https://wa.me/+977<?php echo e($customer->phone); ?>?text=<?php echo e(rawurlencode('*Welcome to MPG Solution!*
We are delighted to inform you that you have been successfully integrated into our system.
As a valued customer of MPG Solution, you now have access to a range of services including digital marketing, advertisement management, and dedicated customer support. Our team is here to ensure that you receive the best possible service and support for your business needs.

For more information about our services and policies, please review the following:
- Terms and Conditions: https://mpg.com.np/terms-services/
- Privacy Policy: https://mpg.com.np/privacy-policy/

_Our service hours are 9 AM to 5 PM, Sunday to Friday._
Thank you for giving us the opportunity to serve you. We are excited to help your business grow with MPG Solution.')); ?>" 
                                       target="_blank" class="whatsapp-link mb-1">
                                        <i class="fab fa-whatsapp"></i><?php echo e($customer->phone); ?>

                                    </a>
                                    <?php if($customer->phone_2): ?>
                                    <div class="small text-muted">
                                        <i class="fas fa-phone fa-xs mr-1"></i><?php echo e($customer->phone_2); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Created Date -->
                            <td>
                                <?php if($customer->created_at): ?>
                                    <div class="small">
                                        <div class="font-weight-medium"><?php echo e(\Carbon\Carbon::parse($customer->created_at)->format('M d, Y')); ?></div>
                                        <div class="text-muted"><?php echo e(\Carbon\Carbon::parse($customer->created_at)->format('h:i A')); ?></div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>

 <!-- Added By -->
                            <td>
                                <?php if($customer->createdByAdmin): ?>
                                    <span class="badge-admin d-inline-block">
                                        <i class="fas fa-user-shield fa-xs mr-1"></i><?php echo e(Str::limit($customer->createdByAdmin->name, 10)); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <!-- Actions -->
                            <td class="pe-4">
                                <div class="action-buttons justify-content-center">
                                    <a href="<?php echo e(url('/admin/dashboard/customer/details/' . $customer->id)); ?>" 
                                       class="btn btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(url('/admin/dashboard/customer/edit/' . $customer->id)); ?>" 
                                       class="btn btn-action edit" title="Edit Customer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-action dropdown-toggle" 
                                                type="button" data-toggle="dropdown" 
                                                aria-haspopup="true" aria-expanded="false" title="More Actions">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="<?php echo e(route('admin.customer.impersonate', $customer->id)); ?>" 
                                               target="_blank" class="dropdown-item">
                                                <i class="fas fa-user-secret"></i>Impersonate
                                            </a>
                                            <a href="https://wa.me/+977<?php echo e($customer->phone); ?>" 
                                               target="_blank" class="dropdown-item">
                                                <i class="fab fa-whatsapp"></i>Message
                                            </a>
                                            <div class="action-separator"></div>
                                            <form action="<?php echo e(url('/admin/dashboard/customer/delete/' . $customer->id)); ?>" 
                                                  method="get" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" 
                                                        class="dropdown-item text-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                                                    <i class="fas fa-trash-alt"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="empty-state py-4">
                                    <i class="fas fa-users fa-2x mb-3"></i>
                                    <h4>No customers found</h4>
                                    <p class="mb-0">Try adjusting your search or add a new customer.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="d-md-none">
            <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mobile-customer-card">
                <div class="mobile-card-header">
                    <a href="<?php echo e(url('/admin/dashboard/customer/details/' . $customer->id)); ?>">
                        <?php if($customer->profile_picture): ?>
                            <img src="<?php echo e(asset('uploads/customers/' . $customer->profile_picture)); ?>" 
                                 alt="<?php echo e($customer->name); ?>" 
                                 class="mobile-profile-img">
                        <?php else: ?>
                            <div class="mobile-profile-img bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="mobile-customer-info">
                        <a href="<?php echo e(url('/admin/dashboard/customer/details/' . $customer->id)); ?>" 
                           class="mobile-customer-name">
                            <?php echo e($customer->name); ?>

                        </a>
                        <div class="small text-muted mb-1">
                            <a href="<?php echo e(route('admin.customer.impersonate', $customer->id)); ?>" 
                               class="impersonate-link" target="_blank">
                                <?php echo e($customer->display_name); ?>

                                <i class="fas fa-external-link-alt fa-xs ml-1"></i>
                            </a>
                        </div>
                        <div class="small">
                            <?php if($customer->createdByAdmin): ?>
                                <span class="badge-admin">
                                    <i class="fas fa-user-shield fa-xs mr-1"></i><?php echo e(Str::limit($customer->createdByAdmin->name, 12)); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="mobile-meta-grid">
                    <div class="mobile-meta-item">
                        <span class="meta-label">Phone</span>
                        <a href="https://wa.me/+977<?php echo e($customer->phone); ?>" 
                           target="_blank" class="meta-value whatsapp-link">
                            <i class="fab fa-whatsapp mr-1"></i><?php echo e($customer->phone); ?>

                        </a>
                    </div>
                    <div class="mobile-meta-item">
                        <span class="meta-label">Created</span>
                        <span class="meta-value">
                            <?php if($customer->created_at): ?>
                                <?php echo e(\Carbon\Carbon::parse($customer->created_at)->format('M d')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="mobile-meta-item">
                        <span class="meta-label">Email</span>
                        <span class="meta-value text-ellipsis"><?php echo e(Str::limit($customer->email, 15)); ?></span>
                    </div>
                    <div class="mobile-meta-item">
                        <span class="meta-label">USD Rate</span>
                        <span class="meta-value"><?php echo e($customer->usd_rate ?? 170); ?></span>
                    </div>
                </div>

                <div class="mobile-actions">
                    <a href="<?php echo e(url('/admin/dashboard/customer/details/' . $customer->id)); ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="<?php echo e(url('/admin/dashboard/customer/edit/' . $customer->id)); ?>" 
                       class="btn btn-outline-success btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                type="button" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?php echo e(route('admin.customer.impersonate', $customer->id)); ?>" 
                               target="_blank" class="dropdown-item">
                                <i class="fas fa-user-secret mr-2"></i>Impersonate
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="<?php echo e(url('/admin/dashboard/customer/delete/' . $customer->id)); ?>" 
                                  method="get" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                        class="dropdown-item text-danger" 
                                        onclick="return confirm('Are you sure you want to delete this customer?')">
                                    <i class="fas fa-trash-alt mr-2"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state px-3 py-4">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h4>No customers found</h4>
                <p class="mb-0">Try adjusting your search or add a new customer.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($customers->hasPages()): ?>
        <div class="card-footer bg-white border-top py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <span class="d-none d-md-inline">Showing </span><?php echo e($customers->firstItem() ?? 0); ?>-<?php echo e($customers->lastItem() ?? 0); ?> of <?php echo e($customers->total()); ?>

                </div>
                <div>
                    <?php echo e($customers->links('pagination::bootstrap-5', ['paginator' => $customers->appends(request()->query()), 'perPage' => 10])); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">
                    <i class="fas fa-user-plus mr-2"></i>Add New Customer
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?php echo e(url('/admin/dashboard/customer/add')); ?>" id="addCustomerForm">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter customer's full name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="display_name" class="form-label">Display Name</label>
                            <input type="text" class="form-control" id="display_name" name="display_name" placeholder="Optional display name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="customer@example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="text" class="form-control" id="phone" name="phone" required placeholder="98XXXXXXXX">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_2" class="form-label">Secondary Phone</label>
                            <input type="text" class="form-control" id="phone_2" name="phone_2" placeholder="Optional secondary number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="usd_rate" class="form-label">USD Exchange Rate *</label>
                            <input type="number" class="form-control" id="usd_rate" name="usd_rate" value="170" step="0.01" required>
                            <small class="text-muted">Default exchange rate for this customer</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <input type="text" class="form-control" id="address" name="address" required placeholder="Enter complete address">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="created_by" class="form-label">Assign Admin *</label>
                            <select class="form-control" id="created_by" name="created_by" required>
                                <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($admin->id); ?>"
                                        <?php echo e((isset($currentAdmin) && $currentAdmin->id == $admin->id) ? 'selected' : ''); ?>>
                                        <?php echo e($admin->name); ?> (<?php echo e($admin->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">This admin will be responsible for this customer</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Export button functionality
    const exportButton = document.getElementById('exportButton');
    if (exportButton) {
        exportButton.addEventListener('click', function() {
            window.location.href = '/export-customers';
        });
    }

    // Initialize Date Range Picker
    $('#dateRangePicker').daterangepicker({
        locale: { 
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear',
            applyLabel: 'Apply',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        },
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end, label) {
        $('#dateRangePicker').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    });

    // Clear date range picker when cancel is clicked
    $('#dateRangePicker').on('cancel.daterangepicker', function() {
        $(this).val('');
    });

    // Auto-focus search input on page load
    $('input[name="search"]').focus();

    // Add Customer Form Validation
    $('#addCustomerForm').on('submit', function(e) {
        const phoneInput = $('#phone');
        const emailInput = $('#email');
        let isValid = true;

        // Simple phone validation
        if (!phoneInput.val().match(/^[0-9]{10}$/)) {
            alert('Please enter a valid 10-digit phone number.');
            phoneInput.focus();
            isValid = false;
        }

        // Simple email validation
        if (!emailInput.val().match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            alert('Please enter a valid email address.');
            emailInput.focus();
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Mobile card dropdown fix
    $(document).on('click', '.dropdown-toggle', function(e) {
        e.stopPropagation();
        $(this).dropdown('toggle');
        return false;
    });

    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    // Add animation to stats cards on page load
    $('.stats-card').each(function(i) {
        $(this).delay(i * 100).animate({
            opacity: 1
        }, 300);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/customer/list.blade.php ENDPATH**/ ?>