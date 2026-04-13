@extends('admin.layout.layout')

@section('title', 'USA Calendar Intelligence')

@section('content')
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

            {{-- LEFT COLUMN: Federal / Bank / Payment --}}
            <div class="col-lg-8">

                {{-- 🇺🇸 FEDERAL HOLIDAYS CARD --}}
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            🇺🇸 Federal Holidays
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @if($federalUpcoming->isNotEmpty())
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
                                        @foreach($federalUpcoming as $h)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($h->date)->format('M d, Y') }}</td>
                                                <td>{{ $h->name }}</td>
                                                <td>{{ $h->state }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-3">
                                <p class="text-muted mb-1">
                                    No upcoming federal holidays.
                                </p>
                                @if($federalRecent->isNotEmpty())
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
                                                @foreach($federalRecent as $h)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($h->date)->format('M d, Y') }}</td>
                                                        <td>{{ $h->name }}</td>
                                                        <td>{{ $h->state }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-xs text-muted mb-0">
                                        No records in the last 7 days either.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 🏦 BANK STATUS CARD (includes WEEKENDS) --}}
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            🏦 Bank Status
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @if($bankUpcomingUi->isNotEmpty())
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
                                        @foreach($bankUpcomingUi as $b)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($b->date)->format('M d, Y') }}</td>
                                                <td>{{ $b->provider }}</td>
                                                <td>
                                                    <span class="badge badge-{{ strtolower($b->status) === 'closed' ? 'danger' : 'success' }}">
                                                        {{ ucfirst($b->status) }}
                                                    </span>
                                                    @if(!empty($b->is_weekend))
                                                        <span class="badge badge-light border ml-1">
                                                            Weekend
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $b->reason ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-3">
                                <p class="text-muted mb-1">
                                    No bank closures scheduled.
                                </p>
                                @if($bankRecentUi->isNotEmpty())
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
                                                @foreach($bankRecentUi as $b)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($b->date)->format('M d, Y') }}</td>
                                                        <td>{{ $b->provider }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ strtolower($b->status) === 'closed' ? 'danger' : 'success' }}">
                                                                {{ ucfirst($b->status) }}
                                                            </span>
                                                            @if(!empty($b->is_weekend))
                                                                <span class="badge badge-light border ml-1">
                                                                    Weekend
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $b->reason ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-xs text-muted mb-0">
                                        No records in the last 7 days either.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 💳 PAYMENT HOLIDAYS CARD --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            💳 Payment Holidays (PayPal / Relay / Wise / Stripe)
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        @if($paymentUpcoming->isNotEmpty())
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
                                        @foreach($paymentUpcoming as $p)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($p->date)->format('M d, Y') }}</td>
                                                <td>{{ $p->provider }}</td>
                                                <td class="text-capitalize">{{ $p->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-3">
                                <p class="text-muted mb-1">
                                    No upcoming payment holidays.
                                </p>
                                @if($paymentRecent->isNotEmpty())
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
                                                @foreach($paymentRecent as $p)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($p->date)->format('M d, Y') }}</td>
                                                        <td>{{ $p->provider }}</td>
                                                        <td class="text-capitalize">{{ $p->status }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-xs text-muted mb-0">
                                        No records in the last 7 days either.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: Live US Time + Emergency + Timezones --}}
            <div class="col-lg-4">

                {{-- 🕒 LIVE US TIME CARD --}}
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
                                    @foreach($usClock as $c)
                                        <tr>
                                            <td>{{ $c->label }}</td>
                                            <td>{{ $c->time }}</td>
                                            <td>{{ $c->day }}, {{ $c->date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- 🚨 EMERGENCY CLOSURES CARD --}}
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            🚨 Emergency Closures / Weather Alerts
                        </h3>
                    </div>
                    <div class="card-body">
                        @php
                            $hasUpcomingEmergency = $emergencyUpcoming->isNotEmpty();
                            $hasRecentEmergency   = $emergencyRecent->isNotEmpty();
                        @endphp

                        @if($hasUpcomingEmergency)
                            <p class="mb-2">
                                <span class="badge badge-success">Upcoming / Today</span>
                            </p>
                            <ul class="list-unstyled mb-3">
                                @foreach($emergencyUpcoming as $e)
                                    <li class="mb-2">
                                        <strong>
                                            Alert {{ $e->state }} — {{ $e->reason }}
                                        </strong><br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($e->date)->format('M d, Y') }}
                                            • Severity:
                                            <span class="badge badge-{{ strtolower($e->severity) === 'extreme'
                                                ? 'danger'
                                                : (strtolower($e->severity) === 'moderate' ? 'warning' : 'secondary') }}">
                                                {{ $e->severity }}
                                            </span>
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-1">
                                No upcoming emergency closures.
                            </p>
                            @if($hasRecentEmergency)
                                <p class="text-xs text-muted mb-2">
                                    Showing last 7 days history:
                                </p>
                                <ul class="list-unstyled mb-0">
                                    @foreach($emergencyRecent as $e)
                                        <li class="mb-2">
                                            <strong>
                                                Alert {{ $e->state }} — {{ $e->reason }}
                                            </strong><br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($e->date)->format('M d, Y') }}
                                                • Severity:
                                                <span class="badge badge-{{ strtolower($e->severity) === 'extreme'
                                                    ? 'danger'
                                                    : (strtolower($e->severity) === 'moderate' ? 'warning' : 'secondary') }}">
                                                    {{ $e->severity }}
                                                </span>
                                            </small>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-xs text-muted mb-0">
                                    No records in the last 7 days either.
                                </p>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- 🕒 TIMEZONES (optional) --}}
                @if($times->isNotEmpty())
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
                                        @foreach($times as $t)
                                            <tr>
                                                <td>{{ $t->state }}</td>
                                                <td>{{ $t->timezone }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</section>
@endsection
