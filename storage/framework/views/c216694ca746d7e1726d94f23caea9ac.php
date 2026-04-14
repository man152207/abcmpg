

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <h3>ADD Item</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo e(url('/admin/dashboard/item/add')); ?>" class="form-inline">
                <?php echo csrf_field(); ?>
                <!-- Name Field -->
                <div class="form-group mb-2">
                    <label for="name" class="sr-only">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                </div>

                <!-- Unit Field -->
                <div class="form-group mx-sm-3 mb-2">
                    <label for="unit" class="sr-only">Unit</label>
                    <input type="text" class="form-control" id="unit" name="unit" placeholder="Unit" required>
                </div>

                <!-- Selling Price Field -->
                <div class="form-group mx-sm-3 mb-2">
                    <label for="selling_price" class="sr-only">Selling Price</label>
                    <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price" placeholder="Selling Price" required>
                </div>

                <!-- Description Field -->
                <div class="form-group mx-sm-3 mb-2">
                    <label for="description" class="sr-only">Description</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Description" required>
                </div>

                <!-- Add Item Button -->
                <button type="submit" class="btn btn-primary mb-2">Add Item</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/item/add.blade.php ENDPATH**/ ?>