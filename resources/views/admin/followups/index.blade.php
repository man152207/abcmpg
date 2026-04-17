@extends('admin.layout.layout')

@section('title', 'Follow-Up Hub | MPG Solution')

@section('content')
<div class="crm-container mpg-layout">
    <div class="crm-nav mpg-layout">
        <button class="crm-sidebar-toggle mpg-layout" id="sidebarToggle">
            <i class="nav-icon fas fa-address-book mpg-layout"></i>
        </button>
        <span class="crm-admin-info">Follow-Up Hub</span>
        <button class="btn btn-info mpg-layout" data-toggle="modal" data-target="#modalNewLead">
            <i class="fas fa-user-plus mr-1"></i> New Lead
        </button>
    </div>

    <div class="crm-content mpg-layout">
        {{-- FILTER BAR --}}
        <div class="crm-header-bar mpg-layout">
            <form id="filterForm" class="form-row align-items-end w-100">
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small">From</label>
                    <input type="date" class="form-control mpg-form-control" name="from">
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small">To</label>
                    <input type="date" class="form-control mpg-form-control" name="to">
                </div>

                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small">Status</label>
                    <select class="form-control mpg-form-select" name="status" id="statusSelect">
                        <option value="">All</option>
                        <option>New</option>
                        <option>Warm</option>
                        <option>Follow-up Due</option>
                        <option>Negotiation</option>
                        <option>Won</option>
                        <option>Lost</option>
                        <option>Dormant</option>
                    </select>
                </div>

                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small">Channel</label>
                    <select class="form-control mpg-form-select" name="channel">
                        <option value="">All</option>
                        <option>WhatsApp</option>
                        <option>Messenger</option>
                        <option>Call</option>
                        <option>SMS</option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-12 mb-2">
                    <label class="small">Search</label>
                    <input type="text" name="search" id="searchBox" class="form-control mpg-form-control" placeholder="Search name/phone/city/tags (Ctrl+K)">
                </div>

                <div class="col-md-12 mb-2 text-right">
                    <button class="btn btn-info mpg-btn-primary" id="btnApply">
                        <i class="fas fa-filter mr-1"></i> Apply
                    </button>
                    <button type="reset" class="btn btn-secondary mpg-btn-secondary" id="btnReset">Reset</button>
                    <button class="btn btn-secondary mpg-btn-secondary" id="btnExport">
                        <i class="fas fa-download mr-1"></i> Export (.csv)
                    </button>
                </div>
            </form>
        </div>

        <div id="loader" class="mpg-loader" style="display:none;">
            Loading...
        </div>

        {{-- TABLE --}}
        <div class="crm-table-container mpg-layout">
            <div class="crm-table-wrapper">
                <table class="table table-hover mpg-table" id="tblContacts">
                    <thead>
                        <tr>
                            <th style="width: 22%;">Customer</th>
                            <th style="width: 12%;">Phone</th>
                            <th style="width: 12%;">Status</th>
                            <th style="width: 16%;">Next Follow-Up</th>
                            <th style="width: 16%;">Last Contact</th>
                            <th style="width: 10%;">Channel</th>
                            <th style="width: 12%;">Assigned</th>
                            <th style="width: 10%;">Priority</th>
                            <th style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="crm-pagination p-2" id="pager"></div>
        </div>
    </div>

    {{-- PROFILE / EDIT MODAL --}}
    <div class="modal fade" id="profileDrawer" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card-header mpg-modal-head">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0" id="ocName">Customer</span>
                        <button class="btn-close text-white" data-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body p-4">
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Primary Phone</div>
                                <div class="h6 mb-0" id="ocPhone">—</div>
                                <div class="small mt-1 text-muted">Last Contact: <span id="ocLast">—</span></div>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <a id="ocWaLink" class="btn btn-success mpg-btn-success" target="_blank">
                                    <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                                </a>
                                <a id="ocMsgLink" class="btn btn-info mpg-btn-primary" target="_blank">
                                    <i class="fab fa-facebook-messenger mr-1"></i> Messenger
                                </a>
                            </div>
                        </div>
                    </div>

                    <h6 class="mb-2">Edit Lead</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Status</label>
                            <select class="form-control mpg-form-select" id="ocStatus">
                                <option>New</option>
                                <option>Warm</option>
                                <option>Follow-up Due</option>
                                <option>Negotiation</option>
                                <option>Won</option>
                                <option>Lost</option>
                                <option>Dormant</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Priority</label>
                            <select class="form-control mpg-form-select" id="ocPriority">
                                <option>High</option>
                                <option>Medium</option>
                                <option>Low</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Assign To</label>
                            <select class="form-control mpg-form-select" id="ocAssign"></select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Next Follow-Up</label>
                            <input id="ocNext" type="datetime-local" class="form-control mpg-form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tags</label>
                            <input id="ocTags" class="form-control mpg-form-control" placeholder="comma,separated,tags">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Notes</label>
                            <textarea id="ocNotes" class="form-control mpg-form-control" rows="2" placeholder="Short note..."></textarea>
                        </div>

                        <div class="col-md-12 text-right">
                            <button class="btn btn-info mpg-btn-primary" id="btnSaveProfile">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2">Add Follow-Up</h6>

                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label class="small text-muted">Channel</label>
                            <select id="fuChannel" class="form-control mpg-form-select">
                                <option>WhatsApp</option>
                                <option>Messenger</option>
                                <option>Call</option>
                                <option>SMS</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small text-muted">Planned At</label>
                            <input id="fuPlanned" type="datetime-local" class="form-control mpg-form-control">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="small text-muted">Outcome</label>
                            <select id="fuOutcome" class="form-control mpg-form-select">
                                <option value="">—</option>
                                <option>No Answer</option>
                                <option>Interested</option>
                                <option>Not Now</option>
                                <option>Converted</option>
                                <option>Invalid</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <textarea id="fuNote" class="form-control mpg-form-control" rows="2" placeholder="Short follow-up note..."></textarea>
                        </div>

                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm" onclick="snoozeLocal(1);return false;">+1d</button>
                                <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm" onclick="snoozeLocal(3);return false;">+3d</button>
                                <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm" onclick="snoozeLocal(7);return false;">+7d</button>
                            </div>
                            <button class="btn btn-info mpg-btn-primary crm-btn-sm" id="btnSaveFU">
                                <i class="fas fa-save mr-1"></i> Save Follow-Up
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- NEW LEAD MODAL --}}
    <div class="modal fade" id="modalNewLead" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card-header mpg-modal-head">
                    <h5 class="modal-title mb-0"><i class="fas fa-user-plus mr-1"></i> New Lead</h5>
                </div>

                <div class="modal-body p-4">
                    <form id="formNewLead">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label class="small">Full Name</label>
                                <input name="name" class="form-control mpg-form-control" placeholder="e.g., Sita Gurung">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small">Phone <span class="text-danger">*</span></label>
                                <input name="phone_primary" class="form-control mpg-form-control" required placeholder="98XXXXXXXX">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small">Facebook Profile URL / Username</label>
                                <input name="fb_profile_url" class="form-control mpg-form-control" placeholder="username or https://facebook.com/...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small">Messenger Username</label>
                                <input name="messenger_username" class="form-control mpg-form-control" placeholder="e.g., page.username">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small">City</label>
                                <input name="city" class="form-control mpg-form-control" placeholder="Pokhara">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small">Source</label>
                                <select name="source" class="form-control mpg-form-select">
                                    <option>Facebook</option>
                                    <option>Instagram</option>
                                    <option>Walk-in</option>
                                    <option>Referral</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small">Preferred Language</label>
                                <select name="preferred_language" class="form-control mpg-form-select">
                                    <option>Nepali</option>
                                    <option>English</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small">Service Interest</label>
                                <input name="service_interest" class="form-control mpg-form-control" placeholder="Real Estate / Salon / ...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small">Budget</label>
                                <input name="budget_range" class="form-control mpg-form-control" placeholder="Rs 3,000 – 6,000">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="small">Tags</label>
                                <input name="tags" class="form-control mpg-form-control" placeholder="comma,separated,tags">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="small">Quick Note</label>
                                <textarea name="notes_summary" class="form-control mpg-form-control" rows="2" placeholder="e.g., Call after 5 PM"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <span class="text-muted mr-auto small">Tip: Ctrl/⌘ + Enter to save</span>
                    <button class="btn btn-secondary mpg-btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-info mpg-btn-primary" id="btnSaveLead">
                        <i class="fas fa-save mr-1"></i> Save Lead
                    </button>
                </div>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</div>
