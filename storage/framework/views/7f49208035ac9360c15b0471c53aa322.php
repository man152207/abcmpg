<!-- resources/views/admin/customer/update.blade.php -->

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$customers = DB::select('select * from customers');
?>



<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Update Ad</h3>
        </div>
        <div class="card-body">
            <form action="<?php echo e(url('/admin/dashboard/ads/edit/'. $ad->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="customer">Customer:</label>
                    <select class="form-control" id="customer" name="customer" required>
                        <option value="">Select Customer</option>
                        <!-- Add options dynamically from your database -->
                        <!-- Example: -->
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($customer->phone); ?>" <?php echo e($ad->customer == $customer->phone ? 'selected' : ''); ?>>
                            <?php echo e($customer->phone); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="USD">USD:</label>
                    <input type="number" step="0.01" class="form-control" id="USD" name="USD" value="<?php echo e($ad->USD); ?>" required>
                </div>

                <div class="form-group">
                    <label for="Rate">Rate:</label>
                    <input type="number" step="0.01" class="form-control" id="Rate" name="Rate" value="<?php echo e($ad->Rate); ?>" required>
                </div>

                <div class="form-group">
                    <label for="NRP">NRP:</label>
                    <input type="number" step="0.01" class="form-control" value="<?php echo e($ad->NRP); ?>" id="NRP" name="NRP" required>
                </div>

                <div class="form-group">
                    <label for="Ad_Account">Ad Account:</label>
                    <input type="text" class="form-control" value="<?php echo e($ad->Ad_Account); ?>" id="Ad_Account" name="Ad_Account" required>
                </div>

                <div class="form-group">
                    <label>Payment Method:</label>
                    <select class="form-control" id="<?php echo e($ad->id.'baki'); ?>" name="Payment" required onchange="togglebakiField('<?php echo e($ad->id); ?>baki')">
                        <?php $__currentLoopData = ['Pending', 'Paused', 'FPY Received', 'eSewa Received', 'Baki']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($Payment); ?>" <?php echo e(@$ad->Payment == $Payment ? 'selected' : ''); ?>>
                            <?php echo e($Payment); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- <option value="" <?php echo e($ad->Status === '$status' ? 'selected' : ''); ?>>Select Status</option>
                        <option value="No Payment" <?php echo e($ad->Status === 'No Payment' ? 'No Payment' : ''); ?>>No Payment</option>
                        <option value="Advance" <?php echo e($ad->Status === 'Advance' ? 'Advance' : ''); ?>>Advance</option>
                        <option value="Paid" <?php echo e($ad->Status === 'Paid' ? 'Paid' : ''); ?>>Paid</option> -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Duration:</label>
                    <input type="number" id="Duration" name="Duration" class="form-control" value="<?php echo e($ad->Duration); ?>" required>
                </div>

                <div class="form-group">
                    <label for="Quantity">Quantity:</label>
                    <input type="number" class="form-control" value="<?php echo e($ad->Quantity); ?>" id="Quantity" name="Quantity" required>
                </div>

                <div class="form-group">
                    <label for="start_date">Status:</label>
                    <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                        <?php $__currentLoopData = ['New', 'Extend', 'Both']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php echo e(@$ad->Status == $status ? 'selected' : ''); ?>>
                            <?php echo e($status); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <!-- <option value="" <?php echo e($ad->Status === '$status' ? 'selected' : ''); ?>>Select Status</option>
                        <option value="No Payment" <?php echo e($ad->Status === 'No Payment' ? 'No Payment' : ''); ?>>No Payment</option>
                        <option value="Advance" <?php echo e($ad->Status === 'Advance' ? 'Advance' : ''); ?>>Advance</option>
                        <option value="Paid" <?php echo e($ad->Status === 'Paid' ? 'Paid' : ''); ?>>Paid</option> -->
                    </select>
                </div>

                <?php if($ad->advance == ''): ?>
                <div class="form-group" id="<?php echo e($ad->id.'bakifield'); ?>" style="display: none;">
                    <label for="Quantity">Baki:</label>
                    <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                <?php else: ?>
                <div class="form-group" id="<?php echo e($ad->id.'bakifield'); ?>">
                    <label for="Quantity">Baki:</label>
                    <input type="text" class="form-control" id="advanceAmount" value="<?php echo e($ad->advance); ?>" name="advance">
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="Ad_Nature_Page">Ad Nature/Page:</label>
                    <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" value="<?php echo e($ad->Ad_Nature_Page); ?>" required>
                </div>

                <div class="form-group">
                    <input type="hidden" class="form-control" value="<?php echo e(auth('admin')->user()->name); ?>,(<?php echo e(auth('admin')->user()->id); ?>) " id="admin" name="admin" required>
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
<script src="<?php echo e(asset('https://code.jquery.com/jquery-3.3.1.slim.min.js')); ?>"></script>
<script src="<?php echo e(asset('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js_'); ?>
<!-- Include Select2 CSS -->
<link href="<?php echo e(asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css')); ?>" rel="stylesheet" />

<!-- Include jQuery (required for Select2) -->
<script src="<?php echo e(asset('https://code.jquery.com/jquery-3.6.4.min.js')); ?>"></script>

<!-- Include Select2 JS -->
<script src="<?php echo e(asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js')); ?>"></script>
<script>
    $(document).ready(function() {
        $('#customer').select2({
            placeholder: 'Select Customer',
            allowClear: true,
            data: <?php echo json_encode($customers, 15, 512) ?>,
            // minimumInputLength: 1 // Minimum characters to start a search
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/ads_update.blade.php ENDPATH**/ ?>