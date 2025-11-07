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
        <div class="crm-header-bar mpg-layout">
            <form id="filterForm" class="form-row align-items-end">
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
    <select class="form-control mpg-form-select" name="status" id="statusMulti">
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
                <div class="col-md-1 col-sm-6 mb-2">
                    <label class="small">Assignee</label>
                    <input type="number" class="form-control mpg-form-control" name="assignee" placeholder="ID">
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small">Priority</label>
                    <select class="form-control mpg-form-select" name="priority">
                        <option value="">All</option>
                        <option>High</option>
                        <option>Medium</option>
                        <option>Low</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <div class="custom-control custom-checkbox d-inline mr-3">
                        <input type="checkbox" class="custom-control-input" id="dueToday">
                        <label class="custom-control-label" for="dueToday">Due Today</label>
                    </div>
                    <div class="custom-control custom-checkbox d-inline mr-3">
                        <input type="checkbox" class="custom-control-input" id="overdue">
                        <label class="custom-control-label" for="overdue">Overdue</label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-2">
                    <input type="text" name="search" id="searchBox" class="form-control mpg-form-control" placeholder="Search name/phone/city/tags (⌘/Ctrl+K)">
                </div>
                <div class="col-md-4 col-sm-12 mb-2 text-right">
                    <button class="btn btn-info mpg-btn-primary" id="btnApply"><i class="fas fa-filter mr-1"></i> Apply</button>
                    <button type="reset" class="btn btn-secondary mpg-btn-secondary" id="btnReset">Reset</button>
                    <button class="btn btn-secondary mpg-btn-secondary" id="btnExport"><i class="fas fa-download mr-1"></i> Export (.csv)</button>
                </div>
            </form>
        </div>

        <div class="mb-2" id="statusPills"></div>

        <div class="crm-table-container mpg-layout">
            <div class="crm-table-wrapper">
                <div id="loader" class="p-3" style="display:none">
                    <div style="height:18px;width:40%;background:#e2e8f0;border-radius:8px"></div>
                    <div class="mt-2" style="height:56px;background:#f1f5f9;border-radius:12px"></div>
                    <div class="mt-2" style="height:56px;background:#f1f5f9;border-radius:12px"></div>
                    <div class="mt-2" style="height:56px;background:#f1f5f9;border-radius:12px"></div>
                </div>
                <table class="table table-hover mpg-table" id="tblContacts">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Customer</th>
                            <th style="width: 15%;">Phone</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 15%;">Next Follow-Up</th>
                            <th style="width: 15%;">Last Contact</th>
                            <th style="width: 10%;">Channel</th>
                            <th style="width: 10%;">Assigned</th>
                            <th style="width: 10%;">Priority</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="crm-pagination p-2" id="pager"></div>
        </div>
    </div>

    <!-- Profile Drawer -->
    <div class="modal fade" id="profileDrawer" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card-header" style="background: linear-gradient(90deg, #093b7b 0%, #646564 100%); color: #ffffff; padding: 16px; border-radius: 8px 8px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0" id="ocName">Customer</span>
                        <button class="btn-close text-white" data-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Priority</label>
                            <select class="form-control mpg-form-select" id="ocPriority">
                                <option>High</option>
                                <option>Medium</option>
                                <option>Low</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Assign To (Admin ID)</label>
                            <input id="ocAssign" class="form-control mpg-form-control" placeholder="e.g., 1">
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Primary Phone</div>
                                <div class="h6 mb-0" id="ocPhone">—</div>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <a id="ocWaLink" class="btn btn-info mpg-btn-primary" target="_blank"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
                                <a id="ocMsgLink" class="btn btn-info mpg-btn-primary" target="_blank"><i class="fab fa-facebook-messenger mr-1"></i> Messenger</a>
                            </div>
                        </div>
                        <div class="small mt-1">
                            <span id="ocConsent" class="mr-2 d-none"><i class="fas fa-shield-alt text-success mr-1"></i> Consent</span>
                            <span class="text-muted">Last Contact: <span id="ocLast">—</span></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="text-muted small">Service Interest</div>
                            <div id="ocService">—</div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="text-muted small">Budget</div>
                            <div id="ocBudget">—</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="text-muted small">City</div>
                            <div id="ocCity">—</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="text-muted small">Source</div>
                            <div id="ocSource">—</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="text-muted small">Tags</div>
                            <div id="ocTags">—</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="text-muted small">Notes</div>
                            <div id="ocNotes">—</div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Add Follow-Up</h6>
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
                            <textarea id="fuNote" class="form-control mpg-form-control" rows="2" placeholder="Short note..."></textarea>
                        </div>
                        <div class="col-md-12 d-flex align-items-center justify-content-between">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="fuRem">
                                <label class="custom-control-label" for="fuRem">Set reminder</label>
                            </div>
                            <div>
                                <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm" onclick="snoozeLocal(1);return false;">Snooze +1d</button>
                                <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm" onclick="snoozeLocal(3);return false;">+3d</button>
                                <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm" onclick="snoozeLocal(7);return false;">+7d</button>
                                <button class="btn btn-info mpg-btn-primary crm-btn-sm" id="btnSaveFU"><i class="fas fa-save mr-1"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Lead Modal -->
    <div class="modal fade" id="modalNewLead" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="card-header" style="background: linear-gradient(90deg, #093b7b 0%, #646564 100%); color: #ffffff; padding: 16px; border-radius: 8px 8px 0 0;">
                    <h5 class="modal-title mb-0"><i class="fas fa-user-plus mr-1"></i> New Lead</h5>
                </div>
                <div class="modal-body p-4">
                    <form id="formNewLead">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label class="small">Full Name</label>
                                <input name="name" class="form-control mpg-form-control" placeholder="e.g., Sita Gurung">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small">Phone <span class="text-danger">*</span></label>
                                <input name="phone_primary" class="form-control mpg-form-control" required placeholder="98XXXXXXXX">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small">Alt. Phone</label>
                                <input name="phone_alt" class="form-control mpg-form-control" placeholder="98XXXXXXXX">
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
                                <label class="small">Preferred Language</label>
                                <select name="preferred_language" class="form-control mpg-form-select">
                                    <option>Nepali</option>
                                    <option>English</option>
                                </select>
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
                            <div class="col-md-6 mb-3">
                                <label class="small">Service Interest</label>
                                <input name="service_interest" class="form-control mpg-form-control" placeholder="Hair Keratin / Real Estate / ...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small">Budget Range</label>
                                <input name="budget_range" class="form-control mpg-form-control" placeholder="Rs 3,000 – 6,000">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small">Tags</label>
                                <input name="tags" class="form-control mpg-form-control" placeholder="comma,separated,tags">
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="consentNew" name="whatsapp_opt_in">
                                    <label class="custom-control-label" for="consentNew">WhatsApp/Messenger consent obtained</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="small">Quick Note</label>
                                <textarea name="notes_summary" class="form-control mpg-form-control" rows="2" placeholder="e.g., Asked to call after 5 PM"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <span class="text-muted mr-auto small">Tip: Press <kbd>Ctrl</kbd>/<kbd>⌘</kbd> + <kbd>Enter</kbd> to save</span>
                    <button class="btn btn-secondary mpg-btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-info mpg-btn-primary" id="btnSaveLead"><i class="fas fa-save mr-1"></i> Save Lead</button>
                </div>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let CURRENT = null;
let LAST_PARAMS = '';
let ADMINS = [];
let CONTACTS = {};

function toast(msg, type='success') {
    showNotification(msg, type === 'error' ? '#dc2626' : '#38a169');
}

function relTime(iso) {
    if (!iso) return '—';
    const d = new Date(iso);
    const diff = (Date.now() - d.getTime()) / 1000;
    const abs = Math.abs(diff);
    const units = [[60, 's'], [60, 'm'], [24, 'h'], [7, 'd'], [4.345, 'w'], [12, 'mo']];
    let n = abs, label = 'y', i = 0;
    for (; i < units.length && n >= units[i][0]; i++) {
        n /= units[i][0];
        label = units[i][1];
    }
    n = Math.floor(n);
    const when = diff > 0 ? `${n}${label} ago` : `in ${n}${label}`;
    return `${d.toLocaleString()} · <span class="text-muted">${when}</span>`;
}

function adminOptions(selectedId) {
    return ADMINS.map(a => `<option value="${a.id}" ${String(a.id) === String(selectedId) ? 'selected' : ''}>${a.name}</option>`).join('');
}

function pillHtml(counts) {
    const names = ['New', 'Warm', 'Follow-up Due', 'Negotiation', 'Won', 'Lost', 'Dormant'];
    let h = '<ul class="nav nav-pills flex-wrap">';
    names.forEach(n => {
        const c = counts[n] || 0;
        h += `<li class="nav-item mr-2 mb-2"><a href="#" data-status="${n}" class="nav-link ${n === 'New' ? 'active' : ''}">${n} <span class="badge badge-light ml-1">${c}</span></a></li>`;
    });
    h += '</ul>';
    return h;
}

function rowHtml(c) {
    CONTACTS[c.id] = c;
    const lf = c.latest_follow_up;
    const channel = lf ? lf.contact_channel : '-';
    const last = c.last_contact_at ? relTime(c.last_contact_at) : '—';
    const statusBadge = (c.status === 'Follow-up Due') ? 'badge-danger' : (c.status === 'New' ? 'badge-info' : (c.status === 'Warm' ? 'badge-primary' : 'badge-secondary'));
    const dueFlag = c.next_followup_at && new Date(c.next_followup_at) < new Date() ? '<span class="badge badge-danger ml-2">Overdue</span>' : '';
    const msgHref = c.messenger_username ? `https://m.me/${c.messenger_username}` : (c.fb_profile_url || '#');
    const assignedName = c.assigned_name || (c.assigned_to || '');

    return `
    <tr data-id="${c.id}" class="${c.status === 'New' ? 'crm-row-pending' : c.status === 'Warm' ? 'crm-row-in-progress' : c.status === 'Follow-up Due' ? 'crm-row-waiting-payment' : ''}">
        <td class="crm-contact-col">
            <a href="#" class="crm-contact-col" onclick="openProfile(${c.id});return false;">${c.name || '—'}</a>
            <div class="small text-muted">${c.tags || ''} ${c.city ? ('• ' + c.city) : ''}</div>
        </td>
        <td>
            <span class="mr-2">${c.phone_primary || '—'}</span>
            ${c.phone_primary ? `<i class="fas fa-copy" style="cursor:pointer" title="Copy" onclick="navigator.clipboard.writeText('${c.phone_primary}');toast('Copied');"></i>` : ''}
        </td>
        <td><span class="badge ${statusBadge}">${c.status}</span></td>
        <td class="crm-input-row">
            <input type="datetime-local" class="form-control mpg-form-control-sm" value="${c.next_followup_at ? c.next_followup_at.replace(' ', 'T').slice(0, 16) : ''}" onchange="inlineUpdate(${c.id}, 'next_followup_at', this.value)">${dueFlag}
        </td>
        <td>${last}</td>
        <td>${channel}</td>
        <td>
            <select class="form-control mpg-form-select-sm" onchange="inlineUpdate(${c.id}, 'assigned_to', this.value)">${adminOptions(c.assigned_to)}</select>
            <small class="text-muted d-block mt-1">Now: ${assignedName || '—'}</small>
        </td>
        <td>
            <select class="form-control mpg-form-select-sm" onchange="inlineUpdate(${c.id}, 'priority', this.value)">
                <option ${c.priority === 'High' ? 'selected' : ''}>High</option>
                <option ${c.priority === 'Medium' ? 'selected' : ''}>Medium</option>
                <option ${c.priority === 'Low' ? 'selected' : ''}>Low</option>
            </select>
        </td>
        <td>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-info mpg-btn-primary crm-btn-sm" onclick="openProfile(${c.id})" title="View"><i class="fas fa-eye"></i></button>
                <a class="btn btn-success mpg-btn-success crm-btn-sm" target="_blank" href="https://wa.me/977${c.phone_primary}?text=${encodeURIComponent('Namaste ' + (c.name || 'Sir/Madam') + ', ' + (c.service_interest || 'your inquiry') + ' ko barema chito call garna milcha?')}">
                    <i class="fab fa-whatsapp"></i></a>
                <a class="btn btn-info mpg-btn-primary crm-btn-sm" target="_blank" href="${msgHref}"><i class="fab fa-facebook-messenger"></i></a>
                <div class="btn-group">
                    <button class="btn btn-secondary mpg-btn-secondary crm-btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="setStatus(${c.id}, 'Warm');return false;">Mark Warm</a>
                        <a class="dropdown-item" href="#" onclick="setStatus(${c.id}, 'Negotiation');return false;">Mark Negotiation</a>
                        <a class="dropdown-item" href="#" onclick="setStatus(${c.id}, 'Won');return false;">Mark Won</a>
                        <a class="dropdown-item" href="#" onclick="setStatus(${c.id}, 'Lost');return false;">Mark Lost</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="snooze(${c.id}, 1);return false;">Snooze +1d</a>
                        <a class="dropdown-item" href="#" onclick="snooze(${c.id}, 3);return false;">Snooze +3d</a>
                        <a class="dropdown-item" href="#" onclick="snooze(${c.id}, 7);return false;">+7d</a>
                    </div>
                </div>
            </div>
        </td>
    </tr>`;
}

function render(data) {
    ADMINS = data.admins || [];
    document.getElementById('statusPills').innerHTML = pillHtml(data.counts || {});
    const tbody = document.querySelector('#tblContacts tbody');
    const rows = (data.data?.data || []).map(rowHtml).join('');
    tbody.innerHTML = rows || '<tr><td colspan="9" class="text-center text-muted">No records</td></tr>';

    const p = data.data;
    const pager = document.getElementById('pager');
    if (p && p.total > p.per_page) {
        let h = `<div class="d-flex justify-content-between align-items-center">
            <div>Showing ${p.from}–${p.to} of ${p.total}</div><div>`;
        if (p.prev_page_url) h += `<button class="btn btn-outline-secondary mpg-btn-outline-secondary mr-2" onclick="loadData('${p.prev_page_url}')">Prev</button>`;
        if (p.next_page_url) h += `<button class="btn btn-outline-secondary mpg-btn-outline-secondary" onclick="loadData('${p.next_page_url}')">Next</button>`;
        h += `</div></div>`;
        pager.innerHTML = h;
    } else {
        pager.innerHTML = '';
    }
}

function showLoader(on) {
    document.getElementById('loader').style.display = on ? 'block' : 'none';
}

function getStatuses() {
    const val = document.getElementById('statusMulti').value;
    // यदि "All" (value="") छ भने filter नपठाउने
    return val ? [val] : [];
}

function buildParams(url = null) {
    const params = new URLSearchParams();
    const f = document.getElementById('filterForm');
    getStatuses().forEach(s => params.append('status[]', s));
    const ch = f.channel.value, pr = f.priority.value, asg = f.assignee.value, stext = f.search.value, from = f.from.value, to = f.to.value;
    if (ch) params.append('channel', ch);
    if (pr) params.append('priority', pr);
    if (asg) params.append('assignee', asg);
    if (stext) params.append('search', stext);
    if (from) params.append('from', from);
    if (to) params.append('to', to);
    if (document.getElementById('dueToday').checked) params.append('due_today', 1);
    if (document.getElementById('overdue').checked) params.append('overdue', 1);
    return url ? url : (`{{ route('admin.followups.data') }}?` + params.toString());
}

function loadData(url = null) {
    const endpoint = buildParams(url);
    LAST_PARAMS = endpoint;
    showLoader(true);
    fetch(endpoint, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(render)
        .catch(() => toast('Failed to load', 'error'))
        .finally(() => showLoader(false));
}

function inlineUpdate(id, field, value) {
    fetch(`{{ route('admin.followups.contact.inline') }}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ id, field, value })
    }).then(async r => {
        const j = await r.json().catch(() => ({ ok: false }));
        if (j.ok) {
            toast('Updated');
            loadData();
        } else {
            toast('Update failed', 'error');
        }
    }).catch(() => toast('Update failed', 'error'));
}

function setStatus(id, status) {
    inlineUpdate(id, 'status', status);
}

function snooze(id, days) {
    fetch(`{{ route('admin.followups.contact.snooze') }}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ id, days })
    }).then(async r => {
        const j = await r.json().catch(() => ({ ok: false }));
        if (j.ok) {
            toast('Snoozed');
            loadData();
        } else {
            toast('Snooze failed', 'error');
        }
    }).catch(() => toast('Snooze failed', 'error'));
}

