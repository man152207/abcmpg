
<?php $__env->startSection('title', 'All Funds | MPG Solution'); ?>

<?php $__env->startSection('content'); ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    .table-container {
        display: flex;
        gap: 2px;
        width: 100%;
    }


    .table-headings {
        display: flex;
        gap: 2px;
        width: 100%;
    }

       .div-container-2 {
        width: 37.5%;
        display: flex;
        align-items: flex-start;
        border: 1px solid #dee2e6;
        overflow-x: auto;

    }

    .div-container-3 {
        width: 37.5%;
        display: flex;
        align-items: flex-start;
        border: 1px solid #dee2e6;
        overflow-x: auto;
    }

    .box2,
    .box3 {
        width: 300px !important;
        border-right: 1px solid #dee2e6;

    }

    .box2:last-child,
    .box3:last-child {
        border-right: none;
    }

    .table td {
        padding: 5px !important;
       
}
</style>
<style>
    .input-group {
        display: flex;
        align-items: center;
    }

    #card_number {
        padding-right: 30px;
        /* Space for the icon */
    }

    .btn-icon {
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        /* background-color: #007bff; */
        /* color: #fff; */
        cursor: pointer;
        border: none;
        padding: 5px 10px;
    }


.table-container {
    display: flex;
    flex-wrap: wrap;
    font-size: 14px;
}

.div-container-1 {
    flex: 0 0 23%;
    background-color: #f9f9f9; /* For visualization */
    /* Other styling as needed */
}
/* Enhance overall table appearance */
.table {
    font-family: 'Poppins', sans-serif; /* Using Poppins for a modern, clean look */
    border-collapse: collapse;
    width: 100%;
}

.table th, .table td {
    border: 1px solid #ddd; /* Light borders for each cell for better readability */
    text-align: left;
    padding: 8px;
}

/* Style for table headers */
.table thead th {
    background-color: #004085; /* Deep blue for contrast */
    color: white;
    font-size: 15px; /* Slightly larger font for headers */
}