@endsection

@push('scripts')
<script>
/**
 * ✅ IMPORTANT (New entry always TOP):
 * Backend मा `admin.followups.data` query मा orderByDesc('id') वा created_at desc राख्नुपर्छ।
 * Frontend sort ले page भित्र मात्र top बनाउँछ। Pagination मा 100% guarantee backend बाटै हुन्छ।
 */

const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let CURRENT = null;
let LAST_PARAMS = '';
let ADMINS = [];
let CONTACTS = {};

function toast(msg, type='success') {
    showNotification(msg, type === 'error' ? '#dc2626' : '#38a169');
}

function showLoader(on) {
    document.getElementById('loader').style.display = on ? 'block' : 'none';
}

function pad2(n){ return String(n).padStart(2,'0'); }

function parseISO(iso){
    if(!iso) return null;
    const s = String(iso).includes('T') ? String(iso) : String(iso).replace(' ', 'T');
    const d = new Date(s);
    if (isNaN(d.getTime())) return null;
    return d;
}

// ✅ Clear date format: YYYY-MM-DD HH:MM
function formatDT(iso) {
    const d = parseISO(iso);
    if(!d) return (iso ? String(iso) : '—');
    return `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())} ${pad2(d.getHours())}:${pad2(d.getMinutes())}`;
}

