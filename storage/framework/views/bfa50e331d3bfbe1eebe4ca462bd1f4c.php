

<?php $__env->startSection('title', '2FA Auth Codes | MPG Solution'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* 2FA Page Custom Styles */
:root {
    --2fa-primary: #1e90ff;
    --2fa-secondary: #ffffff;
    --2fa-accent: #ff6b6b;
    --2fa-background: #f4f7fa;
    --2fa-text: #2d3748;
    --2fa-border: #e2e8f0;
    --2fa-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    --2fa-success: #48bb78;
    --2fa-error: #e53e3e;
}

body {
    background: var(--2fa-background);
    font-family: 'Inter', sans-serif;
    color: var(--2fa-text);
}

/* Container Styles */
.2fa-container-fluid {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.2fa-row {
    margin: 0 -15px;
}

.2fa-col-md-12 {
    padding: 15px;
}

/* Card Styles */
.2fa-card {
    background: var(--2fa-secondary);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--2fa-shadow);
    transition: transform 0.3s ease;
}

.2fa-card:hover {
    transform: translateY(-5px);
}

.2fa-card-header {
    background: var(--2fa-primary);
    padding: 20px;
    border-bottom: none;
}

.2fa-card-title {
    color: var(--2fa-secondary);
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
}

.2fa-card-body {
    padding: 25px;
}

/* Form Styles */
.2fa-form {
    margin-bottom: 30px;
}

.2fa-form-group {
    margin-bottom: 20px;
}

.2fa-form-group label {
    font-weight: 500;
    color: var(--2fa-text);
    margin-bottom: 8px;
    display: block;
}

.2fa-form-control {
    border: 1px solid var(--2fa-border);
    border-radius: 8px;
    padding: 12px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    background: #fff;
}

.2fa-form-control:focus {
    border-color: var(--2fa-primary);
    box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.2);
    outline: none;
}

.2fa-auth-token-input {
    text-transform: uppercase;
    font-family: 'Courier New', Courier, monospace;
}

.2fa-text-danger {
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
}

.2fa-btn {
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.2fa-btn-info {
    background: var(--2fa-primary);
    color: var(--2fa-secondary);
    border: none;
}

.2fa-btn-info:hover {
    background: #187bcd;
    transform: translateY(-2px);
}

.2fa-btn-warning {
    background: #f6ad55;
    color: var(--2fa-secondary);
    border: none;
}

.2fa-btn-warning:hover {
    background: #ed9a3a;
    transform: translateY(-2px);
}

.2fa-btn-danger {
    background: var(--2fa-error);
    color: var(--2fa-secondary);
    border: none;
}

.2fa-btn-danger:hover {
    background: #c53030;
    transform: translateY(-2px);
}

.2fa-btn-secondary {
    background: #cbd5e0;
    color: var(--2fa-text);
    border: none;
}

.2fa-btn-secondary:hover {
    background: #b7c0cc;
    transform: translateY(-2px);
}

.2fa-btn-primary {
    background: var(--2fa-primary);
    color: var(--2fa-secondary);
    border: none;
}

.2fa-btn-primary:hover {
    background: #187bcd;
    transform: translateY(-2px);
}

/* Table Styles */
.2fa-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: var(--2fa-secondary);
    border-radius: 8px;
    overflow: hidden;
}

.2fa-table thead {
    background: var(--2fa-primary);
    color: var(--2fa-secondary);
}

.2fa-table th {
    padding: 15px;
    font-weight: 600;
    text-align: left;
}

.2fa-table tbody tr {
    transition: background 0.2s ease;
}

.2fa-table tbody tr:hover {
    background: #edf2f7;
}

.2fa-table td {
    padding: 15px;
    border-bottom: 1px solid var(--2fa-border);
}

.2fa-table a {
    color: var(--2fa-primary);
    text-decoration: none;
    font-weight: 500;
}

.2fa-table a:hover {
    text-decoration: underline;
}

.2fa-btn-group {
    display: flex;
    gap: 10px;
}

.2fa-btn-sm {
    padding: 8px 12px;
    font-size: 0.9rem;
}

/* Modal Styles */
.2fa-modal {
    background: rgba(0, 0, 0, 0.5);
}

.2fa-modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}

.2fa-modal-content {
    border-radius: 12px;
    box-shadow: var(--2fa-shadow);
}

.2fa-modal-header {
    padding: 20px;
    border-bottom: 1px solid var(--2fa-border);
}

.2fa-modal-body {
    padding: 20px;
}

.2fa-modal-footer {
    padding: 15px 20px;
    border-top: 1px solid var(--2fa-border);
}

.2fa-close {
    font-size: 1.5rem;
    color: var(--2fa-text);
    opacity: 0.7;
}

.2fa-close:hover {
    opacity: 1;
}