/* Row styling for better readability */
.table tbody tr:nth-child(even){background-color: #f2f2f2;} /* Zebra striping for rows */

.table tbody tr:hover {background-color: #ddd;} /* Hover effect for rows */

/* Form input styling for consistency and attractiveness */
.input[type="text"], .form-control {
    width: 95%; /* Slightly less than full width for padding */
    padding: 10px; /* Comfortable padding inside inputs */
    margin: 5px 0; /* Space out elements */
    display: inline-block;
    border: 1px solid #ccc; /* Subtle border */
    border-radius: 4px; /* Rounded corners for modern feel */
    box-sizing: border-box; /* Box-sizing to include padding in width */
    font-family: 'Poppins', sans-serif; /* Consistent font with the table */
}

/* Style for the submit button */
.btn-primary {
    width: 100%;
    background-color: #007bff; /* Bootstrap primary blue */
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px; /* Rounded corners */
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #0056b3; /* Darker shade on hover */
}


.div-container-2, .div-container-3 {
    flex: 1; /* Allow these containers to grow */
    background-color: #e8e8e8; /* For visualization */
    transition: flex-grow 0.5s ease; /* Smooth transition for expanding */
    cursor: pointer; /* Indicate they are clickable */
    position: relative; /* For absolute positioning of expanded content */
}

.div-container-2.expanded, .div-container-3.expanded {
    flex-grow: 15; /* Expand the container */
    z-index: 2; /* Ensure it's above other content */
}

/* General Container Styling */
.container {
    width: 500px; /* As per your requirement */
    margin: 0 auto; /* Centering the container */
    padding: 0; /* Adjust padding as needed */
}

/* Card Styling */
.card {
    background-color: #0d3569; /* A nice shade of blue for background */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    color: #ffffff; /* White text color for contrast */
}

.card-body {
    padding: 20px; /* Ample padding for content */
}

/* Header Styling */
.card h4 {
    font-size: 1.2rem; /* Slightly larger font size */
    text-align: center; /* Center-align the text */
    margin-bottom: 0; /* Remove bottom margin for tighter spacing */
}

/* Optional: If you want to use the commented-out headers */
.head-div1, .head-div2 {
    background-color: #82ccdd; /* Different background color */
    padding: 10px;
    text-align: center;
    border-radius: 4px;
    margin: 5px 0; /* Spacing between headers */
}

.head-div1 h2, .head-div2 h2 {
    color: #ffffff; /* White text color */
    margin: 0; /* Remove default margins */
}
/* Add styling to the <tfoot> element */
.table tfoot {
    background-color: #f0f0f0; /* Background color */
    text-align: right; /* Align text to the right */
    font-weight: 400; /* Bold font for the total amount */
    border-top: 2px solid #ddd; /* Add a top border for separation */
    padding: 1px; /* Padding for spacing */
    display: table-caption;
}
    a {
    text-decoration: none; /* Remove underline */
    color: #004085; /* Default link color */
    transition: color 0.3s ease; /* Smooth transition for hover effect */
}

a:hover {
    color: #0056b3; /* Change color on hover */
}
table a {
    text-decoration: none; /* Remove underline for links inside the table */
}

table a:hover {
    text-decoration: none; /* Ensure underline doesn't appear on hover */
}


</style>
<div>
    <!-- <div class="table-headings">
        <div class="head-div">

        </div>
        <div class="head-div1">
            <h3>Credit</h3>
        </div>
        <div class="head-div2">
            <h3>Debit</h3>
        </div>
    </div> -->
    <div class="container ml-0 mt-0 mb-0" style="width: 500px;">
        <div class="card">
            <div class="card-body">
                <h4>Total Amount in cards : $<?php echo e($summary->totalUSD); ?></h4>
            </div>
        </div>
    </div>
        <?php if(session('status')): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo e(session('status')); ?>

        </div>
        <?php endif; ?>
    </div>
    <div style="display: flex;">
        <div style="margin-left: 10px; background: #646564;color: white;padding-top: 3px;padding-bottom: 3px;padding-left: 7px;padding-right: 7px;border-radius: 5px;">
            <h3>Card Details</h33>
        </div>
        <div style="margin-left: 33.33%; background: #646564;color: white;padding-top: 3px;padding-bottom: 3px;padding-left: 7px;padding-right: 7px;border-radius: 5px;">
            <h3>Credit</h3>
        </div>
        <div style="margin-left: 33.33%; background: #646564;color: white;padding-top: 3px;padding-bottom: 3px;padding-left: 7px;padding-right: 7px;border-radius: 5px;">
            <h3>Debit</h3>
        </div>
    </div>
    <div class="table-container">
        <div class="div-container-1">
            <table class="box1 table">
                <thead>
                    <th>Name</th>
                    <th>Account</th>
                    <th>Balance</th>
                </thead>
                <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><a href="<?php echo e(route('card.details', $card->id)); ?>"><?php echo e($card->name); ?></a></td>
                    <td><?php echo e($card->card_number); ?></td>
                    <td>$ <?php echo e($card->USD); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <form method="post" action="<?php echo e(url('/admin/dashboard/card/add')); ?>">
                    <?php echo csrf_field(); ?>
                    <tr>
                        <td>
                            <div class="mb-3">
                                <!-- <label for="name" class="form-label">Name</label> -->
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name')); ?>" placeholder="Name" required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </td>
                        <td>
                            <div class="mb-3">
                                <!-- <label for="card_number" class="form-label">Card Number</label> -->
                                <input type="text" class="form-control <?php $__errorArgs = ['card_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="card_number" name="card_number" value="<?php echo e(old('card_number')); ?>" placeholder="Account Number" required>
                                <?php $__errorArgs = ['card_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </td>
                        <td>
                            <div class="mb-3">
                                <!-- <label for="USD" class="form-label">USD</label> -->
                                <input type="text" step="0.01" class="form-control <?php $__errorArgs = ['USD'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="USD" name="USD" value="<?php echo e(old('USD')); ?>" placeholder="Balance" required>
                                <?php $__errorArgs = ['USD'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><button style="width: 100%;" type="submit" class="btn btn-primary">Add Card</button></td>
                    </tr>
                </form>

            </table>
        </div>

       <div class="div-container-2">
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $totalAmount = 0; ?>

        <table class="box2 table">
            <thead>
                <th style="min-width: 50px;"><?php echo e($card->name); ?></th>
            </thead>
            <tbody>
                <?php $__currentLoopData = $credits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($credit->card_id == $card->id): ?>
                        <tr>
                            <td>$ <?php echo e($credit->USD); ?></td>
                        </tr>
                        <?php $totalAmount += $credit->USD; ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Add Credit Form Row -->
                <tr>
                    <td>
                        <form method="post" action="<?php echo e(url('/admin/dashboard/credit/add')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="text" step="0.01" class="form-control <?php $__errorArgs = ['USD'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="USD" name="USD" value="<?php echo e(old('USD')); ?>" required>
                            <div class="input-group">
                                <input type="hidden" id="card_number" name="card_number" value="<?php echo e($card->card_number); ?>">
                                <button style="display: none;" type="submit" class="btn-icon"><i class="fa fa-plus"></i></button>
                            </div>
                        </form>
                    </td>
                </tr>
            </tbody>

            <!-- Fixed Total Amount Row -->
            <tfoot>
                <tr>
                    <td style="text-align: right; font-weight: 400;">$<?php echo e(number_format($totalAmount, 2)); ?></td>
                </tr>
            </tfoot>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="div-container-3">
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $totalAmount = 0; ?>

        <table class="box3 table">
            <thead>
                <th style="min-width: 50px;"><?php echo e($card->name); ?></th>
            </thead>
            <tbody>
                <?php $__currentLoopData = $debits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($debit->card_id == $card->id): ?>
                        <tr>
                            <td>$ <?php echo e($debit->USD); ?></td>
                        </tr>
                        <?php $totalAmount += $debit->USD; ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- Add Debit Form Row -->
                <tr>
                    <td>
                        <form method="post" action="<?php echo e(url('/admin/dashboard/debit/add')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="text" step="0.01" class="form-control <?php $__errorArgs = ['USD'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="USD" name="USD" value="<?php echo e(old('USD')); ?>" required>
                            <div class="input-group">
                                <input type="hidden" id="card_number" name="card_number" value="<?php echo e($card->card_number); ?>">
                                <button style="display: none;" type="submit" class="btn-icon"><i class="fa fa-plus"></i></button>
                            </div>
                        </form>
                    </td>
                </tr>
            </tbody>

            <!-- Fixed Total Amount Row -->
            <tfoot>
                <tr>
                    <td style="text-align: right; font-weight: 400;">$<?php echo e(number_format($totalAmount, 2)); ?></td>
                </tr>
            </tfoot>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

        </div>
    </div>


</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var container2 = document.querySelector('.div-container-2');
    var container3 = document.querySelector('.div-container-3');

    container2.addEventListener('click', function(event) {
        if (!isEditingTable(event.target)) {
            this.classList.toggle('expanded');
            container3.classList.remove('expanded');
        }
    });

    container3.addEventListener('click', function(event) {
        if (!isEditingTable(event.target)) {
            this.classList.toggle('expanded');
            container2.classList.remove('expanded');
        }
    });

    // Function to check if the clicked element is an input or textarea
    function isEditingTable(element) {
        return element.tagName === 'INPUT' || element.tagName === 'TEXTAREA';
    }
});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/card/all_in_one.blade.php ENDPATH**/ ?>