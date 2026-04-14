
<?php $__env->startSection('title','Reception | Students'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid p-3">
  <div class="card">
    <div class="card-body">
      <form class="mb-2" method="get" action="<?php echo e(route('recp.students.list')); ?>">
        <div class="input-group">
          <input type="text" name="s" class="form-control" placeholder="Search name/phone" value="<?php echo e(request('s')); ?>">
          <button class="btn btn-secondary">Search</button>
          <a href="<?php echo e(route('recp.students.create')); ?>" class="btn btn-primary">+ New</a>
        </div>
      </form>
      <div class="table-responsive">
        <table id="studentsTable" class="table table-sm table-bordered">
          <thead><tr>
            <th>#</th><th>Name</th><th>Phone</th><th>Status</th><th>Actions</th>
          </tr></thead>
          <tbody>
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e($st->id); ?></td>
              <td><a href="<?php echo e(route('recp.students.show', $st)); ?>"><?php echo e($st->full_name); ?></a></td>
              <td><?php echo e($st->phone); ?></td>
              <td><?php echo e($st->status); ?></td>
              <td>
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#enrollModal" data-student-id="<?php echo e($st->id); ?>">Enroll</button>
                <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('recp.students.edit', $st)); ?>">Edit</a>
                <a class="btn btn-outline-success btn-sm" href="<?php echo e(route('recp.doc.create', $st)); ?>">+ Doc</a>
                <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?php echo e($st->id); ?>)">Delete</button>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
        <?php echo e($students->links()); ?>

      </div>
    </div>
  </div>
</div>
<!-- Enroll Modal -->
<div class="modal fade" id="enrollModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enroll Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Form loaded via AJAX or include partial -->
        <form id="enrollForm" method="post" action="">
          <?php echo csrf_field(); ?>
          <!-- Fields similar to enroll_student.blade.php -->
          <!-- ... (copy from enroll_student.blade.php, but make dynamic with student_id) -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="submitEnroll()">Save</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $('#studentsTable').DataTable({ paging: false }); // Customize as needed
  function confirmDelete(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "This will soft-delete the student!",
      icon: 'warning',
      showCancelButton: true,
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = '/admin/recp/students/' + id + '/delete'; // Custom route for delete
      }
    });
  }
  // Modal logic for enroll (AJAX load form if needed)
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/reception/students/students_list.blade.php ENDPATH**/ ?>