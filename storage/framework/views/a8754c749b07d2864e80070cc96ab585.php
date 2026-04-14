

<?php $__env->startSection('title', 'USA Calendar Intelligence'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    🇺🇸 USA Calendar Intelligence
                    <small class="text-muted d-block" style="font-size: 0.8rem;">
                        Federal, Bank & Payment Holidays + Emergency Closures (US)
                    </small>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <span class="badge badge-info">
                    Focus: Upcoming days
                </span>
                <span class="badge badge-light border">
                    If empty, shows last 7 days history
                </span>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">

            
            <div class="col-lg-8">

                
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            🇺🇸 Federal Holidays
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if($federalUpcoming->isNotEmpty()): ?>
                            <div class="p-3">
                                <span class="badge badge-success">Upcoming (next 30 days)</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 140px;">Date</th>
                                            <th>Name</th>
                                            <th style="width: 90px;">State</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $federalUpcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(\Carbon\Carbon::parse($h->date)->format('M d, Y')); ?></td>
                                                <td><?php echo e($h->name); ?></td>
                                                <td><?php echo e($h->state); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-3">
                                <p class="text-muted mb-1">
                                    No upcoming federal holidays.
                                </p>
                                <?php if($federalRecent->isNotEmpty()): ?>
                                    <p class="text-xs text-muted mb-2">
                                        Showing last 7 days history:
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 140px;">Date</th>
                                                    <th>Name</th>
                                                    <th style="width: 90px;">State</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $federalRecent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e(\Carbon\Carbon::parse($h->date)->format('M d, Y')); ?></td>
                                                        <td><?php echo e($h->name); ?></td>
                                                        <td><?php echo e($h->state); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-xs text-muted mb-0">
                                        No records in the last 7 days either.
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            🏦 Bank Status
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if($bankUpcomingUi->isNotEmpty()): ?>
                            <div class="p-3">
                                <span class="badge badge-success">Upcoming (next 30 days)</span>
                                <span class="badge badge-light border ml-1">
                                    Includes Weekends (Sat/Sun)
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 140px;">Date</th>
                                            <th style="width: 140px;">Provider</th>
                                            <th style="width: 130px;">Status</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bankUpcomingUi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(\Carbon\Carbon::parse($b->date)->format('M d, Y')); ?></td>
                                                <td><?php echo e($b->provider); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo e(strtolower($b->status) === 'closed' ? 'danger' : 'success'); ?>">
                                                        <?php echo e(ucfirst($b->status)); ?>

                                                    </span>
                                                    <?php if(!empty($b->is_weekend)): ?>
                                                        <span class="badge badge-light border ml-1">
                                                            Weekend
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($b->reason ?? '-'); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-3">
                                <p class="text-muted mb-1">
                                    No bank closures scheduled.
                                </p>
                                <?php if($bankRecentUi->isNotEmpty()): ?>
                                    <p class="text-xs text-muted mb-2">
                                        Showing last 7 days history (incl. weekends):
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 140px;">Date</th>
                                                    <th style="width: 140px;">Provider</th>
                                                    <th style="width: 130px;">Status</th>
                                                    <th>Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $bankRecentUi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e(\Carbon\Carbon::parse($b->date)->format('M d, Y')); ?></td>
                                                        <td><?php echo e($b->provider); ?></td>
                                                        <td>
                                                            <span class="badge badge-<?php echo e(strtolower($b->status) === 'closed' ? 'danger' : 'success'); ?>">
                                                                <?php echo e(ucfirst($b->status)); ?>

                                                            </span>
                                                            <?php if(!empty($b->is_weekend)): ?>
                                                                <span class="badge badge-light border ml-1">
                                                                    Weekend
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo e($b->reason ?? '-'); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-xs text-muted mb-0">
                                        No records in the last 7 days either.
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            💳 Payment Holidays (PayPal / Relay / Wise / Stripe)
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if($paymentUpcoming->isNotEmpty()): ?>
                            <div class="p-3">
                                <span class="badge badge-success">Upcoming (next 30 days)</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 140px;">Date</th>
                                            <th style="width: 140px;">Provider</th>
                                            <th style="width: 100px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $paymentUpcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(\Carbon\Carbon::parse($p->date)->format('M d, Y')); ?></td>
                                                <td><?php echo e($p->provider); ?></td>
                                                <td class="text-capitalize"><?php echo e($p->status); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-3">
                                <p class="text-muted mb-1">
                                    No upcoming payment holidays.
                                </p>
                                <?php if($paymentRecent->isNotEmpty()): ?>
                                    <p class="text-xs text-muted mb-2">
                                        Showing last 7 days history:
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 140px;">Date</th>
                                                    <th style="width: 140px;">Provider</th>
                                                    <th style="width: 100px;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $paymentRecent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e(\Carbon\Carbon::parse($p->date)->format('M d, Y')); ?></td>
                                                        <td><?php echo e($p->provider); ?></td>
                                                        <td class="text-capitalize"><?php echo e($p->status); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-xs text-muted mb-0">
                                        No records in the last 7 days either.
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            
            <div class="col-lg-4">

                
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            🕒 Live US Time (Key States)
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>State / Zone</th>
                                        <th>Time</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $usClock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($c->label); ?></td>
                                            <td><?php echo e($c->time); ?></td>
                                            <td><?php echo e($c->day); ?>, <?php echo e($c->date); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            🚨 Emergency Closures / Weather Alerts
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                            $hasUpcomingEmergency = $emergencyUpcoming->isNotEmpty();
                            $hasRecentEmergency   = $emergencyRecent->isNotEmpty();
                        ?>

                        <?php if($hasUpcomingEmergency): ?>
                            <p class="mb-2">
                                <span class="badge badge-success">Upcoming / Today</span>
                            </p>
                            <ul class="list-unstyled mb-3">
                                <?php $__currentLoopData = $emergencyUpcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="mb-2">
                                        <strong>
                                            Alert <?php echo e($e->state); ?> — <?php echo e($e->reason); ?>

                                        </strong><br>
                                        <small class="text-muted">
                                            <?php echo e(\Carbon\Carbon::parse($e->date)->format('M d, Y')); ?>

                                            • Severity:
                                            <span class="badge badge-<?php echo e(strtolower($e->severity) === 'extreme'
                                                ? 'danger'
                                                : (strtolower($e->severity) === 'moderate' ? 'warning' : 'secondary')); ?>">
                                                <?php echo e($e->severity); ?>

                                            </span>
                                        </small>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-1">
                                No upcoming emergency closures.
                            </p>
                            <?php if($hasRecentEmergency): ?>
                                <p class="text-xs text-muted mb-2">
                                    Showing last 7 days history:
                                </p>
                                <ul class="list-unstyled mb-0">
                                    <?php $__currentLoopData = $emergencyRecent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="mb-2">
                                            <strong>
                                                Alert <?php echo e($e->state); ?> — <?php echo e($e->reason); ?>

                                            </strong><br>
                                            <small class="text-muted">
                                                <?php echo e(\Carbon\Carbon::parse($e->date)->format('M d, Y')); ?>

                                                • Severity:
                                                <span class="badge badge-<?php echo e(strtolower($e->severity) === 'extreme'
                                                    ? 'danger'
                                                    : (strtolower($e->severity) === 'moderate' ? 'warning' : 'secondary')); ?>">
                                                    <?php echo e($e->severity); ?>

                                                </span>
                                            </small>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-xs text-muted mb-0">
                                    No records in the last 7 days either.
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php if($times->isNotEmpty()): ?>
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                🕒 US Timezones (Overview)
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>State</th>
                                            <th>Timezone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($t->state); ?></td>
                                                <td><?php echo e($t->timezone); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/admin/uscalendar/index.blade.php ENDPATH**/ ?>