// datetime-local value: YYYY-MM-DDTHH:MM
function toInputDT(iso){
    if(!iso) return '';
    const s = String(iso).replace(' ', 'T');
    return s.slice(0, 16);
}

function isOverdue(nextIso){
    const d = parseISO(nextIso);
    return d ? (d.getTime() < Date.now()) : false;
}

function statusOptions(selected){
    const list = ['New','Warm','Follow-up Due','Negotiation','Won','Lost','Dormant'];
    return list.map(s => `<option ${String(selected)===String(s)?'selected':''}>${s}</option>`).join('');
}

function priorityOptions(selected){
    const list = ['High','Medium','Low'];
    return list.map(s => `<option ${String(selected)===String(s)?'selected':''}>${s}</option>`).join('');
}

function adminOptions(selectedId){
    const opts = ['<option value="">—</option>']
        .concat(ADMINS.map(a =>
            `<option value="${a.id}" ${String(a.id)===String(selectedId)?'selected':''}>${a.name}</option>`
        ));
    return opts.join('');
}

function buildParams(url = null){
    const params = new URLSearchParams();
    const f = document.getElementById('filterForm');

    const from = f.from.value;
    const to = f.to.value;
    const status = document.getElementById('statusSelect').value;
    const channel = f.channel.value;
    const search = f.search.value;

    if (from) params.append('from', from);
    if (to) params.append('to', to);

    // Backward compatible: status or status[]
    if (status) {
        params.append('status', status);
        params.append('status[]', status);
    }

    if (channel) params.append('channel', channel);
    if (search) params.append('search', search);

    return url ? url : (`{{ route('admin.followups.data') }}?` + params.toString());
}

function loadData(url = null){
    const endpoint = buildParams(url);
    LAST_PARAMS = endpoint;

    showLoader(true);
    fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json())
        .then(render)
        .catch(() => toast('Failed to load', 'error'))
        .finally(() => showLoader(false));
}

