@extends('admin.layout.layout')

@section('content')
<style>
  :root{
    --fs-base: 14px;
    --fs-sm: 13px;
    --fs-lg: 16px;
  }
  .hidden{display:none}
  .logpage{border:1px solid #000;background:#fff;padding:12px;margin-bottom:16px}
  .sec-hd{display:flex;justify-content:space-between;align-items:center;margin:8px 0}
  .sec-hd h3{margin:0;font-size:var(--fs-lg);text-transform:uppercase;letter-spacing:.5px}
  .hint{font-size:var(--fs-sm);color:#374151;background:#f9fafb;border:1px dashed #e5e7eb;padding:8px;border-radius:8px;margin:-4px 0 10px}
  table.log{width:100%;border-collapse:collapse;table-layout:fixed;background:#fff;font-size:var(--fs-base)}
  table.log thead th{border-bottom:2px solid #000;font-size:var(--fs-sm);padding:8px;text-align:left}
  table.log tbody td{border-bottom:1px dashed #000;padding:8px;vertical-align:top}
  .cell{width:100%;border:none;outline:none;font:inherit;background:transparent;min-height:34px}
  .cell::placeholder{color:#9ca3af}
  .btn{padding:8px 12px;border-radius:8px;border:1px solid #cbd5e1;background:#eef2ff;cursor:pointer;font-size:var(--fs-base)}
  .btn-primary{background:#2563eb;color:#fff;border-color:#2563eb}
  .btn-danger{background:#ef4444;color:#fff;border-color:#ef4444}
  .btn[disabled]{opacity:.5;cursor:not-allowed}
  .pillbar{display:flex;gap:8px;flex-wrap:wrap}
  .pill{padding:10px 14px;border:1px solid #e5e7eb;border-radius:999px;background:#fff;cursor:pointer;font-weight:600}
  .pill.active{background:#111827;color:#fff;border-color:#111827}
  .meta-grid{display:flex;gap:12px;flex-wrap:wrap}
  .meta-grid label{font-weight:600;font-size:var(--fs-sm);display:block;margin-bottom:6px}
  .meta-grid input, .meta-grid select{height:38px;padding:6px 10px;border:1px solid #e5e7eb;border-radius:8px;font-size:var(--fs-base)}
  .sticky-actions{position:sticky;bottom:0;background:#fff;padding:12px;border-top:1px solid #e5e7eb;display:flex;gap:10px}
  .client-badge{font-size:12px; opacity:.8; margin-top:4px}
</style>

<form method="post" action="{{ route('admin.daily-logs.store') }}" id="dailyLogForm">
  @csrf

  {{-- STEP 0: Department चयन – यसपछि मात्र form खुल्ने --}}
  <div class="card p-3" id="deptStep">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap">
      <div>
        <div style="font-size:18px;font-weight:700">Choose Department</div>
        <div style="color:#6b7280">कुन विभागमा log भर्नुछ चयन गर्नुहोस् (पछि पनि switch गर्न सक्नुहुन्छ)।</div>
      </div>
      <div class="pillbar" role="tablist" aria-label="Departments">
        <button class="pill" type="button" data-dept="prod" aria-selected="false">Production</button>
        <button class="pill" type="button" data-dept="rec"  aria-selected="false">Reception</button>
        <button class="pill" type="button" data-dept="ops"  aria-selected="false">Operations</button>
        <button class="pill" type="button" data-dept="all"  aria-selected="false" title="Show all departments">All</button>
      </div>
    </div>
  </div>

  {{-- STEP 1: Meta --}}
  <div class="card p-3 hidden" id="metaCard">
    <div class="meta-grid">
      <div>
        <label for="log_date">Date *</label>
        <input id="log_date" type="date" name="log_date" value="{{ old('log_date', now()->toDateString()) }}" required>
      </div>
      <div>
        <label for="status">Status</label>
        <select id="status" name="status">
          <option value="submitted" selected>Submitted</option>
          <option value="draft" @selected(old('status')==='draft')>Draft</option>
          @if(auth('admin')->id() === 1)
            <option value="approved" @selected(old('status')==='approved')>Approved</option>
          @endif
        </select>
      </div>
    </div>
  </div>

  {{-- PRODUCTION --}}
  <div class="logpage hidden" id="sec_prod" data-sec="prod">
    <div class="sec-hd">
      <h3>Production</h3>
      <div>
        <button class="btn" type="button" onclick="addRow('prod_body','prod')">Add Row</button>
        <button class="btn btn-danger" type="button" onclick="clearRows('prod_body')">Clear</button>
      </div>
    </div>

    <div class="hint">
      भर्नुपर्ने तरिका: <strong>Time</strong> • <strong>Task/Job</strong> •
      <strong>Details</strong> • <strong>By</strong> •
      <strong>Status</strong> • <strong>Notes</strong>
    </div>

    <table class="log" aria-label="Production log table">
      <thead>
        <tr>
          <th style="width:90px">Time</th>
          <th style="width:200px">Task / Job</th>
          <th>Details / Asset</th>
          <th style="width:110px">By</th>
          <th style="width:130px">Status</th>
          <th style="width:160px">Notes</th>
          <th style="width:48px"></th>
        </tr>
      </thead>
      <tbody id="prod_body"></tbody>
    </table>
  </div>

  {{-- RECEPTION --}}
  <div class="logpage hidden" id="sec_rec" data-sec="rec">
    <div class="sec-hd">
      <h3>Reception</h3>
      <div>
        <button class="btn" type="button" onclick="addRow('rec_body','rec')">Add Row</button>
        <button class="btn btn-danger" type="button" onclick="clearRows('rec_body')">Clear</button>
      </div>
    </div>

    <div class="hint">
      भर्नुपर्ने तरिका: <strong>Time</strong> • <strong>Name/Org</strong> •
      <strong>Purpose/Message</strong> • <strong>Forwarded To</strong> •
      <strong>Mode</strong> • <strong>Outcome/Next</strong>
    </div>

    <table class="log" aria-label="Reception log table">
      <thead>
        <tr>
          <th style="width:90px">Time</th>
          <th style="width:200px">Name / Org</th>
          <th>Purpose / Message</th>
          <th style="width:170px">Forwarded To</th>
          <th style="width:140px">Mode</th>
          <th style="width:160px">Outcome / Next</th>
          <th style="width:48px"></th>
        </tr>
      </thead>
      <tbody id="rec_body"></tbody>
    </table>
  </div>

  {{-- OPERATIONS --}}
  <div class="logpage hidden" id="sec_ops" data-sec="ops">
    <div class="sec-hd">
      <h3>Operations</h3>
      <div>
        <button class="btn" type="button" onclick="addRow('ops_body','ops')">Add Row</button>
        <button class="btn btn-danger" type="button" onclick="clearRows('ops_body')">Clear</button>
      </div>
    </div>

    <div class="hint">
      भर्नुपर्ने तरिका: <strong>Time</strong> • <strong>Action</strong> •
      <strong>Client</strong> (phone/name) • <strong>Ticket/Ref</strong> •
      <strong>Owner</strong> • <strong>Status</strong> • <strong>Remarks</strong>
    </div>

    <table class="log" aria-label="Operations log table">
      <thead>
        <tr>
          <th style="width:90px">Time</th>
          <th style="width:200px">Action</th>
          <th style="width:240px">Client</th>
          <th>Ticket / Ref</th>
          <th style="width:150px">Owner</th>
          <th style="width:140px">Status</th>
          <th style="width:160px">Remarks</th>
          <th style="width:48px"></th>
        </tr>
      </thead>
      <tbody id="ops_body"></tbody>
    </table>

    {{-- global datalist for customers --}}
    <datalist id="customers_datalist"></datalist>
  </div>

  <div class="card p-3 hidden" id="summaryCard">
    <label style="font-weight:600;margin-bottom:6px;display:block">End-of-Day Summary</label>
    <textarea name="summary" rows="3" placeholder="आजको मुख्य उपलब्धि, tomorrow को priority, blockers…"
              style="width:100%;border:1px solid #e5e7eb;border-radius:8px;padding:10px;font-size:var(--fs-base)">{{ old('summary') }}</textarea>
  </div>

  {{-- Hidden JSON fields --}}
  <input type="hidden" name="production" id="production_json">
  <input type="hidden" name="reception"  id="reception_json">
  <input type="hidden" name="operations" id="operations_json">

  <div class="sticky-actions hidden" id="actionsBar">
    <button class="btn btn-primary" onclick="return packAndSubmit()" id="saveBtn" disabled>Save Log</button>
    <a class="btn" href="{{ route('admin.daily-logs.index') }}">Cancel</a>
  </div>
</form>

<script>
  /* =========================
   * Settings
   * ========================= */
  const MIN_ROWS = 5; // minimum 5 rows for every section
  const currentAdminName = @json(optional(auth('admin')->user())->name) || 'Me';

  /* =========================
   * Customers lookup (USED by Ops rows)
   * ========================= */
  function customerSelect(phoneEl, nameEl=null, badgeEl=null){
    phoneEl.addEventListener('blur', async () => {
      const raw = phoneEl.value.trim(); if(!raw) return;
      try{
        const res = await fetch(`/admin/customers/lookup-by-phone?q=${encodeURIComponent(raw)}`, {
          headers: {'X-Requested-With':'XMLHttpRequest'}
        });
        const data = await res.json();
        if(data.found){
          const label = `${data.label}${data.phone ? ' — ' + data.phone : ''}`;
          if(nameEl) nameEl.value = label;
          if(badgeEl){ badgeEl.textContent='Found'; badgeEl.style.color='green'; }
        } else {
          if(badgeEl){ badgeEl.textContent='No convert as customer'; badgeEl.style.color='red'; }
        }
      }catch(e){
        console.error(e);
        if(badgeEl){ badgeEl.textContent='Lookup error'; badgeEl.style.color='red'; }
      }
    });

    if(nameEl){
      nameEl.addEventListener('input', e=>{
        const q = e.target.value.trim();
        if(q.length>=2) ensureCustomersDatalist(q);
      });
    }
  }

  let customersCache = [];
  async function ensureCustomersDatalist(q=''){
    const url = q ? `/admin/customers/minimal?q=${encodeURIComponent(q)}` : `/admin/customers/minimal`;
    try{
      const res = await fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}});
      const data = await res.json();
      customersCache = data || [];
      const dl = document.getElementById('customers_datalist');
      if(!dl) return;
      dl.innerHTML = '';
      customersCache.forEach(c=>{
        const opt = document.createElement('option');
        opt.value = `${c.label}${c.phone ? ' — ' + c.phone : ''}`;
        dl.appendChild(opt);
      });
    }catch(e){
      console.error(e);
    }
  }
  // preload some names (non-blocking)
  ensureCustomersDatalist();

  /* =========================
   * Utilities
   * ========================= */
  function setPlaceholders(inputs, phs){
    inputs.forEach((el,i)=>{ if(el) el.placeholder = phs[i] || ''; });
  }
  function cell(tag='input') {
    if(tag==='select') return document.createElement('select');
    if(tag==='textarea'){ const t=document.createElement('textarea'); t.rows=1; return t; }
    const i=document.createElement('input'); i.type='text'; return i;
  }
  function td(el){ const d=document.createElement('td'); el.className='cell'; d.appendChild(el); return d; }

  function rowsCount(tbodyId){
    const tb=document.getElementById(tbodyId);
    return tb ? tb.querySelectorAll('tr').length : 0;
  }

  function updateDeleteButtons(tbodyId){
    const tb=document.getElementById(tbodyId);
    if(!tb) return;
    const count = rowsCount(tbodyId);
    tb.querySelectorAll('button[data-del="1"]').forEach(btn=>{
      btn.disabled = count <= MIN_ROWS;
      btn.title = btn.disabled ? `Minimum ${MIN_ROWS} rows required` : 'Delete row';
    });
  }

  function delBtn(tbodyId){
    const b=document.createElement('button');
    b.type='button';
    b.className='btn btn-danger';
    b.textContent='X';
    b.dataset.del='1';
    b.addEventListener('click', (e)=>{
      const tb=document.getElementById(tbodyId);
      if(!tb) return;
      const count = rowsCount(tbodyId);
      if(count <= MIN_ROWS){
        updateDeleteButtons(tbodyId);
        return;
      }
      e.target.closest('tr').remove();
      updateDeleteButtons(tbodyId);
    });
    return b;
  }

  /* =========================
   * Row builder (Production / Reception / Operations)
   * ========================= */
  function addRow(tbodyId,type){
    const tb=document.getElementById(tbodyId);
    const tr=document.createElement('tr');

    if(type==='prod'){
      const t1=cell(), t2=cell(), t3=cell('textarea'), t4=cell();
      const status=cell('select'); ['Open','In Progress','Done','Hold'].forEach(v=>status.add(new Option(v,v)));
      const t6=cell('textarea');
      [t1,t2,t3,t4,status,t6].forEach(el=>tr.appendChild(td(el)));
      setPlaceholders([t1,t2,t3,t4,t6],
        ['10:30','Flyer Design – Client','A3 poster 3 concepts (#123)', currentAdminName, 'Client review pending']
      );
      t4.value = currentAdminName;

    } else if(type==='rec'){
      const t1=cell(), t2=cell(), t3=cell('textarea'), t4=cell();
      const mode=cell('select'); ['Call','Walk-in','Courier','Email','WhatsApp'].forEach(v=>mode.add(new Option(v,v)));
      const t6=cell('textarea');
      [t1,t2,t3,t4,mode,t6].forEach(el=>tr.appendChild(td(el)));
      setPlaceholders([t1,t2,t3,t4,t6],
        ['11:15','Ramesh / Sajha Suvidha','Quotation about video','Operations','Follow-up tomorrow']
      );

    } else { // ops
      const time=cell(), action=cell();

      // Client widget: phone + name (datalist) + badge
      const clientTd = document.createElement('td');

      const phone = document.createElement('input');
      phone.type='text'; phone.className='cell'; phone.placeholder='WhatsApp / Phone';

      const name = document.createElement('input');
      name.type='text'; name.className='cell'; name.setAttribute('list','customers_datalist');
      name.placeholder='Search customer name';

      const badge = document.createElement('div');
      badge.className='client-badge';
      badge.textContent='—';

      clientTd.appendChild(phone);
      clientTd.appendChild(name);
      clientTd.appendChild(badge);

      const ref=cell('textarea');
      const owner=cell(); owner.value=currentAdminName;
      const status=cell('select'); ['Open','In Progress','Done','Pending Client'].forEach(v=>status.add(new Option(v,v)));
      const remarks=cell('textarea');

      tr.appendChild(td(time));
      tr.appendChild(td(action));
      tr.appendChild(clientTd);
      tr.appendChild(td(ref));
      tr.appendChild(td(owner));
      tr.appendChild(td(status));
      tr.appendChild(td(remarks));

      setPlaceholders([time,action,ref,owner,remarks],
        ['14:00','Ads Management','Ticket #456', currentAdminName, 'Awaiting docs']
      );

      // NOW safe: this function is already defined above
      customerSelect(phone, name, badge);
    }

    const tdd=document.createElement('td');
    tdd.appendChild(delBtn(tbodyId));
    tr.appendChild(tdd);

    tb.appendChild(tr);
    updateDeleteButtons(tbodyId);
  }

  function ensureMinRows(tbodyId, type){
    let c = rowsCount(tbodyId);
    while(c < MIN_ROWS){
      addRow(tbodyId, type);
      c++;
    }
    updateDeleteButtons(tbodyId);
  }

  function clearRows(id){
    const el=document.getElementById(id); if(!el) return;
    el.innerHTML='';
    const type = id.includes('prod') ? 'prod' : id.includes('rec') ? 'rec' : 'ops';
    ensureMinRows(id, type); // always restore to MIN_ROWS
  }

  function tableToJSON(tbodyId){
    const rows = [];
    document.querySelectorAll(`#${tbodyId} tr`).forEach(tr=>{
      const arr=[];
      tr.querySelectorAll('td .cell').forEach(el=>{
        const v = (el.tagName==='SELECT') ? el.value : (el.value||'').replace(/\r?\n/g,' ').trim();
        arr.push(v);
      });
      if(arr.join('').trim()!=='') rows.push(arr);
    });
    return rows;
  }

  function packAndSubmit(){
    document.getElementById('production_json').value = JSON.stringify(tableToJSON('prod_body'));
    document.getElementById('reception_json').value  = JSON.stringify(tableToJSON('rec_body'));
    document.getElementById('operations_json').value = JSON.stringify(tableToJSON('ops_body'));
    return true;
  }

  /* =========================
   * Department gating / visibility
   * ========================= */
  const pills = document.querySelectorAll('.pill');
  const sections = {
    prod: document.getElementById('sec_prod'),
    rec:  document.getElementById('sec_rec'),
    ops:  document.getElementById('sec_ops'),
  };
  const metaCard   = document.getElementById('metaCard');
  const summaryCard= document.getElementById('summaryCard');
  const actionsBar = document.getElementById('actionsBar');
  const saveBtn    = document.getElementById('saveBtn');

  function showForDept(d){
    pills.forEach(p=>{
      const active = p.dataset.dept===d;
      p.classList.toggle('active', active);
      p.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    metaCard.classList.remove('hidden');
    summaryCard.classList.remove('hidden');
    actionsBar.classList.remove('hidden');
    saveBtn.disabled = false;

    if(d==='all'){
      Object.values(sections).forEach(sec=>sec.classList.remove('hidden'));
    } else {
      Object.entries(sections).forEach(([k,sec])=>{
        sec.classList.toggle('hidden', k!==d);
      });
    }

    try { localStorage.setItem('dailylog_dept_choice', d); } catch(e){}
  }
  pills.forEach(p=>p.addEventListener('click', ()=>showForDept(p.dataset.dept)));

  // Boot: make sure every section has at least MIN_ROWS (now `customerSelect` exists)
  ensureMinRows('prod_body','prod');
  ensureMinRows('rec_body','rec');
  ensureMinRows('ops_body','ops');

  // Restore last chosen dept (optional)
  (function(){
    let d = null;
    try { d = localStorage.getItem('dailylog_dept_choice'); } catch(e){}
    if(!d) return; // keep step 0 visible until user selects
    const pill = Array.from(pills).find(x=>x.dataset.dept===d);
    if(pill){ showForDept(d); }
  })();
</script>

@endsection
