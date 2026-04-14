

<?php $__env->startSection('content'); ?>
<div class="container">
  <h1>User Privileges</h1>

  <form id="privilegeForm">
    <?php echo csrf_field(); ?>

    
    <div class="form-group mt-3">
      <label style="font-size: xx-large;">Assign Departments:</label>
      <div class="row mt-2" style="font-size: x-large;">
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-md-3 mb-2">
            <div class="custom-control custom-checkbox">
              <input type="checkbox"
                     class="custom-control-input"
                     id="dept_<?php echo e($dept->id); ?>"
                     name="departments[]"
                     value="<?php echo e($dept->id); ?>"
                     <?php echo e(in_array($dept->id, $selectedDeptIds) ? 'checked' : ''); ?>>
              <label class="custom-control-label" for="dept_<?php echo e($dept->id); ?>">
                <?php echo e($dept->name); ?>

              </label>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    
    <div class="form-group mt-4">
      <label style="font-size: xx-large;">Select Privileges:</label>

      <div class="row mt-2" style="font-size: x-large;">
        <div class="col-md-3">
          <div class="custom-control custom-checkbox">
            <input style="width:40px;height:40px;"
                   type="checkbox" class="custom-control-input"
                   id="dashboard" name="privileges[]" value="1"
                   <?php echo e(in_array(1, $userPrivileges) ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="dashboard">Dashboard</label>
          </div>
        </div>
      </div>

      <div class="row mt-2" style="font-size: x-large;">
        <div class="col-md-3">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input"
                   id="ads" name="privileges[]" value="2"
                   <?php echo e(in_array(2, $userPrivileges) ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="ads">Ads</label>
          </div>
        </div>
      </div>

      <div class="row mt-2" style="font-size: x-large;">
        <div class="col-md-3">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input"
                   id="customers" name="privileges[]" value="3"
                   <?php echo e(in_array(3, $userPrivileges) ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="customers">Customers</label>
          </div>
        </div>
      </div>

      <div class="row mt-2" style="font-size: x-large;">
        <div class="col-md-3">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input"
                   id="client" name="privileges[]" value="4"
                   <?php echo e(in_array(4, $userPrivileges) ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="client">Client</label>
          </div>
        </div>
      </div>

      <div class="row mt-2" style="font-size: x-large;">
        <div class="col-md-3">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input"
                   id="item" name="privileges[]" value="5"
                   <?php echo e(in_array(5, $userPrivileges) ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="item">Item</label>
          </div>
        </div>
      </div>

      <div class="row mt-2" style="font-size: x-large;">
        <div class="col-md-3">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input"
                   id="invoice" name="privileges[]" value="6"
                   <?php echo e(in_array(6, $userPrivileges) ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="invoice">Invoice</label>
          </div>
        </div>
      </div>

      <div class="row mt-2" style="font-size: x-large;">
        <div class="col-md-3">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input"
                   id="card" name="privileges[]" value="7"
                   <?php echo e(in_array(7, $userPrivileges) ? 'checked' : ''); ?>>
            <label class="custom-control-label" for="card">Card (includes credit and debit)</label>
          </div>
        </div>
      </div>
    </div>

    <button type="button" onclick="submitForm(<?php echo e($user->id); ?>)" class="btn btn-primary">Save</button>
  </form>
</div>

<script>
function submitForm(user_id) {
  const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

  const privs = Array.from(document.querySelectorAll('input[name="privileges[]"]:checked'))
    .map(cb => parseInt(cb.value, 10));

  const deptIds = Array.from(document.querySelectorAll('input[name="departments[]"]:checked'))
    .map(cb => parseInt(cb.value, 10));

  const payload = {
    privileges: privs.join(','),   // backend CSV-friendly
    departments: deptIds           // array (also accepted)
  };

  fetch('/admin/dashboard/user/privilege/' + user_id, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrfToken,
      "Accept": "application/json"
    },
    body: JSON.stringify(payload)
  })
  .then(async (res) => {
    if (!res.ok) throw new Error(await res.text());
    return res.json();
  })
  .then(() => alert('Saved successfully'))
  .catch(err => {
    console.error(err);
    alert('Failed to save. Please try again.');
  });
}

// Local autosave (load-shedding साठी)
document.addEventListener('DOMContentLoaded', () => {
  const key = 'privs-dept:<?php echo e($user->id); ?>';
  const privBoxes = document.querySelectorAll('input[name="privileges[]"]');
  const deptBoxes = document.querySelectorAll('input[name="departments[]"]');

  const saved = localStorage.getItem(key);
  if (saved) {
    try {
      const {privs, depts} = JSON.parse(saved);
      if (Array.isArray(privs)) {
        privBoxes.forEach(cb => cb.checked = privs.includes(parseInt(cb.value,10)));
      }
      if (Array.isArray(depts)) {
        deptBoxes.forEach(cb => cb.checked = depts.includes(parseInt(cb.value,10)));
      }
    } catch(_) {}
  }

  function persist() {
    const privs = Array.from(privBoxes).filter(cb=>cb.checked).map(cb=>parseInt(cb.value,10));
    const depts = Array.from(deptBoxes).filter(cb=>cb.checked).map(cb=>parseInt(cb.value,10));
    localStorage.setItem(key, JSON.stringify({privs, depts}));
  }
  [...privBoxes, ...deptBoxes].forEach(cb => cb.addEventListener('change', persist));
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/user/privilege.blade.php ENDPATH**/ ?>