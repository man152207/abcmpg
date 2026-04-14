
<?php $__env->startSection('title', 'Card Management | MPG Solution'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    /* Unique styling for the card list page */
    .cardlist {
        width: 100%;
        margin: 0 auto;
        max-width: 1600px; /* Expand to full width */
    }

    .cardlist .card-header {
        background-color: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .cardlist .card-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .cardlist .btn-primary {
        background-color: #0069d9;
        border-color: #0069d9;
        transition: background-color 0.3s ease;
    }

    .cardlist .btn-primary:hover {
        background-color: #0056b3;
    }

    .cardlist .card-body {
        background-color: white;
        padding: 30px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .cardlist .input-group {
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cardlist .input-group .form-control {
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
    }

    .cardlist .input-group .input-group-append .btn {
        border-top-right-radius: 50px;
        border-bottom-right-radius: 50px;
        background-color: #007bff;
        color: white;
    }

    /* Table Styling */
    .cardlist .table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
        border-radius: 10px;
    }

    .cardlist .table thead th {
        background-color: #007bff;
        color: white;
        font-weight: 500;
        font-size: 1rem;
        text-align: center;
    }

    .cardlist .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .cardlist .table tbody tr:hover {
        background-color: #e9ecef;
        transition: background-color 0.3s ease;
    }

    .cardlist .table td {
        text-align: center;
        padding: 15px;
        vertical-align: middle;
    }

    /* Status and Action Buttons */
    .cardlist .badge {
        font-size: 0.9rem;
        padding: 8px 15px;
        border-radius: 20px;
    }

    .cardlist .badge-active {
        background-color: #28a745;
        color: white;
    }

    .cardlist .badge-suspended {
        background-color: #dc3545;
        color: white;
    }

    .cardlist .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }

    .cardlist .btn-warning {
        color: #ffffff;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .cardlist .btn-warning:hover {
        color: #ffffff;
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .cardlist .btn-danger {
        background-color: #ff4136;
        border-color: #ff4136;
    }

    .cardlist .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .cardlist .table th, .cardlist .table td {
            font-size: 0.9rem;
            padding: 10px;
        }

        .cardlist .card-header h3 {
            font-size: 1.2rem;
        }
    }
</style>

<div class="container cardlist mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Card List</h3>
            <a class="btn btn-primary btn-sm" href="<?php echo e(route('card.add')); ?>">Add New</a>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('search_card')); ?>" method="get">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <input type="text" name="search" placeholder="Search by customer name" class="form-control">
                    <div class="input-group-append">
                        <button type="submit" class="btn">
                            <i class="fas fa-search fa-fw"></i> Search
                        </button>
                    </div>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Card Number</th>
                        <th>USD</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $cards->sortByDesc('status')->sortByDesc('USD'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><a href="<?php echo e(route('card.details', $card->id)); ?>"><?php echo e($card->name); ?></a></td>
                        <td>$<?php echo e(number_format($card->USD, 2)); ?></td>
                        <td>
                            <span class="badge <?php echo e($card->status ? 'badge-active' : 'badge-suspended'); ?>">
                                <?php echo e($card->status ? 'Active' : 'Suspended'); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(url('/admin/dashboard/card/edit/' . $card->id)); ?>" class="btn btn-primary btn-sm">Edit</a>
                            <form action="<?php echo e(url('/admin/dashboard/card/delete/' . $card->id)); ?>" method="get" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure you want to delete this card?')">Delete</button>
                            </form>
                            <?php if($card->status): ?>
                                <form action="<?php echo e(route('card.suspend', $card->id)); ?>" method="post" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-warning btn-sm" type="submit" onclick="return confirm('Are you sure you want to suspend this card?')">Suspend</button>
                                </form>
                            <?php else: ?>
                                <form action="<?php echo e(route('card.reactivate', $card->id)); ?>" method="post" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-success btn-sm" type="submit" onclick="return confirm('Are you sure you want to re-activate this card?')">Re-activate</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-4">
                <?php echo e($cards->appends(request()->query())->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/card/list.blade.php ENDPATH**/ ?>