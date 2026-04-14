

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- Bootstrap CSS for styling and responsiveness -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


<style>
    /* Custom Styles for a Modern Look */
    body {
        background-color: #f4f4f4;
        font-family: 'Arial', sans-serif;
    }
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        border-radius: 5px; 
    }
    .card-header2 {
        background-color: #0d3569;
        color: white;
        font-size: 20px;
        padding: 10px 15px;
    }
    .card-body2 {
        padding: 15px;
    }
    .btn-primary, .btn-success {
        margin-bottom: 10px;
    }
    .table-responsive {
        margin-top: 20px;
    }
    .table thead th {
        background-color: #0d3569;
        color: white;
    }
    @media screen and (max-width: 768px) {
        .card-header2 h3 {
            font-size: 18px;
        }
    }
    .form-custom-style .form-group {
        margin-bottom: 15px;
    }
    .form-custom-style .form-label {
        color: #fff;
    }
    .form-custom-style .form-control {
        background-color: #f8f9fa;
        color: #495057;
    }
    .btn-primary {
        color: #fff;
        background-color: #646564;
        border-color: #0d3569;
    }
    .btn-primary:hover {
        background-color: #0b2e5a;
        border-color: #0b2e5a;
    }
    /* Responsive adjustments for smaller screens */
    @media (max-width: 768px) {
        .form-custom-style .col-md-2, .form-custom-style .col-md-3, .form-custom-style .col-md-1 {
            width: 100%;
            max-width: none;
        }
    }
</style>
<div class="container-fluid">
    <div class="card my-3">
        <div class="card-header2 d-flex justify-content-between align-items-center">
            <h3>Item List</h3>
            <!-- Removed the Add New button as we will have the form in the table -->
        </div>
        <div class="card-body2">
            <form action="<?php echo e(route('search_item')); ?>" method="get" class="mb-4">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <input type="text" name="search" placeholder="Search by item name" class="form-control">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Selling Price</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Add Item Form Row -->
                        <tr>
                            <form method="post" action="<?php echo e(url('/admin/dashboard/item/add')); ?>">
                                <?php echo csrf_field(); ?>
                                <td><input type="text" class="form-control" name="name" placeholder="Name" required></td>
                                <td><input type="text" class="form-control" name="unit" placeholder="Unit" required></td>
                                <td><input type="number" step="0.01" class="form-control" name="selling_price" placeholder="Selling Price" required></td>
                                <td><input type="text" class="form-control" name="description" placeholder="Description" required></td>
                                <td><button type="submit" class="btn btn-primary">Add Item</button></td>
                            </form>
                        </tr>
                        <!-- Existing Items -->
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->name); ?></td>
                            <td><?php echo e($item->unit); ?></td>
                            <td><?php echo e($item->selling_price); ?></td>
                            <td><?php echo e($item->description); ?></td>
                            <td>
                                <a href="<?php echo e(url('/admin/dashboard/item/edit/'. $item->id)); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <form action="<?php echo e(url('/admin/dashboard/item/delete/'. $item->id)); ?>" method="get" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('GET'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($items->appends(request()->query())->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/item/list.blade.php ENDPATH**/ ?>