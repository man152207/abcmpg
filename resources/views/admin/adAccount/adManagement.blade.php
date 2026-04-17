@extends('admin.layout.layout')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush

@section('content')

<!-- New Target Form -->
<form method="POST" action="/ad-management/adaccount/store" class="card p-3 mb-3">
    @csrf
    <h5 class="mb-3">New Target</h5>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <select name="account_name" class="js-example-basic-multiple form-control adm-select" required>
            <option value="">Select Ad Account</option>
            @foreach($adAccountOptions as $option)
                <option value="{{ $option->Ad_Account_Display }}">{{ $option->Ad_Account_Display }}</option>
            @endforeach
        </select>
        <input type="date" name="active_since" class="form-control adm-w-160" value="{{ date('Y-m-d') }}">
        <input type="date" name="threshold_reached_date" class="form-control adm-w-180" placeholder="Threshold Reached Date" oninput="calculateRemainingDays()">
        <input type="number" name="initial_remaining_days" class="form-control adm-w-150" placeholder="Remaining Days" required readonly>
        <input type="number" name="account_threshold" class="form-control adm-w-160" placeholder="Account Threshold" step="0.01" oninput="updateBudget()">
        <input type="number" name="running_ads_balance" class="form-control adm-w-170" placeholder="Running Ads Balance" step="0.01" oninput="updateBudget()">
        <input type="number" name="targeted_budget" class="form-control adm-w-150" placeholder="Targeted Budget" step="0.01" readonly>
        <button type="submit" class="btn btn-success">Add</button>
    </div>
</form>

<!-- Ad Account Table -->
<div class="card mb-3">
    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-dark">
                <tr>
                    <th><input type="checkbox" id="select-all" onclick="toggleAll(this)"></th>
                    <th>S.N.</th>
                    <th>Ad Account</th>
                    <th>Active Since</th>
                    <th>Threshold Reached Date</th>
                    <th>Remaining Days</th>
                    <th>Account Threshold</th>
                    <th>Running Ads Balance</th>
                    <th>Targeted Budget</th>
                    <th>USD</th>
                    <th>New Applied History</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="adAccountsTableBody">
            @foreach($adAccountsWithUSD as $index => $adAccount)
                <tr id="row-adaccount-{{ $adAccount->id }}">
                    <td><input type="checkbox" class="select-checkbox" data-id="{{ $adAccount->id }}"></td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $adAccount->account_name }}</td>
                    <td contenteditable="false" class="editable date-field" data-field="active_since" data-id="{{ $adAccount->id }}">{{ $adAccount->active_since }}</td>
                    <td contenteditable="false" class="editable date-field" data-field="threshold_reached_date" data-id="{{ $adAccount->id }}">{{ $adAccount->threshold_reached_date ?? 'N/A' }}</td>
                    <td class="readonly" data-field="initial_remaining_days" data-id="{{ $adAccount->id }}">{{ $adAccount->remaining_days }}</td>
                    <td contenteditable="false" class="editable" data-field="account_threshold" data-id="{{ $adAccount->id }}">{{ $adAccount->account_threshold }}</td>
                    <td contenteditable="false" class="editable" data-field="running_ads_balance" data-id="{{ $adAccount->id }}">{{ $adAccount->running_ads_balance_updated }}</td>
                    <td class="readonly" data-field="targeted_budget" data-id="{{ $adAccount->id }}">{{ $adAccount->targeted_budget_updated }}</td>
                    <td>{{ isset($adAccount->usd_value) ? number_format($adAccount->usd_value, 2) : '0.00' }}</td>
                    <td>{{ $adAccount->new_applied_history ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" onclick="editRow({{ $adAccount->id }})">Edit</button>
                        <button class="btn btn-success btn-sm save-btn d-none" onclick="saveRow({{ $adAccount->id }})">Save</button>
                        <form method="POST" action="/ad-management/adaccount/{{ $adAccount->id }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Group Management -->
<div class="row mb-3">
    <div class="col-md-5">
        <div class="card p-3">
            <h6>Create New Group</h6>
            <form method="POST" action="{{ route('adaccount.group.store') }}" class="d-flex gap-2">
                @csrf
                <input type="text" name="group_name" class="form-control" placeholder="Group name" required>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card p-3">
            <h6>Add Ad Account to Group</h6>
            <form method="POST" action="{{ route('adaccount.grouped.store') }}" class="d-flex gap-2">
                @csrf
                <select id="group_name" name="group_name" class="form-control" required>
                    <option value="" disabled>Select Group</option>
                    @foreach($allGroups as $group)
                        <option value="{{ $group }}">{{ $group }}</option>
                    @endforeach
                </select>
                <input id="ad_account_name" type="text" name="ad_account_name" class="form-control" placeholder="Ad Account name" required>
                <button type="submit" class="btn btn-success">Add</button>
            </form>
        </div>
    </div>
</div>

@php
    $rowLimit = 15;
    $counter = 0;
    $tableCounter = 0;
@endphp

<div class="row">
    @foreach($groupedAdAccounts as $groupName => $accounts)
        @foreach($accounts as $account)
            @if ($counter % $rowLimit == 0)
                @php $tableCounter++; @endphp
                @if ($tableCounter > 1 && ($tableCounter - 1) % 3 == 0)
                    </div><div class="row">
                @endif
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body p-0">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Group / Ad Account</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
            @endif

            @if ($counter % $rowLimit == 0)
                <tr class="table-active">
                    <td colspan="2"><strong>{{ $groupName }}</strong></td>
                </tr>
            @endif
            <tr>
                <td>{{ $account->ad_account_name }}</td>
                <td>
                    <form method="POST" action="{{ route('adaccount.grouped.delete', $account->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>

            @php $counter++; @endphp

            @if ($counter % $rowLimit == 0 || ($loop->last && $loop->parent->last))
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endforeach
</div>

<div class="mt-2">
    {{ $adAccountsWithUSD->links() }}
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    // Select2 for Ad Account dropdown
    $('.js-example-basic-multiple').select2({
        placeholder: "Select Ad Account",
        allowClear: true
    });

    // Restore saved group selection
    const savedGroup = localStorage.getItem('selectedGroup');
    if (savedGroup) {
        $('#group_name').val(savedGroup);
    }
    $('#group_name').on('change', function () {
        localStorage.setItem('selectedGroup', this.value);
    });

    // Clipboard paste into Ad Account name field
    $('#ad_account_name').on('click', async function () {
        try {
            const text = await navigator.clipboard.readText();
            if (text) {
                $(this).val(text.split(/\s+/).slice(0, 3).join(' '));
            }
        } catch (e) {
            console.warn("Clipboard read not permitted:", e);
        }
    });
});

