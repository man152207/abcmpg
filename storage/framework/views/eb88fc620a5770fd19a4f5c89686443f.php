

<?php $__env->startSection('content'); ?>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #e9ecef;
    }
    .card {
        border: none;
        border-radius: 10px;
        background-color: #ffffff;
        padding: 20px;
        margin-bottom: 10px;
    }
    .card-header2 {
        background-color: #093b7b;
        color: white;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px 10px 0 0;
    }
    .card-body2 {
        padding: 20px;
    }
    .user-activity label, .user-details label {
        font-weight: bold;
        color: #093b7b;
    }
    .row-custom {
        display: flex;
        flex-wrap: wrap;
    }
    .column-custom {
        flex: 50%;
        padding: 10px;
    }
    @media screen and (max-width: 768px) {
        .column-custom {
            flex: 100%;
        }
    }
    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>
<div class="container-fluid">
    <div class="card my-2">
        <div class="card-header2 d-flex justify-content-between align-items-center">
            <h3>User Details: <?php echo e($user->name); ?></h3>
            <a href="<?php echo e(url('/admin/dashboard/user/list')); ?>" class="btn btn-primary">Back to Users</a>
        </div>
        <div class="card-body2">
            <div class="row-custom">
                <div class="column-custom">
                    <h4>User Activities</h4>
                    
                    <?php if($userActivity): ?>
                        <div class="user-activity">
                            <label>Login Time and Date:</label> <?php echo e($userActivity->login_time ?? 'N/A'); ?>

                        </div>
                        <div class="user-activity">
  <span data-field="active_minutes"><?php echo e((int)($userActivity->active_hours ?? 0)); ?></span> minutes
</div>
                        <div class="user-activity">
                            <label>Login Real-Time Location:</label> <?php echo e($userActivity->location ?? 'N/A'); ?>

                        </div>
<div class="user-activity">
  <label>Frequently Visited Pages:</label>
<?php
  $freq = is_array($userActivity->frequent_page)
       ? $userActivity->frequent_page
       : (json_decode($userActivity->frequent_page ?? '[]', true) ?: []);
?>
<?php if($freq && is_array($freq) && count($freq)): ?>
  <div class="table-responsive">
    <table class="table table-sm table-striped">
      <thead>
        <tr>
          <th style="width:65%;">Page</th>
          <th class="text-right">Visits</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $freq; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $pretty = $pageNameMap[$key] ?? $key;
            $pretty = preg_replace('#/(\d+)(/|$)#', '/:id$2', $pretty);
          ?>
          <tr>
            <td><?php echo e($pretty); ?></td>
            <td class="text-right"><?php echo e($count); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <p>No pages visited yet.</p>
<?php endif; ?>
</div>
                        <div class="user-activity">
  <span data-field="inactive_minutes"><?php echo e((int)($userActivity->inactive_time ?? 0)); ?></span> minutes
</div>
<div class="row mt-3">
  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Ads (7 दिन)</div>
    <div class="h3 font-weight-bold"><?php echo e($kpis['ads_created'] ?? 0); ?></div>
  </div></div></div>

  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Invoices (7 दिन)</div>
    <div class="h3 font-weight-bold"><?php echo e($kpis['invoices_created'] ?? 0); ?></div>
  </div></div></div>

  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Clients (7 दिन)</div>
    <div class="h3 font-weight-bold"><?php echo e($kpis['clients_added'] ?? 0); ?></div>
  </div></div></div>

  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Ad Amount (7 दिन)</div>
    <div class="h6 mb-0">
      Rs. <?php echo e(number_format($kpis['nrp'] ?? 0, 2, '.', ',')); ?><br>
      $ <?php echo e(number_format($kpis['usd'] ?? 0, 2, '.', ',')); ?>

    </div>
  </div></div></div>
