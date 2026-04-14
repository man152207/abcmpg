

<?php $__env->startSection('title', 'Duty Schedule'); ?>

<?php $__env->startSection('content'); ?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="container-fluid duty-schedule-page pb-4">

    <?php if(session('success')): ?>
        <div class="alert success" style="margin-top:10px;">
            <?php echo e(session('success')); ?>

            <button onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>
        </div>
    <?php endif; ?>

    <style>
        .duty-schedule-page * { box-sizing: border-box; }
        .duty-schedule-page {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 16px;
            background: #eef2f7;
            color: #2c3e50;
            line-height: 1.5;
        }

        /* Card */
        .section-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(16,24,40,.08);
            padding: 16px 16px 12px;
            margin-bottom: 16px;
        }

        .layout-row {
            display:flex;
            flex-wrap:wrap;
            gap:14px;
            margin-bottom:14px;
        }
        .layout-half {
            flex:1 1 320px;
            min-width:0;
        }
        .layout-half .section-card {
            margin-bottom:0;
            height:100%;
            display:flex;
            flex-direction:column;
        }

        h1.page-title {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0 0 10px;
            color:#111827;
        }
        h2.sec-head {
            font-size:.95rem;
            font-weight:700;
            margin:0 0 10px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            color:#111827;
        }

        p.subtext {
            font-size: 12px;
            color: #667085;
            margin: 0 0 8px;
        }

        .row-line,
        .action-row-main {
            display:flex;
            flex-wrap:wrap;
            gap:12px;
            align-items:flex-end;
            margin-bottom:10px;
        }

        label.form-block {
            display:flex;
            flex-direction:column;
            font-size:12px;
            font-weight:600;
            color:#1e2a37;
            min-width:140px;
        }
        .form-block span.lbl {
            margin-bottom:4px;
            color:#475467;
            font-weight:600;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width:100%;
            font-size:13px;
            padding:8px 10px;
            border-radius:8px;
            border:1.8px solid #dde4ed;
            background:#fff;
            font-weight:500;
            color:#1e2a37;
        }
        input:focus,
        select:focus {
            outline:none;
            border-color:#7c3aed;
            box-shadow:0 0 0 4px rgba(124,58,237,.14);
        }

        button.btn,
        .btn-inline {
            background: linear-gradient(135deg,#7c3aed,#4f46e5);
            border: none;
            border-radius: 8px;
            color:#fff;
            font-size:12px;
            font-weight:700;
            padding: 9px 14px;
            line-height:1.2;
            cursor:pointer;
            box-shadow:0 6px 16px rgba(79,70,229,.25);
            white-space:nowrap;
        }
        button.btn:disabled,
        .btn-inline:disabled {
            opacity:.5;
            cursor:not-allowed;
            box-shadow:none;
        }
        button.btn:hover:not(:disabled),
        .btn-inline:hover:not(:disabled) {
            transform: translateY(-1px);
        }

        /* red destructive button */
        .btn-danger {
            background:linear-gradient(135deg,#ef4444,#dc2626) !important;
            box-shadow:0 6px 16px rgba(220,38,38,.25) !important;
        }

        /* mini buttons in Actions col */
        .btn-mini {
            background:#4f46e5;
            color:#fff;
            border:none;
            font-size:12px;
            font-weight:700;
            padding:7px 10px;
            border-radius:6px;
            cursor:pointer;
            line-height:1.2;
            box-shadow:0 4px 10px rgba(79,70,229,.25);
        }
        .btn-mini.week-toggle-btn {
            background:#111827;
            box-shadow:0 4px 10px rgba(0,0,0,.28);
        }
        .btn-mini.week-toggle-btn[disabled]{
            opacity:.65;
            cursor:not-allowed;
        }

        /* Badges */
        .badges { display:flex; flex-wrap:wrap; gap:6px; }
        .badge {
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
            line-height:1.2;
        }
        .badge-on  { background:#eafaef; color:#1d7a42; border:1px solid #b7e3c8; }
        .badge-off { background:#fff4e5; color:#8a4c00; border:1px solid #ffd79f; }
        .badge-cover { background:#eef5ff; color:#1e40af; border:1px solid #bfd3ff; }

        /* LEGEND */
        .legend-row { display:flex; flex-wrap:wrap; gap:14px; font-size:12px; font-weight:600; color:#475467; }
        .legend-item { display:flex; align-items:center; gap:8px; }
        .legend-dot { width:12px; height:12px; border-radius:999px; }
        .dot-on{background:#22c55e;} .dot-off{background:#f59e0b;} .dot-sat{background:#60a5fa;}
        .dot-hol{background:#f87171;} .dot-today{background:#a78bfa;}

        /* TABLE */
        .table-wrapper {
            background:#fff;
            border-radius:12px;
            box-shadow:0 6px 18px rgba(16,24,40,.08);
            overflow-x:auto;
        }
        table#scheduleTable { width:100%; border-collapse:collapse; min-width:800px; font-size:14px; }
        #scheduleTable th {
            background:#111827; color:#fff; font-weight:700; font-size:12.5px; text-align:left;
            padding:12px 10px; position:sticky; top:0; z-index:5; letter-spacing:.2px;
        }
        #scheduleTable td {
            background:#fff; border-bottom:1px solid #e5e7eb; vertical-align:top; padding:12px 10px; color:#111827;
        }
        #scheduleTable tr:nth-child(even) td.data-row-cell { background:#fafafa; }

        tr.saturday td.data-row-cell { background:#eff6ff !important; }
        tr.holiday  td.data-row-cell { background:#fff1f2 !important; }
        tr.today-row td.data-row-cell { position:relative; background:#f5f3ff !important; }
        tr.today-row td.data-row-cell:first-child { border-left:4px solid #7c3aed; }

        /* Week header row */
        tr.week-header-row td {
            background:#1f2937 !important; color:#fff; font-size:12.5px; font-weight:600; border-bottom:2px solid #4b5563;
        }
        .week-header-main { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; width:100%; }
        .week-header-left { display:flex; flex-direction:column; gap:2px; }
        .week-label { font-size:13px; font-weight:700; color:#fff; }
        .week-range { font-size:12px; font-weight:500; color:#e5e7eb; }

        .small-note { font-size:13px; color:#4b5563; }
        .remark-text { font-size:12px; color:#555; line-height:1.45; word-break:break-word; }

        .action-col { display:flex; flex-direction:column; gap:6px; }
        .action-col select {
            font-size:12px; padding:7px 8px; border-radius:6px; border:1.8px solid #dde4ed; outline:none;
        }
        .action-col select:focus { border-color:#7c3aed; box-shadow:0 0 0 4px rgba(124,58,237,.14); }
        .action-col label { font-size:12px; font-weight:600; color:#374151; display:flex; align-items:center; gap:6px; line-height:1.2; }
        .action-col input[type="checkbox"] { width:14px; height:14px; }

        /* Alerts */
        .alert {
            background:#fff7ed; border:1px solid #fdba74; border-radius:8px; padding:8px 10px; margin:8px 0 12px;
            font-size:12px; font-weight:700; color:#8a4c00; display:flex; justify-content:space-between; align-items:center;
        }
        .alert.success { background:#ecfdf5; border-color:#34d399; color:#065f46; }

        /* History list */
        .history-box {
            background:#f8fafc; border-left:4px solid #4f46e5; border-radius:8px; padding:10px; max-height:150px; overflow:auto;
            font-size:11.5px; line-height:1.4;
        }

        @media(max-width:900px){ .layout-row { flex-direction:column; } }
        @media(max-width:768px){
            .row-line, .action-row-main { flex-direction:column; align-items:stretch; }
            label.form-block { min-width:0; width:100%; }
        }
    </style>

    <!-- HEADER -->
    <div class="section-card" style="margin-bottom:14px;">
        <h1 class="page-title">Company Duty Schedule</h1>
        <p class="subtext">
            Rotation updates every Saturday. One staff works Saturday (10–18),
            others get Thu/Fri pre-leave with auto cover.
        </p>
        <p class="subtext">All changes auto-save to DB.</p>
    </div>

    <!-- OPS + COVER -->
    <div class="layout-row">
        <div class="layout-half">
            <div class="section-card" id="opsSection">
                <h2 class="sec-head"><span>Operations Team (3 staff)</span></h2>
                <p class="subtext">Enter exactly 3 operations staff and set first three Saturdays order (A→B→C…)</p>

                <div class="row-line">
                    <label class="form-block">
                        <span class="lbl">Staff A (Name)</span>
                        <input type="text" id="staffAInput" placeholder="Kalpana">
                    </label>
                    <label class="form-block">
                        <span class="lbl">Staff B (Name)</span>
                        <input type="text" id="staffBInput" placeholder="Sharu">
                    </label>
                    <label class="form-block">
                        <span class="lbl">Staff C (Name)</span>
                        <input type="text" id="staffCInput" placeholder="Prakriti">
                    </label>
                </div>

                <div class="row-line" style="margin-top:4px;">
                    <label class="form-block">
                        <span class="lbl">1st Saturday</span>
                        <select id="rotFirst"></select>
                    </label>
                    <label class="form-block">
                        <span class="lbl">2nd Saturday</span>
                        <select id="rotSecond"></select>
                    </label>
                    <label class="form-block">
                        <span class="lbl">3rd Saturday</span>
                        <select id="rotThird"></select>
                    </label>

                    <button class="btn" id="applyOpsBtn" style="margin-left:auto;">Apply</button>
                </div>
            </div>
        </div>

        <div class="layout-half">
            <div class="section-card" id="coverSection">
                <h2 class="sec-head"><span>Cover Pool</span></h2>
                <p class="subtext">Covers are 10:00–18:00. “Ops-capable” has priority.</p>

                <div class="row-line">
                    <label class="form-block">
                        <span class="lbl">Name</span>
                        <input type="text" id="coverName" placeholder="Cover Staff">
                    </label>

                    <label class="form-block">
                        <span class="lbl">Tags (comma)</span>
                        <input type="text" id="coverTags" placeholder="Ops-capable">
                    </label>

                    <label class="form-block" style="max-width:120px;">
                        <span class="lbl">Max covers</span>
                        <input type="number" id="coverCap" value="5" min="1">
                    </label>

                    <label class="form-block" style="flex-direction:row;align-items:center;gap:8px;min-width:auto;">
                        <input type="checkbox" id="coverUnavailable" style="width:16px;height:16px;">
                        <span class="lbl" style="margin-bottom:0;">Unavailable</span>
                    </label>

                    <button class="btn" onclick="addCover()">Add</button>
                    <button class="btn" onclick="clearCovers()">Clear All</button>
                </div>

                <div class="subtext" id="coverQueue" style="font-weight:700;margin-bottom:6px;"></div>
                <div class="badges" id="coverChips"></div>
            </div>
        </div>
    </div>

    <!-- GENERATE / SAVE / DELETE -->
    <div class="section-card" id="buildSection">
        <h2 class="sec-head"><span>Generate / Save</span></h2>

        <div class="action-row-main">
            <label class="form-block" style="max-width:160px;">
                <span class="lbl">6-week Start Date</span>
                <input type="date" id="startDateInput">
            </label>

            <button class="btn" id="build6Btn">Build 6 Week</button>

            <button class="btn" id="undoBtn" onclick="undoAction()">Undo</button>

            <label class="form-block" style="max-width:180px;">
                <span class="lbl">Filter</span>
                <select id="filterSelect">
                    <option value="all">All days</option>
                    <option value="sat">Saturdays only</option>
                    <option value="off">Off / Cover days</option>
                    <option value="holiday">Holidays only</option>
                </select>
            </label>

            <!-- Changed delete to ENTIRE schedule -->
            <button class="btn btn-danger" style="margin-left:auto;" onclick="deleteEntireSchedule()">
                Delete Entire Schedule
            </button>
        </div>

        <div id="alerts"></div>

        <div class="legend-row" style="margin-top:6px;">
            <div class="legend-item"><span class="legend-dot dot-sat"></span>Saturday</div>
            <div class="legend-item"><span class="legend-dot dot-hol"></span>Public Holiday</div>
            <div class="legend-item"><span class="legend-dot dot-on"></span>On duty</div>
            <div class="legend-item"><span class="legend-dot dot-off"></span>Off / Cover</div>
            <div class="legend-item"><span class="legend-dot dot-today"></span>Today</div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="section-card" id="tableSection">
        <h2 class="sec-head"><span>Schedule Preview / Edit</span></h2>

        <div class="table-wrapper">
            <table id="scheduleTable" role="table" aria-label="Duty Schedule">
                <thead>
                    <tr>
                        <th style="min-width:90px;">Date</th>
                        <th style="min-width:80px;">Day</th>
                        <th style="min-width:260px;">Operations</th>
                        <th style="min-width:220px;">Fixed Depts</th>
                        <th style="min-width:240px;">Remarks</th>
                        <th style="min-width:170px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="history" style="margin-top:12px; display:none;">
            <div class="subtext" style="font-weight:700;margin-bottom:6px;">Recent actions</div>
            <div class="history-box" id="historyList"></div>
        </div>
    </div>

</div> <!-- /.duty-schedule-page -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ---- time helpers (Asia/Kathmandu) ----
    const TZ = 'Asia/Kathmandu';

    function formatDateTZ(date) {
        return new Intl.DateTimeFormat('en-CA', {
            timeZone: TZ,
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        }).format(date); // yyyy-mm-dd
    }

    function weekdayTZ(date) {
        return new Intl.DateTimeFormat('en-US', {
            timeZone: TZ,
            weekday: 'long'
        }).format(date);
    }

    /* ===== STATE ===== */
    let staffInputs   = { A:'Kalpana', B:'Sharu', C:'Prakriti' };
    let rotationStart = [];
    let coverPool     = [];
    let holidays      = []; // internal list
    let scheduleData  = [];
    let overrides     = {};
    let dualCounter   = 0;

    // Week collapse state (persist + one-way collapse)
    let weekCollapsed = {};          // {0:true/false,...}
    let weekLockedCollapsed = {};    // {0:true if collapsed & locked}
    let startWindowDateStr = null;

    let historyStack      = [];
    let currentStateIndex = -1;

    const todayStr = formatDateTZ(new Date());

    // server-provided rows + start window
    const serverSchedule = <?php echo json_encode($dutySchedules ?? [], 15, 512) ?>;
    const serverWindowStart = <?php echo json_encode($windowStart ?? null, 15, 512) ?>;

    /* ===== LOCAL STORAGE ===== */
    function saveLocal() {
        localStorage.setItem('dutyScheduleState_simple', JSON.stringify({
            staffInputs,
            rotationStart,
            coverPool,
            holidays,
            overrides,
            dualCounter,
            startWindowDateStr,
            weekCollapsed,
            weekLockedCollapsed
        }));
    }

    function loadLocal() {
        const raw = localStorage.getItem('dutyScheduleState_simple');
        if (!raw) return;
        try {
            const st = JSON.parse(raw);
            staffInputs         = st.staffInputs    || staffInputs;
            rotationStart       = st.rotationStart  || rotationStart;
            coverPool           = st.coverPool      || [];
            holidays            = st.holidays       || [];
            overrides           = st.overrides      || {};
            dualCounter         = st.dualCounter    || 0;
            startWindowDateStr  = st.startWindowDateStr || startWindowDateStr;
            weekCollapsed       = st.weekCollapsed || {};
            weekLockedCollapsed = st.weekLockedCollapsed || {};
        } catch(_){}
    }

    /* ===== ALERTS ===== */
    function showAlert(msg, type='info') {
        const alerts = document.getElementById('alerts');
        const div = document.createElement('div');
        div.className = 'alert '+(type||'info');
        div.innerHTML = msg + ` <button onclick="this.parentElement.remove()" aria-label="Dismiss">×</button>`;
        alerts.appendChild(div);
        setTimeout(()=>{ div.remove(); }, 5000);
    }

    /* ===== OPS HELPERS ===== */
    function getAllOpsNames() {
        const arr = [staffInputs.A, staffInputs.B, staffInputs.C]
            .map(s => (s||'').trim())
            .filter(Boolean);
        return [...new Set(arr)];
    }

    function getSaturdayTriple(satIdx) {
        const base = rotationStart.length === 3 ? rotationStart.slice() : getAllOpsNames();
        while (base.length < 3) base.push('N/A');
        const rotated = base.slice();
        for (let i=0;i<satIdx;i++){
            rotated.push(rotated.shift());
        }
        return rotated.slice(0,3);
    }

    function addRemarkOnce(row, text) {
        if (!row.remarks.includes(text)) row.remarks.push(text);
    }

    /* ===== COVER ASSIGNMENT ===== */
    function assignCoverForOff(row, offPerson, reason) {
        if (row.covers[offPerson]) return;
        const FIX_TIME = '10:00-18:00';

        // prefer Ops-capable under cap
        let elig = coverPool.filter(c =>
    !c.unavailable &&
    c.name !== offPerson && // ✅
    (c.tags||[]).includes('Ops-capable') &&
    (c.coversThisPeriod||0) < (c.cap||5)
);

        // fallback any available under cap
        if (!elig.length) {
            elig = coverPool.filter(c =>
                !c.unavailable &&
                (c.coversThisPeriod||0) < (c.cap||5)
            );
        }

        if (!elig.length) {
            row.remarks.push(`No cover available - ${reason}`);
            return;
        }

        elig.sort((a,b)=>(a.coversThisPeriod||0)-(b.coversThisPeriod||0));
        const chosen = elig[0];
        chosen.coversThisPeriod = (chosen.coversThisPeriod||0)+1;

        row.remarks.push(`Cover: ${chosen.name} (10-18) - ${reason}`);
        row.covers[offPerson] = { name: chosen.name, time: FIX_TIME };
    }

    function persistOverride(row) {
        if (!overrides[row.date]) overrides[row.date] = {};
        overrides[row.date].twoNeeded   = row.twoNeeded;
        overrides[row.date].dualChoice  = row.dualChoice;
        overrides[row.date].manualOffs  = [...row.manualOffs];
        overrides[row.date].covers      = {...row.covers};
        saveLocal();
    }

    function reconcileCoversForRow(idx) {
        const row = scheduleData[idx];
        const offList = [
            ...Object.keys(row.ruleOffs||{}),
            ...row.manualOffs,
            ...row.opsOff
        ];

        // remove covers for people not off anymore
        Object.keys(row.covers).forEach(offPerson => {
            if (!offList.includes(offPerson)) {
                row.remarks = row.remarks.filter(r => !(r.includes('Cover:') && r.includes(offPerson)));
                delete row.covers[offPerson];
            }
        });

        // add covers for new offs
        offList.forEach(offPerson => {
            if (!row.covers[offPerson]) {
                const reason = row.ruleOffs?.[offPerson] || 'Ad-hoc leave';
                assignCoverForOff(row, offPerson, reason);
            }
        });

        persistOverride(row);
    }

    /* ===== CLEAR PRE-LEAVE AROUND A SATURDAY ===== */
    function clearPreleaveMarksAround(satIdx) {
        let thuIdx = satIdx-2;
        while (thuIdx>=0 && !scheduleData[thuIdx].isThursday) thuIdx--;
        let friIdx = satIdx-1;
        while (friIdx>=0 && !scheduleData[friIdx].isFriday)   friIdx--;

        [thuIdx, friIdx].forEach(id => {
            if (id<0) return;
            const r = scheduleData[id];

            Object.keys(r.ruleOffs).forEach(person => {
                const reason = r.ruleOffs[person] || '';
                if (
                    reason.includes('pre-dual') ||
                    reason.includes('for Sat single') ||
                    reason.includes('Fri off (pre-dual)')
                ) {
                    delete r.ruleOffs[person];
                }
            });

            r.remarks = r.remarks.filter(rem =>
                !rem.includes('pre-dual') &&
                !rem.includes('Sat single') &&
                !rem.includes('Fri off (pre-dual)') &&
                !rem.includes('Fri off for Sat single')
            );

            r.covers = {};
            persistOverride(r);
        });
    }

    /* ===== APPLY CORE RULES ===== */
    function applyRulesAndCovers() {
        scheduleData.forEach((row) => {
            if (!row.isSaturday) return;
            const [A,B,C] = row.satTriple || [null,null,null];
            const isDual = !!row.twoNeeded;

            if (isDual) {
                row.opsAssigned = [A,B].filter(Boolean);
                row.opsOff      = [C].filter(Boolean);

                row.remarks = row.remarks.filter(r => !r.includes('Sat '));
                addRemarkOnce(row,'Dual Sat (High workload)');

                if (row.dualChoice === undefined) {
                    row.dualChoice = dualCounter % 2;
                    dualCounter++;
                }
            } else {
                row.opsAssigned = [A].filter(Boolean);
                row.opsOff      = [B,C].filter(Boolean);

                row.remarks = row.remarks.filter(r => !r.includes('Sat '));
                addRemarkOnce(row,'Single Sat (Weekly rotation)');
                if (B || C) {
                    addRemarkOnce(row,'Sat off: '+[B,C].filter(Boolean).join(', '));
                }
            }
        });

        scheduleData.forEach((satRow, satIdx) => {
            if (!satRow.isSaturday) return;
            const [A,B] = satRow.satTriple || [null,null,null];

            let thuIdx = satIdx-2;
            while (thuIdx>=0 && !scheduleData[thuIdx].isThursday) thuIdx--;
            let friIdx = satIdx-1;
            while (friIdx>=0 && !scheduleData[friIdx].isFriday)   friIdx--;

            clearPreleaveMarksAround(satIdx);

            if (!satRow.twoNeeded) {
                if (friIdx>=0 && A) {
                    const friRow = scheduleData[friIdx];
                    friRow.ruleOffs[A] = 'Fri off for Sat single';
                    addRemarkOnce(friRow,'Fri off for Sat single');
                    assignCoverForOff(friRow, A, 'Fri off for Sat single');
                    persistOverride(friRow);
                } else {
                    addRemarkOnce(satRow,'Fri out of window');
                }
            } else {
                const choice = satRow.dualChoice ?? 0;
                const thuOff = (choice === 0 ? A : B);
                const friOff = (choice === 0 ? B : A);

                if (thuIdx>=0 && thuOff) {
                    const thuRow = scheduleData[thuIdx];
                    thuRow.ruleOffs[thuOff] = 'Thu off (pre-dual)';
                    addRemarkOnce(thuRow,'Thu off (pre-dual)');
                    assignCoverForOff(thuRow, thuOff, 'Thu off (pre-dual)');
                    persistOverride(thuRow);
                } else {
                    addRemarkOnce(satRow,'Thu out of window');
                }

                if (friIdx>=0 && friOff) {
                    const friRow = scheduleData[friIdx];
                    friRow.ruleOffs[friOff] = 'Fri off (pre-dual)';
                    addRemarkOnce(friRow,'Fri off (pre-dual)');
                    assignCoverForOff(friRow, friOff, 'Fri off (pre-dual)');
                    persistOverride(friRow);
                } else {
                    addRemarkOnce(satRow,'Fri out of window');
                }
            }

            persistOverride(satRow);
        });

        scheduleData.forEach((row, idx) => {
            if (row.isSaturday) return;
            reconcileCoversForRow(idx);
        });
    }

    /* ===== BUILD 6 WEEK SCHEDULE (manual) ===== */
    function build6WeekSchedule() {
    const startVal = document.getElementById('startDateInput').value;
    if (!startVal) {
        showAlert('Please choose a 6-week start date first.','error');
        return;
    }
    const allOps = getAllOpsNames();
    if (allOps.length < 3) {
        showAlert('Please enter all 3 Operations staff first.','error');
        return;
    }

    coverPool.forEach(c => { c.coversThisPeriod = 0; });

    const start = new Date(startVal);          // ✅
    scheduleData = [];
    let saturdayCounter = 0;
    startWindowDateStr = startVal;

    weekCollapsed = {};
    weekLockedCollapsed = {};
    for (let i=0;i<6;i++){ weekCollapsed[i]=false; weekLockedCollapsed[i]=false; }

    for (let i=0; i<42; i++) {
        const d = new Date(start.getTime());
        d.setDate(d.getDate()+i);

        const yyyyMmDd = formatDateTZ(d);      // ✅
        const dayName  = weekdayTZ(d);         // ✅

        const isSaturday = (dayName === 'Saturday');
        const isThursday = (dayName === 'Thursday');
        const isFriday   = (dayName === 'Friday');
        const isHoliday  = holidays.some(h => h.date === yyyyMmDd);


            let satTriple = null;
            if (isSaturday) {
                satTriple = getSaturdayTriple(saturdayCounter);
                saturdayCounter++;
            }

            const rowObj = {
                date: yyyyMmDd,
                dayName,
                isSaturday,
                isThursday,
                isFriday,
                isHoliday,
                remarks: [],
                satTriple,
                twoNeeded: false,
                dualChoice: undefined,
                opsAssigned: [],
                opsOff: [],
                ruleOffs: {},
                manualOffs: [],
                covers: {}
            };

            if (isHoliday) {
                const h = holidays.find(x => x.date === yyyyMmDd);
                rowObj.remarks.push('Holiday: ' + (h ? h.name : 'Public Holiday'));
            }

            if (overrides[yyyyMmDd]) {
                rowObj.twoNeeded  = overrides[yyyyMmDd].twoNeeded  ?? rowObj.twoNeeded;
                rowObj.dualChoice = overrides[yyyyMmDd].dualChoice ?? rowObj.dualChoice;
                rowObj.manualOffs = overrides[yyyyMmDd].manualOffs ? [...overrides[yyyyMmDd].manualOffs] : [];
                rowObj.covers     = overrides[yyyyMmDd].covers     ? {...overrides[yyyyMmDd].covers}     : {};
            }

            scheduleData.push(rowObj);
        }

        applyRulesAndCovers();
        renderTable();
        pushHistory('build 6-week schedule');
        autoSave();
        showAlert('6-week schedule generated ✔ (auto-saved)','success');
        saveLocal();
    }

    /* ===== WEEK HELPERS ===== */
    function parseDate(str) { return new Date(str + 'T00:00:00'); }
    function daysBetween(a,b) {
        const ms = parseDate(b).getTime() - parseDate(a).getTime();
        return Math.floor(ms / (1000*60*60*24));
    }
    function getWeekIndexForDate(dStr) {
        if (!startWindowDateStr) return 0;
        const diff = daysBetween(startWindowDateStr, dStr);
        return Math.floor(diff / 7);
    }
    function getWeekRangeLabel(weekIdx) {
        if (!startWindowDateStr) return '';
        const startD = parseDate(startWindowDateStr);
        const wStart = new Date(startD.getTime());
        wStart.setDate(wStart.getDate() + (weekIdx*7));

        const wEnd = new Date(wStart.getTime());
        wEnd.setDate(wEnd.getDate()+6);

        const opts = { month:'short', day:'numeric', timeZone:'Asia/Kathmandu' };
        const startLabel = wStart.toLocaleDateString('en-US', opts);
        const endLabel   = wEnd.toLocaleDateString('en-US', opts);

        return startLabel + ' – ' + endLabel;
    }

    function ensureWeekCollapsedInit() {
        // init all 6 weeks to expanded (false) unless already stored
        for (let i=0;i<6;i++){
            if (typeof weekCollapsed[i] === 'undefined') weekCollapsed[i] = false;
            if (typeof weekLockedCollapsed[i] === 'undefined') weekLockedCollapsed[i] = false;
        }
    }

    // One-way collapse: once collapsed, stays collapsed (button disabled)
    window.collapseWeek = (wk) => {
        if (weekLockedCollapsed[wk]) return;      // already collapsed & locked
        weekCollapsed[wk] = true;
        weekLockedCollapsed[wk] = true;
        renderTable();
        saveLocal();
    };

    /* ===== RENDER ===== */
    function renderBadges(list, cls) {
        return `<div class="badges">${
            list.map(n=>`<span class="badge ${cls}">${n}</span>`).join('')
        }</div>`;
    }

    function renderTable() {
        const tbody = document.querySelector('#scheduleTable tbody');
        if (!tbody) return;
        tbody.innerHTML = '';

        const filter = document.getElementById('filterSelect').value;
        const allOps = getAllOpsNames();

        // sort by date ascending
        scheduleData.sort((a,b) => a.date.localeCompare(b.date));

        // group rows per week index
        const grouped = {};
        scheduleData.forEach((row, idx) => {
            const w = getWeekIndexForDate(row.date);
            if (!grouped[w]) grouped[w] = [];
            grouped[w].push({ row, idx });
        });

        // loop weeks in order
        const weekKeys = Object.keys(grouped).map(k=>parseInt(k,10)).sort((a,b)=>a-b);

        weekKeys.forEach(weekIdx => {
            const weekRows = grouped[weekIdx];

            // header row
            const headerTr = document.createElement('tr');
            headerTr.classList.add('week-header-row');
            const isLocked = !!weekLockedCollapsed[weekIdx];
            headerTr.innerHTML = `
                <td colspan="6">
                    <div class="week-header-main">
                        <div class="week-header-left">
                            <div class="week-label">Week ${weekIdx+1}</div>
                            <div class="week-range">${getWeekRangeLabel(weekIdx)}</div>
                        </div>
                        <div>
                            <button
                                class="btn-mini week-toggle-btn"
                                ${isLocked ? 'disabled' : ''}
                                onclick="collapseWeek(${weekIdx})"
                                title="${isLocked ? 'Collapsed (locked)' : 'Collapse this week'}"
                            >
                                ${isLocked ? 'Collapsed' : 'Collapse'}
                            </button>
                        </div>
                    </div>
                </td>
            `;
            tbody.appendChild(headerTr);

            // if collapsed, skip detail rows
            if (weekCollapsed[weekIdx]) return;

            weekRows.forEach(({row, idx}) => {

                // filter logic (per row)
                const offList = [
                    ...Object.keys(row.ruleOffs||{}),
                    ...row.manualOffs,
                    ...row.opsOff
                ];

                const hasCovers = Object.keys(row.covers||{}).length>0;

                let visible = true;
                if (filter === 'sat') {
                    visible = row.isSaturday;
                } else if (filter === 'holiday') {
                    visible = row.isHoliday;
                } else if (filter === 'off') {
                    visible = (offList.length>0 || hasCovers);
                } else {
                    visible = true;
                }
                if (!visible) return;

                const onList = row.isSaturday
                    ? row.opsAssigned
                    : allOps.filter(n => !offList.includes(n));

                const fixedDeptHtml = row.isSaturday
                    ? `<div class="small-note">All departments: Off</div>`
                    : `
                        <div class="small-note">Production: Aayusha Gurung (10:00-18:00)</div>
                        <div class="small-note">Reception: Receptionist (10:00-18:00)</div>
                        <div class="small-note">Office Helper: Didi (10:00-18:00)</div>
                    `;

                const coversHtml = Object.entries(row.covers||{}).length
                    ? `<div style="margin-top:6px;">${
                        Object.entries(row.covers).map(([off,c]) =>
                            `<span class="badge badge-cover">${off} → ${c.name} (${c.time})</span>`
                        ).join(' ')
                    }</div>`
                    : '';

                const onHtml  = renderBadges(onList, 'badge-on');
                const offHtml = offList.length ? renderBadges(offList, 'badge-off') : '';

                const markOffSelect = !row.isSaturday ? `
                    <select onchange="markOff(${idx}, this.value); this.value='';">
                        <option value="">Mark Off (10-18)...</option>
                        ${
                            allOps.map(opName => `
                                <option value="${opName}" ${offList.includes(opName)?'disabled':''}>
                                    ${opName}
                                </option>
                            `).join('')
                        }
                    </select>
                ` : '';

                const dualToggle = row.isSaturday ? `
                    <label>
                        <input type="checkbox"
                            ${row.twoNeeded ? 'checked' : ''}
                            onchange="toggleDual(${idx})">
                        Dual Saturday
                    </label>
                ` : '';

                const reassignBtn = Object.keys(row.covers||{}).length>0
                    ? `<button class="btn-mini" onclick="reassignCovers(${idx})">Reassign Covers</button>`
                    : '';

                const tr = document.createElement('tr');
                tr.classList.add(`week-${weekIdx}-row`);
                if (row.isSaturday) tr.classList.add('saturday');
                if (row.isHoliday)  tr.classList.add('holiday');
                if (row.date === todayStr) tr.classList.add('today-row');

                tr.innerHTML = `
                    <td class="data-row-cell">${row.date}</td>
                    <td class="data-row-cell">${row.dayName}${row.isSaturday?' (Sat)':''}${row.isHoliday?' (Holiday)':''}</td>
                    <td class="data-row-cell">
                        <div><strong>On:</strong> ${onHtml}</div>
                        ${
                            offList.length
                            ? `<div style="margin-top:6px;"><strong>Off:</strong> ${offHtml}</div>`
                            : ''
                        }
                        ${coversHtml}
                    </td>
                    <td class="data-row-cell">${fixedDeptHtml}</td>
                    <td class="data-row-cell small-note">
                        <div class="remark-text">${row.remarks.join(' | ')}</div>
                    </td>
                    <td class="data-row-cell">
                        <div class="action-col">
                            ${markOffSelect}
                            ${dualToggle}
                            ${reassignBtn}
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });
    }

    /* ===== TABLE ACTIONS ===== */
    window.markOff = (idx, who) => {
        if (!who) return;
        const row = scheduleData[idx];

        const allAlreadyOff = [
            ...Object.keys(row.ruleOffs||{}),
            ...row.manualOffs,
            ...row.opsOff
        ];
        if (allAlreadyOff.includes(who)) {
            showAlert(who+' is already off '+row.date, 'warning');
            return;
        }

        row.manualOffs.push(who);
        row.remarks.push('Ad-hoc leave');

        reconcileCoversForRow(idx);
        persistOverride(row);
        pushHistory('markOff '+who+' '+row.date);
        renderTable();
        autoSave();
    };

    window.toggleDual = (idx) => {
        const row = scheduleData[idx];
        row.twoNeeded = !row.twoNeeded;

        clearPreleaveMarksAround(idx);
        applyRulesAndCovers();
        persistOverride(row);

        pushHistory('toggleDual '+(row.twoNeeded?'ON':'OFF')+' '+row.date);
        renderTable();
        saveLocal();
        autoSave();
    };

    window.reassignCovers = (idx) => {
        const row = scheduleData[idx];

        Object.keys(row.covers).forEach(offPerson => {
            const coverName = row.covers[offPerson].name;
            const staff = coverPool.find(c=>c.name===coverName);
            if (staff) {
                staff.coversThisPeriod = Math.max(0,(staff.coversThisPeriod||0)-1);
            }
        });

        row.remarks = row.remarks.filter(r => !r.startsWith('Cover:'));
        row.covers  = {};

        reconcileCoversForRow(idx);
        persistOverride(row);

        pushHistory('reassign covers '+row.date);
        renderTable();
        saveLocal();
        autoSave();
        showAlert('Covers reassigned for '+row.date, 'success');
    };

    /* ===== HISTORY / UNDO ===== */
    function pushHistory(action) {
        historyStack = historyStack.slice(0, currentStateIndex+1);

        historyStack.push(JSON.stringify({
            scheduleData: scheduleData.map(r=>({...r})),
            holidays: [...holidays],
            overrides: {...overrides},
            dualCounter,
            startWindowDateStr,
            weekCollapsed: {...weekCollapsed},
            weekLockedCollapsed: {...weekLockedCollapsed},
            action,
            timestamp: new Date().toISOString()
        }));
        currentStateIndex = historyStack.length-1;

        if (historyStack.length>60) {
            historyStack.shift();
            currentStateIndex--;
        }

        renderHistoryList();
    }

    window.undoAction = () => {
        if (currentStateIndex <= 0) return;
        currentStateIndex--;

        const snap = JSON.parse(historyStack[currentStateIndex]);
        scheduleData        = snap.scheduleData.map(r=>({...r}));
        holidays            = [...snap.holidays];
        overrides           = {...snap.overrides};
        dualCounter         = snap.dualCounter;
        startWindowDateStr  = snap.startWindowDateStr;
        weekCollapsed       = snap.weekCollapsed || {};
        weekLockedCollapsed = snap.weekLockedCollapsed || {};

        renderTable();
        renderHistoryList();
        saveLocal();
        autoSave();
    };

    function renderHistoryList() {
        const box  = document.getElementById('history');
        const list = document.getElementById('historyList');
        if (!list) return;

        list.innerHTML = historyStack
            .slice(0, currentStateIndex+1)
            .map(raw => {
                const s = JSON.parse(raw);
                return `<div style="border-bottom:1px dashed #e5e7eb;padding:4px 0;">
                    ${s.timestamp}: ${s.action}
                </div>`;
            })
            .reverse()
            .join('');

        box.style.display = historyStack.length ? 'block' : 'none';
    }

    /* ===== AUTO SAVE TO DB ===== */
    async function autoSave() {
        if (!scheduleData.length) return;

        const payloadRows = scheduleData.map(r => ({
            duty_date:      r.date,
            day_name:       r.dayName,
            is_holiday:     !!r.isHoliday,
            remarks:        r.remarks,
            operations_on:  r.opsAssigned,
            operations_off: r.opsOff,
            covers:         r.covers
        }));

        try {
            const res = await fetch("<?php echo e(route('duty_schedule.saveMonth')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ schedule: payloadRows })
            });

            const json = await res.json().catch(()=>null);

            if (json && (json.success || json.ok)) {
                showAlert('Auto-saved to DB ✅','success');
            } else {
                showAlert('Auto-save failed on server.','error');
            }
        } catch (err) {
            showAlert('Network / server error while auto-saving.','error');
        }
    }

    /* ===== DELETE ENTIRE SCHEDULE ===== */
    window.deleteEntireSchedule = async () => {
        const ok = confirm('पूरै ६ हप्ताको schedule (DB का सबै DutySchedule rows) मेटिन्छ। पक्का हुनुहुन्छ?');
        if (!ok) return;

        try {
            const res = await fetch("<?php echo e(route('duty_schedule.deleteAll')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({})
            });

            const json = await res.json().catch(()=>null);

            if (json && json.ok) {
                showAlert(json.message || 'Entire schedule deleted.', 'success');

                // Clear local state + UI
                scheduleData = [];
                weekCollapsed = {};
                weekLockedCollapsed = {};
                overrides = {};
                dualCounter = 0;
                renderTable();
                pushHistory('delete entire schedule');
                saveLocal();
            } else {
                showAlert('Delete failed on server.', 'error');
            }
        } catch (e) {
            showAlert('Network / server error while deleting.', 'error');
        }
    };

    /* ===== COVER POOL UI RENDER ===== */
    function renderCoverQueue() {
        const elig = coverPool.filter(c =>
            !c.unavailable &&
            (c.tags||[]).includes('Ops-capable')
        );
        const queue = elig.length
            ? elig.map(c=>c.name).join(' → ')
            : 'No eligible covers available';

        document.getElementById('coverQueue').textContent =
            'Next Cover Queue: ' + queue;
    }

    function renderCoverChips() {
        const wrap = document.getElementById('coverChips');
        wrap.innerHTML = '';
        coverPool.forEach((c, idx) => {
            const div = document.createElement('span');
            div.className = 'badge badge-cover';
            div.innerHTML = `
                ${c.name}${c.unavailable?' (Unavailable)':''}
                ${(c.tags&&c.tags.length)?'['+c.tags.join(', ')+']':''}
                <span style="cursor:pointer;font-weight:900;margin-left:6px;"
                      onclick="removeCover(${idx})">&times;</span>
            `;
            wrap.appendChild(div);
        });
    }

    function renderCoverUI() {
        renderCoverQueue();
        renderCoverChips();
    }

    window.addCover = () => {
        const name = document.getElementById('coverName').value.trim();
        const rawTags = document.getElementById('coverTags').value.trim();
        const cap = parseInt(document.getElementById('coverCap').value) || 5;
        const unavailable = document.getElementById('coverUnavailable').checked;

        if (!name) { showAlert('Cover name required','error'); return; }
        if (coverPool.some(c => c.name.toLowerCase() === name.toLowerCase())) {
            showAlert('Duplicate cover staff','error'); return;
        }

        const tags = rawTags ? rawTags.split(',').map(t=>t.trim()).filter(Boolean) : [];

        coverPool.push({
            name,
            dutyTime:'10:00-18:00',
            tags,
            cap,
            unavailable,
            coversThisPeriod:0
        });

        document.getElementById('coverName').value = '';
        document.getElementById('coverTags').value = '';
        document.getElementById('coverCap').value = '5';
        document.getElementById('coverUnavailable').checked = false;

        renderCoverUI();
        saveLocal();

        if (scheduleData.length) {
            applyRulesAndCovers();
            renderTable();
            autoSave();
        }
    };

    window.removeCover = (idx) => {
        coverPool.splice(idx,1);
        renderCoverUI();
        saveLocal();

        if (scheduleData.length) {
            applyRulesAndCovers();
            renderTable();
            autoSave();
        }
    };

    window.clearCovers = () => {
        const ok = confirm('Clear all cover staff?');
        if (!ok) return;

        coverPool = [];
        renderCoverUI();
        saveLocal();

        if (scheduleData.length) {
            applyRulesAndCovers();
            renderTable();
            autoSave();
        }
    };

    /* ===== OPS UI FILL ===== */
    function hydrateOpsUI() {
        document.getElementById('staffAInput').value = staffInputs.A || '';
        document.getElementById('staffBInput').value = staffInputs.B || '';
        document.getElementById('staffCInput').value = staffInputs.C || '';

        const names = getAllOpsNames();
        function fillSelect(selId, val) {
            const sel = document.getElementById(selId);
            sel.innerHTML = names.map(n=>`<option value="${n}">${n}</option>`).join('');
            if (val && names.includes(val)) {
                sel.value = val;
            } else if (names.length){
                sel.value = names[0];
            }
        }

        fillSelect('rotFirst',  rotationStart[0] || names[0] || '');
        fillSelect('rotSecond', rotationStart[1] || names[1] || names[0] || '');
        fillSelect('rotThird',  rotationStart[2] || names[2] || names[0] || '');
    }

    document.getElementById('applyOpsBtn').addEventListener('click', () => {
        staffInputs.A = document.getElementById('staffAInput').value.trim() || staffInputs.A;
        staffInputs.B = document.getElementById('staffBInput').value.trim() || staffInputs.B;
        staffInputs.C = document.getElementById('staffCInput').value.trim() || staffInputs.C;

        const f = document.getElementById('rotFirst').value;
        const s = document.getElementById('rotSecond').value;
        const t = document.getElementById('rotThird').value;
        rotationStart = [f,s,t].filter(Boolean);

        saveLocal();
        showAlert('Operations setup applied ✔','success');
    });

    document.getElementById('build6Btn').addEventListener('click', () => {
        build6WeekSchedule();
    });

    document.getElementById('filterSelect').addEventListener('change', () => {
        renderTable();
    });

    // saturday duty भएका मान्छेलाई reload बेला पनि Friday off देखाउन
function restoreFridayOffsFromSaturdays() {
    // scheduleData पहिले नै date अनुसार sort भइसकेको हुन्छ
    for (let i = 0; i < scheduleData.length; i++) {
        const row = scheduleData[i];
        if (!row.isSaturday) continue;

        // यस Saturday मा काम गर्नेहरू
        const satWorkers = Array.isArray(row.opsAssigned) ? row.opsAssigned : [];
        if (satWorkers.length === 0) continue;

        // अघिल्लो Friday खोज्ने
        let friIdx = i - 1;
        while (friIdx >= 0 && !scheduleData[friIdx].isFriday) {
            friIdx--;
        }
        if (friIdx < 0) continue;

        const friRow = scheduleData[friIdx];

        // यो केस तपाईंले भन्नुभएको - single Saturday
        if (satWorkers.length === 1) {
            const worker = satWorkers[0];

            // पहिले नै off नभए मात्र राख्ने
            if (!friRow.opsOff.includes(worker)) {
                friRow.opsOff.push(worker);
            }

            // remark पनि राख्ने (duplicate नहोस्)
            if (!friRow.remarks.includes('Fri off for Sat single')) {
                friRow.remarks.push('Fri off for Sat single');
            }
        }

        // dual saturday को case यहाँ चाहियो भने छुट्टै logic राख्न मिल्छ
    }
}

    /* ===== INIT PAGE ===== */
    (function initPage() {
        loadLocal();
        hydrateOpsUI();
        renderCoverUI();

        if (Array.isArray(serverSchedule) && serverSchedule.length > 0) {
            // sort earliest first
            serverSchedule.sort((a,b) => (a.duty_date > b.duty_date ? 1 : -1));

            // set startWindowDateStr from serverWindowStart first, else earliest row
            if (serverWindowStart) {
                startWindowDateStr = serverWindowStart;
            } else {
                startWindowDateStr = serverSchedule[0].duty_date;
            }

            // set datepicker default to startWindowDateStr
            const startInput = document.getElementById('startDateInput');
            startInput.value = startWindowDateStr;

            scheduleData = serverSchedule.map(r => {
                const remarksArr = Array.isArray(r.remarks)
                    ? r.remarks
                    : (typeof r.remarks === 'string'
                        ? r.remarks.split(' | ').filter(Boolean)
                        : []);

                const opsOn  = parseMaybeJSON(r.operations_on,  []);
                const opsOff = parseMaybeJSON(r.operations_off, []);
                const covers = parseMaybeJSON(r.covers,         {});

                const weekdayName = r.day_name ?? '';
                const isSat = (weekdayName === 'Saturday');

                return {
                    date: r.duty_date,
                    dayName: weekdayName,
                    isSaturday: isSat,
                    isThursday: (weekdayName === 'Thursday'),
                    isFriday:   (weekdayName === 'Friday'),
                    isHoliday:  !!r.is_holiday,
                    remarks: remarksArr,

                    satTriple: [],
                    twoNeeded: (opsOn && opsOn.length>1) ? true : false,
                    dualChoice: undefined,
                    opsAssigned: opsOn || [],
                    opsOff:      opsOff || [],
                    ruleOffs: {},
                    manualOffs: [],
                    covers: covers || {}
                };
            });

            ensureWeekCollapsedInit();
            restoreFridayOffsFromSaturdays();
            renderTable();
            pushHistory('loaded from DB existing data');
        } else {
            const todayISO = formatDateTZ(new Date());
startWindowDateStr = todayISO;
const startInput = document.getElementById('startDateInput');
startInput.value = todayISO;


            ensureWeekCollapsedInit();
            scheduleData = [];
            renderTable();
        }
    })();

    function parseMaybeJSON(val, fallback){
        if (val == null) return fallback;
        if (Array.isArray(val) || typeof val === 'object') return val;
        if (typeof val === 'string') {
            try { return JSON.parse(val); }
            catch(_){ return fallback; }
        }
        return fallback;
    }

});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/DutySchedule/index.blade.php ENDPATH**/ ?>