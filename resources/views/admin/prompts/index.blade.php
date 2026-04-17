@extends('admin.layout.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ========= Tight, Modern UI/UX Styles with prmpt- Prefix ========= -->
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
      <div id="clientHint" class="prmpt-client-hint"></div>
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
      <span class="prmpt-muted prmpt-tip-row">
        Tips:
        <span class="prmpt-kbd">Ctrl/⌘ + Enter</span> add •
        <span class="prmpt-kbd">/</span> focus search
      </span>
    </div>

    <div class="prmpt-btn-row">
      <button class="prmpt-btn prmpt-btn-primary" id="addBtn">➕ Add Prompt</button>
      <button class="prmpt-btn prmpt-btn-ghost" id="clearBtn">🧹 Clear</button>
    </div>
  </div>

  <!-- ========= Prompt List ========= -->
  <div class="prmpt-card">
    <div class="prmpt-card-header">Saved Prompts</div>
    <div class="prmpt-card-body">
      <div class="prmpt-toolbar">
        <h2 class="prmpt-toolbar-h2">Saved Prompts</h2>
        <div class="prmpt-muted" id="totalCount">0 items</div>
      </div>

      <div id="list" class="prmpt-prompt-list"></div>

      <div id="empty" class="prmpt-prompt-item prmpt-empty" style="display: none;">
        No prompts yet. Use the form above to add one.
      </div>

      <div id="pager" class="prmpt-pager" style="display: none;">
        <button class="prmpt-btn prmpt-btn-ghost" id="prevPage">← Previous</button>
        <span class="prmpt-hint" id="pageInfo">Page 1</span>
        <button class="prmpt-btn prmpt-btn-ghost" id="nextPage">Next →</button>
      </div>

      <div class="prmpt-wipe-row">
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
