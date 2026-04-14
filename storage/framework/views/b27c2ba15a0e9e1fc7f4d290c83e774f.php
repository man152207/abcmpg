<!-- resources/views/update.blade.php -->



<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Update Invoice</h2>

    <form method="post" action="<?php echo e(URL('/admin/dashboard/invoice/update/' . $invoice->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('POST'); ?> <!-- Use the appropriate HTTP method for updates -->

        <div class="form-group">
            <label for="customer">Customer:</label>
            <select class="form-control" id="customer" name="customer" required>
                <option value="">Select Customer</option>
                <!-- Add options dynamically from your database -->
                <!-- Example: -->
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($customer->phone); ?>" <?php echo e($invoice->customer == $customer->phone ? 'selected' : ''); ?>><?php echo e($customer->phone); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input class="form-control" type="date" name="date" value="<?php echo e($invoice->date); ?>" required>
        </div>

        <table style=" width:100%">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Tax</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="invoice_items">
                <!-- Rows for dynamically added items will go here -->
                <?php $__currentLoopData = $invoice_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <select class="form-control" name="items[]">
                            <option value="">Select Item</option>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($itemOption->id); ?>" <?php echo e($item->Item_id == $itemOption->id ? 'selected' : ''); ?>><?php echo e($itemOption->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>

                    <td><input class="form-control" type="number" name="quantities[]" value="<?php echo e($item->quantity); ?>"></td>
                    <td><input class="form-control" type="number" step="0.01" name="rate[]" value="<?php echo e($item->rate); ?>"></td>
                    <td><input class="form-control" type="number" step="0.01" name="tax[]" value="<?php echo e($item->tax); ?>"></td>
                    <td><input class="form-control" type="number" step="0.01" name="amount[]" value="<?php echo e($item->amount); ?>"></td>
                    <td><button class="form-control" type="button" onclick="removeRow(this)">Remove</button></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <button class="btn btn-success" type="button" onclick="addRow()">Add Item</button>

        <div class="form-group">
            <label for="Description">Description:</label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10" required><?php echo e($invoice->description); ?></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Update Invoice</button>
    </form>
</div>

<script>
    // Assuming you have a variable $items containing the list of items from the backend
    var items = <?php echo json_encode($items, 15, 512) ?>;

    function addRow() {
        // Create a new row with select option for items
        var newRow = '<tr>' +
            '<td><select class="form-control" name="items[]">' +
            '<option value="">Select Item</option>';

        // Add options for each item
        items.forEach(function(item) {
            newRow += '<option value="' + item.id + '">' + item.name + '</option>';
        });

        newRow += '</select></td>' +
            '<td><input class="form-control" type="number" name="quantities[]"></td>' +
            '<td><input class="form-control" type="number" step="0.01" name="rate[]"></td>' +
            '<td><input class="form-control" type="number" step="0.01" name="tax[]"></td>' +
            '<td><input class="form-control" type="number" name="amount[]"></td>' +
            '<td><button type="button" onclick="removeRow(this)">Remove</button></td>' +
            '</tr>';

        // Append the new row to the table
        document.getElementById('invoice_items').insertAdjacentHTML('beforeend', newRow);
    }

    function removeRow(button) {
        // Remove the row when the "Remove" button is clicked
        button.closest('tr').remove();
    }
</script>
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
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/invoice/update.blade.php ENDPATH**/ ?>