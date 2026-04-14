

<?php $__env->startSection('content'); ?>
<style>
  :root{
    --bg:#0b1020;            /* subtle gradient base */
    --card:#ffffff;
    --card-2:#f8fafc;
    --ink:#0f172a;
    --muted:#6b7280;
    --ring:#2563eb;
    --border:#e5e7eb;
    --border-soft:#eef2f7;
    --primary:#2563eb;
    --primary-ink:#ffffff;
    --danger:#ef4444;
    --shadow:0 10px 25px rgba(2,6,23,0.10), 0 3px 10px rgba(2,6,23,0.05);
    --shadow-soft:0 6px 18px rgba(2,6,23,0.08);
    --radius:16px;
  }

  /* Page ambience */
  body{
    background:
      radial-gradient(1200px 600px at 20% -10%, rgba(37,99,235,0.10), transparent 60%),
      radial-gradient(1000px 500px at 90% 0%, rgba(99,102,241,0.10), transparent 60%),
      linear-gradient(180deg, #f9fbff, #f5f7fb 40%, #f6f8ff);
  }

  .card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    overflow:hidden;
  }

  .card-header2{
    display:flex; align-items:center; justify-content:space-between;
    gap:12px;
    padding:18px 22px;
    border-bottom:1px solid var(--border);
    background:
      linear-gradient(180deg, #ffffff, #fafcff 60%),
      radial-gradient(600px 200px at 0% 0%, rgba(37,99,235,0.06), transparent 60%);
    font-weight:700; color:var(--ink);
    letter-spacing:.2px;
  }
  .card-header2 .sub{
    font-weight:500; color:var(--muted); font-size:13px; letter-spacing:.2px;
  }

  .card-body2{ padding:18px 22px 20px; }

  /* Buttons */
  .btn{ --b:var(--border); --bg:#fff; --ink:var(--ink);
    display:inline-flex; align-items:center; gap:8px;
    border:1px solid var(--b); background:var(--bg); color:var(--ink);
    padding:9px 14px; border-radius:12px; font-weight:600; font-size:14px;
    transition:.2s ease; text-decoration:none !important;
  }
  .btn:hover{ transform:translateY(-1px); box-shadow:var(--shadow-soft); }
  .btn:focus{ outline:3px solid rgba(37,99,235,.25); outline-offset:2px; }

  .btn-primary{ --bg:var(--primary); --b:var(--primary); --ink:var(--primary-ink); }
  .btn-secondary{ --bg:#f3f6ff; --b:#dbe7ff; --ink:#1e3a8a; }
  .btn-outline{ --bg:#fff; --b:var(--border); --ink:#0f172a; }
  .btn-danger{ --bg:var(--danger); --b:var(--danger); --ink:#fff; }
  .btn-sm{ padding:7px 10px; border-radius:10px; font-size:13px; }

  /* Segmented switch look for "All Team / Only My Logs" */
  .seg{
    display:inline-flex; border:1px solid var(--border); border-radius:12px;
    background:#fff; overflow:hidden;
  }
  .seg a{ border:0; border-radius:0; }
  .seg a + a{ border-left:1px solid var(--border); }
  .seg a.active{ background:#eef2ff; color:#3730a3; border-color:#c7d2fe; }

  /* Filter bar */
  .toolbar{
    display:flex; flex-wrap:wrap; gap:10px; align-items:center;
  }
  .toolbar .ml-auto{ margin-left:auto; }
  .toolbar input[type="date"]{
    appearance:none; -webkit-appearance:none;
    padding:9px 12px; border:1px solid var(--border);
    border-radius:10px; background:#fff; color:var(--ink);
    font-size:14px; transition:.2s ease; min-width:160px;
  }
  .toolbar input[type="date"]:focus{
    border-color:#c7d2fe; box-shadow:0 0 0 4px rgba(99,102,241,.15);
    outline:none;
  }

  /* Table */
  .table-responsive{ border:1px solid var(--border); border-radius:14px; overflow:hidden; }
  .table{ width:100%; margin:0; border-collapse:separate; border-spacing:0; }
  .table thead th{
    background:linear-gradient(180deg, #f8fbff, #f2f6ff);
    color:#0b2447; font-weight:700; font-size:13px; letter-spacing:.3px;
    padding:11px 14px; border-bottom:1px solid var(--border);
    position:sticky; top:0; z-index:1; white-space:nowrap;
  }
  .table tbody td{
    padding:12px 14px; border-bottom:1px solid var(--border-soft); vertical-align:middle; color:#0b1220;
  }
  .table tbody tr:hover td{
    background:#fbfdff;
  }
  .table tfoot td{ padding:12px 14px; }

  /* Status chips */
  .badge{
    display:inline-flex; align-items:center; gap:8px;
    padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; letter-spacing:.2px;
    border:1px solid transparent; user-select:none;
  }
  .badge svg{ width:14px; height:14px; }
  .badge-draft{ background:#fff7ed; color:#92400e; border-color:#fed7aa; }
  .badge-submitted{ background:#e0f2fe; color:#075985; border-color:#bae6fd; }
  .badge-approved{ background:#dcfce7; color:#166534; border-color:#86efac; }

  /* Muted / Empty */
  .muted{ color:var(--muted); }
  .empty{
    display:flex; gap:14px; align-items:center;
    padding:16px 0; color:var(--muted);
  }
  .empty .dot{
    width:10px; height:10px; border-radius:999px; background:#e5e7eb; box-shadow:inset 0 0 0 1px #d1d5db;
  }

  /* Action cluster */
  .actions{ display:flex; gap:8px; flex-wrap:wrap; }
  .actions .btn{ border-radius:10px; }

  /* Pagination alignment refinement (Laravel default links) */
  nav[role="navigation"]{
    margin-top:16px; display:flex; justify-content:flex-end;
  }
  .pagination{ gap:6px; }
  .pagination .page-link{
    border-radius:10px !important;
  }

  /* Responsiveness */
  @media (max-width: 840px){
    .toolbar .ml-auto{ width:100%; margin-left:0; }
    .toolbar form{ width:100%; }
    .toolbar form > *:not(input[type="hidden"]){ flex:1 1 auto; }
    .table thead th:nth-child(4), .table tbody td:nth-child(4){ max-width:280px; }
  }
  @media (max-width: 520px){
    .card-header2{ flex-direction:column; align-items:flex-start; }
  }
</style>

<div class="card">
  <div class="card-header2">
    <div>
      <?php echo e($isSuper ? 'Daily Logs (All Team)' : 'My Daily Logs'); ?>

      <div class="sub">Track submissions, review summaries, and manage logs with ease.</div>
    </div>

    <div class="toolbar" style="gap:8px;">
      <a class="btn btn-primary" href="<?php echo e(route('admin.daily-logs.create')); ?>">
        <!-- plus icon -->
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        New
      </a>

      <?php if($isSuper): ?>
        <?php
          $qs = request()->except(['page']);
          $mineUrl = request()->fullUrlWithQuery(array_merge($qs, ['mine'=>1, 'page'=>null]));
          $allUrl  = route('admin.daily-logs.index');
          $onlyMineActive = request('mine')==1;
        ?>
        <span class="seg">
          <a class="btn <?php echo e(!$onlyMineActive ? 'active' : ''); ?>" href="<?php echo e($allUrl); ?>">All Team</a>
          <a class="btn <?php echo e($onlyMineActive ? 'active' : ''); ?>" href="<?php echo e($mineUrl); ?>">Only My Logs</a>
        </span>
      <?php endif; ?>
    </div>
  </div>

  <div class="card-body2">
    <div class="toolbar mb-3">
      <form method="get" class="ml-auto" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        <?php $__currentLoopData = request()->except(['from','to','page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <input type="date" name="from" value="<?php echo e(request('from')); ?>" placeholder="From" aria-label="From date">
        <input type="date" name="to"   value="<?php echo e(request('to')); ?>" placeholder="To" aria-label="To date">
        <button class="btn btn-outline" type="submit">
          <!-- filter icon -->
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Filter
        </button>

        <?php if(request()->hasAny(['from','to','mine'])): ?>
          <a class="btn btn-outline" href="<?php echo e(route('admin.daily-logs.index')); ?>">
            <!-- x icon -->
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Clear
          </a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-sm">
        <thead>
          <tr>
            <th>Date</th>
            <?php if($isSuper): ?><th>Staff</th><?php endif; ?>
            <th>Status</th>
            <th>Summary</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php $st = strtolower($r->status ?? 'submitted'); ?>
          <tr>
            <td><?php echo e(optional($r->log_date)->format('Y-m-d') ?? '—'); ?></td>

            <?php if($isSuper): ?>
              <td>
                <?php if($r->admin_id): ?>
                  <a href="<?php echo e(route('admin.user.details', $r->admin_id)); ?>" class="muted" style="font-weight:600; color:#0b1220;">
                    <?php echo e($r->admin->name ?? '—'); ?>

                  </a>
                <?php else: ?>
                  <?php echo e($r->admin->name ?? '—'); ?>

                <?php endif; ?>
              </td>
            <?php endif; ?>

            <td>
              <span class="badge badge-<?php echo e($st); ?>">
                <?php if($st==='approved'): ?>
                  <svg viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php elseif($st==='draft'): ?>
                  <svg viewBox="0 0 24 24" fill="none"><path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <?php else: ?>
                  <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <?php endif; ?>
                <?php echo e(ucfirst($r->status ?? 'submitted')); ?>

              </span>
            </td>

            <td title="<?php echo e($r->summary ?? ''); ?>">
              <?php echo e(\Illuminate\Support\Str::limit($r->summary ?? '', 90) ?: '—'); ?>

            </td>

            <td>
              <div class="actions">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view',$r)): ?>
                  <a class="btn btn-sm btn-outline" href="<?php echo e(route('admin.daily-logs.show',$r)); ?>?dept=auto">
                    <!-- eye -->
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                    View
                  </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update',$r)): ?>
                  <a class="btn btn-sm btn-primary" href="<?php echo e(route('admin.daily-logs.edit',$r)); ?>">
                    <!-- pencil -->
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21l3.5-.5L20 7.1 16.9 4 4.5 16.5 4 20z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                    Edit
                  </a>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete',$r)): ?>
                  <form method="post" action="<?php echo e(route('admin.daily-logs.destroy',$r)); ?>" style="display:inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this log?')">
                      <!-- trash -->
                      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V5h6v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                      Delete
                    </button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="<?php echo e($isSuper ? 5 : 4); ?>">
              <div class="empty">
                <span class="dot"></span>
                <div>
                  <div class="muted" style="font-weight:600;">No logs for selected filters.</div>
                  <?php if(request()->hasAny(['from','to','mine'])): ?>
                    <div class="muted">Try adjusting the date range or
                      <a href="<?php echo e(route('admin.daily-logs.index')); ?>" style="text-decoration:underline;">clear filters</a>.
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php echo e($logs->links()); ?>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/daily_logs/index.blade.php ENDPATH**/ ?>