</div>
<div class="row mt-3">
  
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">Customers (All-time)</div>
        <div class="h3 font-weight-bold"><?php echo e($kpis['customers_total'] ?? 0); ?></div>
        <div class="small text-muted mt-1">Last 7 days: <?php echo e($kpis['customers_7d'] ?? 0); ?></div>
      </div>
    </div>
  </div>

  
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">Multimedia (All-time)</div>
        <div class="h3 font-weight-bold"><?php echo e($kpis['multimedia_total'] ?? 0); ?></div>
        <div class="small text-muted mt-1">Last 7 days: <?php echo e($kpis['multimedia_7d'] ?? 0); ?></div>
      </div>
    </div>
  </div>

  
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">CRM Contacts (All-time)</div>
        <div class="h3 font-weight-bold"><?php echo e($kpis['fu_contacts_total'] ?? 0); ?></div>
        <div class="small text-muted mt-1">Last 7 days: <?php echo e($kpis['fu_contacts_7d'] ?? 0); ?></div>
      </div>
    </div>
  </div>

  
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">Follow-ups (All-time)</div>
        <div class="h3 font-weight-bold"><?php echo e($kpis['followups_total'] ?? 0); ?></div>
        <div class="small text-muted mt-1">Last 7 days: <?php echo e($kpis['followups_7d'] ?? 0); ?></div>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header2">30 Days Works</div>
  <div class="card-body2">
    <canvas id="userDailyChart" height="120"></canvas>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const labels = <?php echo json_encode($dailyLabels ?? []); ?>;
  const data   = <?php echo json_encode($dailyCounts ?? []); ?>;

  const ctx = document.getElementById('userDailyChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: { labels, datasets: [{ label: 'कार्य संख्या', data }]},
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true, ticks: { precision:0 } } }
    }
  });
})();
</script>
<?php $__env->stopPush(); ?>
<div class="card mt-3">
  <div class="card-header2">भर्खरका कामहरू</div>
  <div class="card-body2">
    <ul class="list-unstyled mb-0">
      <?php $__empty_1 = true; $__currentLoopData = $recentAds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class="mb-1">
          <strong><?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d M, h:i A')); ?></strong>
          — Ad #<?php echo e($row->id); ?>

        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <?php endif; ?>

      <?php $__empty_1 = true; $__currentLoopData = $recentInv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class="mb-1">
          <strong><?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d M, h:i A')); ?></strong>
          — Invoice #<?php echo e($row->id); ?>

        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <?php endif; ?>

      <?php $__empty_1 = true; $__currentLoopData = $recentCli; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class="mb-1">
          <strong><?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d M, h:i A')); ?></strong>
          — Client #<?php echo e($row->id); ?>

        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <?php endif; ?>

      <?php if(($recentAds->count()+$recentInv->count()+$recentCli->count())===0): ?>
        <li>No recent items.</li>
      <?php endif; ?>
    </ul>
  </div>
</div>


<div class="user-activity">
  <label>Active Periods:</label>
  <?php if($userActivity->last_active_start && $userActivity->last_active_end): ?>
    <p>From: <?php echo e(\Carbon\Carbon::parse($userActivity->last_active_start)->format('d M Y, h:i A')); ?>

       to <?php echo e(\Carbon\Carbon::parse($userActivity->last_active_end)->format('d M Y, h:i A')); ?></p>
    <p>Total Active Duration: <?php echo e((int)($userActivity->active_hours ?? 0)); ?> minutes</p>
  <?php else: ?>
    <p>No active period data available.</p>
  <?php endif; ?>
</div>
                        <div class="user-activity">
                            <label>Inactive Periods:</label>
                            <p>Total Inactive Duration: <?php echo e($userActivity->inactive_time ?? 'N/A'); ?> minutes</p>
                        </div>
                    <?php else: ?>
                        <p>No user activity data available.</p>
                    <?php endif; ?>
                </div>

                <div class="column-custom">
                    <h4>User Details</h4>
<?php
    use Illuminate\Support\Str;
    // $user->profile_picture भित्र कहिले काँही URL (http/https) हुन सक्छ, कहिले relative path
    $img = $user->profile_picture
        ? (Str::startsWith($user->profile_picture, ['http://','https://'])
            ? $user->profile_picture
            : asset('storage/'.$user->profile_picture))
        : null;
?>

<?php if($img): ?>
    <img src="<?php echo e($img); ?>" alt="<?php echo e($user->name); ?>" class="profile-picture-large"
         style="height:150px;width:150px;object-fit:cover;border-radius:50%;border:1px solid #ddd;">
<?php else: ?>
    <i class="fas fa-user-circle" style="font-size: 150px; color: rgba(0, 0, 0, 0.7);"></i>
<?php endif; ?>
                    <div class="user-details">
                        <label>Status:</label>
                        <span id="userStatus">
                            <?php if($isOnline): ?>
                                <span style="color: green; font-weight: bold;">
                                    <i class="fas fa-circle" style="animation: blink 1s infinite;"></i> Online
                                </span>
                            <?php else: ?>
                                <span style="color: red; font-weight: bold;">
                                    <i class="fas fa-circle"></i> Offline
                                </span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="user-details">
                        <label>Full Name:</label> <?php echo e($user->full_name ?? 'N/A'); ?>

                    </div>
                    <div class="user-details">
                        <label>Name:</label> <?php echo e($user->name); ?>

                    </div>
                    <div class="user-details">
                        <label>Email:</label> <?php echo e($user->email); ?>

                    </div>
                    <div class="user-details">
                        <label>Phone:</label> <?php echo e($user->phone); ?>

                    </div>
                    <div class="user-details">
                        <label>Registered On:</label> <?php echo e($user->created_at->format('d M Y, h:i A')); ?>

                    </div>
                <div class="user-details">
    <label>Departments:</label>
    <?php $deps = $user->departments ?? collect(); ?>
    <?php $__empty_1 = true; $__currentLoopData = $deps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <span class="badge badge-primary mr-1"><?php echo e($d->name); ?></span>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <span class="text-muted">Not assigned</span>
    <?php endif; ?>
