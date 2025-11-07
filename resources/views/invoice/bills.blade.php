@extends('admin.layout.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Icons (remove if you already load globally) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

<style>
  :root{
    --bg: #f7f9fc;
    --card: #ffffff;
    --muted: #6b7280;
    --text: #111827;
    --primary: #2563eb;
    --primary-2: #7c3aed;
    --success: #059669;
    --danger: #dc2626;
    --warning: #d97706;
    --info: #0891b2;
    --chip: #f3f4f6;
    --row-hover: rgba(37, 99, 235, .06);
    --border: #e5e7eb;
  }

  .bill-wrap{color:var(--text)}
  .bill-hero{
    background:
      radial-gradient(900px 240px at -10% -10%, rgba(124,58,237,.08), transparent 50%),
      radial-gradient(700px 200px at 110% -5%, rgba(37,99,235,.10), transparent 50%),
      linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px 18px 12px;
    box-shadow: 0 8px 22px rgba(17,24,39,.06), inset 0 1px 0 rgba(255,255,255,.6);
  }
  .bill-title{
    font-weight: 800; letter-spacing:.2px; margin:0 0 6px; display:flex; gap:10px; align-items:center;
  }
  .bill-title .tag{
    background: linear-gradient(90deg,var(--primary),var(--primary-2));
    -webkit-background-clip:text; background-clip:text; color:transparent; font-weight:800;
  }

  .controls{display:flex; gap:12px; align-items:center; flex-wrap:wrap}
  .stats{display:flex; flex-wrap:wrap; gap:8px}
  .chip{
    background: var(--chip);
    border:1px solid var(--border);
    color:var(--muted);
    padding:6px 10px; border-radius:999px; font-size:12px; display:inline-flex; align-items:center; gap:8px;
  }
  .chip .dot{width:8px;height:8px;border-radius:999px; display:inline-block}
  .dot-success{background:var(--success)} .dot-danger{background:var(--danger)} .dot-info{background:var(--info)}
  .chip strong{color:var(--text); font-weight:800}

  .searchbar{position:relative; display:flex; gap:10px; align-items:center; margin-top:12px}
  .searchbar input{
    background:#fff; border:1px solid var(--border); color:var(--text);
    border-radius:12px; padding:10px 42px 10px 38px; outline:none; width:100%;
    box-shadow: 0 1px 0 rgba(17,24,39,.02);
  }
  .searchbar .icon{position:absolute; left:12px; color:var(--muted)}
  .searchbar button{
    border-radius:12px; padding:10px 16px; border:none;
    background:linear-gradient(90deg,var(--primary),var(--primary-2)); color:#fff; font-weight:700
  }

  .toggle{
    display:inline-flex; align-items:center; gap:8px; padding:6px 10px;
    border:1px solid var(--border); border-radius:999px; background:#fff; color:#text; font-size:12px; cursor:pointer
  }
  .toggle input{accent-color:var(--primary)}
  .thin{font-variant-numeric:tabular-nums}

  /* Table shell */
  .table-shell{
    margin-top:14px; border:1px solid var(--border); border-radius:14px; overflow:hidden; background:#fff;
    box-shadow: 0 6px 18px rgba(17,24,39,.06);
  }
  table.fancy{width:100%; border-collapse:separate; border-spacing:0; color:var(--text)}
  .fancy thead th{
    position:sticky; top:0; z-index:5;
    background: #f8fafc;
    color:#334155; font-weight:800; text-transform:uppercase; font-size:11px; letter-spacing:.6px;
    padding:12px 12px; border-bottom:1px solid var(--border);
  }
  .fancy tbody td{border-bottom:1px solid var(--border); padding:12px; vertical-align:middle; background:#fff}
  .fancy tbody tr:hover td{background:var(--row-hover)}

  .avatar{width:32px;height:32px;border-radius:999px; object-fit:cover; border:1px solid var(--border)}
  .sub{color:var(--muted); font-size:12px}

  .badge{
    padding:3px 8px; border-radius:999px; font-size:12px; font-weight:700; border:1px solid transparent;
    display:inline-flex; gap:6px; align-items:center;
  }
  .badge-success{background:#ecfdf5; color:#065f46; border-color:#a7f3d0}
  .badge-danger{background:#fef2f2; color:#991b1b; border-color:#fecaca}
  .badge-info{background:#ecfeff; color:#155e75; border-color:#a5f3fc}
  .badge-warn{background:#fffbeb; color:#92400e; border-color:#fde68a}

  .pill{padding:2px 8px;border-radius:999px;font-size:11px;border:1px solid var(--border);color:#334155;background:#f8fafc}
  .pill.pending,.pill-paused,.pill-baki{background:#fef2f2; color:#991b1b; border-color:#fecaca}
  .pill-fpy-received,.pill-esewa-received{background:#ecfeff; color:#155e75; border-color:#a5f3fc}
  .pill-pv-adjusted{background:#fffbeb; color:#92400e; border-color:#fde68a}

  .select-shell{position:relative}
  .select-shell select{
    appearance:none; -webkit-appearance:none;
    background:#fff url("data:image/svg+xml,%3Csvg width='16' height='16' fill='%236b7280' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4'/%3E%3C/svg%3E") no-repeat right .6rem center/16px;
    border:1px solid var(--border); color:var(--text); border-radius:10px; padding:8px 32px 8px 10px; width:100%;
  }
  .select-shell[data-state="Bill Not Sent"] select{ box-shadow: inset 0 0 0 1px rgba(220,38,38,.25) }
  .select-shell[data-state="Bill Issued"]    select{ box-shadow: inset 0 0 0 1px rgba(217,119,6,.25) }
  .select-shell[data-state="Bill Sent"]      select{ box-shadow: inset 0 0 0 1px rgba(5,150,105,.25) }

  .wa-actions{display:flex; gap:6px; align-items:center}
  .btn-icon{
    display:inline-flex; justify-content:center; align-items:center; width:32px;height:32px;border-radius:8px;
    border:1px solid var(--border); background:#fff; color:#2563eb;
  }
  .btn-icon.copy{color:#0891b2}
  .btn-icon:hover{transform:translateY(-1px); box-shadow:0 6px 14px rgba(17,24,39,.1)}

  .hide-md{ }
  @media (max-width: 992px){
    .hide-md{display:none}
    .fancy tbody td, .fancy thead th{padding:10px}
  }

  .toast{
    position:fixed; right:18px; bottom:18px; background:#ffffff; color:#065f46;
    border:1px solid #a7f3d0; padding:10px 14px; border-radius:10px;
    box-shadow:0 10px 24px rgba(17,24,39,.14); display:none; z-index:9999
  }
  .toast.danger{color:#991b1b; border-color:#fecaca}

  /* ================================
     BILLING STATUS–BASED ROW COLORS
     ================================ */

  /* Bill Not Sent => #006d77 background, white text */
  .fancy tbody tr[data-billstate="Bill Not Sent"] td{
    background:#006d77 !important;
    color:#fff;
  }
  .fancy tbody tr[data-billstate="Bill Not Sent"] .sub{ color:#e5e7eb; }
  .fancy tbody tr[data-billstate="Bill Not Sent"] a{ color:#fff !important; }
  .fancy tbody tr[data-billstate="Bill Not Sent"] .badge{
    background:rgba(255,255,255,.12); color:#fff; border-color:rgba(255,255,255,.35);
  }
  .fancy tbody tr[data-billstate="Bill Not Sent"] .pill{
    background:rgba(255,255,255,.12); color:#fff; border-color:rgba(255,255,255,.35);
  }
  .fancy tbody tr[data-billstate="Bill Not Sent"] .btn,
  .fancy tbody tr[data-billstate="Bill Not Sent"] .btn-icon{
    background:rgba(255,255,255,.08); color:#fff; border-color:rgba(255,255,255,.35);
  }
  .fancy tbody tr[data-billstate="Bill Not Sent"]:hover td{
    background:#006d77 !important;
  }
  .fancy tbody tr[data-billstate="Bill Not Sent"] select{
    background:rgba(255,255,255,.12);
    color:#fff;
    border-color:rgba(255,255,255,.35);
  }

  /* Bill Issued => #83c5be background, black text */
  .fancy tbody tr[data-billstate="Bill Issued"] td{
    background:#83c5be !important;
    color:#111827;
  }
  .fancy tbody tr[data-billstate="Bill Issued"] .sub{ color:#1f2937; }
  .fancy tbody tr[data-billstate="Bill Issued"] a{ color:#111827 !important; }
  .fancy tbody tr[data-billstate="Bill Issued"] .badge{
    background:rgba(255,255,255,.70); color:#111827; border-color:rgba(0,0,0,.06);
  }
  .fancy tbody tr[data-billstate="Bill Issued"] .pill{
    background:rgba(255,255,255,.70); color:#111827; border-color:rgba(0,0,0,.06);
  }
  .fancy tbody tr[data-billstate="Bill Issued"]:hover td{
    background:#83c5be !important;
  }
</style>

@php
  $dueStatuses = $duePaymentStatuses ?? ['Pending','Paused','Baki'];
  $pageDueCount = 0; $pageDueNpr = 0.0;
  foreach ($ads as $x) {
      if (in_array($x->ad_payment, $dueStatuses)) { $pageDueCount++; $pageDueNpr += (float)($x->ad_npr ?? 0); }
  }
@endphp

<div class="bill-wrap">

  {{-- HEADER --}}
  <div class="bill-hero">
    <div class="bill-title">
      <h3 class="mb-0">Pending Bills <span class="tag">• per-Ad</span></h3>
    </div>

    <div class="controls" style="margin-top:6px;">
      <div class="stats">
        <span class="chip"><span class="dot dot-info"></span> Rows <strong>{{ $ads->count() }}</strong></span>
        <span class="chip"><span class="dot dot-danger"></span> Due Rows <strong>{{ $pageDueCount }}</strong></span>
        <span class="chip"><span class="dot dot-success"></span> Due (Page) <strong>Rs. {{ number_format($pageDueNpr,2) }}</strong></span>
      </div>

      <label class="toggle ms-auto">
        <input id="toggleDueOnly" type="checkbox"> Show only Due
      </label>
    </div>

    <form method="GET" action="{{ route('invoice.pendingBills') }}" class="searchbar">
      <i class="fa-solid fa-magnifying-glass icon"></i>
      <div class="w-100">
        <input name="search" value="{{ $search }}" placeholder="Search by name, display name or phone…">
      </div>
      <button type="submit"><i class="fa-solid fa-filter"></i>&nbsp; Search</button>
    </form>
  </div>

  {{-- TABLE --}}
  <div class="table-shell">
    <table class="fancy">
      <thead>
        <tr>
          <th>Customer</th>
          <th>WhatsApp</th>
          <th class="hide-md">Amount (NPR)</th>
          <th>Due</th>
          <th class="hide-md">Last Activity</th>
          <th>Ad</th>
          <th>Billing Status</th>
          <th class="hide-md">Actions</th>
        </tr>
      </thead>
      <tbody id="billRows">
      @forelse($ads as $ad)
        @php
          $isDue = in_array($ad->ad_payment, $dueStatuses);
          $paySlug = \Illuminate\Support\Str::slug($ad->ad_payment, '-');

          // If DB value is empty, default by payment type:
          // - Due (Pending/Paused/Baki)  => "Bill Not Sent"
          // - Non-due (others)           => "Bill Sent"
          $currentState = $ad->ad_billing_status
              ?: ($isDue ? 'Bill Not Sent' : 'Bill Sent');
        @endphp

        <tr
          data-due="{{ $isDue ? '1' : '0' }}"
          data-billstate="{{ $currentState }}"
        >
          <td>
            <div class="d-flex align-items-center gap-2">
              <img class="avatar" src="{{ asset('uploads/customers/' . ($ad->customer_profile_picture ?: 'default.jpg')) }}" alt="">
              <div>
                <a href="{{ route('customer.details', ['id' => $ad->customer_id]) }}" class="text-decoration-none" style="color:var(--text)">
                  <strong>{{ $ad->customer_display_name ?? $ad->customer_name }}</strong>
                </a>
                <div class="sub">{{ $ad->customer_email }}</div>
              </div>
            </div>
          </td>

          <td>
            <div class="wa-actions">
              <span class="thin">{{ $ad->customer_phone }}</span>
              <a class="btn-icon" title="Open WhatsApp" target="_blank" href="https://wa.me/977{{ preg_replace('/\D/','',$ad->customer_phone) }}">
                <i class="fa-brands fa-whatsapp"></i>
              </a>
              <button type="button" class="btn-icon copy" title="Copy number" data-copy="{{ $ad->customer_phone }}">
                <i class="fa-regular fa-copy"></i>
              </button>
            </div>
          </td>

          <td class="hide-md">Rs. {{ number_format($ad->ad_npr ?? 0, 2) }}</td>

          <td>
            @if($isDue)
              <span class="badge badge-danger">Rs. {{ number_format($ad->ad_npr ?? 0, 2) }}</span>
            @else
              <span class="badge badge-success">No Due</span>
            @endif
          </td>

          <td class="hide-md thin">{{ \Carbon\Carbon::parse($ad->ad_created_at)->format('Y-m-d H:i') }}</td>

          <td>
            <div class="d-flex flex-column">
              <span class="thin">#{{ $ad->ad_id }}</span>
              <span class="pill pill-{{ $paySlug }}">{{ $ad->ad_payment }}</span>
            </div>
          </td>

          <td style="min-width:210px">
            <div class="select-shell billing-select"
                  data-state="{{ $currentState }}"
                  data-action="{{ route('invoice.updateBillingStatus', $ad->ad_id) }}">
              <select name="billing_status" data-ad="{{ $ad->ad_id }}">
                @foreach($billingStatuses as $status)
                  <option value="{{ $status }}" {{ $currentState === $status ? 'selected' : '' }}>
                    {{ $status }}
                  </option>
                @endforeach
              </select>
            </div>
          </td>

          <td class="hide-md">
            <div class="d-flex gap-2">
              <a href="{{ route('customer.details', ['id' => $ad->customer_id]) }}" class="btn btn-sm btn-outline-primary">View</a>
              <a href="{{ route('admin.customer.impersonate', $ad->customer_id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Portal</a>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center sub">No ads found for customers that require bill.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-2">
    {{ $ads->withQueryString()->links('pagination::bootstrap-5') }}
  </div>
</div>

<div id="toast" class="toast"></div>

<script>
  // Copy phone
  document.querySelectorAll('.btn-icon.copy').forEach(btn=>{
    btn.addEventListener('click',()=>{
      const v = btn.getAttribute('data-copy') || '';
      navigator.clipboard.writeText(v).then(()=>showToast('Phone copied'));
    });
  });

  // Show only Due toggle
  const dueToggle = document.getElementById('toggleDueOnly');
  const rows = document.getElementById('billRows')?.rows || [];
  dueToggle?.addEventListener('change',()=>{
    const onlyDue = dueToggle.checked;
    [...rows].forEach(r=>{
      const isDue = r.getAttribute('data-due') === '1';
      r.style.display = (onlyDue && !isDue) ? 'none' : '';
    });
  });

  // AJAX billing status (no reload)
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  document.querySelectorAll('.billing-select select').forEach(sel=>{
    sel.dataset.prev = sel.value;
    sel.addEventListener('change', async function(){
      const val = this.value;
      const action = this.closest('.billing-select').getAttribute('data-action');

      try{
        const res = await fetch(action, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json', 'Content-Type':'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ _method:'PATCH', billing_status: val })
        });
        if(!res.ok){ throw new Error('Request failed'); }
        showToast('Billing status updated');

        // Update select-shell visual state
        const shell = this.closest('.billing-select');
        shell.setAttribute('data-state', val);
        this.dataset.prev = val;

        // Update row color instantly based on new billing status
        const tr = this.closest('tr');
        tr && tr.setAttribute('data-billstate', val);
      }catch(e){
        this.value = this.dataset.prev || this.value;
        showToast('Update failed', true);
      }
    });
  });

  function showToast(msg, danger=false){
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.toggle('danger', !!danger);
    t.style.display = 'block';
    clearTimeout(window.__toastTimer);
    window.__toastTimer = setTimeout(()=>{ t.style.display='none'; }, 1500);
  }
</script>
@endsection