/* Timer and Result Cells */
.2fa-timer-cell, .2fa-result-cell {
    font-weight: 500;
}

.2fa-timer-cell {
    color: var(--2fa-accent);
}

/* Notification Styling */
.2fa-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    color: var(--2fa-secondary);
    font-weight: 500;
    box-shadow: var(--2fa-shadow);
    z-index: 1000;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .2fa-row {
        flex-direction: column;
    }

    .2fa-col-md-3 {
        width: 100%;
        padding: 10px;
    }

    .2fa-form-group {
        margin-bottom: 15px;
    }

    .2fa-btn-group {
        flex-direction: column;
        gap: 8px;
    }

    .2fa-table {
        display: block;
        overflow-x: auto;
    }

    .2fa-table th, .2fa-table td {
        min-width: 120px;
    }

    .2fa-modal-dialog {
        margin: 1rem;
        max-width: 95%;
    }
}

@media (max-width: 576px) {
    .2fa-card-title {
        font-size: 1.5rem;
    }

    .2fa-btn {
        width: 100%;
        text-align: center;
    }

    .2fa-form-control {
        font-size: 0.9rem;
    }
}
</style>
    <div class="container-fluid 2fa-container-fluid">
        <div class="row 2fa-row">
            <div class="col-md-12 2fa-col-md-12">
                <div class="card 2fa-card">
                    <div class="card-header 2fa-card-header">
                        <h3 class="card-title 2fa-card-title">Auth Codes Management</h3>
                    </div>
                    <div class="card-body 2fa-card-body">
                        <!-- Form for adding new auth code -->
                        <form action="<?php echo e(route('admin.2fa.store')); ?>" method="POST" class="2fa-form">
                            <?php echo csrf_field(); ?>
                            <div class="row 2fa-row">
                                <div class="col-md-3 2fa-col-md-3">
                                    <div class="form-group 2fa-form-group">
                                        <label for="account_name">Account Name</label>
                                        <input type="text" name="account_name" class="form-control 2fa-form-control" value="<?php echo e(old('account_name')); ?>" required>
                                        <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger 2fa-text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-3 2fa-col-md-3">
                                    <div class="form-group 2fa-form-group">
                                        <label for="auth_token_code">Auth Token Code</label>
                                        <input type="text" name="auth_token_code" id="auth_token_code" class="form-control 2fa-form-control 2fa-auth-token-input" value="<?php echo e(old('auth_token_code')); ?>" required>
                                        <?php $__errorArgs = ['auth_token_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger 2fa-text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-3 2fa-col-md-3">
                                    <div class="form-group 2fa-form-group">
                                        <label for="recovery_code">Recovery Code</label>
                                        <input type="text" name="recovery_code" class="form-control 2fa-form-control" value="<?php echo e(old('recovery_code')); ?>">
                                        <?php $__errorArgs = ['recovery_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger 2fa-text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-3 2fa-col-md-3">
                                    <div class="form-group 2fa-form-group" style="margin-top: 30px;">
                                        <button type="submit" class="btn btn-info 2fa-btn 2fa-btn-info">Add Auth Code</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Table for displaying auth codes -->
                        <table class="table table-bordered table-hover 2fa-table">
                            <thead>
                                <tr class="2fa-table-row">
                                    <th>Account Name</th>
                                    <th>Auth Token Code</th>
                                    <th>Recovery Code</th>
                                    <th>Action</th>
                                    <th>Result</th>
                                    <th>Timer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $authCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $authCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="2fa-table-row">
                                        <td><a href="<?php echo e(route('admin.2fa.logs', $authCode->id)); ?>" class="2fa-table-link"><?php echo e($authCode->account_name); ?></a></td>
                                        <td><?php echo e($authCode->auth_token_code); ?></td>
                                        <td><?php echo e($authCode->recovery_code ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="btn-group 2fa-btn-group">
                                                <!-- Generate Code Button -->
                                                <button type="button" class="btn btn-info btn-sm 2fa-btn 2fa-btn-info 2fa-generate-code-btn" data-id="<?php echo e($authCode->id); ?>" data-url="<?php echo e(route('admin.2fa.generate', $authCode->id)); ?>" data-reset-url="<?php echo e(route('admin.2fa.reset', $authCode->id)); ?>">Generate Code</button>
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-warning btn-sm 2fa-btn 2fa-btn-warning" data-toggle="modal" data-target="#editModal<?php echo e($authCode->id); ?>">Edit</button>
                                                <!-- Delete Button -->
                                                <form action="<?php echo e(route('admin.2fa.destroy', $authCode->id)); ?>" method="POST" class="2fa-form" onsubmit="return confirm('Are you sure you want to delete this auth code?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm 2fa-btn 2fa-btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="2fa-result-cell" data-id="<?php echo e($authCode->id); ?>"><?php echo e($authCode->result ?? 'N/A'); ?></td>
                                        <td class="2fa-timer-cell" data-id="<?php echo e($authCode->id); ?>">N/A</td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade 2fa-modal" id="editModal<?php echo e($authCode->id); ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo e($authCode->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog 2fa-modal-dialog" role="document">
                                            <div class="modal-content 2fa-modal-content">
                                                <div class="modal-header 2fa-modal-header">
                                                    <h5 class="card-title 2fa-card-title" id="editModalLabel<?php echo e($authCode->id); ?>">Edit Auth Code</h5>
                                                    <button type="button" class="close 2fa-close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <form action="<?php echo e(route('admin.2fa.update', $authCode->id)); ?>" method="POST" class="2fa-form">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <div class="modal-body 2fa-modal-body">
                                                        <div class="form-group 2fa-form-group">
                                                            <label for="account_name_<?php echo e($authCode->id); ?>">Account Name</label>
                                                            <input type="text" name="account_name" id="account_name_<?php echo e($authCode->id); ?>" class="form-control 2fa-form-control" value="<?php echo e($authCode->account_name); ?>" required>
                                                            <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="text-danger 2fa-text-danger"><?php echo e($message); ?></span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                        <div class="form-group 2fa-form-group">
                                                            <label for="auth_token_code_<?php echo e($authCode->id); ?>">Auth Token Code</label>
                                                            <input type="text" name="auth_token_code" id="auth_token_code_<?php echo e($authCode->id); ?>" class="form-control 2fa-form-control 2fa-auth-token-input" value="<?php echo e($authCode->auth_token_code); ?>" required>
                                                            <?php $__errorArgs = ['auth_token_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="text-danger 2fa-text-danger"><?php echo e($message); ?></span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                        <div class="form-group 2fa-form-group">
                                                            <label for="recovery_code_<?php echo e($authCode->id); ?>">Recovery Code</label>
                                                            <input type="text" name="recovery_code" id="recovery_code_<?php echo e($authCode->id); ?>" class="form-control 2fa-form-control" value="<?php echo e($authCode->recovery_code); ?>">
                                                            <?php $__errorArgs = ['recovery_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                <span class="text-danger 2fa-text-danger"><?php echo e($message); ?></span>
                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer 2fa-modal-footer">
                                                        <button type="button" class="btn btn-secondary 2fa-btn 2fa-btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary 2fa-btn 2fa-btn-primary">Update Auth Code</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="2fa-table-row">
                                        <td colspan="6" class="text-center">No auth codes found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js_'); ?>
    <script>
        $(document).ready(function() {
            <?php if(session('success')): ?>
                showNotification('<?php echo e(session('success')); ?>');
            <?php endif; ?>
            <?php if(session('error')): ?>
                showNotification('<?php echo e(session('error')); ?>', '#e53e3e');
            <?php endif; ?>

            // Clean auth token input on keyup
            $('.2fa-auth-token-input, #auth_token_code').on('input', function() {
                let value = $(this).val();
                // Remove spaces and invalid characters, convert to uppercase
                let cleaned = value.replace(/\s/g, '').replace(/[^A-Z2-7]/g, '').toUpperCase();
                $(this).val(cleaned);
            });

            // Handle Generate Code button click with AJAX
            $('.2fa-generate-code-btn').on('click', function() {
                let button = $(this);
                let authCodeId = button.data('id');
                let url = button.data('url');
                let resetUrl = button.data('reset-url');
                let resultCell = $('.2fa-result-cell[data-id="' + authCodeId + '"]');
                let timerCell = $('.2fa-timer-cell[data-id="' + authCodeId + '"]');

                // Disable button to prevent multiple clicks
                button.prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update result cell with new code
                            resultCell.text(response.code);

                            // Start 30-second countdown timer
                            let timeLeft = 30;
                            timerCell.text('Expiring in ' + timeLeft + 's');
                            let timer = setInterval(function() {
                                timeLeft--;
                                if (timeLeft <= 0) {
                                    clearInterval(timer);
                                    timerCell.text('Expired');
                                    resultCell.text('N/A');

                                    // Reset code in database via AJAX
                                    $.ajax({
                                        url: resetUrl,
                                        type: 'POST',
                                        data: {
                                            _token: '<?php echo e(csrf_token()); ?>'
                                        },
                                        success: function() {
                                            button.prop('disabled', false);
                                        },
                                        error: function() {
                                            showNotification('Error resetting code', '#e53e3e');
                                            button.prop('disabled', false);
                                        }
                                    });
                                } else {
                                    timerCell.text('Expiring in ' + timeLeft + 's');
                                }
                            }, 1000);
                        } else {
                            showNotification(response.message, '#e53e3e');
                            button.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        showNotification('Error generating code', '#e53e3e');
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/2fa.blade.php ENDPATH**/ ?>