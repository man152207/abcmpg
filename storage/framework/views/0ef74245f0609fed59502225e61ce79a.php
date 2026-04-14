

<?php $__env->startSection('title', 'Link Management | MPG Solution'); ?>
<?php $__env->startSection('content'); ?>


<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Roboto', sans-serif;
        color: #212529;
    }
    .linkroom-container-fluid {
        padding: 0px;
    }
    .linkroom-header-section {
        background: linear-gradient(135deg, #0056b3, #003d82); /* MPG Blue gradient */
        color: #ffffff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .linkroom-header-section h1 {
        font-size: 28px;
        margin: 0;
        font-weight: 700;
    }
    .linkroom-header-section p {
        font-size: 16px;
        margin: 5px 0 0;
        font-weight: 400;
    }
    .linkroom-customer-highlight {
        background: #ffffff;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: -5px 0 5px rgba(0, 0, 0, 0.1);
        text-align: right;
        border-left: 4px solid #0056b3; /* MPG Blue accent */
    }
    .linkroom-customer-highlight h4 {
        color: #0056b3;
        font-size: 20px;
        font-weight: bold;
        margin: 0;
        text-transform: uppercase;
    }
    .linkroom-customer-highlight p {
        color: #6c757d; /* MPG Gray */
        font-size: 16px;
        margin: 0;
        text-transform: uppercase;
    }
    .linkroom-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .linkroom-card-header {
        background: #343a40;
        color: #ffffff;
        font-size: 20px;
        font-weight: 600;
        border-radius: 10px 10px 0 0;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 60px; /* Ensure consistent height */
    }
    .linkroom-card-header .linkroom-controls {
        display: flex;
        align-items: center;
        gap: 10px; /* Reduced gap for tighter fit */
        white-space: nowrap; /* Prevent wrapping */
    }
    .linkroom-btn-primary {
        background-color: #0056b3; /* MPG Blue */
        border-color: #0056b3;
        color: #ffffff;
    }
    .linkroom-btn-primary:hover {
        background-color: #003d82; /* Darker MPG Blue */
        border-color: #003d82;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
    }
    .linkroom-btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
        padding: 5px 10px; /* Reduced padding for compactness */
        font-size: 14px;
    }
    .linkroom-btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
    }
    .linkroom-btn-success, .linkroom-btn-info {
        background-color: #28a745;
        border-color: #28a745;
        color: #ffffff;
    }
    .linkroom-btn-success:hover, .linkroom-btn-info:hover {
        background-color: #218838;
        border-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
    }
    .linkroom-table {
        border-radius: 8px;
        overflow: hidden;
    }
    .linkroom-table th, .linkroom-table td {
        vertical-align: middle;
        padding: 12px;
    }
    .linkroom-table th {
        background: #f1f1f1;
        font-weight: 600;
        color: #333;
    }
    .linkroom-table tbody tr:hover {
        background: #f8f9fa;
    }
    .linkroom-form-control {
        border-radius: 6px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        font-size: 14px;
        height: 34px; /* Match button height */
    }
    .linkroom-date-range-filter {
        max-width: 180px; /* Reduced width for better fit */
        margin-left: 5px; /* Adjusted margin */
    }
    .linkroom-pagination .page-link {
        border-radius: 4px;
        margin: 0 3px;
        color: #0056b3; /* MPG Blue */
    }
    .linkroom-pagination .page-item.active .page-link {
        background: #0056b3; /* MPG Blue */
        border-color: #0056b3;
        color: #ffffff;
    }
    .linkroom-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 10000;
        padding: 15px 25px;
        border-radius: 6px;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        font-size: 14px;
        opacity: 0;
        transform: translateY(20px);
        animation: slideIn 0.3s forwards, slideOut 0.3s 2.7s forwards;
    }
    .linkroom-notification.linkroom-success {
        background: #28a745;
    }
    .linkroom-notification.linkroom-error {
        background: #dc3545;
    }
    @keyframes slideIn {
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideOut {
        to { opacity: 0; transform: translateY(20px); }
    }
    @media (max-width: 768px) {
        .linkroom-header-section {
            flex-direction: column;
            text-align: center;
        }
        .linkroom-customer-highlight {
            margin-top: 15px;
            text-align: center;
        }
        .linkroom-table-responsive {
            border-radius: 8px;
        }
        .linkroom-card-header {
            flex-direction: column;
            text-align: center;
        }
        .linkroom-card-header .linkroom-controls {
            margin-top: 10px;
            flex-direction: column;
            gap: 10px;
        }
        .linkroom-card-header .linkroom-controls .d-flex {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="linkroom-container-fluid">
    <!-- Page Header -->
    <div class="linkroom-header-section">
        <div>
            <h1>Campaign Link Management</h1>
            <p>Effortlessly manage customer campaign links with advanced features.</p>
        </div>
        <?php if($customer): ?>
        <div class="linkroom-customer-highlight">
            <a href="<?php echo e(route('customer.details', ['id' => $customer->id])); ?>" style="text-decoration: none; color: inherit;">
                <h4><?php echo e($customer->name); ?></h4>
                <p><?php echo e($customer->display_name); ?> | <?php echo e($customer->phone); ?></p>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php if($customer): ?>
    <!-- Campaign Links Section -->
    <div class="linkroom-card">
        <div class="linkroom-card-header">
            <span>Campaign Links</span>
            <div class="linkroom-controls">
                <div class="d-flex align-items-center gap-2" style="flex-wrap: nowrap;">
                    <input type="checkbox" id="select-all-checkbox" class="linkroom-form-check-input me-2" title="Select all campaign links">
                    <label for="select-all-checkbox" class="linkroom-form-check-label text-white me-2">Select All</label>
                    <button class="linkroom-btn linkroom-btn-danger" id="bulk-delete-btn" title="Delete selected campaign links">
                        <i class="fa fa-trash"></i> Bulk Delete (0)
                    </button>
                    <input type="text" id="dateRangeFilter" class="linkroom-form-control linkroom-date-range-filter" placeholder="Filter by Date Range" title="Filter links by date range">
                </div>
            </div>
        </div>
        <div class="linkroom-card-body p-0">
            <div class="linkroom-table-responsive">
                <table class="linkroom-table linkroom-table-hover mb-0" id="campaignLinksTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;"><i class="fa fa-check"></i></th>
                            <th>Campaign Link</th>
                            <th>Added At</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $campaignLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="linkroom-bulk-delete-checkbox linkroom-form-check-input" value="<?php echo e($link->id); ?>" title="Select this campaign link">
                            </td>
                            <td>
                                <a href="<?php echo e($link->campaign_link); ?>" target="_blank" class="text-primary text-decoration-none text-truncate d-block" style="max-width: 400px;" title="<?php echo e($link->campaign_link); ?>">
                                    <?php echo e($link->campaign_link); ?>

                                </a>
                            </td>
                            <td><?php echo e($link->created_at->format('d M Y, H:i A')); ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="linkroom-btn linkroom-btn-sm linkroom-btn-info linkroom-copy-link" data-link="<?php echo e($link->campaign_link); ?>" title="Copy link to clipboard">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                    <form class="linkroom-delete-link-form" data-id="<?php echo e($link->id); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" class="linkroom-btn linkroom-btn-sm linkroom-btn-danger linkroom-delete-link" title="Delete this campaign link">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No campaign links found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="p-3">
                <?php echo e($campaignLinks->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
<?php else: ?>
<div class="linkroom-card">
    <div class="linkroom-card-body text-center">
        <h3 class="text-danger mb-3">Customer Not Found</h3>
        <p>Please select a customer to manage their campaign links.</p>
        <a href="<?php echo e(route('admin.ads.list')); ?>" class="linkroom-btn linkroom-btn-primary">Go Back</a>
    </div>
</div>
<?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Notification Function
    function showNotification(message, isError = false) {
        const notification = document.createElement('div');
        notification.className = `linkroom-notification ${isError ? 'linkroom-error' : 'linkroom-success'}`;
        notification.innerText = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Copy Link Functionality
    document.querySelectorAll('.linkroom-copy-link').forEach(button => {
        button.addEventListener('click', () => {
            const link = button.dataset.link;
            navigator.clipboard.writeText(link)
                .then(() => showNotification('Link copied to clipboard!'))
                .catch(() => showNotification('Failed to copy link.', true));
        });
    });

    // Individual Delete Functionality
    document.querySelectorAll('.linkroom-delete-link').forEach(button => {
        button.addEventListener('click', () => {
            const form = button.closest('.linkroom-delete-link-form');
            const linkId = form.dataset.id;

            if (confirm('Are you sure you want to delete this link?')) {
                fetch(`/admin/link/${linkId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message);
                            form.closest('tr').remove();
                            updateBulkDeleteCount();
                        } else {
                            showNotification(data.message || 'Failed to delete link.', true);
                        }
                    })
                    .catch(err => {
                        console.error('Error deleting link:', err);
                        showNotification('An error occurred. Please try again.', true);
                    });
            }
        });
    });

    // Bulk Delete Functionality
    const bulkDeleteButton = document.getElementById('bulk-delete-btn');
    const checkboxes = document.querySelectorAll('.linkroom-bulk-delete-checkbox');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');

    function updateBulkDeleteCount() {
        const selectedCount = document.querySelectorAll('.linkroom-bulk-delete-checkbox:checked').length;
        bulkDeleteButton.textContent = `Bulk Delete (${selectedCount})`;
        bulkDeleteButton.title = `Delete ${selectedCount} selected campaign links`;
    }

    selectAllCheckbox.addEventListener('change', () => {
        checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
        updateBulkDeleteCount();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteCount);
    });

    bulkDeleteButton.addEventListener('click', () => {
        const selectedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
        if (selectedCheckboxes.length === 0) {
            showNotification('Please select at least one link to delete.', true);
            return;
        }

        if (confirm('Are you sure you want to delete the selected links?')) {
            const ids = selectedCheckboxes.map(checkbox => checkbox.value);
            fetch('<?php echo e(route('admin.link.bulkDelete')); ?>', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ids }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message);
                        selectedCheckboxes.forEach(checkbox => checkbox.closest('tr').remove());
                        updateBulkDeleteCount();
                        selectAllCheckbox.checked = false;
                    } else {
                        showNotification(data.message || 'Failed to delete links.', true);
                    }
                })
                .catch(err => {
                    console.error('Error deleting links:', err);
                    showNotification('An error occurred. Please try again.', true);
                });
        }
    });

    // Date Range Filter
    $('#dateRangeFilter').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD',
            applyLabel: 'Apply',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        },
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
    });

    // Set initial value if dates are present in URL
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date');
    const endDate = urlParams.get('end_date');
    if (startDate && endDate) {
        $('#dateRangeFilter').val(`${startDate} - ${endDate}`);
    }

    $('#dateRangeFilter').on('apply.daterangepicker', function(ev, picker) {
        const startDate = picker.startDate.format('YYYY-MM-DD');
        const endDate = picker.endDate.format('YYYY-MM-DD');
        $(this).val(`${startDate} - ${endDate}`);
        filterLinks(startDate, endDate);
    });

    $('#dateRangeFilter').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        filterLinks(null, null);
    });

    function filterLinks(startDate, endDate) {
        const customerId = '<?php echo e($customer->id ?? ''); ?>';
        let url = `/admin/link-store-room/${customerId ? customerId : ''}`;
        
        // Only append date parameters if both dates are provided
        if (startDate && endDate) {
            url += `?start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`;
        }
        
        window.location.href = url;
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/link-store-room.blade.php ENDPATH**/ ?>