function openProfile(id) {
    const c = CONTACTS[id];
    if (!c) {
        toast('Data missing', 'error');
        return;
    }
    CURRENT = { id, name: c.name, phone_primary: c.phone_primary };

    document.getElementById('ocName').textContent = c.name || 'Customer';
    document.getElementById('ocPhone').textContent = c.phone_primary || '—';
    document.getElementById('ocStatus').value = c.status || 'New';
    document.getElementById('ocPriority').value = c.priority || 'Medium';
    document.getElementById('ocAssign').value = c.assigned_to || '';
    document.getElementById('ocService').textContent = c.service_interest || '—';
    document.getElementById('ocBudget').textContent = c.budget_range || '—';
    document.getElementById('ocCity').textContent = c.city || '—';
    document.getElementById('ocSource').textContent = c.source || '—';
    document.getElementById('ocTags').textContent = c.tags || '—';
    document.getElementById('ocNotes').textContent = c.notes_summary || '—';
    document.getElementById('ocLast').textContent = c.last_contact_at ? new Date(c.last_contact_at).toLocaleString() : '—';
    const consent = String(c.whatsapp_opt_in || '0') === '1';
    document.getElementById('ocConsent').classList.toggle('d-none', !consent);

    document.getElementById('ocWaLink').href = `https://wa.me/977${c.phone_primary}?text=${encodeURIComponent('Namaste ' + (c.name || 'Sir/Madam') + ', ' + (c.service_interest || 'your inquiry') + ' ko barema chito call garna milcha?')}`;
    const msgHref = c.messenger_username ? `https://m.me/${c.messenger_username}` : (c.fb_profile_url || '#');
    document.getElementById('ocMsgLink').href = msgHref;

    const d = new Date();
    d.setDate(d.getDate() + 2);
    document.getElementById('fuPlanned').value = d.toISOString().slice(0, 16);

    $('#profileDrawer').modal('show');
}

