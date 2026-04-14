<tbody id="expense-table-body">
    <?php $__currentLoopData = $exps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr id="row-<?php echo e($exp->id); ?>">
        <td class="date-col"><?php echo e($exp->date); ?></td>
        <td class="title-col">
            <span class="display"><?php echo e($exp->title); ?></span>
            <input type="text" class="form-control edit" value="<?php echo e($exp->title); ?>" style="display:none;">
        </td>
        <td class="amount-col">
            <span class="display"><?php echo e($exp->amount); ?></span>
            <input type="number" class="form-control edit" value="<?php echo e($exp->amount); ?>" style="display:none;">
        </td>
        <td class="note-col">
            <span class="display"><?php echo e($exp->note); ?></span>
            <input type="text" class="form-control edit" value="<?php echo e($exp->note); ?>" style="display:none;">
        </td>
        <td class="action-col">
            <button class="btn btn-primary btn-sm edit-btn">Edit</button>
            <button class="btn btn-success btn-sm save-btn" style="display:none;">Save</button>
            <button class="btn btn-danger btn-sm cancel-btn" style="display:none;">Cancel</button>
            <form action="<?php echo e(url('/admin/dashboard/exp/delete/'. $exp->id)); ?>" method="get" style="display:inline;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('GET'); ?>
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
<div id="pagination-links">
    <?php echo e($exps->links('pagination::bootstrap-5')); ?>

</div>
<?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/client/other_exp/partials/expense_table.blade.php ENDPATH**/ ?>