<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$customers = DB::select('select * from customers');
$ad_accounts = DB::select('select * from ad__accounts');
?>



<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>ADD Ad</h3>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('storeAd')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label for="customer">Customer:</label>
                    <select class="form-control" id="customer" name="customer" required>
                        <option value="">Select Customer</option>
                        <!-- Add options dynamically from your database -->
                        <!-- Example: -->
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($customer->phone); ?>"><?php echo e($customer->phone); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="USD">USD:</label>
                    <input type="number" step="0.01" class="form-control" id="USD" name="USD" required>
                </div>

                <div class="form-group">
                    <label for="Rate">Rate:</label>
                    <input type="number" step="0.01" class="form-control" id="Rate" name="Rate" required>
                </div>

                <div class="form-group">
                    <label for="NRP">NRP:</label>
                    <input type="number" step="0.01" class="form-control" id="NRP" name="NRP" required>
                </div>

                <div class="form-group">
                    <label for="Ad_Account">Ad Account:</label>
                    <input class="form-control" id="Ad_Account" name="Ad_Account" required>
                </div>

                <div class="form-group">
                    <label for="Payment">Payment Status:</label>
                    <!-- <input type="text" class="form-control" id="Payment" name="Payment" required> -->
                    <select class="form-control" id="Payment" name="Payment" required onchange="togglebakiField()">
                        <option value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Paused">Paused</option>
                        <option value="FPY Received">FPY Received</option>
                        <option value="eSewa Received">eSewa Received</option>
                        <option value="Baki">Baki</option>
                    </select>
                </div>
                <div class="form-group" id="bakifield" style="display: none;">
                    <label for="bakiAmount">Baki Amount:</label>
                    <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div>
                <!-- <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> -->

                <div class="form-group">
                    <label for="start_date">Duration:</label>
                    <input type="number" id="duration" name="Duration" class="form-control" required>
                </div>

                <!-- <div class="form-group">
                    <label for="Duration">Duration:</label>
                    <input type="number" class="form-control" id="Duration" name="Duration" required>
                </div> -->

                <div class="form-group">
                    <label for="Quantity">Quantity:</label>
                    <input type="number" class="form-control" id="Quantity" name="Quantity" required>
                </div>

                <div class="form-group">
                    <label for="Status">Status:</label>
                    <select class="form-control" id="Status" name="Status" required onchange="toggleAdvanceField()">
                        <option value="">Select Status</option>
                        <option value="New">New</option>
                        <option value="Extend">Extend</option>
                        <option value="Both">Both</option>
                    </select>
                </div>

                <!-- <div class="form-group" id="advanceField" style="display: none;">
                    <label for="advanceAmount">Advance Amount:</label>
                    <input type="text" class="form-control" id="advanceAmount" value="" name="advance">
                </div> -->

                <div class="form-group">
                    <label for="Ad_Nature_Page">Ad Nature/Page:</label>
                    <input type="text" class="form-control" id="Ad_Nature_Page" name="Ad_Nature_Page" required>
                </div>

                <div class="form-group">
                    <input type="hidden" class="form-control" value="<?php echo e(auth('admin')->user()->name); ?>,(<?php echo e(auth('admin')->user()->id); ?>) " id="admin" name="admin" required>
                </div>
                <!-- <input type="hidden" name="is_complete" value="0" id=""> -->
                <button type="submit" class="btn btn-primary">Submit</button>
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
<!-- <script>
    function toggleAdvanceField() {
        var statusSelect = document.getElementById("Status");
        var advanceField = document.getElementById("advanceField");

        if (statusSelect.value === "Advance") {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
        }
    }
</script> -->
<script>
    function togglebakiField() {
        var statusSelect = document.getElementById("Payment");
        var advanceField = document.getElementById("bakifield");

        if (statusSelect.value === "Baki") {
            advanceField.style.display = "block";
        } else {
            advanceField.style.display = "none";
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
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/ads.blade.php ENDPATH**/ ?>