function snoozeLocal(days) {
    const el = document.getElementById('fuPlanned');
    const d = new Date();
    d.setDate(d.getDate() + days);
    el.value = d.toISOString().slice(0, 16);
}

function exportCSV() {
    fetch(LAST_PARAMS, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(j => {
            const rows = j.data?.data || [];
            const head = ['Name', 'Phone', 'Status', 'Next Follow-Up', 'Last Contact', 'Channel', 'Assigned Name', 'Assigned ID', 'Priority', 'City', 'Tags', 'Service'];
            const csv = [head.join(',')].concat(rows.map(r => [
                r.name || '', r.phone_primary || '', r.status || '', r.next_followup_at || '', r.last_contact_at || '',
                (r.latest_follow_up?.contact_channel) || '', r.assigned_name || '', r.assigned_to || '', r.priority || '',
                r.city || '', (r.tags || '').replace(/,/g, ';'), r.service_interest || ''
            ].map(v => `"${String(v).replace(/"/g, '""')}"`).join(','))).join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `followups_${Date.now()}.csv`;
            a.click();
            URL.revokeObjectURL(url);
        }).catch(() => toast('Export failed', 'error'));
}
document.getElementById('btnExport').addEventListener('click', exportCSV);

document.getElementById('btnSaveLead').addEventListener('click', saveLead);
document.getElementById('formNewLead').addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        saveLead();
    }
});
async function saveLead() {
    const f = document.getElementById('formNewLead');
    const formData = new FormData(f);
    formData.set('whatsapp_opt_in', document.getElementById('consentNew').checked ? '1' : '0');
    try {
        const r = await fetch(`{{ route('admin.followups.contact.store') }}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const ct = r.headers.get('content-type') || '';
        const j = ct.includes('application/json') ? await r.json() : { ok: false, msg: 'Non-JSON', status: r.status };
        if (r.status === 401) {
            toast('Please login again', 'error');
            return;
        }
        if (r.status === 419) {
            toast('CSRF mismatch. Refresh page.', 'error');
            return;
        }
        if (r.status === 422) {
            const first = j?.errors ? Object.values(j.errors)[0]?.[0] : 'Validation failed';
            toast(first, 'error');
            return;
        }
        if (j.ok) {
            toast('Lead saved');
            $('#modalNewLead').modal('hide');
            f.reset();
            loadData();
        } else {
            toast(j.msg || 'Save failed', 'error');
            console.error('SaveLead error', j);
        }
    } catch (e) {
        console.error(e);
        toast('Network/Server error', 'error');
    }
}

document.getElementById('btnSaveFU').addEventListener('click', async function() {
    if (!CURRENT) {
        toast('Open a contact first', 'error');
        return;
    }
    const payload = {
        crm_contact_id: CURRENT.id,
        contact_channel: document.getElementById('fuChannel').value,
        planned_at: document.getElementById('fuPlanned').value || null,
        done_at: new Date().toISOString(),
        outcome: document.getElementById('fuOutcome').value || null,
        note: document.getElementById('fuNote').value || '',
        reminder_set: document.getElementById('fuRem').checked ? 1 : 0
    };
    try {
        const r = await fetch(`{{ route('admin.followups.followup.store') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(payload)
        });
        const j = await r.json().catch(() => ({ ok: false }));
        if (r.status === 401) {
            toast('Please login again', 'error');
            return;
        }
        if (r.status === 419) {
            toast('CSRF mismatch. Refresh page.', 'error');
            return;
        }
        if (r.status === 422) {
            const first = j?.errors ? Object.values(j.errors)[0]?.[0] : 'Validation failed';
            toast(first, 'error');
            return;
        }
        if (j.ok) {
            toast('Follow-up saved');
            $('#profileDrawer').modal('hide');
            loadData();
        } else {
            toast(j.msg || 'Failed', 'error');
        }
    } catch (e) {
        console.error(e);
        toast('Failed', 'error');
    }
});

