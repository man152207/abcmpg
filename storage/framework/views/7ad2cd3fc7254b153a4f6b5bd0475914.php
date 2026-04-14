
<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* Global Styles */
    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .container {
        max-width: 100%;
        padding: 15px;
    }

    /* Summary Section */
    .summary-section {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        justify-content: space-between;
        padding:10px;
    }
    .summary-card {
        flex: 1;
        min-width: 250px;
        text-align: center;
        padding: 20px;
        color: white;
        border-radius: 10px;
        background-color: #2e4c72;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .summary-card h4 {
        font-size: 18px;
        margin-bottom: 10px;
    }
    .summary-card p {
        font-size: 22px;
        font-weight: bold;
    }

        /* Table Section */
    .table-section {
        margin-top: 20px;
        padding: 10px;
    }
    .table {
        width: 100%;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table th, .table td {
        padding: 15px;
        text-align: center;
        word-wrap: break-word;
        border: 1px solid #ddd;
    }
    .table th {
        background-color: #16a085;
        color: #fff;
    }
    .table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Profile Card */
    .custom-profile-card {
        background-color: #16a085;
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .custom-profile-card h3 {
        font-size: 24px;
        margin-bottom: 20px;
    }
    .custom-profile-card p {
        margin: 5px 0;
        font-size: 16px;
    }
            .top-summary-section {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .top-summary-card {
            flex: 1;
            min-width: 200px;
            text-align: center;
            padding: 20px;
            color: white;
            border-radius: 10px;
            background-color: #2e4c72;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .top-summary-card h4 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .top-summary-card p {
            font-size: 20px;
            font-weight: bold;
        }

        .due-date {
            font-size: 14px;
            font-weight: normal;
            color: #ffd700;
        }
        .due-date span {
    color: red;
    font-weight: bold;
    font-size: 16px;
}
    .invoice-section {
    margin-top: 0px;
    padding: 10px;
}
.invoice-section h3 {
    background-color: #3498db;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}
.invoice-section table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.invoice-section th, .invoice-section td {
    border: 1px solid #ddd;
    padding: 5px;
    text-align: left;
}
.invoice-section th {
    background-color: #f3f3f3;
    font-weight: bold;
}
.invoice-section .btn {
    margin-right: 5px;
}
        /* Badge Styles */
    .badge {
        display: inline-block;
        padding: 0.5em 0.75em;
        border-radius: 5px;
        font-size: 0.85rem;
        color: white;
        font-weight: bold;
    }

    .payment-status-pending {
        background-color: #ffc107; /* Yellow */
    }

    .payment-status-paused {
        background-color: #6c757d; /* Gray */
    }

    .payment-status-fpy-received {
        background-color: #28a745; /* Green */
    }

    .payment-status-esewa-received {
        background-color: #007bff; /* Blue */
    }

    .payment-status-baki {
        background-color: #17a2b8; /* Cyan */
    }

    .payment-status-paid {
        background-color: #28a745; /* Green */
    }

    .payment-status-refunded {
        background-color: #dc3545; /* Red */
    }

    .payment-status-cancelled {
        background-color: #343a40; /* Dark Gray */
    }

    .payment-status-overpaid {
        background-color: #6f42c1; /* Purple */
    }

    .payment-status-pv-adjusted {
        background-color: #20c997; /* Teal */
    }

    .btn
{
        display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.1rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
        /* Responsive Adjustments */
    @media (max-width: 768px) {
        .table-section {
            padding: 5px;
        }
        .table th, .table td {
            padding: 10px;
            font-size: 14px;
        }
    }
    @media (max-width: 480px) {
        .table th, .table td {
            padding: 8px;
            font-size: 12px;
        }
    }

}


</style>

<div class="container">
    <div class="top-summary-section">
        <!-- My Order -->
        <div class="top-summary-card" style="background-color: #16a085;">
            <h4>My Order</h4>
            <p>Rs.<?php echo e(number_format($myOrderAmount, 2)); ?></p>
        </div>

        <!-- Quantity -->
        <div class="top-summary-card" style="background-color: #3498db;">
            <h4>Quantity</h4>
    <p><?php echo e($quantity); ?></p>
        </div>

        <!-- Unpaid Invoice -->
        <div class="top-summary-card" style="background-color: #e74c3c;">
            <h4>Unpaid Invoice</h4>
            <p>Rs.<?php echo e(number_format($dueAmount, 2)); ?></p>
        </div>

        <!-- Paid Invoice -->
        <div class="top-summary-card" style="background-color: #2ecc71;">
            <h4>Paid Invoice</h4>
            <p>Rs.<?php echo e(number_format($paidInvoice, 2)); ?></p>
        </div>

        <!-- Due Amount with Due Date -->
        <div class="top-summary-card" style="background-color: #f39c12;">
    <h4>Due Amount</h4>
    <p>Rs.<?php echo e(number_format($dueAmount, 2)); ?></p>
  </div>
    </div>
</div>

    <!-- Monthly and Today's Summary -->
    <div class="summary-section">
        <div class="summary-card">
            <h4>Total NPR (This Month)</h4>
            <p>Rs.<?php echo e(number_format($totalNPRThisMonth, 2)); ?></p>
        </div>
        <div class="summary-card">
            <h4>Total Quantity (This Month)</h4>
            <p><?php echo e($totalQuantityThisMonth); ?></p>
        </div>
    </div>

    <!-- Month-wise Data -->
    
<div class="table-section">
    <!-- Heading with buttons -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
        <h3>All Month Summary</h3>
        <div>
            <a href="javascript:void(0)" class="btn btn-secondary" id="prev-btn" data-offset="<?php echo e($offset - 5); ?>">New</a>
            <a href="javascript:void(0)" class="btn btn-secondary" id="next-btn" data-offset="<?php echo e($offset + 5); ?>">Old</a>
        </div>
    </div>

    <!-- Table -->
    <table id="data-table" class="table">
        <thead>
            <tr>
                <th>Month</th>
                <th>NPR Amount</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $previousMonthsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($month); ?></td>
                    <td>Rs.<?php echo e(number_format($data['npr'], 2)); ?></td>
                    <td><?php echo e($data['quantity']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const dataTable = document.getElementById('data-table').getElementsByTagName('tbody')[0];

        function fetchData(offset) {
            fetch(`/customer/dashboard-data/${offset}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Clear current table rows
                    dataTable.innerHTML = '';

                    // Add new rows
                    Object.entries(data.data).forEach(([month, details]) => {
                        const row = dataTable.insertRow();
                        row.innerHTML = `
                            <td>${month}</td>
                            <td>Rs.${Number(details.npr).toLocaleString()}</td>
                            <td>${details.quantity}</td>
                        `;
                    });

                    // Update buttons' offset
                    prevBtn.setAttribute('data-offset', offset - 5);
                    nextBtn.setAttribute('data-offset', offset + 5);
                })
                .catch(err => console.error(err));
        }

        prevBtn.addEventListener('click', () => {
            const offset = parseInt(prevBtn.getAttribute('data-offset'));
            fetchData(offset);
        });

        nextBtn.addEventListener('click', () => {
            const offset = parseInt(nextBtn.getAttribute('data-offset'));
            fetchData(offset);
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customerlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/auth/dashboard.blade.php ENDPATH**/ ?>