@extends('admin.layout.layout')

@section('content')
<style>
  .logpage{border:1px solid #000;background:#fff;padding:12px;margin-bottom:16px}
  .sec-hd{display:flex;justify-content:space-between;align-items:center;margin:6px 0}
  .sec-hd h3{margin:0;font-size:16px;text-transform:uppercase}
  table.log{width:100%;border-collapse:collapse;table-layout:fixed;background:#fff}
  table.log thead th{border-bottom:2px solid #000;font-size:12px;padding:6px;text-align:left}
  table.log tbody td{border-bottom:1px dashed #000;font-size:12px;padding:6px;vertical-align:top}
  .cell{width:100%;border:none;outline:none;font:inherit;background:transparent}
  .btn{padding:6px 10px;border-radius:6px;border:1px solid #cbd5e1;background:#eef2ff;cursor:pointer}
  .btn-primary{background:#2563eb;color:#fff;border-color:#2563eb}
  .btn-danger{background:#ef4444;color:#fff;border-color:#ef4444}
</style>

<form method="post" action="{{ route('admin.daily-logs.update',$log) }}">
  @csrf @method('PUT')

  <div class="card p-3">
    <div style="display:flex;gap:12px;flex-wrap:wrap">
      <div>
        <label>Date *</label>
        <input type="date" name="log_date" value="{{ old('log_date', optional($log->log_date)->toDateString()) }}" required>
      </div>
      <div>
        <label>Status</label>
        <select name="status">
          <option value="submitted" @selected(old('status',$log->status)==='submitted')>Submitted</option>
          <option value="draft" @selected(old('status',$log->status)==='draft')>Draft</option>
          @if(auth('admin')->id() === 1)
            <option value="approved" @selected(old('status',$log->status)==='approved')>Approved</option>
          @endif
        </select>
      </div>
    </div>
  </div>

  {{-- PRODUCTION --}}
  <div class="logpage">
    <div class="sec-hd">
      <h3>Production</h3>
      <div>
        <button class="btn" type="button" onclick="addRow('prod_body','prod')">Add Row</button>
        <button class="btn btn-danger" type="button" onclick="clearRows('prod_body')">Clear</button>
      </div>
    </div>
    <table class="log">
      <thead>
        <tr>
          <th style="width:80px">Time</th>
          <th style="width:160px">Task / Job</th>
          <th>Details / Asset</th>
          <th style="width:90px">By</th>
          <th style="width:110px">Status</th>
          <th style="width:140px">Notes</th>
          <th style="width:40px"></th>
        </tr>
      </thead>
      <tbody id="prod_body"></tbody>
    </table>
  </div>

  {{-- RECEPTION --}}
  <div class="logpage">
    <div class="sec-hd">
      <h3>Reception</h3>
      <div>
        <button class="btn" type="button" onclick="addRow('rec_body','rec')">Add Row</button>
        <button class="btn btn-danger" type="button" onclick="clearRows('rec_body')">Clear</button>
      </div>
    </div>
    <table class="log">
      <thead>
        <tr>
          <th style="width:80px">Time</th>
          <th style="width:170px">Name / Org</th>
          <th>Purpose / Message</th>
          <th style="width:150px">Forwarded To</th>
          <th style="width:120px">Mode</th>
          <th style="width:140px">Outcome / Next</th>
          <th style="width:40px"></th>
        </tr>
      </thead>
      <tbody id="rec_body"></tbody>
    </table>
  </div>

  {{-- OPERATIONS --}}
  <div class="logpage">
    <div class="sec-hd">
      <h3>Operations</h3>
      <div>
        <button class="btn" type="button" onclick="addRow('ops_body','ops')">Add Row</button>
        <button class="btn btn-danger" type="button" onclick="clearRows('ops_body')">Clear</button>
      </div>
    </div>
    <table class="log">
      <thead>
        <tr>
          <th style="width:80px">Time</th>
          <th style="width:180px">Action</th>
          <th>Client / Ticket / Ref</th>
          <th style="width:130px">Owner</th>
          <th style="width:120px">Status</th>
          <th style="width:140px">Remarks</th>
          <th style="width:40px"></th>
        </tr>
      </thead>
      <tbody id="ops_body"></tbody>
    </table>
  </div>

  <div class="card p-3">
    <label>End-of-Day Summary</label>
    <textarea name="summary" rows="3">{{ old('summary', $log->summary) }}</textarea>
  </div>

  {{-- Hidden JSON fields --}}
  <input type="hidden" name="production" id="production_json">
  <input type="hidden" name="reception"  id="reception_json">
  <input type="hidden" name="operations" id="operations_json">

  <div class="mt-3">
    <button class="btn btn-primary" onclick="return packAndSubmit()">Save</button>
    <a class="btn" href="{{ route('admin.daily-logs.index') }}">Back</a>
  </div>
</form>

<script>
  // ===== same helpers as create =====
  function cell(tag='input') {
    if(tag==='select') return document.createElement('select');
    if(tag==='textarea'){ const t=document.createElement('textarea'); t.rows=1; return t; }
    const i=document.createElement('input'); i.type='text'; return i;
  }
  function td(el){ const d=document.createElement('td'); el.className='cell'; d.appendChild(el); return d; }
  function delBtn(){ const b=document.createElement('button'); b.type='button'; b.className='btn btn-danger'; b.textContent='X'; b.onclick=e=>{ e.target.closest('tr').remove(); }; return b; }

  function addRow(tbodyId,type){
    const tb=document.getElementById(tbodyId); const tr=document.createElement('tr');
    if(type==='prod'){
      const status=cell('select'); ['Open','In Progress','Done','Hold'].forEach(v=>status.add(new Option(v,v)));
      [cell(),cell(),cell('textarea'),cell(),status,cell('textarea')].forEach(el=>tr.appendChild(td(el)));
    } else if(type==='rec'){
      const mode=cell('select'); ['Call','Walk-in','Courier','Email','WhatsApp'].forEach(v=>mode.add(new Option(v,v)));
      [cell(),cell(),cell('textarea'),cell(),mode,cell('textarea')].forEach(el=>tr.appendChild(td(el)));
    } else { // ops
      const status=cell('select'); ['Open','In Progress','Done','Pending Client'].forEach(v=>status.add(new Option(v,v)));
      [cell(),cell(),cell('textarea'),cell(),status,cell('textarea')].forEach(el=>tr.appendChild(td(el)));
    }
    const tdd=document.createElement('td'); tdd.appendChild(delBtn()); tr.appendChild(tdd);
    tb.appendChild(tr);
  }
  function addRowWithValues(tbodyId,type,arr){
    addRow(tbodyId,type);
    const tr = document.querySelector(`#${tbodyId} tr:last-child`);
    const inputs = tr.querySelectorAll('.cell');
    arr.forEach((v,i)=>{ if(inputs[i]) inputs[i].value = v; });
  }
  function clearRows(id){ document.getElementById(id).innerHTML=''; }

  function tableToJSON(tbodyId){
    const rows = [];
    document.querySelectorAll(`#${tbodyId} tr`).forEach(tr=>{
      const arr=[]; tr.querySelectorAll('td .cell').forEach(el=>{
        arr.push(el.tagName==='SELECT' ? el.value : (el.value||'').replace(/\r?\n/g,' ').trim());
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

  // ===== seed existing data from PHP arrays =====
  const prodData = @json($log->production_array);
  const recData  = @json($log->reception_array);
  const opsData  = @json($log->operations_array);

  function seedFromData(){
    if(prodData.length){ prodData.forEach(r=>addRowWithValues('prod_body','prod',r)); } else { for(let i=0;i<10;i++) addRow('prod_body','prod'); }
    if(recData.length){ recData.forEach(r=>addRowWithValues('rec_body','rec',r)); } else { for(let i=0;i<10;i++) addRow('rec_body','rec'); }
    if(opsData.length){ opsData.forEach(r=>addRowWithValues('ops_body','ops',r)); } else { for(let i=0;i<10;i++) addRow('ops_body','ops'); }
  }
  seedFromData();
</script>
@endsection
