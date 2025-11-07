@extends('admin.layout.layout')

@section('content')
<div id="logShow" class="card">
  <style>
    /* ========= Design tokens ========= */
    :root{
      --ink:#0f172a; --muted:#64748b;
      --card:#ffffff; --panel:#f8fafc;
      --border:#e5e7eb; --border-soft:#eef2f7;
      --ring:#2563eb; --primary:#2563eb; --danger:#ef4444; --success:#16a34a; --warn:#eab308;
      --header-grad: linear-gradient(180deg,#ffffff,#fafcff 60%);
      --shadow: 0 14px 32px rgba(2,6,23,.08), 0 6px 16px rgba(2,6,23,.06);
      --shadow-soft: 0 10px 28px rgba(2,6,23,.07);
      --radius-lg:16px; --radius:12px;
      --fs-12:12px; --fs-13:13px; --fs-14:14px; --fs-15:15px; --fs-16:16px;
    }

    /* Card shell (inherits your admin layout styles) */
    #logShow{
      border:1px solid var(--border);
      border-radius:var(--radius-lg);
      overflow:hidden;
      box-shadow:var(--shadow);
      background:var(--card);
    }

    .card-header2{
      display:flex; align-items:center; justify-content:space-between;
      padding:18px 20px;
      border-bottom:1px solid var(--border);
      background:
        var(--header-grad),
        radial-gradient(600px 220px at 0% 0%, rgba(37,99,235,.06), transparent 60%);
      font-weight:800; color:var(--ink); letter-spacing:.2px;
    }
    .card-body2{ padding:18px 20px; }

    /* Toolbar */
    #logShow .toolbar{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
    #logShow .btn{
      --b:var(--border); --bg:#fff; --ink:var(--ink);
      display:inline-flex; align-items:center; gap:8px;
      padding:9px 14px; border-radius:12px; border:1px solid var(--b);
      background:var(--bg); color:var(--ink);
      font-weight:700; font-size:var(--fs-14); text-decoration:none !important; cursor:pointer;
      transition:.18s ease;
    }
    #logShow .btn:hover{ transform:translateY(-1px); box-shadow:var(--shadow-soft); }
    #logShow .btn:focus{ outline:3px solid rgba(37,99,235,.25); outline-offset:2px; }
    #logShow .btn-primary{ --bg:var(--primary); --b:var(--primary); --ink:#fff; }
    #logShow .btn-outline{ --bg:#fff; --b:var(--border); --ink:var(--ink); }

    /* Pills (dept filters) */
    #logShow .pillbar{ display:flex; gap:8px; flex-wrap:wrap; margin-left:auto; }
    #logShow .pill{
      padding:9px 14px; border:1px solid var(--border); border-radius:999px;
      background:#fff; color:#111827; font-weight:700; text-decoration:none !important;
      transition:.18s ease;
    }
    #logShow .pill:hover{ transform:translateY(-1px); box-shadow:var(--shadow-soft); }
    #logShow .pill.active{ background:#111827; color:#fff; border-color:#111827; }

    /* Badges with icons */
    #logShow .badge{
      display:inline-flex; align-items:center; gap:8px;
      padding:6px 10px; border-radius:999px; font-size:var(--fs-12); font-weight:800;
      border:1px solid transparent; user-select:none;
    }
    #logShow .badge svg{ width:14px; height:14px; }
    #logShow .badge-draft{ background:#fff7ed; color:#92400e; border-color:#fed7aa; }
    #logShow .badge-submitted{ background:#e0f2fe; color:#075985; border-color:#bae6fd; }
    #logShow .badge-approved{ background:#dcfce7; color:#166534; border-color:#86efac; }

    /* Sections */
    #logShow .card-section{
      border:1px solid var(--border);
      border-radius:14px; margin-bottom:16px; background:#fff; overflow:hidden;
      box-shadow:var(--shadow-soft);
    }
    #logShow .card-section .hd{
      display:flex; align-items:center; justify-content:space-between;
      padding:12px 14px; border-bottom:1px solid var(--border);
      position:sticky; top:0; background:#fff; z-index:2;
    }
    #logShow .card-section .hd h3{
      margin:0; font-size:var(--fs-15); letter-spacing:.3px; color:#0b2447;
      display:flex; align-items:center; gap:10px;
    }
    #logShow .card-section .hd h3::before{
      content:''; width:10px; height:10px; border-radius:999px;
      background:#c7d2fe; box-shadow:0 0 0 3px rgba(99,102,241,.15);
    }

    /* Key/Value ribbon */
    #logShow .kv{ display:flex; gap:18px; flex-wrap:wrap; padding:12px 14px; background:#fbfdff; }
    #logShow .kv .item{ min-width:190px; }
    #logShow .kv .k{ font-size:var(--fs-12); color:var(--muted); }
    #logShow .kv .v{ font-weight:800; color:#0b1220; }

    /* Summary text */
    #logShow .summary{ padding:0 14px 14px 14px; color:#0b1220; line-height:1.55; }
    #logShow .muted{ color:var(--muted); }

    /* Tables */
    #logShow table.view{ width:100%; border-collapse:separate; border-spacing:0; table-layout:fixed; }
    #logShow table.view thead th{
      position:sticky; top:0; z-index:1;
      background:linear-gradient(180deg,#f8fbff,#f2f6ff);
      color:#0b2447; text-transform:uppercase; letter-spacing:.3px;
      font-size:12.5px; padding:10px 10px; border-bottom:1px solid var(--border);
    }
    #logShow table.view tbody td{
      padding:10px 10px; border-bottom:1px solid var(--border-soft); vertical-align:top; color:#0b1220;
    }
    #logShow table.view tbody tr:nth-child(odd) td{ background:#ffffff; }
    #logShow table.view tbody tr:nth-child(even) td{ background:#fcfeff; }
    #logShow table.view tbody tr:hover td{ background:#f7fbff; }

    /* Section counters chip inside pill labels (optional light style) */
    #logShow .count{
      display:inline-flex; align-items:center; justify-content:center;
      min-width:22px; height:22px; padding:0 6px; margin-left:6px;
      border-radius:999px; font-size:11px; font-weight:800;
      background:#eef2ff; color:#3730a3; border:1px solid #c7d2fe;
    }

    /* Print styles */
    @media print {
      #logShow, #logShow .card-section{ box-shadow:none; }
      #logShow .toolbar, #logShow .pillbar{ display:none !important; }
      .card-header2{ border-bottom:1px solid #000; background:#fff; }
      #logShow .card-section{ border-color:#000; }
      #logShow .badge{ border:1px solid #000 !important; color:#000 !important; background:#fff !important; }
      #logShow table.view thead th{ background:#fff !important; }
    }

    /* Responsive tweaks */
    @media (max-width: 740px){
      #logShow .kv .item{ min-width:46%; }
    }
    @media (max-width: 520px){
      #logShow .kv .item{ min-width:100%; }
    }
  </style>

  @php
    // arrays
    $prodRows = $log->production_array;
    $recRows  = $log->reception_array;
    $opsRows  = $log->operations_array;

    // Helper to count only meaningful rows (ignore rows where only status is filled)
    $countMeaningful = function(array $rows, $statusIndex){
        $c = 0;
        foreach ($rows as $r) {
            $nonEmpty = false;
            foreach ($r as $i => $v) {
                if ($i === $statusIndex) continue; // ignore status col for emptiness
                if (trim((string)($v ?? '')) !== '') { $nonEmpty = true; break; }
            }
            if ($nonEmpty) $c++;
        }
        return $c;
    };

    $prodCount = $countMeaningful($prodRows, 4);
    $recCount  = $countMeaningful($recRows,  4);
    $opsCount  = $countMeaningful($opsRows,  4);

    // dept param: prod|rec|ops|all|auto
    $dept = request('dept', 'auto');
    if (!in_array($dept, ['prod','rec','ops','all','auto'])) { $dept = 'auto'; }

    // auto ⇒ show the first non-empty section, else fall back to 'all' if all empty
    if ($dept === 'auto') {
        if     ($prodCount > 0) $deptShow = 'prod';
        elseif ($recCount  > 0) $deptShow = 'rec';
        elseif ($opsCount  > 0) $deptShow = 'ops';
        else                    $deptShow = 'all';
    } else {
        $deptShow = $dept;
    }

    // convenience URLs
    $urlAll  = request()->fullUrlWithQuery(['dept'=>'all']);
    $urlProd = request()->fullUrlWithQuery(['dept'=>'prod']);
    $urlRec  = request()->fullUrlWithQuery(['dept'=>'rec']);
    $urlOps  = request()->fullUrlWithQuery(['dept'=>'ops']);

    // badge class + icon
    $badgeClass = 'badge-submitted';
    $badgeIcon  = '<svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';
    if($log->status === 'draft'){
      $badgeClass = 'badge-draft';
      $badgeIcon  = '<svg viewBox="0 0 24 24" fill="none"><path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    } elseif($log->status === 'approved'){
      $badgeClass = 'badge-approved';
      $badgeIcon  = '<svg viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    }
  @endphp

  <div class="card-header2">
    <div>
      Daily Log – {{ optional($log->log_date)->format('Y-m-d') ?? '—' }}
      <div style="font-size:12.5px;color:var(--muted);font-weight:500">Readable summary and structured record of the day</div>
    </div>
    <span class="badge {{ $badgeClass }}">{!! $badgeIcon !!} {{ strtoupper($log->status) }}</span>
  </div>

  <div class="card-body2">
    <div class="toolbar" style="margin-bottom:12px">
      <a href="{{ url()->previous() }}" class="btn btn-outline">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Back
      </a>
      @can('update',$log)
        <a href="{{ route('admin.daily-logs.edit',$log) }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21l3.5-.5L20 7.1 16.9 4 4.5 16.5 4 20z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
          Edit
        </a>
      @endcan
      <button class="btn btn-outline" onclick="window.print()">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 9V4h12v5M6 18h12M6 14h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Print
      </button>

      {{-- Dept pills (query param only) --}}
      <div class="pillbar">
        <a href="{{ $urlAll  }}" class="pill {{ $deptShow==='all'  ? 'active' : '' }}">All</a>
        <a href="{{ $urlProd }}" class="pill {{ $deptShow==='prod' ? 'active' : '' }}">
          Production @if($prodCount) <span class="count">{{ $prodCount }}</span> @endif
        </a>
        <a href="{{ $urlRec  }}" class="pill {{ $deptShow==='rec'  ? 'active' : '' }}">
          Reception @if($recCount) <span class="count">{{ $recCount }}</span> @endif
        </a>
        <a href="{{ $urlOps  }}" class="pill {{ $deptShow==='ops'  ? 'active' : '' }}">
          Operations @if($opsCount) <span class="count">{{ $opsCount }}</span> @endif
        </a>
      </div>
    </div>

    {{-- META --}}
    <div class="card-section">
      <div class="hd">
        <h3>Summary</h3>
        <span class="badge {{ $badgeClass }}">{!! $badgeIcon !!} {{ strtoupper($log->status) }}</span>
      </div>
      <div class="kv">
        <div class="item">
          <div class="k">Date</div>
          <div class="v">{{ optional($log->log_date)->format('Y-m-d') }}</div>
        </div>
        @if($isSuper)
          <div class="item">
            <div class="k">Staff</div>
            <div class="v">{{ $log->admin->name ?? '—' }}</div>
          </div>
        @endif
      </div>
      <div class="summary">
        <div class="k" style="font-size:var(--fs-12);color:var(--muted);margin-bottom:6px">End-of-Day Notes</div>
        <div style="white-space:pre-wrap">{{ $log->summary ?: '—' }}</div>
      </div>
    </div>

    {{-- PRODUCTION --}}
    @if($deptShow==='all' || $deptShow==='prod')
      @php
        $rows = array_values(array_filter($prodRows, function($r){
          foreach ($r as $i=>$v) { if ($i!==4 && trim((string)($v ?? ''))!=='') return true; }
          return false;
        }));
      @endphp

      @if(!empty($rows))
      <div class="card-section">
        <div class="hd"><h3>Production</h3></div>
        <div style="padding:0 14px 14px 14px">
          <table class="view" aria-label="Production table">
            <thead>
              <tr>
                <th style="width:90px">Time</th>
                <th style="width:200px">Task / Job</th>
                <th>Details / Asset</th>
                <th style="width:120px">By</th>
                <th style="width:140px">Status</th>
                <th style="width:160px">Notes</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $r)
                <tr>
                  <td>{{ $r[0] ?? '' }}</td>
                  <td>{{ $r[1] ?? '' }}</td>
                  <td>{{ $r[2] ?? '' }}</td>
                  <td>{{ $r[3] ?? '' }}</td>
                  <td>{{ $r[4] ?? '' }}</td>
                  <td>{{ $r[5] ?? '' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @elseif($deptShow==='prod')
        <div class="muted">No production entries.</div>
      @endif
    @endif

    {{-- RECEPTION --}}
    @if($deptShow==='all' || $deptShow==='rec')
      @php
        $rows = array_values(array_filter($recRows, function($r){
          foreach ($r as $i=>$v) { if ($i!==4 && trim((string)($v ?? ''))!=='') return true; }
          return false;
        }));
      @endphp

      @if(!empty($rows))
      <div class="card-section">
        <div class="hd"><h3>Reception</h3></div>
        <div style="padding:0 14px 14px 14px">
          <table class="view" aria-label="Reception table">
            <thead>
              <tr>
                <th style="width:90px">Time</th>
                <th style="width:220px">Name / Org</th>
                <th>Purpose / Message</th>
                <th style="width:170px">Forwarded To</th>
                <th style="width:140px">Mode</th>
                <th style="width:160px">Outcome / Next</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $r)
                <tr>
                  <td>{{ $r[0] ?? '' }}</td>
                  <td>{{ $r[1] ?? '' }}</td>
                  <td>{{ $r[2] ?? '' }}</td>
                  <td>{{ $r[3] ?? '' }}</td>
                  <td>{{ $r[4] ?? '' }}</td>
                  <td>{{ $r[5] ?? '' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @elseif($deptShow==='rec')
        <div class="muted">No reception entries.</div>
      @endif
    @endif

    {{-- OPERATIONS --}}
    @if($deptShow==='all' || $deptShow==='ops')
      @php
        $rows = array_values(array_filter($opsRows, function($r){
          foreach ($r as $i=>$v) { if ($i!==4 && trim((string)($v ?? ''))!=='') return true; }
          return false;
        }));
      @endphp

      @if(!empty($rows))
      <div class="card-section">
        <div class="hd"><h3>Operations</h3></div>
        <div style="padding:0 14px 14px 14px">
          <table class="view" aria-label="Operations table">
            <thead>
              <tr>
                <th style="width:90px">Time</th>
                <th style="width:200px">Action</th>
                <th>Client / Ticket / Ref</th>
                <th style="width:150px">Owner</th>
                <th style="width:140px">Status</th>
                <th style="width:160px">Remarks</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $r)
                <tr>
                  <td>{{ $r[0] ?? '' }}</td>
                  <td>{{ $r[1] ?? '' }}</td>
                  <td>{{ $r[2] ?? '' }}</td>
                  <td>{{ $r[3] ?? '' }}</td>
                  <td>{{ $r[4] ?? '' }}</td>
                  <td>{{ $r[5] ?? '' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @elseif($deptShow==='ops')
        <div class="muted">No operations entries.</div>
      @endif
    @endif
  </div>
</div>
@endsection