function updateBudget() {
    const threshold = parseFloat($('input[name="account_threshold"]').val()) || 0;
    const balance   = parseFloat($('input[name="running_ads_balance"]').val()) || 0;
    const budget    = threshold - balance;
    $('input[name="targeted_budget"]').val(budget >= 0 ? budget.toFixed(2) : 0);
}

function calculateRemainingDays() {
    const thresholdDate = new Date($('input[name="threshold_reached_date"]').val() || new Date());
    const today         = new Date();
    const remaining     = Math.max(0, Math.ceil((thresholdDate - today) / (1000 * 60 * 60 * 24)));
    $('input[name="initial_remaining_days"]').val(remaining);
}

function editRow(id) {
    const row    = document.querySelector(`#row-adaccount-${id}`);
    const fields = row.querySelectorAll(".editable");
    fields.forEach(field => {
        if (field.classList.contains("date-field")) {
            $(field).datepicker({ dateFormat: "yy-mm-dd" });
        }
        field.contentEditable = "true";
    });
    row.querySelector(".edit-btn").classList.add("d-none");
    row.querySelector(".save-btn").classList.remove("d-none");
}

function saveRow(id) {
    const row    = document.querySelector(`#row-adaccount-${id}`);
    const fields = row.querySelectorAll(".editable");
    const data   = {};

    fields.forEach(field => {
        data[field.getAttribute("data-field")] = field.innerText.trim();
    });

    if (data['active_since'] && data['threshold_reached_date']) {
        const thresholdDate = new Date(data['threshold_reached_date']);
        data['initial_remaining_days'] = Math.max(0, Math.ceil((thresholdDate - new Date()) / (1000 * 60 * 60 * 24)));
    }
    if (data['account_threshold'] && data['running_ads_balance']) {
        data['targeted_budget'] = (parseFloat(data['account_threshold']) - parseFloat(data['running_ads_balance'])).toFixed(2);
    }

    fetch(`/ad-management/adaccount/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(result => {
        if (result.success) {
            row.querySelector('[data-field="initial_remaining_days"]').innerText = data['initial_remaining_days'] || '';
            row.querySelector('[data-field="targeted_budget"]').innerText        = data['targeted_budget'] || '';
        } else {
            alert('Update failed!');
        }
    })
    .catch(() => alert('Error updating row.'));

    fields.forEach(field => { field.contentEditable = "false"; });
    row.querySelector(".edit-btn").classList.remove("d-none");
    row.querySelector(".save-btn").classList.add("d-none");
}

function toggleAll(source) {
    document.querySelectorAll('.select-checkbox').forEach(cb => { cb.checked = source.checked; });
}
</script>
@endpush