let tSearch;
document.getElementById('searchBox').addEventListener('input', function() {
    clearTimeout(tSearch);
    tSearch = setTimeout(() => { loadData(); }, 350);
});
document.getElementById('btnApply').addEventListener('click', function(e) {
    e.preventDefault();
    loadData();
});
document.getElementById('btnReset').addEventListener('click', function() {
    setTimeout(() => { loadData(); }, 50);
});
document.addEventListener('click', function(e) {
    const a = e.target.closest('#statusPills a[data-status]');
    if (!a) return;
    e.preventDefault();
    const s = a.getAttribute('data-status');
    const sel = document.getElementById('statusMulti');
    Array.from(sel.options).forEach(o => o.selected = (o.value === s));
    loadData();
});
document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        document.getElementById('searchBox').focus();
    }
    if (e.key.toLowerCase() === 'n') {
        const t = e.target;
        const typing = ['INPUT', 'TEXTAREA', 'SELECT'].includes(t.tagName);
        if (!typing) {
            $('#modalNewLead').modal('show');
        }
    }
});

document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.main-sidebar').classList.toggle('sidebar-open');
    document.querySelector('.crm-nav').classList.toggle('crm-collapsed');
    document.querySelector('.crm-content').classList.toggle('crm-full-width');
});

loadData();
</script>
@endpush

