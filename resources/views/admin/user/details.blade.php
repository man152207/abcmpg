@extends('admin.layout.layout')

@section('content')

<div class="container-fluid">
    <div class="card my-2">
        <div class="card-header2 d-flex justify-content-between align-items-center">
            <h3>User Details: {{ $user->name }}</h3>
            <a href="{{ url('/admin/dashboard/user/list') }}" class="btn btn-primary">Back to Users</a>
        </div>
        <div class="card-body2">
            <div class="row-custom">
                <div class="column-custom">
                    <h4>User Activities</h4>
                    
                    @if($userActivity)
                        <div class="user-activity">
                            <label>Login Time and Date:</label> {{ $userActivity->login_time ?? 'N/A' }}
                        </div>
                        <div class="user-activity">
  <span data-field="active_minutes">{{ (int)($userActivity->active_hours ?? 0) }}</span> minutes
</div>
                        <div class="user-activity">
                            <label>Login Real-Time Location:</label> {{ $userActivity->location ?? 'N/A' }}
                        </div>
<div class="user-activity">
  <label>Frequently Visited Pages:</label>
@php
  $freq = is_array($userActivity->frequent_page)
       ? $userActivity->frequent_page
       : (json_decode($userActivity->frequent_page ?? '[]', true) ?: []);
