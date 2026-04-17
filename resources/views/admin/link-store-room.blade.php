
@extends('admin.layout.layout')
@section('title', 'Link Management | MPG Solution')
@section('content')


<div class="linkroom-container-fluid">
    <!-- Page Header -->
    <div class="linkroom-header-section">
        <div>
            <h1>Campaign Link Management</h1>
            <p>Effortlessly manage customer campaign links with advanced features.</p>
        </div>
        @if ($customer)
        <div class="linkroom-customer-highlight">
            <a href="{{ route('customer.details', ['id' => $customer->id]) }}" style="text-decoration: none; color: inherit;">
                <h4>{{ $customer->name }}</h4>
                <p>{{ $customer->display_name }} | {{ $customer->phone }}</p>
            </a>
        </div>
        @endif
    </div>

    @if ($customer)
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
                        @forelse ($campaignLinks as $link)
                        <tr>
                            <td>
                                <input type="checkbox" class="linkroom-bulk-delete-checkbox linkroom-form-check-input" value="{{ $link->id }}" title="Select this campaign link">
                            </td>
                            <td>
                                <a href="{{ $link->campaign_link }}" target="_blank" class="text-primary text-decoration-none text-truncate d-block" style="max-width: 400px;" title="{{ $link->campaign_link }}">
                                    {{ $link->campaign_link }}
                                </a>
                            </td>
                            <td>{{ $link->created_at->format('d M Y, H:i A') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="linkroom-btn linkroom-btn-sm linkroom-btn-info linkroom-copy-link" data-link="{{ $link->campaign_link }}" title="Copy link to clipboard">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                    <form class="linkroom-delete-link-form" data-id="{{ $link->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="linkroom-btn linkroom-btn-sm linkroom-btn-danger linkroom-delete-link" title="Delete this campaign link">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No campaign links found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="p-3">
                {{ $campaignLinks->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@else
<div class="linkroom-card">
    <div class="linkroom-card-body text-center">
        <h3 class="text-danger mb-3">Customer Not Found</h3>
        <p>Please select a customer to manage their campaign links.</p>
        <a href="{{ route('admin.ads.list') }}" class="linkroom-btn linkroom-btn-primary">Go Back</a>
    </div>
</div>
@endif
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
            fetch('{{ route('admin.link.bulkDelete') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
        const customerId = '{{ $customer->id ?? '' }}';
        let url = `/admin/link-store-room/${customerId ? customerId : ''}`;
        
        // Only append date parameters if both dates are provided
        if (startDate && endDate) {
            url += `?start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`;
        }
        
        window.location.href = url;
    }
});
</script>
@endsection