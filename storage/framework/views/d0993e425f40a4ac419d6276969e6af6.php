<!-- resources/views/admin/customer/list.blade.php -->
<?php
use App\Models\Card;

$cards = Card::where('status', true)->get(); // Fetch only active cards
?>


<?php $__env->startSection('title', 'Fund Suppliers | MPG Solution'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    .header {
        background-color: #123456; /* Replace with your brand's primary color */
        color: white;
        padding: 20px 0;
        text-align: center;
        margin-bottom: 20px;
    }

    .btn-brand {
        background-color: #654321; /* Replace with your brand's secondary color */
        border-color: #654321;
        color: white;
    }

    .btn-brand:hover {
        background-color: #543210; /* A darker shade for hover effect */
        border-color: #543210;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
        font-size: 14px; /* Slightly smaller font size for better readability */
    }

    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px 12px; /* Adjust padding for more balance */
        word-wrap: break-word;
    }

    th {
        background-color: #f2f2f2;
        color: #123456; /* Your brand's primary color for text */
        font-weight: 500; /* Slightly bolder font for headers */
    }

    /* Truncate long text with ellipses */
    td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Ensure the search and filter area is in one line */
    .search-filters {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        gap: 10px; /* Add some space between elements */
    }

    .quick-links {
        margin-bottom: 15px;
        display: flex;
        gap: 10px; /* Add some space between buttons */
    }

    /* Add some vibrant colors to the page */
    .card-header {
        background-color: #345678; /* A different color for the card headers */
        color: white;
        font-weight: 500;
    }

    .table th {
        background-color: #56789A; /* A different color for table headers */
        color: white;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #007BFF;
        border-color: #007BFF;
    }

    .btn-danger {
        background-color: #FF4136;
        border-color: #FF4136;
    }

    /* Reduce the padding between rows */
    .table tr {
        padding: 4px 6px;
    }

    /* Pagination styles */
    .pagination {
        justify-content: center;
        margin-top: 20px;
    }

    .page-link {
        color: #007BFF;
    }

    .page-item.active .page-link {
        background-color: #007BFF;
        border-color: #007BFF;
        color: white;
    }

    .page-item.disabled .page-link {
        color: #cccccc;
    }

    /* Handle mobile responsiveness */
    @media screen and (max-width: 768px) {
        .col-md-4, .col-md-8 {
            width: 100%;
        }

        .search-filters {
            flex-direction: column;
            align-items: stretch;
        }

        .quick-links {
            flex-direction: column;
            align-items: stretch;
        }

        .search-filters .col-md-3 {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
    @media (min-width: 768px) {
    .col-md-4 {
        flex: 0 0 20%;
        max-width: 20%;
    }

    .col-md-8 {
        flex: 0 0 80%;
        max-width: 80%;
    }
}

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>New Purchase</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo e(url('/admin/dashboard/client/add')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <?php $__errorArgs = ['name'];
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

                        <div class="form-group">
                            <label for="USD">USD:</label>
                            <input type="number" step="0.01" class="form-control" id="USD" name="USD" required oninput="calculateNRP()">
                            <?php $__errorArgs = ['USD'];
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

                        <div class="form-group">
                            <label for="Rate">Rate:</label>
                            <input type="number" step="0.01" class="form-control" id="Rate" name="Rate" required oninput="calculateNRP()">
                            <?php $__errorArgs = ['Rate'];
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

                        <div class="form-group">
                            <label for="NRP">NRP:</label>
                            <input type="number" step="0.01" class="form-control" id="NRP" name="NRP">
                            <?php $__errorArgs = ['NRP'];
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

                        <div class="form-group">
    <label for="Ad_Account">Account:</label>
    <select class="form-control" id="Ad_Account" name="account" required>
        <option value="">Select account</option>
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($card->card_number); ?>"><?php echo e($card->card_number); ?></option>
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

                        <button type="submit" class="btn btn-primary">Add Client</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Purchased Records</h4>
                </div>
                <div class="card-body">
                    <!-- Search and Filters in one line -->
                    <div class="search-filters-quick-links mb-3">
                        <form action="<?php echo e(route('search_client')); ?>" method="get" class="form-inline w-100">
                            <?php echo csrf_field(); ?>
                            <div class="d-flex w-100 align-items-center flex-wrap">
                                <div class="flex-grow-1 p-1">
                                    <input type="text" name="search" placeholder="Search by customer name" class="form-control w-100" value="<?php echo e(request()->get('search')); ?>">
                                </div>
                                <div class="p-1">
                                    <input type="date" name="start_date" class="form-control" value="<?php echo e(request()->get('start_date')); ?>">
                                </div>
                                <div class="p-1">
                                    <input type="date" name="end_date" class="form-control" value="<?php echo e(request()->get('end_date')); ?>">
                                </div>
                                <div class="p-1">
                                    <button type="submit" class="btn btn-brand">
                                        <i class="fas fa-search fa-fw"></i> Search
                                    </button>
                                </div>
                                <div class="ml-auto p-1 d-flex align-items-center">
                                    <a href="<?php echo e(route('client.yesterday')); ?>" class="btn btn-brand ml-2">Yesterday</a>
                                    <a href="<?php echo e(route('client.this_day')); ?>" class="btn btn-brand ml-2">Today</a>
                                    <a href="<?php echo e(route('client.this_week')); ?>" class="btn btn-brand ml-2">This Week</a>
                                    <a href="<?php echo e(route('client.this_month')); ?>" class="btn btn-brand ml-2">This Month</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Records Table -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="name-col">Name</th>
                                <th class="account-col">Account</th>
                                <th class="usd-col">USD</th>
                                <th class="rate-col">Rate</th>
                                <th class="nrp-col">NRP</th>
                                <th class="date-col">Created At</th>
                                <th class="action-col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr id="row-<?php echo e($client->id); ?>">
        <td class="name-col">
    <a href="<?php echo e(route('client.detailsByName', $client->name)); ?>">
        <span class="display"><?php echo e($client->name); ?></span>
    </a>
    <input type="text" class="form-control edit" value="<?php echo e($client->name); ?>" style="display:none;">
</td>
        <td class="account-col">
            <span class="display"><?php echo e($client->account); ?></span>
            <input type="text" class="form-control edit" value="<?php echo e($client->account); ?>" style="display:none;">
        </td>
        <td class="usd-col">
            <span class="display"><?php echo e($client->USD); ?></span>
            <input type="number" class="form-control edit" value="<?php echo e($client->USD); ?>" style="display:none;">
        </td>
        <td class="rate-col">
            <span class="display"><?php echo e($client->Rate); ?></span>
            <input type="number" class="form-control edit" value="<?php echo e($client->Rate); ?>" style="display:none;">
        </td>
        <td class="nrp-col">
            <span class="display"><?php echo e($client->NRP); ?></span>
            <input type="number" class="form-control edit" value="<?php echo e($client->NRP); ?>" style="display:none;">
        </td>
        <td class="date-col"><?php echo e($client->created_at->format('Y-m-d')); ?></td>
        <td>
            <button class="btn btn-primary btn-sm edit-btn">Edit</button>
            <button class="btn btn-success btn-sm save-btn" style="display:none;">Save</button>
            <button class="btn btn-danger btn-sm cancel-btn" style="display:none;">Cancel</button>
            <form action="<?php echo e(url('/admin/dashboard/client/delete/'. $client->id)); ?>" method="get" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('GET'); ?>
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this client?')">Delete</button>
                            </form>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
                        <tfoot>
    <tr style="background-color: #f2f2f2; font-weight: bold;">
        <td colspan="2"><strong style="float: right;">Grand Total USD:</strong></td>
        <td><strong><?php echo e($totalUSD); ?></strong></td>
        <td></td>
        <td><strong><?php echo e($totalNRP); ?></strong></td>
        <td colspan="2"><strong>Total We Paid (NRP)</strong></td>
    </tr>
</tfoot>
                    </table>
                    <?php echo e($clients->appends(request()->except('page'))->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function calculateNRP() {
        var usd = parseFloat(document.getElementById('USD').value) || 0;
        var rate = parseFloat(document.getElementById('Rate').value) || 0;
        var nrp = usd * rate;
        document.getElementById('NRP').value = nrp.toFixed(2);
    }
</script>
<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            row.querySelectorAll('.display').forEach(span => span.style.display = 'none');
            row.querySelectorAll('.edit').forEach(input => input.style.display = 'block');
            row.querySelector('.edit-btn').style.display = 'none';
            row.querySelector('.save-btn').style.display = 'inline-block';
            row.querySelector('.cancel-btn').style.display = 'inline-block';
        });
    });

    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            row.querySelectorAll('.display').forEach(span => span.style.display = 'block');
            row.querySelectorAll('.edit').forEach(input => input.style.display = 'none');
            row.querySelector('.edit-btn').style.display = 'inline-block';
            row.querySelector('.save-btn').style.display = 'none';
            row.querySelector('.cancel-btn').style.display = 'none';
        });
    });

    document.querySelectorAll('.save-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = row.id.replace('row-', '');
            const name = row.querySelector('.name-col .edit').value;
            const account = row.querySelector('.account-col .edit').value;
            const usd = row.querySelector('.usd-col .edit').value;
            const rate = row.querySelector('.rate-col .edit').value;
            const nrp = row.querySelector('.nrp-col .edit').value;

            fetch(`/admin/dashboard/client/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                },
                body: JSON.stringify({
                    name: name,
                    account: account,
                    USD: usd,
                    Rate: rate,
                    NRP: nrp,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.querySelector('.name-col .display').innerText = name;
                    row.querySelector('.account-col .display').innerText = account;
                    row.querySelector('.usd-col .display').innerText = usd;
                    row.querySelector('.rate-col .display').innerText = rate;
                    row.querySelector('.nrp-col .display').innerText = nrp;

                    row.querySelectorAll('.display').forEach(span => span.style.display = 'block');
                    row.querySelectorAll('.edit').forEach(input => input.style.display = 'none');
                    row.querySelector('.edit-btn').style.display = 'inline-block';
                    row.querySelector('.save-btn').style.display = 'none';
                    row.querySelector('.cancel-btn').style.display = 'none';
                } else {
                    alert('Failed to save changes.');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/client/list.blade.php ENDPATH**/ ?>