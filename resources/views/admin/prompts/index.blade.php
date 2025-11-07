@extends('admin.layout.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ========= Tight, Modern UI/UX Styles with prmpt- Prefix ========= -->
<style>
  /* ==== MPG Solution Brand (update these hex codes if needed) ==== */
  :root {
    --prmpt-brand:        #0057D9;  /* Primary (MPG blue)    */
    --prmpt-brand-2:      #00AEEF;  /* Secondary blue/cyan    */
    --prmpt-accent:       #10B981;  /* Accent (green)         */
    --prmpt-danger:       #EF4444;

    /* Neutrals / surfaces */
    --prmpt-bg:           #F5F7FB;  /* page background */
    --prmpt-panel:        #FFFFFF;  /* cards/panels    */
    --prmpt-text:         #0F172A;  /* primary text    */
    --prmpt-muted:        #64748B;  /* secondary text  */
    --prmpt-border:       #E6E8EF;  /* borders         */

    /* Effects */
    --prmpt-ring:         0 0 0 3px rgba(0, 126, 214, .20);
    --prmpt-shadow:       0 8px 24px rgba(2, 6, 23, .06);
    --prmpt-gradient:     linear-gradient(135deg, var(--prmpt-brand), var(--prmpt-brand-2));

    --prmpt-font-base: 15px;
    --prmpt-font-sm:   13px;
    --prmpt-font-xs:   12px;
    --prmpt-radius:    12px;
  }

  /* ==== Global reset / full-bleed ==== */
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; }

  body{
    font-family: Arial, "Helvetica Neue", Helvetica, system-ui, -apple-system, "Segoe UI",
                 Roboto, "Noto Sans", sans-serif;
    font-size: var(--prmpt-font-base);
    line-height: 1.45;
    letter-spacing: .01em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
  }

  .prmpt-title h1{ line-height: 1.15; letter-spacing: .2px; }
  label{ letter-spacing: .02em; color: var(--prmpt-text); }

  /* Inputs & buttons: consistent size & radius */
  input[type="text"], select, textarea{
    font-size: 14px;
    border-radius: var(--prmpt-radius);
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-btn, .prmpt-create-btn{
    font-size: 14px;
    border-radius: var(--prmpt-radius);
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }

  /* Cards & lists */
  .prmpt-prompt-title{ font-size: 16px; }
  .prmpt-prompt-item pre{
    font-size: var(--prmpt-font-sm);
    line-height: 1.6;
    word-break: break-word;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }

  /* Small UI text */
  .prmpt-item-count, .prmpt-prompt-meta, #pageInfo{
    font-size: var(--prmpt-font-xs);
  }

  /* ==== Full width header (sticky) ==== */
  .prmpt-header {
    position: sticky; top: 0; z-index: 50;
    width: 100%;
    display: grid; grid-template-columns: 1fr auto auto; gap: 12px;
    align-items: center;
    padding: 8px 12px; /* tighter */
    background: linear-gradient(180deg, rgba(245,247,251,.9), rgba(245,247,251,.7));
    backdrop-filter: saturate(160%) blur(6px);
    border-bottom: 1px solid var(--prmpt-border);
  }
  .prmpt-brand { display: flex; align-items: center; gap: 10px; min-width: 260px; }
  .prmpt-logo {
    width: 40px; height: 40px; border-radius: 12px;
    background: var(--prmpt-gradient); color: #fff; display: grid; place-items: center;
    font-weight: 800; letter-spacing: .2px; box-shadow: 0 6px 16px rgba(0, 126, 214, .25);
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-title h1 { font-size: 18px; font-weight: 700; font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; }
  .prmpt-title p  { font-size: 12px; color: var(--prmpt-muted); }

  /* Right-side counters */
  .prmpt-item-count { font-size: 12px; color: var(--prmpt-muted); padding-left: 8px; }

  /* ==== Full-width container (no max-width) ==== */
  .prmpt-container {
    width: 100%;
    padding: 12px;
  }

  /* ==== Create button (top) ==== */
  .prmpt-create-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 14px; border-radius: 10px; border: none; cursor: pointer;
    background: var(--prmpt-gradient); color: #fff; font-weight: 600; font-size: 14px;
    box-shadow: 0 8px 18px rgba(0, 126, 214, .22);
    transition: transform .12s ease, box-shadow .2s ease;
  }
  .prmpt-create-btn:hover { transform: translateY(-1px); }

  /* ==== Form panel (edge-to-edge inside container) ==== */
  .prmpt-create-form {
    display: none;
    margin-top: 10px;
    background: var(--prmpt-panel); border: 1px solid var(--prmpt-border);
    border-radius: 12px; box-shadow: var(--prmpt-shadow);
    padding: 12px;
  }
  .prmpt-create-form.show { display: block; }
  .prmpt-form-group { margin-bottom: 10px; }
  label { display: block; font-size: 12px; font-weight: 600; margin: 2px 0 6px; }

  input[type="text"], select, textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--prmpt-border);
    border-radius: 10px;
    background: #fff; color: var(--prmpt-text); font-size: 14px;
    transition: border-color .18s ease, box-shadow .18s ease;
  }
  input:focus, select:focus, textarea:focus {
    border-color: var(--prmpt-brand);
    box-shadow: var(--prmpt-ring);
    outline: none;
  }
  textarea { min-height: 120px; resize: vertical; }

  /* Buttons (form) */
  .prmpt-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 14px; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; border: 1px solid transparent; transition: transform .12s ease, box-shadow .2s ease;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-btn-primary { background: var(--prmpt-gradient); color: #fff; }
  .prmpt-btn-ghost   { background: #fff; border-color: var(--prmpt-border); color: var(--prmpt-text); }
  .prmpt-btn-danger  { background: var(--prmpt-danger); color: #fff; }
  .prmpt-btn:hover   { transform: translateY(-1px); }

  /* ==== Filters (header middle column) ==== */
  .prmpt-filters {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: 1fr;
    gap: 8px;
    align-items: center;
  }
  .prmpt-filters input,
  .prmpt-filters select {
    min-width: 160px;
    padding: 8px 10px;
    border: 1px solid var(--prmpt-border);
    border-radius: 8px;
    background: #fff; font-size: 13px;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-switch { display: inline-flex; align-items: center; gap: 8px; }
  .prmpt-switch input { display:none; }
  .prmpt-tag {
    font-size: 12px; padding: 4px 10px; border-radius: 999px;
    background: rgba(0, 126, 214, .10); color: var(--prmpt-text);
    border: 1px solid rgba(0, 126, 214, .18);
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }

  /* ==== Prompts list card ==== */
  .prmpt-card {
    margin-top: 12px; background: var(--prmpt-panel);
    border: 1px solid var(--prmpt-border); border-radius: 12px; box-shadow: var(--prmpt-shadow);
  }
  .prmpt-card-header {
    padding: 10px 12px; font-weight: 700; color: #fff;
    background: var(--prmpt-gradient); border-bottom: 1px solid rgba(255,255,255,.15);
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-card-body { padding: 12px; }

  /* List grid full-width */
  .prmpt-prompt-list { display: grid; grid-template-columns: 1fr; gap: 10px; }

  .prmpt-prompt-item {
    background: #fff; border: 1px solid var(--prmpt-border);
    border-radius: 12px; padding: 12px; transition: border-color .18s, box-shadow .18s, transform .12s;
  }
  .prmpt-prompt-item:hover {
    transform: translateY(-1px);
    border-color: #D7DCE8; box-shadow: 0 10px 22px rgba(2,6,23,.08);
  }

  /* Suggestions dropdown */
  .prmpt-suggest{ position: relative; }
  .prmpt-suggest-list{
    position: absolute; z-index: 40; left: 0; top: 4px;
    width: min(520px, 100%);
    background:#fff; border:1px solid var(--prmpt-border);
    border-radius: 10px; box-shadow: var(--prmpt-shadow); overflow: hidden;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-suggest-item{
    padding: 8px 12px; display: flex; justify-content: space-between; gap: 12px;
    cursor: pointer; font-size: 13px;
  }
  .prmpt-suggest-item:hover{ background:#F7FAFF; }
  .prmpt-suggest-item .left{ color:#0F172A; font-weight:600; font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; }
  .prmpt-suggest-item .right{ color:#64748B; font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; }

  .prmpt-prompt-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
  .prmpt-prompt-title  { font-size: 15px; font-weight: 700; font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; }
  .prmpt-prompt-meta   { display: flex; gap: 8px; flex-wrap: wrap; color: var(--prmpt-muted); font-size: 12px; }

  .prmpt-controls { display: inline-flex; gap: 6px; }
  .prmpt-icon-btn {
    padding: 8px 10px; border: 1px solid var(--prmpt-border); border-radius: 10px;
    background: #fff; cursor: pointer; font-size: 14px; transition: border-color .18s, background .18s;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-icon-btn:hover { border-color: rgba(0, 126, 214, .35); background: #F9FBFF; }

  /* BODY COLLAPSE / EXPAND */
  .prmpt-body {
    margin-top: 0.5rem;
  }
  .prmpt-body-text {
    white-space: pre-wrap;
    font-size: 0.8rem;
    line-height: 1.5;
    max-height: calc(1.5em * 2); /* ~2 lines tall before expand */
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;      /* clamp to 2 lines */
    -webkit-box-orient: vertical;
  }
  .prmpt-body.expanded .prmpt-body-text {
    max-height: none;
    -webkit-line-clamp: unset;
    -webkit-box-orient: unset;
    overflow: visible;
  }
  .prmpt-expand-btn {
    background: none;
    border: 0;
    color: var(--prmpt-brand);
    font-size: 12px;
    font-weight: 600;
    margin-top: 6px;
    padding: 0;
    cursor: pointer;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-expand-btn:hover {
    text-decoration: underline;
  }

  /* Department color tags */
  .prmpt-dep-Operations { background: #E6F7FF; color: #0C4A6E; border: 1px solid #BFE7FF; }
  .prmpt-dep-Productions{ background: #FFF7CC; color: #7A4F00; border: 1px solid #FFE799; }
  .prmpt-dep-Reception  { background: #E9FBF1; color: #166534; border: 1px solid #B7F3CF; }

  /* Empty state */
  #empty { text-align:center; color: var(--prmpt-muted); border-style: dashed; }

  /* Pager */
  #pager { display:none; margin-top: 10px; align-items:center; justify-content:center; gap: 8px; }
  #pageInfo { font-size: 12px; color: var(--prmpt-muted); }

  /* Toast */
  .prmpt-toast {
    position: fixed; right: 12px; bottom: 12px; z-index: 60;
    background: #0B1224; color: #E5E7EB; border: 1px solid #1F2937;
    padding: 10px 12px; border-radius: 10px; opacity: 0; transform: translateY(10px);
    transition: opacity .2s, transform .2s;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-toast.show { opacity: 1; transform: translateY(0); }

  /* Modal */
  .prmpt-modal { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(2,6,23,.45); z-index: 70; }
  .prmpt-modal-panel {
    width: 96%; max-width: 420px; background: #fff; border: 1px solid var(--prmpt-border);
    border-radius: 12px; box-shadow: var(--prmpt-shadow); overflow: hidden;
  }
  .prmpt-modal-header {
    padding: 10px 12px; background: var(--prmpt-gradient); color: #fff; font-weight: 700;
    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
  }
  .prmpt-modal-body   { padding: 12px; color: #334155; font-size: 14px; font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; }
  .prmpt-modal-footer { padding: 10px 12px; display: flex; gap: 8px; justify-content: flex-end; background: #F8FAFC; border-top: 1px solid var(--prmpt-border); }

  /* Responsive tweaks for tighter full-page fit */
  @media (max-width: 1024px) {
    .prmpt-header { grid-template-columns: 1fr; gap: 8px; }
    .prmpt-item-count { justify-self: end; }
    .prmpt-filters { grid-auto-flow: row; grid-auto-columns: unset; grid-template-columns: 1fr 1fr 1fr; }
  }
  @media (max-width: 640px) {
    .prmpt-filters { grid-template-columns: 1fr 1fr; }
    .prmpt-title h1 { font-size: 16px; }
    .prmpt-logo { width: 36px; height: 36px; border-radius: 10px; }
  }

  #wipeBtn {
    display: none !important;
  }
</style>

<!-- ========= Header ========= -->
<div class="prmpt-header">
  <div class="prmpt-brand">
    <div class="prmpt-logo">PV</div>
    <div class="prmpt-title">
      <h1>Prompt Vault</h1>
      <p>Add prompts to the collection below — search, copy, edit, and delete quickly.</p>
    </div>
  </div>

  <div class="prmpt-filters">
    <!-- Create Prompt Button -->
    <button class="prmpt-create-btn" id="createBtn">➕ Create Prompt</button>

    <input id="search" type="text" placeholder="Search title, client, text… (press /)">
    <select id="filterDepartment">
      <option value="">All Depts</option>
      <option value="Operations">Operations</option>
      <option value="Productions">Productions</option>
      <option value="Reception">Reception</option>
    </select>
    <select id="sort">
      <option value="new">Newest</option>
      <option value="old">Oldest</option>
      <option value="az">A → Z</option>
      <option value="za">Z → A</option>
      <option value="fav">Favourites</option>
    </select>
    <label class="prmpt-switch">
      <input type="checkbox" id="onlyFav">
      <span class="prmpt-tag">⭐ Favourites Only</span>
    </label>
  </div>

  <div class="prmpt-item-count"><span id="totalCount">0 items</span></div>
</div>

<div class="prmpt-container">

  <!-- ========= Create Prompt Form (Hidden by Default) ========= -->
  <div class="prmpt-create-form" id="createForm">
    <div class="prmpt-form-group">
      <label for="title">Title / Name</label>
      <input id="title" type="text" placeholder="e.g., Real Estate Lead Form Builder">
    </div>

    <div class="prmpt-form-group">
      <label for="client">Assigned Client</label>
      <input id="client" type="text" placeholder="e.g., Sheetal Property Pvt. Ltd.">

      <!-- live hint + suggestions dropdown -->
      <div id="clientHint" style="margin-top:6px; font-size:12px; color:#475569;"></div>
      <div id="clientSuggest" class="prmpt-suggest"></div>
    </div>

    <div class="prmpt-form-group">
      <label for="department">Department</label>
      <select id="department">
        <option value="Operations">Operations</option>
        <option value="Productions">Productions</option>
        <option value="Reception">Reception</option>
      </select>
    </div>

    <div class="prmpt-form-group">
      <label for="prompt">Prompt</label>
      <textarea id="prompt" placeholder="Your full prompt here… (Ctrl/Cmd + Enter to add)"></textarea>
    </div>

    <div class="prmpt-form-group">
      <label for="charCount">Characters</label>
      <input id="charCount" type="text" value="0" readonly>
      <span class="prmpt-muted" style="font-size: 0.75rem; color: var(--prmpt-muted); margin-left: 0.5rem;">
        Tips:
        <span class="prmpt-kbd" style="font-size: 0.6875rem; border: 1px solid var(--prmpt-border); padding: 0.125rem 0.375rem; border-radius: 6px; color: #334155; background: #f1f5f9;">Ctrl/⌘ + Enter</span> add •
        <span class="prmpt-kbd" style="font-size: 0.6875rem; border: 1px solid var(--prmpt-border); padding: 0.125rem 0.375rem; border-radius: 6px; color: #334155; background: #f1f5f9;">/</span> focus search
      </span>
    </div>

    <div style="display: flex; gap: 0.5rem;">
      <button class="prmpt-btn prmpt-btn-primary" id="addBtn">➕ Add Prompt</button>
      <button class="prmpt-btn prmpt-btn-ghost" id="clearBtn">🧹 Clear</button>
    </div>
  </div>

  <!-- ========= Prompt List ========= -->
  <div class="prmpt-card">
    <div class="prmpt-card-header">Saved Prompts</div>
    <div class="prmpt-card-body">
      <div class="prmpt-toolbar" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
        <h2 style="margin: 0; font-size: 1rem; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">Saved Prompts</h2>
        <div class="prmpt-muted" id="totalCount">0 items</div>
      </div>

      <div id="list" class="prmpt-prompt-list"></div>

      <div id="empty" class="prmpt-prompt-item" style="display: none; text-align: center; color: var(--prmpt-muted); border-style: dashed; border-radius: 8px; padding: 1.5rem;">
        No prompts yet. Use the form above to add one.
      </div>

      <div id="pager" style="display: none; margin-top: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
        <button class="prmpt-btn prmpt-btn-ghost" id="prevPage">← Previous</button>
        <span class="prmpt-hint" id="pageInfo" style="font-size: 0.75rem; color: var(--prmpt-muted);">Page 1</span>
        <button class="prmpt-btn prmpt-btn-ghost" id="nextPage">Next →</button>
      </div>

      <div style="margin-top: 0.75rem;">
        <button class="prmpt-btn prmpt-btn-danger" id="wipeBtn">🗑️ Wipe All (Hard delete)</button>
      </div>
    </div>
  </div>
</div>

<!-- ========= Toast ========= -->
<div id="toast" class="prmpt-toast"></div>

<!-- ========= Confirm Deletion Modal ========= -->
<div class="prmpt-modal" id="confirmModal">
  <div class="prmpt-modal-panel">
    <div class="prmpt-modal-header">Confirm Deletion</div>
    <div class="prmpt-modal-body">Are you sure you want to delete this prompt? This action cannot be undone.</div>
    <div class="prmpt-modal-footer">
      <button class="prmpt-btn prmpt-btn-ghost" id="cancelDel">Cancel</button>
      <button class="prmpt-btn prmpt-btn-danger" id="confirmDel">Delete</button>
    </div>
  </div>
</div>

<!-- ========= JavaScript ========= -->
<script>
  const api = {
    list: '{{ route('admin.prompts.list') }}',
    store: '{{ route('admin.prompts.store') }}',
    update: id => '{{ url('admin/prompts') }}/' + id,
    destroy: id => '{{ url('admin/prompts') }}/' + id,
    duplicate: id => '{{ url('admin/prompts') }}/' + id + '/duplicate',
    toggleFav: id => '{{ url('admin/prompts') }}/' + id + '/toggle-fav',
    quickSearch: '{{ route('admin.customers.quickSearch') }}',
  };

  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const $ = s => document.querySelector(s);

  const listEl       = $('#list'),
        emptyEl      = $('#empty'),
        toastEl      = $('#toast'),
        titleEl      = $('#title'),
        clientEl     = $('#client'),
        deptEl       = $('#department'),
        promptEl     = $('#prompt'),
        countEl      = $('#charCount'),
        addBtn       = $('#addBtn'),
        clearBtn     = $('#clearBtn'),
        createBtn    = $('#createBtn'),
        createForm   = $('#createForm'),
        searchEl     = $('#search'),
        sortEl       = $('#sort'),
        favOnlyEl    = $('#onlyFav'),
        filterDeptEl = $('#filterDepartment'),
        totalCount   = $('#totalCount'),
        pager        = $('#pager'),
        prevPage     = $('#prevPage'),
        nextPage     = $('#nextPage'),
        pageInfo     = $('#pageInfo'),
        modal        = $('#confirmModal'),
        btnCancelDel = $('#cancelDel'),
        btnConfirmDel= $('#confirmDel'),
        clientHint   = $('#clientHint'),
        clientSuggestWrap = $('#clientSuggest');

  let editingId = null;
  let deleteId  = null;
  let page      = 1,
      lastPage  = 1;

  const toast = (msg) => {
    toastEl.textContent = msg;
    toastEl.classList.add('show');
    setTimeout(() => toastEl.classList.remove('show'), 2000);
  };

  function debounce(fn, delay=200) {
    let t;
    return (...args)=>{
      clearTimeout(t);
      t=setTimeout(()=>fn(...args), delay);
    };
  }

  async function fetchCustomerSuggestions(term){
    if(!term || term.trim().length < 2) return [];
    const r = await fetch(api.quickSearch + '?term=' + encodeURIComponent(term), {
      headers: { 'X-CSRF-TOKEN': token }
    });
    if(!r.ok) return [];
    const j = await r.json();
    return j.data || [];
  }

  function closeSuggest(){
    clientSuggestWrap.innerHTML = '';
    clientSuggestWrap.classList.remove('open');
  }

  function renderSuggestions(items){
    if(!items.length){
      closeSuggest();
      clientHint.textContent='';
      return;
    }

    const list = document.createElement('div');
    list.className = 'prmpt-suggest-list';

    items.forEach(it=>{
      const row = document.createElement('div');
      row.className = 'prmpt-suggest-item';
      row.innerHTML = `
        <div class="left">${escapeHtml(it.label || it.name || '')}</div>
        <div class="right">${it.phone ? escapeHtml(it.phone) : ''}</div>
      `;
      row.onclick = ()=>{
        clientEl.value = it.label || it.name || '';
        clientHint.textContent =
          `${(it.label || it.name || '').trim()} — ${it.phone || ''}`.trim();
        closeSuggest();
      };
      list.appendChild(row);
    });

    clientSuggestWrap.innerHTML = '';
    clientSuggestWrap.appendChild(list);
    clientSuggestWrap.classList.add('open');

    const top = items[0];
    clientHint.textContent =
      `${(top.label || top.name || '').trim()} — ${top.phone || ''}`.trim();
  }

  const onClientType = debounce(async ()=>{
    const val = clientEl.value.trim();
    if(val.length < 2){
      clientHint.textContent='';
      closeSuggest();
      return;
    }
    try{
      const items = await fetchCustomerSuggestions(val);
      renderSuggestions(items);
    }catch{
      clientHint.textContent = '';
      closeSuggest();
    }
  }, 220);

  clientEl.addEventListener('input', onClientType);
  clientEl.addEventListener('focus', onClientType);

  document.addEventListener('click', (e)=>{
    if(!clientSuggestWrap.contains(e.target) && e.target !== clientEl){
      closeSuggest();
    }
  });

  const escapeHtml = (s) => (s || '').replace(/[&<>"']/g, c => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    "\"": "&quot;",
    "'": "&#39;"
  })[c]);

  function updateCount() {
    countEl.value = (promptEl.value || '').length;
  }
  promptEl.addEventListener('input', updateCount);

  async function fetchList() {
    const params = new URLSearchParams();
    if (searchEl.value) params.set('q', searchEl.value);
    if (favOnlyEl.checked) params.set('onlyFav', '1');
    if (filterDeptEl.value) params.set('department', filterDeptEl.value);
    params.set('sort', sortEl.value || 'new');
    params.set('page', page);

    try {
      const r = await fetch(api.list + '?' + params.toString(), {
        headers: { 'X-CSRF-TOKEN': token }
      });
      if (!r.ok) throw new Error('Failed to fetch prompts');
      const j = await r.json();
      totalCount.textContent = `${j.total} item${j.total !== 1 ? 's' : ''}`;
      lastPage = j.last_page || 1;
      renderList(j.data || []);
      renderPager();
    } catch (e) {
      toast(e.message);
    }
  }

  function renderPager() {
    if (lastPage <= 1) {
      pager.style.display = 'none';
      return;
    }
    pager.style.display = 'flex';
    pageInfo.textContent = `Page ${page} of ${lastPage}`;
    prevPage.disabled = (page <= 1);
    nextPage.disabled = (page >= lastPage);
  }

  function deptClass(dep) {
    return dep === 'Productions'
      ? 'prmpt-dep-Productions'
      : dep === 'Reception'
      ? 'prmpt-dep-Reception'
      : 'prmpt-dep-Operations';
  }

  function initPromptItem(card){
    // collapse logic: hide expand button if not overflowing 2 lines
    const bodyWrap    = card.querySelector('.prmpt-body');
    const bodyText    = card.querySelector('.prmpt-body-text');
    const expandBtn   = card.querySelector('.prmpt-expand-btn');

    if (!bodyWrap || !bodyText || !expandBtn) return;

    // Check overflow (if content fits within ~2 lines, no need "Show more")
    const isOverflowing = bodyText.scrollHeight > bodyText.offsetHeight + 2;
    if (!isOverflowing) {
      expandBtn.style.display = 'none';
    }

    expandBtn.addEventListener('click', ()=>{
      const expanded = bodyWrap.classList.toggle('expanded');
      expandBtn.textContent = expanded ? 'Show less ▲' : 'Show more ▼';
    });
  }

  function renderList(items) {
    listEl.innerHTML = '';
    if (!items.length) {
      emptyEl.style.display = 'block';
      return;
    }
    emptyEl.style.display = 'none';

    items.forEach(i => {
      const card = document.createElement('div');
      card.className = 'prmpt-prompt-item';

      card.innerHTML = `
        <div class="prmpt-prompt-header">
          <div>
            <h3 class="prmpt-prompt-title">${escapeHtml(i.title)}</h3>
            <div class="prmpt-prompt-meta">
              ${i.client ? `<span class="prmpt-tag">🏷️ ${escapeHtml(i.client)}</span>` : ''}
              ${i.department ? `<span class="prmpt-tag ${deptClass(i.department)}">${escapeHtml(i.department)}</span>` : ''}
              ${i.creator_name ? `<span class="prmpt-tag">👨‍💻 ${escapeHtml(i.creator_name)}</span>` : ''}
              <span>${new Date(i.updated_at).toLocaleString()}</span>
            </div>
          </div>
          <div class="prmpt-controls">
            <button class="prmpt-icon-btn prmpt-fav" title="Favourite">${i.is_fav ? '⭐' : '☆'}</button>
            <button class="prmpt-icon-btn prmpt-copy" title="Copy">📋</button>
            <button class="prmpt-icon-btn prmpt-edit" title="Edit">✏️</button>
            <button class="prmpt-icon-btn prmpt-dup" title="Duplicate">🧬</button>
            <button class="prmpt-icon-btn prmpt-del" title="Delete">🗑️</button>
          </div>
        </div>

        <div class="prmpt-body">
          <pre class="prmpt-body-text">${escapeHtml(i.body)}</pre>
          <button class="prmpt-expand-btn">Show more ▼</button>
        </div>
      `;

      // favourite
      card.querySelector('.prmpt-fav').onclick = () => toggleFav(i.id);

      // copy
      card.querySelector('.prmpt-copy').onclick = async () => {
        await navigator.clipboard.writeText(i.body || '');
        toast('Copied to clipboard');
      };

      // edit
      card.querySelector('.prmpt-edit').onclick = () => {
        editingId = i.id;
        titleEl.value = i.title || '';
        clientEl.value = i.client || '';
        deptEl.value = i.department || 'Operations';
        promptEl.value = i.body || '';
        updateCount();
        createForm.classList.add('show');
        createBtn.textContent = '➖ Close Form';
        window.scrollTo({ top: createForm.offsetTop, behavior: 'smooth' });
        addBtn.textContent = '💾 Update Prompt';
      };

      // duplicate
      card.querySelector('.prmpt-dup').onclick = () => duplicate(i.id);

      // delete
      card.querySelector('.prmpt-del').onclick = () => {
        deleteId = i.id;
        openModal();
      };

      listEl.appendChild(card);

      // init expand/collapse for this card
      initPromptItem(card);
    });
  }

  function collect() {
    return {
      title: titleEl.value.trim(),
      client: clientEl.value.trim(),
      department: deptEl.value,
      body: promptEl.value.trim()
    };
  }

  function resetForm() {
    editingId = null;
    titleEl.value = '';
    clientEl.value = '';
    deptEl.value = 'Operations';
    promptEl.value = '';
    updateCount();
    addBtn.textContent = '➕ Add Prompt';
    createForm.classList.remove('show');
    createBtn.textContent = '➕ Create Prompt';
  }

  async function store(data) {
    try {
      const r = await fetch(api.store, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify(data)
      });
      if (!r.ok) throw new Error('Save failed');
      return await r.json();
    } catch (e) {
      toast(e.message);
      throw e;
    }
  }

  async function update(id, data) {
    try {
      const r = await fetch(api.update(id), {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify(data)
      });
      if (!r.ok) throw new Error('Update failed');
      return await r.json();
    } catch (e) {
      toast(e.message);
      throw e;
    }
  }

  async function destroy(id) {
    try {
      const r = await fetch(api.destroy(id), {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': token }
      });
      if (!r.ok) throw new Error('Delete failed');
      return await r.json();
    } catch (e) {
      toast(e.message);
      throw e;
    }
  }

  async function duplicate(id) {
    try {
      const r = await fetch(api.duplicate(id), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token }
      });
      if (!r.ok) throw new Error('Duplicate failed');
      toast('Prompt duplicated');
      fetchList();
    } catch (e) {
      toast(e.message);
    }
  }

  async function toggleFav(id) {
    try {
      const r = await fetch(api.toggleFav(id), {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token }
      });
      if (!r.ok) throw new Error('Failed to toggle favourite');
      fetchList();
    } catch (e) {
      toast(e.message);
    }
  }

  createBtn.onclick = () => {
    if (createForm.classList.contains('show')) {
      resetForm();
    } else {
      createForm.classList.add('show');
      createBtn.textContent = '➖ Close Form';
    }
  };

  addBtn.onclick = async () => {
    const data = collect();
    if (!data.title) return toast('Title is required');
    if (!data.body)  return toast('Prompt cannot be empty');

    try {
      if (editingId) {
        await update(editingId, data);
        toast('Prompt updated');
      } else {
        await store(data);
        toast('Prompt saved');
      }
      resetForm();
      page = 1;
      fetchList();
    } catch (e) {
      // already toasted in store/update
    }
  };

  clearBtn.onclick = resetForm;

  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
      e.preventDefault();
      addBtn.click();
    }
    if (e.key === '/' && document.activeElement !== searchEl) {
      e.preventDefault();
      searchEl.focus();
    }
  });

  [searchEl, sortEl, favOnlyEl, filterDeptEl].forEach(el => {
    el.addEventListener('input', () => { page = 1; fetchList(); });
    el.addEventListener('change', () => { page = 1; fetchList(); });
  });

  prevPage.onclick = () => {
    if (page > 1) {
      page--;
      fetchList();
    }
  };

  nextPage.onclick = () => {
    if (page < lastPage) {
      page++;
      fetchList();
    }
  };

  $('#wipeBtn').onclick = async () => {
    if (!confirm('Really delete ALL prompts?')) return;
    try {
      page = 1;
      await fetchList();
      let guard = 0;
      while (guard++ < 200) {
        const r = await fetch(api.list + '?page=' + page, {
          headers: { 'X-CSRF-TOKEN': token }
        });
        const j = await r.json();
        const ids = (j.data || []).map(x => x.id);
        if (!ids.length) break;
        for (const id of ids) await destroy(id);
      }
      toast('All prompts deleted');
      fetchList();
    } catch (e) {
      toast('Failed to delete all prompts');
    }
  };

  function openModal() {
    modal.style.display = 'flex';
  }

  function closeModal() {
    modal.style.display = 'none';
  }

  btnCancelDel.onclick = () => {
    deleteId = null;
    closeModal();
  };

  btnConfirmDel.onclick = async () => {
    if (!deleteId) return closeModal();
    try {
      await destroy(deleteId);
      toast('Prompt deleted');
    } catch {
      // destroy() already toast on error
    }
    deleteId = null;
    closeModal();
    fetchList();
  };

  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // init
  updateCount();
  fetchList();
</script>
@endsection
