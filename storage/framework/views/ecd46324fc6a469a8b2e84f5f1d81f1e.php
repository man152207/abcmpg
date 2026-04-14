
<?php $__env->startSection('title', 'Boosting Queue'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-3">
    <h4>📋 Boosting Task Queue</h4>

    
    <form action="<?php echo e(route('boosting.store')); ?>" method="POST" class="mb-3" id="boostingForm">
        <?php echo csrf_field(); ?>
        <div class="form-row" style="display:flex; gap:10px; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px;">
                <input type="text" id="customer_phone" name="customer_phone" class="form-control" placeholder="Customer Phone" required autocomplete="off">
                <div id="phoneSuggestions" class="list-group position-absolute" style="z-index:1000; max-height:200px; overflow-y:auto; display:none; width:100%;"></div>
            </div>
            <div style="flex:1; min-width:200px;">
                <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Customer Name" required>
            </div>
            <div style="flex:1; min-width:150px;">
                <select name="priority" class="form-control">
                    <option value="Normal" selected>Normal</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>
            <div style="flex:1; min-width:200px;">
                <input type="datetime-local" name="eta_time" class="form-control" placeholder="ETA (optional)">
            </div>
            <div style="flex:1; min-width:300px;">
                <input type="text" name="remarks" class="form-control" placeholder="Remarks (optional)">
            </div>
            <div style="flex:0;">
                <button type="submit" class="btn btn-success">+ Add Task</button>
            </div>
        </div>
    </form>

    
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Requested</th>
                <th>ETA</th>
                <th>Dispatcher</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($t->id); ?></td>
                <td><?php echo e($t->customer_name); ?></td>
                <td><a href="https://wa.me/+977<?php echo e($t->customer_phone); ?>" target="_blank"><?php echo e($t->customer_phone); ?></a></td>
                <td><?php echo e($t->requested_time ? \Carbon\Carbon::parse($t->requested_time)->format('M d H:i') : ''); ?></td>
                <td><?php echo e($t->eta_time ? \Carbon\Carbon::parse($t->eta_time)->format('M d H:i') : ''); ?></td>
                <td><?php echo e($t->dispatcher?->name ?? '-'); ?></td>
                <td><?php echo e($t->assignedUser?->name ?? '-'); ?></td>
                <td><span class="badge <?php echo e($t->status=='Pending'?'badge-warning': ($t->status=='In Progress'?'badge-info':'badge-success')); ?>"><?php echo e($t->status); ?></span></td>
                <td><span class="badge <?php echo e($t->priority=='Urgent'?'badge-danger':'badge-secondary'); ?>"><?php echo e($t->priority); ?></span></td>
                <td><?php echo e($t->remarks ?? '-'); ?></td>
                <td>
                    <?php if($t->status=='Pending'): ?>
                    <form action="<?php echo e(route('boosting.assign',$t->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-sm btn-info">Claim</button>
                    </form>
                    <?php endif; ?>
                    <?php if($t->status=='In Progress'): ?>
                    <form action="<?php echo e(route('boosting.complete',$t->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-sm btn-success">Done</button>
                    </form>
                    <?php endif; ?>
                    <form action="<?php echo e(route('boosting.destroy',$t->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')">Del</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($tasks->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#customer_phone').on('input', function(){
        let query = $(this).val();
        if(query.length >= 3){
            $.ajax({
                url: "<?php echo e(route('search_user')); ?>",
                method: "GET",
                data: { search: query },
                success: function(data){
                    let list = $('#phoneSuggestions');
                    list.empty().show();
                    if(data.length > 0){
                        data.forEach(function(item){
                            list.append('<a href="#" class="list-group-item list-group-item-action phone-select" data-phone="'+item.phone+'" data-name="'+item.name+'">'+item.phone+' - '+item.name+'</a>');
                        });
                    } else {
                        list.append('<span class="list-group-item">No match found</span>');
                    }
                }
            });
        } else {
            $('#phoneSuggestions').hide();
        }
    });

    $(document).on('click','.phone-select',function(e){
        e.preventDefault();
        let phone = $(this).data('phone');
        let name = $(this).data('name');
        $('#customer_phone').val(phone);
        $('#customer_name').val(name);
        $('#phoneSuggestions').hide();
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/boosting/index.blade.php ENDPATH**/ ?>