</div>
                    <div class="mt-4">
                        <a href="<?php echo e(url('/admin/dashboard/user/edit/' . $user->id)); ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                        <form action="<?php echo e(url('/admin/dashboard/user/delete/' . $user->id)); ?>" method="post" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                        <a href="<?php echo e(url('/admin/dashboard/user/privilege/' . $user->id)); ?>" class="btn btn-warning"><i class="fas fa-key"></i> Edit Privilege</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setInterval(function() {
        $.ajax({
            url: '/admin/dashboard/user/check-status/<?php echo e($user->id); ?>',
            success: function(data) {
                if (data.isOnline) {
                    $('#userStatus').html('<span style="color: green; font-weight: bold;"><i class="fas fa-circle" style="animation: blink 1s infinite;"></i> Online</span>');
                } else {
                    $('#userStatus').html('<span style="color: red; font-weight: bold;"><i class="fas fa-circle"></i> Offline</span>');
                }
            }
        });
    }, 5000); // Check every 5 seconds
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            // Send the coordinates to the server
            fetch('/admin/dashboard/user/update-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng
                })
            });
        }, function(error) {
            console.error('Error getting location:', error);
        });
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
});
</script>
<script>
function updateLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            $.post('/admin/dashboard/user/update-location', {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                _token: $('meta[name="csrf-token"]').attr('content')
            });
        });
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
}

updateLocation();

</script>
<script>
(function(){
  if (!('fetch' in window)) return;

  const csrf   = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const pingUrl = "<?php echo e(route('activity.ping')); ?>";

  const pageId = (crypto.randomUUID ? crypto.randomUUID() : (Date.now()+''+Math.random()));

  let isIdle = false, activeAccum = 0, idleAccum = 0, lastActivityAt = Date.now();
  const IDLE_AFTER_MS = 60000;
  const PING_EVERY_MS = 30000;

  function markActive(){
    const now = Date.now();
    if (!isIdle) activeAccum += (now - lastActivityAt);
    lastActivityAt = now; isIdle = false;
  }
  ['mousemove','keydown','click','scroll','touchstart'].forEach(ev=>{
    window.addEventListener(ev, markActive, {passive:true});
  });
  document.addEventListener('visibilitychange', markActive);
  window.addEventListener('focus', markActive);

  setInterval(function(){
    const now = Date.now();
    if ((now - lastActivityAt) >= IDLE_AFTER_MS) {
      if (!isIdle) { isIdle = true; } else { idleAccum += 1000; }
    }
  }, 1000);

  function sendHeartbeat(){
    const now = Date.now();
    if (!isIdle) activeAccum += (now - lastActivityAt);
    lastActivityAt = now;

    const payload = {
      activeDelta: Math.round(activeAccum / 1000),
      idleDelta:   Math.round(idleAccum / 1000),
      pageId,
      path: window.location.pathname
    };
    activeAccum = 0; idleAccum = 0;

    if (navigator.sendBeacon) {
      const fd = new FormData();
      fd.append('_token', csrf);
      Object.entries(payload).forEach(([k,v])=>fd.append(k, v));
      navigator.sendBeacon(pingUrl, fd);
    } else {
      fetch(pingUrl, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      }).catch(()=>{});
    }
  }

  setInterval(sendHeartbeat, PING_EVERY_MS);
  setTimeout(sendHeartbeat, 3000);
})();
</script>
<script>
(function(){
  const actUrl = "<?php echo e(route('admin.user.activity', $user->id)); ?>";
  function renderActivity(data){
    const a = document.querySelector('[data-field="active_minutes"]');
    if (a) a.textContent = (data.active_minutes||0);

    const i = document.querySelector('[data-field="inactive_minutes"]');
    if (i) i.textContent = (data.inactive_minutes||0);

    const tbody = document.getElementById('freqTableBody');
    if (tbody && data.frequent_page) {
      tbody.innerHTML = '';
      Object.entries(data.frequent_page).forEach(([k,v])=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${k}</td><td class="text-right">${v}</td>`;
        tbody.appendChild(tr);
      });
    }
  }
  function poll(){ fetch(actUrl,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.json()).then(renderActivity).catch(()=>{}); }
  setInterval(poll, 30000);
  setTimeout(poll, 5000);
})();

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/user/details.blade.php ENDPATH**/ ?>