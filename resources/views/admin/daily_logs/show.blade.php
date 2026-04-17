@extends('admin.layout.layout')

@section('content')
<div id="logShow" class="card">
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
      <div class="log-sub-hd">Readable summary and structured record of the day</div>
    </div>
    <span class="badge {{ $badgeClass }}">{!! $badgeIcon !!} {{ strtoupper($log->status) }}</span>
  </div>

  <div class="card-body2">
    <div class="toolbar mb-3">
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
        <div class="k log-eod-label">End-of-Day Notes</div>
        <div class="log-prewrap">{{ $log->summary ?: '—' }}</div>
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
        <div class="log-section">
          <table class="view log-tbl prod-v" aria-label="Production table">
            <thead>
              <tr>
                <th>Time</th>
                <th>Task / Job</th>
                <th>Details / Asset</th>
                <th>By</th>
                <th>Status</th>
                <th>Notes</th>
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
        <div class="log-section">
          <table class="view log-tbl rec-v" aria-label="Reception table">
            <thead>
              <tr>
                <th>Time</th>
                <th>Name / Org</th>
                <th>Purpose / Message</th>
                <th>Forwarded To</th>
                <th>Mode</th>
                <th>Outcome / Next</th>
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
        <div class="log-section">
          <table class="view log-tbl ops-v" aria-label="Operations table">
            <thead>
              <tr>
                <th>Time</th>
                <th>Action</th>
                <th>Client / Ticket / Ref</th>
                <th>Owner</th>
                <th>Status</th>
                <th>Remarks</th>
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