@section('js_')
<style>
/* ===== MPG CRM – Modern SaaS Theme (scoped) ===== */
:root {
  --mpg-bg: #f6f8fb;
  --mpg-card: #ffffff;
  --mpg-text: #1f2937;        /* slate-800 */
  --mpg-text-muted: #6b7280;  /* gray-500 */
  --mpg-border: #e5e7eb;      /* gray-200 */
  --mpg-line: #e2e8f0;        /* slate-200 */

  /* Brand */
  --mpg-grad-start: #0ea5e9;  /* sky-500 */
  --mpg-grad-mid:   #14b8a6;  /* teal-500 */
  --mpg-grad-end:   #0ea5e9;  /* loop for subtle weave */
  --mpg-primary:    #0ea5e9;
  --mpg-primary-600:#0284c7;
  --mpg-secondary:  #6b7280;
  --mpg-success:    #10b981;
  --mpg-danger:     #ef4444;
  --mpg-warning:    #f59e0b;

  /* Radius & shadow */
  --mpg-radius-xs: 6px;
  --mpg-radius:    10px;
  --mpg-radius-lg: 14px;
  --mpg-shadow-sm: 0 1px 2px rgba(16,24,40,.06), 0 1px 1px rgba(16,24,40,.04);
  --mpg-shadow:    0 10px 15px -3px rgba(16,24,40,.12), 0 4px 6px -2px rgba(16,24,40,.08);
  --mpg-shadow-soft: 0 4px 16px rgba(2, 132, 199, .08);

  /* Sizing */
  --mpg-font: 15px;
  --mpg-line: 1.45;
  --mpg-pad: 12px;
  --mpg-gap: 12px;
  --mpg-anim: .18s ease;
}

