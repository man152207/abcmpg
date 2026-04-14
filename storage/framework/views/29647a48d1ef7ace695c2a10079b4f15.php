<!-- resources/views/admin/customer/update.blade.php -->
<?php

use App\Models\Card;

$cards = Card::select('*')->get();
?>
 <!-- Assuming you have a layout file, adjust as needed -->

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Update Client</h3>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo e(url('/admin/dashboard/client/edit/'. $client->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo e($client->name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="USD">USD:</label>
                        <input type="number" step="0.01" value="<?php echo e($client->USD); ?>" class="form-control" id="USD" name="USD" required>
                    </div>

                    <div class="form-group">
                        <label for="Rate">Rate:</label>
                        <input type="number" step="0.01" value="<?php echo e($client->Rate); ?>" class="form-control" id="Rate" name="Rate" required>
                    </div>

                    <div class="form-group">
                        <label for="NRP">NRP:</label>
                        <input type="number" step="0.01" value="<?php echo e($client->NRP); ?>" class="form-control" id="NRP" name="NRP" required>
                    </div>

                    <div class="form-group" style="color: black;">
                        <label for="Ad_Account">Account:</label>
                        <select class="form-control" id="Ad_Account" name="account" required>
                            <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($card->card_number); ?>" <?php echo e(@$client->account == $card->card_number ? 'selected' : ''); ?>>
                                <?php echo e($card->card_number); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['account'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Client</button>
                </form>
            </div>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/client/update.blade.php ENDPATH**/ ?>