@endphp
@if($freq && is_array($freq) && count($freq))
  <div class="table-responsive">
    <table class="table table-sm table-striped">
      <thead>
        <tr>
          <th>Page</th>
          <th class="text-right">Visits</th>
        </tr>
      </thead>
      <tbody>
        @foreach($freq as $key => $count)
          @php
            $pretty = $pageNameMap[$key] ?? $key;
            $pretty = preg_replace('#/(\d+)(/|$)#', '/:id$2', $pretty);
          @endphp
          <tr>
            <td>{{ $pretty }}</td>
            <td class="text-right">{{ $count }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  <p>No pages visited yet.</p>
@endif
</div>
                        <div class="user-activity">
  <span data-field="inactive_minutes">{{ (int)($userActivity->inactive_time ?? 0) }}</span> minutes
</div>
<div class="row mt-3">
  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Ads (7 दिन)</div>
    <div class="h3 font-weight-bold">{{ $kpis['ads_created'] ?? 0 }}</div>
  </div></div></div>

  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Invoices (7 दिन)</div>
    <div class="h3 font-weight-bold">{{ $kpis['invoices_created'] ?? 0 }}</div>
  </div></div></div>

  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Clients (7 दिन)</div>
    <div class="h3 font-weight-bold">{{ $kpis['clients_added'] ?? 0 }}</div>
  </div></div></div>

  <div class="col-md-3"><div class="card text-center"><div class="card-body2">
    <div class="h6 mb-1">Ad Amount (7 दिन)</div>
    <div class="h6 mb-0">
      Rs. {{ number_format($kpis['nrp'] ?? 0, 2, '.', ',') }}<br>
      $ {{ number_format($kpis['usd'] ?? 0, 2, '.', ',') }}
    </div>
  </div></div></div>
</div>
<div class="row mt-3">
  {{-- Customers --}}
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">Customers (All-time)</div>
        <div class="h3 font-weight-bold">{{ $kpis['customers_total'] ?? 0 }}</div>
        <div class="small text-muted mt-1">Last 7 days: {{ $kpis['customers_7d'] ?? 0 }}</div>
      </div>
    </div>
  </div>

  {{-- Multimedia --}}
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">Multimedia (All-time)</div>
        <div class="h3 font-weight-bold">{{ $kpis['multimedia_total'] ?? 0 }}</div>
        <div class="small text-muted mt-1">Last 7 days: {{ $kpis['multimedia_7d'] ?? 0 }}</div>
      </div>
    </div>
  </div>

  {{-- Follow-up Contacts --}}
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">CRM Contacts (All-time)</div>
        <div class="h3 font-weight-bold">{{ $kpis['fu_contacts_total'] ?? 0 }}</div>
        <div class="small text-muted mt-1">Last 7 days: {{ $kpis['fu_contacts_7d'] ?? 0 }}</div>
      </div>
    </div>
  </div>

  {{-- Follow-ups --}}
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body2">
        <div class="h6 mb-1">Follow-ups (All-time)</div>
        <div class="h3 font-weight-bold">{{ $kpis['followups_total'] ?? 0 }}</div>
        <div class="small text-muted mt-1">Last 7 days: {{ $kpis['followups_7d'] ?? 0 }}</div>
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

@push('scripts')
<script>
(function(){
  const labels = {!! json_encode($dailyLabels ?? []) !!};
  const data   = {!! json_encode($dailyCounts ?? []) !!};

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
@endpush
<div class="card mt-3">
  <div class="card-header2">भर्खरका कामहरू</div>
  <div class="card-body2">
    <ul class="list-unstyled mb-0">
      @forelse($recentAds as $row)
        <li class="mb-1">
          <strong>{{ \Carbon\Carbon::parse($row->created_at)->format('d M, h:i A') }}</strong>
          — Ad #{{ $row->id }}
        </li>
      @empty
      @endforelse

      @forelse($recentInv as $row)
        <li class="mb-1">
          <strong>{{ \Carbon\Carbon::parse($row->created_at)->format('d M, h:i A') }}</strong>
          — Invoice #{{ $row->id }}
        </li>
      @empty
      @endforelse

      @forelse($recentCli as $row)
        <li class="mb-1">
          <strong>{{ \Carbon\Carbon::parse($row->created_at)->format('d M, h:i A') }}</strong>
          — Client #{{ $row->id }}
        </li>
      @empty
      @endforelse

      @if(($recentAds->count()+$recentInv->count()+$recentCli->count())===0)
        <li>No recent items.</li>
      @endif
    </ul>
  </div>
</div>


<div class="user-activity">
  <label>Active Periods:</label>
  @if($userActivity->last_active_start && $userActivity->last_active_end)
    <p>From: {{ \Carbon\Carbon::parse($userActivity->last_active_start)->format('d M Y, h:i A') }}
       to {{ \Carbon\Carbon::parse($userActivity->last_active_end)->format('d M Y, h:i A') }}</p>
    <p>Total Active Duration: {{ (int)($userActivity->active_hours ?? 0) }} minutes</p>
  @else
    <p>No active period data available.</p>
  @endif
</div>
                        <div class="user-activity">
                            <label>Inactive Periods:</label>
                            <p>Total Inactive Duration: {{ $userActivity->inactive_time ?? 'N/A' }} minutes</p>
                        </div>
                    @else
                        <p>No user activity data available.</p>
                    @endif
                </div>

                <div class="column-custom">
                    <h4>User Details</h4>
@php
    use Illuminate\Support\Str;
    // $user->profile_picture भित्र कहिले काँही URL (http/https) हुन सक्छ, कहिले relative path
    $img = $user->profile_picture
        ? (Str::startsWith($user->profile_picture, ['http://','https://'])
            ? $user->profile_picture
            : asset('storage/'.$user->profile_picture))
        : null;
@endphp

@if($img)
    <img src="{{ $img }}" alt="{{ $user->name }}" class="profile-picture-large"
         class="user-profile-pic">
@else
    <i class="fas fa-user-circle user-profile-icon"></i>
@endif
                    <div class="user-details">
                        <label>Status:</label>
                        <span id="userStatus">
                            @if($isOnline)
                                <span class="status-online"><i class="fas fa-circle"></i> Online</span>
                            @else
                                <span class="status-offline"><i class="fas fa-circle"></i> Offline</span>
                            @endif
                        </span>
                    </div>
                    <div class="user-details">
                        <label>Full Name:</label> {{ $user->full_name ?? 'N/A' }}
                    </div>
                    <div class="user-details">
                        <label>Name:</label> {{ $user->name }}
                    </div>
                    <div class="user-details">
                        <label>Email:</label> {{ $user->email }}
                    </div>
                    <div class="user-details">
                        <label>Phone:</label> {{ $user->phone }}
                    </div>
                    <div class="user-details">
                        <label>Registered On:</label> {{ $user->created_at->format('d M Y, h:i A') }}
                    </div>
                <div class="user-details">
    <label>Departments:</label>
    @php $deps = $user->departments ?? collect(); @endphp
    @forelse($deps as $d)
        <span class="badge badge-primary mr-1">{{ $d->name }}</span>
    @empty
        <span class="text-muted">Not assigned</span>
    @endforelse
</div>
                    <div class="mt-4">
                        <a href="{{ url('/admin/dashboard/user/edit/' . $user->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ url('/admin/dashboard/user/delete/' . $user->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                        <a href="{{ url('/admin/dashboard/user/privilege/' . $user->id) }}" class="btn btn-warning"><i class="fas fa-key"></i> Edit Privilege</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setInterval(function() {
        $.ajax({
            url: '/admin/dashboard/user/check-status/{{ $user->id }}',
            success: function(data) {
                if (data.isOnline) {
                    $('#userStatus').html('<span class="status-online"><i class="fas fa-circle"></i> Online</span>');
                } else {
                    $('#userStatus').html('<span class="status-offline"><i class="fas fa-circle"></i> Offline</span>');
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
  const pingUrl = "{{ route('activity.ping') }}";

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
  const actUrl = "{{ route('admin.user.activity', $user->id) }}";
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

@endsection