/* Base */
.crm-container.mpg-layout{
  display:flex;flex-direction:column;min-height:100vh;background:var(--mpg-bg);color:var(--mpg-text);
  font-size:var(--mpg-font);line-height:var(--mpg-line);
}

/* Top bar with professional gradient */
.crm-nav.mpg-layout{
  background: linear-gradient(100deg, var(--mpg-grad-start) 0%, var(--mpg-grad-mid) 55%, var(--mpg-grad-end) 100%);
  padding: 10px 20px;border-bottom:1px solid rgba(255,255,255,.15);
  position:sticky;top:0;z-index:1000;display:flex;align-items:center;gap:10px;
  box-shadow: var(--mpg-shadow-sm);
}
.crm-nav .crm-admin-info{color:#fff;font-weight:600;}
.crm-sidebar-toggle{
  color:#fff;background:transparent;border:0;padding:6px 10px;font-size:20px;border-radius:var(--mpg-radius-xs);
  transition: transform var(--mpg-anim), box-shadow var(--mpg-anim), background var(--mpg-anim);
}
.crm-sidebar-toggle:hover{transform:translateY(-1px);box-shadow:var(--mpg-shadow-sm);background:rgba(255,255,255,.12)}
.crm-nav .btn-info.mpg-layout{
  background:#ffffff1a;border:1px solid #ffffff33;color:#fff;border-radius:999px;padding:6px 14px;
}
.crm-nav .btn-info.mpg-layout:hover{background:#ffffff2b;box-shadow:var(--mpg-shadow-sm)}

/* Content wrapper */
.crm-content.mpg-layout{padding:24px;min-height:calc(100vh - 64px)}

/* Cards / surfaces */
.crm-header-bar{
  background:var(--mpg-card);border-radius:var(--mpg-radius);padding:12px 14px;margin-bottom:12px;
  box-shadow:var(--mpg-shadow-sm);display:flex;justify-content:space-between;align-items:center;gap:10px;
}
.crm-header-bar h2{margin:0;font-size:18px;font-weight:700;color:#0f172a;display:flex;gap:8px;align-items:center}

/* Form elements */
.mpg-form-control,.mpg-form-select,
.mpg-form-control-sm,.mpg-form-select-sm, .form-control{
  border:1px solid var(--mpg-border);border-radius:var(--mpg-radius-xs);background:#fff;
  padding:8px 10px;font-size:14px;line-height:1.35;transition:border-color var(--mpg-anim), box-shadow var(--mpg-anim);
}
.mpg-form-control:focus,.mpg-form-select:focus,
.mpg-form-control-sm:focus,.mpg-form-select-sm:focus,
.form-control:focus{
  border-color:var(--mpg-primary);box-shadow:0 0 0 3px rgba(14,165,233,.15);outline:0;
}

/* Buttons – uniform height/spacing */
.btn{border-radius:10px;font-weight:600;letter-spacing:.2px;transition:transform var(--mpg-anim), box-shadow var(--mpg-anim), background var(--mpg-anim)}
.btn, .btn-sm{line-height:1.2}
.btn, .btn-sm, .crm-btn-sm{height:36px;display:inline-flex;align-items:center;gap:8px}

.mpg-btn-primary,.btn-info{
  background:linear-gradient(135deg, var(--mpg-primary) 0%, #22d3ee 100%);border:1px solid rgba(14,165,233,.35);color:#fff;
}
.mpg-btn-primary:hover,.btn-info:hover{transform:translateY(-1px);box-shadow:var(--mpg-shadow-soft)}

.mpg-btn-secondary,.btn-secondary{background:#f3f4f6;border:1px solid var(--mpg-border);color:#111827}
.mpg-btn-secondary:hover,.btn-secondary:hover{background:#eef2f7;box-shadow:var(--mpg-shadow-sm)}

.mpg-btn-success,.btn-success{background:var(--mpg-success);border-color:var(--mpg-success)}
.mpg-btn-success:hover,.btn-success:hover{filter:brightness(.95);box-shadow:var(--mpg-shadow-sm)}

.mpg-btn-outline-secondary{border:1px solid var(--mpg-secondary);color:var(--mpg-secondary);background:#fff}
.mpg-btn-outline-secondary:hover{background:var(--mpg-secondary);color:#fff}

.crm-btn-sm{height:32px;padding:4px 10px;border-radius:9px}

/* Table container */
.crm-table-container{
  background:var(--mpg-card);border-radius:var(--mpg-radius);box-shadow:var(--mpg-shadow-sm);padding:8px;overflow:hidden;
}
.crm-table-wrapper{width:100%;max-height:calc(100vh - 260px);overflow:auto;border-radius:var(--mpg-radius)}

/* Table styling */
.mpg-table{width:100%;border-collapse:separate;border-spacing:0;table-layout:fixed}
.mpg-table thead th{
  position:sticky;top:0;z-index:5;background:#f8fafc;border-bottom:1px solid var(--mpg-line);
  color:#0f172a;font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:.4px;padding:10px 8px;
}
.mpg-table tbody td{
  padding:10px 8px;border-bottom:1px solid var(--mpg-line);font-size:14px;color:var(--mpg-text);
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}

/* Stripes & subtle hover */
.mpg-table tbody tr:nth-child(odd){background:#ffffff}
.mpg-table tbody tr:nth-child(even){background:#fbfdff}
.mpg-table tbody tr:hover{background:#f1f5f9}

/* Status row tints (very light) */
.crm-row-pending{background:linear-gradient(0deg, #fff, #fff) !important}
.crm-row-in-progress{background:linear-gradient(0deg, #f0f9ff, #ffffff) !important}
.crm-row-waiting-payment{background:linear-gradient(0deg, #fff5f5, #ffffff) !important}

/* Badges */
.badge-info{background:var(--mpg-primary)}
.badge-primary{background:#60a5fa}
.badge-danger{background:var(--mpg-danger)}
.badge-secondary{background:#94a3b8}
.badge{border-radius:999px;padding:.35em .6em;font-weight:600}

/* Pills (status tabs) */
#statusPills .nav-link{
  border-radius:999px;background:#eef2f7;color:#111827;padding:6px 12px;font-weight:600;transition:box-shadow var(--mpg-anim), transform var(--mpg-anim);
}
#statusPills .nav-link:hover{transform:translateY(-1px);box-shadow:var(--mpg-shadow-sm)}
#statusPills .nav-link.active{background:linear-gradient(135deg,var(--mpg-primary),#22c55e);color:#fff}

/* Copyable contact & links */
.crm-contact-col{color:#0f172a;font-weight:600}
.crm-contact-col:hover{color:var(--mpg-primary)}

/* Pagination */
.crm-pagination{display:flex;justify-content:center;margin-top:10px}

/* Drawer / Modals */
.modal-content{border:none;border-radius:var(--mpg-radius-lg);box-shadow:var(--mpg-shadow)}
.card-header{
  border-radius:var(--mpg-radius-lg) var(--mpg-radius-lg) 0 0 !important;
  background: linear-gradient(120deg, var(--mpg-grad-start), var(--mpg-grad-mid));
  color:#fff;border:none;
}

/* Inputs in table (inline edit) */
.mpg-form-control-sm, .mpg-form-select-sm{height:34px}

/* Loader – skeleton shimmer */
#loader > div{
  position:relative;overflow:hidden;background:#eef2f7;border-radius:12px;
}
#loader > div::after{
  content:"";position:absolute;inset:0;
  background:linear-gradient(90deg, transparent, rgba(255,255,255,.6), transparent);
  animation:mpg-shimmer 1.3s infinite;
}
@keyframes mpg-shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}

/* Sidebar responsiveness (collapsible on mobile) */
@media (max-width: 992px){
  .crm-content.mpg-layout{padding:16px}
  .crm-header-bar{flex-direction:column;align-items:stretch;gap:10px}
  .crm-header-bar form{width:100%;display:grid;grid-template-columns:1fr 1fr;gap:10px}
  .mpg-form-control,.mpg-form-select{width:100%}
  .crm-nav{padding:10px 14px}

  /* slide-in sidebar if your template exposes .main-sidebar */
  .main-sidebar{position:fixed;inset:0 auto 0 0;width:260px;transform:translateX(-100%);transition:transform .22s ease;z-index:1100;box-shadow:var(--mpg-shadow)}
  .main-sidebar.sidebar-open{transform:translateX(0)}
  .crm-content.crm-full-width{margin-left:0}

  /* table horizontal scroll for small screens */
  .crm-table-wrapper{overflow:auto}
  .mpg-table{min-width:900px}
}

/* Tiny screens: single column filters */
@media (max-width: 576px){
  .crm-header-bar form{grid-template-columns:1fr}
  .btn, .btn-sm, .crm-btn-sm{width:100%}
  .crm-nav .btn-info.mpg-layout{width:100%}
}

/* Utilities */
.text-muted{color:var(--mpg-text-muted)!important}
.small{font-size:.86em}
.kbd, kbd{background:#111827;color:#fff;border-radius:6px;padding:2px 6px}

/* Export/Action dropdown caret spacing */
.dropdown-menu{border-radius:12px;border:1px solid var(--mpg-border);box-shadow:var(--mpg-shadow-sm)}
.dropdown-item{padding:8px 12px}
.dropdown-item:hover{background:#f3f4f6}

/* Inputs in inline rows stay compact */
.crm-input-row input[type="datetime-local"]{min-width:230px}
.modal-backdrop.show {
    opacity: .5;
    display: none;
}
</style>

@endsection