function inlineUpdate(id, field, value, reload=true){
    return fetch(`{{ route('admin.followups.contact.inline') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id, field, value })
    }).then(async r => {
        const j = await r.json().catch(() => ({ ok:false }));
        if (!j.ok) throw new Error('Update failed');
        if (reload) loadData();
        return true;
    }).catch(() => {
        toast('Update failed', 'error');
        return false;
    });
}

function rowHtml(c){
    CONTACTS[c.id] = c;

    const lf = c.latest_follow_up;
    const channel = lf ? (lf.contact_channel || '-') : '-';

    const msgHref = c.messenger_username
        ? `https://m.me/${c.messenger_username}`
        : (c.fb_profile_url || '#');

    const overdueBadge = isOverdue(c.next_followup_at)
        ? `<span class="badge badge-danger ml-2">Overdue</span>`
        : '';

    const assignedName = c.assigned_name || (c.assigned_to || '—');

    return `
    <tr data-id="${c.id}">
        <td>
            <a href="#" class="crm-contact-link" onclick="openProfile(${c.id});return false;">
                ${c.name || '—'}
            </a>
            <div class="small text-muted">
                ${(c.tags || '')}${c.city ? (' • ' + c.city) : ''}
            </div>
        </td>

        <td>
            <span class="mr-2">${c.phone_primary || '—'}</span>
            ${c.phone_primary ? `<i class="fas fa-copy mpg-copy" title="Copy" onclick="navigator.clipboard.writeText('${c.phone_primary}');toast('Copied');"></i>` : ''}
        </td>

        <td>
            <select class="form-control mpg-form-select-sm"
                    onchange="inlineUpdate(${c.id}, 'status', this.value)">
                ${statusOptions(c.status)}
            </select>
        </td>

        <td class="crm-input-row">
            <input type="datetime-local"
                class="form-control mpg-form-control-sm"
                value="${toInputDT(c.next_followup_at)}"
                onchange="inlineUpdate(${c.id}, 'next_followup_at', this.value ? (this.value.replace('T',' ') + ':00') : null)">
            <div class="small text-muted mt-1">
                ${c.next_followup_at ? formatDT(c.next_followup_at) : '—'} ${overdueBadge}
            </div>
        </td>

        <td>
            <div>${c.last_contact_at ? formatDT(c.last_contact_at) : '—'}</div>
        </td>

        <td>${channel}</td>

        <td>
            <select class="form-control mpg-form-select-sm"
                    onchange="inlineUpdate(${c.id}, 'assigned_to', this.value)">
                ${adminOptions(c.assigned_to)}
            </select>
            <small class="text-muted d-block mt-1">Now: ${assignedName}</small>
        </td>

        <td>
            <select class="form-control mpg-form-select-sm"
                    onchange="inlineUpdate(${c.id}, 'priority', this.value)">
                ${priorityOptions(c.priority)}
            </select>
        </td>

        <td>
            <div class="btn-group btn-group-sm">
                <!-- ✅ Edit button -->
                <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm"
                        onclick="openProfile(${c.id});return false;" title="Edit">
                    <i class="fas fa-pen"></i>
                </button>

                <a class="btn btn-success mpg-btn-success crm-btn-sm" target="_blank"
                   href="https://wa.me/977${c.phone_primary || ''}?text=${encodeURIComponent('Namaste ' + (c.name || 'Sir/Madam') + ', ' + (c.service_interest || 'your inquiry') + ' ko barema chito call garna milcha?')}">
                   <i class="fab fa-whatsapp"></i>
                </a>

                <a class="btn btn-info mpg-btn-primary crm-btn-sm" target="_blank" href="${msgHref}">
                    <i class="fab fa-facebook-messenger"></i>
                </a>
            </div>
        </td>
    </tr>`;
}

function render(data){
    ADMINS = data.admins || [];

    const tbody = document.querySelector('#tblContacts tbody');
    const rowsRaw = (data.data?.data || []);

    // ✅ Newest first (frontend backup)
    const rowsSorted = rowsRaw.slice().sort((a,b) => {
        const bid = Number(b.id)||0, aid = Number(a.id)||0;
        if (bid !== aid) return bid - aid;
        // fallback created_at
        const bd = parseISO(b.created_at), ad = parseISO(a.created_at);
        return (bd?bd.getTime():0) - (ad?ad.getTime():0);
    });

    tbody.innerHTML = rowsSorted.length
        ? rowsSorted.map(rowHtml).join('')
        : '<tr><td colspan="9" class="text-center text-muted">No records</td></tr>';

    const p = data.data;
    const pager = document.getElementById('pager');

    if (p && p.total > p.per_page) {
        let h = `<div class="d-flex justify-content-between align-items-center">
            <div class="small text-muted">Showing ${p.from}–${p.to} of ${p.total}</div><div>`;
        if (p.prev_page_url) h += `<button class="btn btn-outline-secondary mpg-btn-outline-secondary mr-2" onclick="loadData('${p.prev_page_url}')">Prev</button>`;
        if (p.next_page_url) h += `<button class="btn btn-outline-secondary mpg-btn-outline-secondary" onclick="loadData('${p.next_page_url}')">Next</button>`;
        h += `</div></div>`;
        pager.innerHTML = h;
    } else {
        pager.innerHTML = '';
    }
}

function openProfile(id){
    const c = CONTACTS[id];
    if (!c) return toast('Data missing', 'error');

    CURRENT = { id };

    document.getElementById('ocName').textContent = c.name || 'Customer';
    document.getElementById('ocPhone').textContent = c.phone_primary || '—';
    document.getElementById('ocLast').textContent = c.last_contact_at ? formatDT(c.last_contact_at) : '—';

    document.getElementById('ocStatus').value = c.status || 'New';
    document.getElementById('ocPriority').value = c.priority || 'Medium';

    const assign = document.getElementById('ocAssign');
    assign.innerHTML = adminOptions(c.assigned_to);

    document.getElementById('ocTags').value = c.tags || '';
    document.getElementById('ocNotes').value = c.notes_summary || '';

    document.getElementById('ocNext').value = toInputDT(c.next_followup_at);

    document.getElementById('ocWaLink').href =
        `https://wa.me/977${c.phone_primary || ''}?text=${encodeURIComponent('Namaste ' + (c.name || 'Sir/Madam') + ', ' + (c.service_interest || 'your inquiry') + ' ko barema chito call garna milcha?')}`;

    const msgHref = c.messenger_username ? `https://m.me/${c.messenger_username}` : (c.fb_profile_url || '#');
    document.getElementById('ocMsgLink').href = msgHref;

    // default planned +2d
    const d = new Date();
    d.setDate(d.getDate() + 2);
    document.getElementById('fuPlanned').value = d.toISOString().slice(0,16);

    $('#profileDrawer').modal('show');
}

async function saveProfile(){
    if (!CURRENT) return toast('Open a contact first', 'error');

    const id = CURRENT.id;
    const status = document.getElementById('ocStatus').value;
    const priority = document.getElementById('ocPriority').value;
    const assigned = document.getElementById('ocAssign').value || null;

    const nextVal = document.getElementById('ocNext').value;
    const nextDb = nextVal ? (nextVal.replace('T',' ') + ':00') : null;

    const tags = document.getElementById('ocTags').value || '';
    const notes = document.getElementById('ocNotes').value || '';

    try {
        showLoader(true);

        await inlineUpdate(id, 'status', status, false);
        await inlineUpdate(id, 'priority', priority, false);
        await inlineUpdate(id, 'assigned_to', assigned, false);
        await inlineUpdate(id, 'next_followup_at', nextDb, false);
        await inlineUpdate(id, 'tags', tags, false);
        await inlineUpdate(id, 'notes_summary', notes, false);

        toast('Saved');
        $('#profileDrawer').modal('hide');
        loadData();
    } catch (e) {
        toast('Save failed', 'error');
    } finally {
        showLoader(false);
    }
}

function snoozeLocal(days){
    const el = document.getElementById('fuPlanned');
    const d = new Date();
    d.setDate(d.getDate() + days);
    el.value = d.toISOString().slice(0,16);
}

async function saveLead(){
    const f = document.getElementById('formNewLead');
    const formData = new FormData(f);

    try {
        const r = await fetch(`{{ route('admin.followups.contact.store') }}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const ct = r.headers.get('content-type') || '';
        const j = ct.includes('application/json') ? await r.json() : { ok:false };

        if (r.status === 401) return toast('Please login again', 'error');
        if (r.status === 419) return toast('CSRF mismatch. Refresh page.', 'error');
        if (r.status === 422) {
            const first = j?.errors ? Object.values(j.errors)[0]?.[0] : 'Validation failed';
            return toast(first, 'error');
        }

        if (j.ok) {
            toast('Lead saved');
            $('#modalNewLead').modal('hide');
            f.reset();

            // ✅ reload first page (backend sorted desc भए new entry top आउने)
            loadData();
        } else {
            toast(j.msg || 'Save failed', 'error');
        }
    } catch (e) {
        toast('Network/Server error', 'error');
    }
}

document.getElementById('btnSaveProfile').addEventListener('click', function(e){
    e.preventDefault();
    saveProfile();
});

document.getElementById('btnSaveLead').addEventListener('click', function(e){
    e.preventDefault();
    saveLead();
});

document.getElementById('formNewLead').addEventListener('keydown', function(e){
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        saveLead();
    }
});

document.getElementById('btnSaveFU').addEventListener('click', async function(e){
    e.preventDefault();
    if (!CURRENT) return toast('Open a contact first', 'error');

    const payload = {
        crm_contact_id: CURRENT.id,
        contact_channel: document.getElementById('fuChannel').value,
        planned_at: document.getElementById('fuPlanned').value || null,
        done_at: new Date().toISOString(),
        outcome: document.getElementById('fuOutcome').value || null,
        note: document.getElementById('fuNote').value || '',
        reminder_set: 0
    };

    try {
        const r = await fetch(`{{ route('admin.followups.followup.store') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        const j = await r.json().catch(() => ({ ok:false }));

        if (r.status === 401) return toast('Please login again', 'error');
        if (r.status === 419) return toast('CSRF mismatch. Refresh page.', 'error');
        if (r.status === 422) {
            const first = j?.errors ? Object.values(j.errors)[0]?.[0] : 'Validation failed';
            return toast(first, 'error');
        }

        if (j.ok) {
            toast('Follow-up saved');
            $('#profileDrawer').modal('hide');
            loadData();
        } else {
            toast(j.msg || 'Failed', 'error');
        }
    } catch (e) {
        toast('Failed', 'error');
    }
});

function exportCSV(e){
    e.preventDefault();
    fetch(LAST_PARAMS, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json()).then(j => {
            const rows = (j.data?.data || []).slice().sort((a,b)=> (Number(b.id)||0)-(Number(a.id)||0));
            const head = ['Name','Phone','Status','Next Follow-Up','Last Contact','Channel','Assigned Name','Assigned ID','Priority','City','Tags','Service'];
            const csv = [head.join(',')].concat(rows.map(r => [
                r.name || '',
                r.phone_primary || '',
                r.status || '',
                r.next_followup_at ? formatDT(r.next_followup_at) : '',
                r.last_contact_at ? formatDT(r.last_contact_at) : '',
                (r.latest_follow_up?.contact_channel) || '',
                r.assigned_name || '',
                r.assigned_to || '',
                r.priority || '',
                r.city || '',
                (r.tags || '').replace(/,/g,';'),
                r.service_interest || ''
            ].map(v => `"${String(v).replace(/"/g,'""')}"`).join(','))).join('\n');

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `followups_${Date.now()}.csv`;
            a.click();
            URL.revokeObjectURL(url);
        }).catch(()=> toast('Export failed', 'error'));
}

document.getElementById('btnExport').addEventListener('click', exportCSV);

let tSearch;
document.getElementById('searchBox').addEventListener('input', function(){
    clearTimeout(tSearch);
    tSearch = setTimeout(() => loadData(), 350);
});

document.getElementById('btnApply').addEventListener('click', function(e){
    e.preventDefault();
    loadData();
});

document.getElementById('btnReset').addEventListener('click', function(){
    setTimeout(() => loadData(), 50);
});

document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        document.getElementById('searchBox').focus();
    }
    if (e.key.toLowerCase() === 'n') {
        const t = e.target;
        const typing = ['INPUT','TEXTAREA','SELECT'].includes(t.tagName);
        if (!typing) $('#modalNewLead').modal('show');
    }
});

document.getElementById('sidebarToggle').addEventListener('click', function(){
    document.querySelector('.main-sidebar')?.classList.toggle('sidebar-open');
});

loadData();
</script>
@endpush

