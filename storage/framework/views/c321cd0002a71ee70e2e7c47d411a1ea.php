<!-- resources/views/list.blade.php -->



<?php $__env->startSection('content'); ?>
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
        .form-custom-style .col-md-2, .form-custom-style .col-md-3, .form-custom-style .col-md-1 {
            width: 100%;
            max-width: none;
        }
        .form-control {
            width: 100%;
        }
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
</style>

<div class="container-fluid"> <!-- Full-width container -->
    <div class="card">
        <div class="card-header2">
            <h4>Invoice List</h4> <!-- Smaller header -->
        </div>
        <div class="card-body2">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Invoice Number</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($invoice->id); ?></td>
                            <td><?php echo e($invoice->invoice_number); ?></td>
                            <td><?php echo e($invoice->customer); ?></td>
                            
                            <?php
                            $items = \DB::table('invoice__items')->where('invoice_id', $invoice->id)->get();
                            $t_amount = 0;
                            foreach ($items as $item) {
                                $t_amount += $item->amount;
                            }
                            ?>

                            <td><?php echo e($t_amount); ?></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example"> <!-- Button group for alignment -->
                                    <a href="<?php echo e(URL('admin/dashboard/invoice/update/'. $invoice->id)); ?>" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this invoice?')">Edit</a>
                                    <form action="<?php echo e(URL('admin/dashboard/invoice/delete/'. $invoice->id)); ?>" method="post" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('POST'); ?>
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</button>
                                    </form>
                                    <a href="<?php echo e(URL('/invoice/show_invoice/'. $invoice->id)); ?>" class="btn btn-primary">View</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/invoice/list.blade.php ENDPATH**/ ?>