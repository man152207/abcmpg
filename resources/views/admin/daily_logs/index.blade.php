@extends('admin.layout.layout')

@section('content')
<div class="card">
  <div class="card-header2">
    <div>
      {{ $isSuper ? 'Daily Logs (All Team)' : 'My Daily Logs' }}
      <div class="sub">Track submissions, review summaries, and manage logs with ease.</div>
    </div>

    <div class="toolbar" style="gap:8px;">
      <a class="btn btn-primary" href="{{ route('admin.daily-logs.create') }}">
        <!-- plus icon -->
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        New
      </a>

      @if($isSuper)
        @php
          $qs = request()->except(['page']);
          $mineUrl = request()->fullUrlWithQuery(array_merge($qs, ['mine'=>1, 'page'=>null]));
          $allUrl  = route('admin.daily-logs.index');
          $onlyMineActive = request('mine')==1;
        @endphp
        <span class="seg">
          <a class="btn {{ !$onlyMineActive ? 'active' : '' }}" href="{{ $allUrl }}">All Team</a>
          <a class="btn {{ $onlyMineActive ? 'active' : '' }}" href="{{ $mineUrl }}">Only My Logs</a>
        </span>
      @endif
    </div>
  </div>

  <div class="card-body2">
    <div class="toolbar mb-3">
      <form method="get" class="ml-auto" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        @foreach(request()->except(['from','to','page']) as $k=>$v)
          <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach

        <input type="date" name="from" value="{{ request('from') }}" placeholder="From" aria-label="From date">
        <input type="date" name="to"   value="{{ request('to')   }}" placeholder="To" aria-label="To date">
        <button class="btn btn-outline" type="submit">
          <!-- filter icon -->
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          Filter
        </button>

        @if(request()->hasAny(['from','to','mine']))
          <a class="btn btn-outline" href="{{ route('admin.daily-logs.index') }}">
            <!-- x icon -->
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Clear
          </a>
        @endif
      </form>
    </div>

    <div class="table-responsive tbl-cards">
      <table class="table table-sm">
        <thead>
          <tr>
            <th>Date</th>
            @if($isSuper)<th>Staff</th>@endif
            <th>Status</th>
            <th>Summary</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        @forelse($logs as $r)
          @php $st = strtolower($r->status ?? 'submitted'); @endphp
          <tr>
            <td>{{ optional($r->log_date)->format('Y-m-d') ?? '—' }}</td>

            @if($isSuper)
              <td>
                @if($r->admin_id)
                  <a href="{{ route('admin.user.details', $r->admin_id) }}" class="muted" style="font-weight:600; color:#0b1220;">
                    {{ $r->admin->name ?? '—' }}
                  </a>
                @else
                  {{ $r->admin->name ?? '—' }}
                @endif
              </td>
            @endif

            <td>
              <span class="badge badge-{{ $st }}">
                @if($st==='approved')
                  <svg viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                @elseif($st==='draft')
                  <svg viewBox="0 0 24 24" fill="none"><path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                @else
                  <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                @endif
                {{ ucfirst($r->status ?? 'submitted') }}
              </span>
            </td>

            <td title="{{ $r->summary ?? '' }}">
              {{ \Illuminate\Support\Str::limit($r->summary ?? '', 90) ?: '—' }}
            </td>

            <td>
              <div class="actions">
                @can('view',$r)
                  <a class="btn btn-sm btn-outline" href="{{ route('admin.daily-logs.show',$r) }}?dept=auto">
                    <!-- eye -->
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                    View
                  </a>
                @endcan

                @can('update',$r)
                  <a class="btn btn-sm btn-primary" href="{{ route('admin.daily-logs.edit',$r) }}">
                    <!-- pencil -->
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21l3.5-.5L20 7.1 16.9 4 4.5 16.5 4 20z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                    Edit
                  </a>
                @endcan

                @can('delete',$r)
                  <form method="post" action="{{ route('admin.daily-logs.destroy',$r) }}" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this log?')">
                      <!-- trash -->
                      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V5h6v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                      Delete
                    </button>
                  </form>
                @endcan
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="{{ $isSuper ? 5 : 4 }}">
              <div class="empty">
                <span class="dot"></span>
                <div>
                  <div class="muted" style="font-weight:600;">No logs for selected filters.</div>
                  @if(request()->hasAny(['from','to','mine']))
                    <div class="muted">Try adjusting the date range or
                      <a href="{{ route('admin.daily-logs.index') }}" style="text-decoration:underline;">clear filters</a>.
                    </div>
                  @endif
                </div>
              </div>
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{ $logs->links() }}
  </div>
</div>
@endsection
