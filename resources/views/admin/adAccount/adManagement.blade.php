@extends('admin.layout.layout')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />

<style>
/* General Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}
h2 { color: #333; margin-bottom: 20px; }

/* Form Styling */
form {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
}
form input, form select, form button {
    flex: 1;
    min-width: 150px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
form button {
    background: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}
form button:hover { background: #45a049; }

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}
table th, table td { padding: 10px; border: 1px solid #ddd; }
table thead tr { background: #4CAF50; color: #fff; }
table tbody tr:nth-child(even) { background: #f9f9f9; }
table tbody tr:hover { background: #f1f1f1; }
.editable { cursor: pointer; }
.readonly { background: #f4f4f9; }
</style>

<!-- New Target Form -->
<form method="POST" action="/ad-management/adaccount/store">
    @csrf
    <h3>New Target</h3>
    <select name="account_name" class="js-example-basic-multiple" required>
        <option value="">Select Ad Account</option>
        @foreach($adAccountOptions as $option)
            <option value="{{ $option->Ad_Account_Display }}">{{ $option->Ad_Account_Display }}</option>
        @endforeach
    </select>
    <input type="date" name="active_since" value="{{ date('Y-m-d') }}">
    <input type="date" name="threshold_reached_date" placeholder="Threshold Reached Date" oninput="calculateRemainingDays()">
    <input type="number" name="initial_remaining_days" placeholder="Remaining Days" required readonly>
    <input type="number" name="account_threshold" placeholder="Account Threshold" step="0.01" oninput="updateBudget()">
    <input type="number" name="running_ads_balance" placeholder="Running Ads Balance" step="0.01" oninput="updateBudget()">
    <input type="number" name="targeted_budget" placeholder="Targeted Budget" step="0.01" readonly>
    <button type="submit">Add</button>
</form>

<!-- Ad Account Table -->
<table>
    <thead>
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
    <button class="btn btn-success btn-sm save-btn" onclick="saveRow({{ $adAccount->id }})" style="display:none;">Save</button>
    <form method="POST" action="/ad-management/adaccount/{{ $adAccount->id }}" style="display:inline;background: none;border: none;padding: 0px;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    </form>
</td>

        </tr>
    @endforeach
    </tbody>
</table>

<div style="display: flex; gap: 20px; align-items: right;">
    <form method="POST" action="{{ route('adaccount.group.store') }}" style="display: inline-block;width: 50%;"">
        @csrf
        <input type="text" name="group_name" placeholder="Create New Group" required>
        <button type="submit">Create Group</button>
    </form>
<form method="POST" action="{{ route('adaccount.grouped.store') }}" style="display: inline-block; width: 100%;">
    @csrf
    <select id="group_name" name="group_name" required>
        <option value="" disabled>Select Group</option>
        @foreach($allGroups as $group)
            <option value="{{ $group }}">{{ $group }}</option>
        @endforeach
    </select>
    <input id="ad_account_name" type="text" name="ad_account_name" placeholder="Add Ad Account to Group" required>
    <button type="submit">Add to Group</button>
</form>


</div>

    @php
        $rowLimit = 15; // Maximum rows per table
        $counter = 0; // Tracks total rows processed
        $tableCounter = 0; // Tracks number of tables created
    @endphp

    <div class="row">
        @foreach($groupedAdAccounts as $groupName => $accounts)
            @foreach($accounts as $account)
                @if ($counter % $rowLimit == 0)
                    @php $tableCounter++; @endphp
                    @if ($tableCounter > 1 && ($tableCounter - 1) % 3 == 0)
                        </div><div class="row"> <!-- Close the row and start a new one after every 3 tables -->
                    @endif
                    <div class="col-md-4"> <!-- Each table takes one-third of the row -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Group and Ad Account</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif

                @if ($counter % $rowLimit == 0 || $counter % $rowLimit < $rowLimit)
                    @if ($counter % $rowLimit == 0)
                        <tr>
                            <td colspan="3"><strong>{{ $groupName }}</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <td>{{ $account->ad_account_name }}</td>
                        <td>
                            <form method="POST" action="{{ route('adaccount.grouped.delete', $account->id) }}" style="display:inline;background: none;border: none;padding: 0px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endif

                @php $counter++; @endphp

                @if ($counter % $rowLimit == 0 || ($loop->last && $loop->parent->last))
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
        @endforeach
    </div>



<div>
    {{ $adAccountsWithUSD->links() }}
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputField = document.getElementById('ad_account_name');

        inputField.addEventListener('click', async function () {
            try {
                // Read text from clipboard
                const text = await navigator.clipboard.readText();
                if (text) {
                    // Extract the first 3 words
                    const words = text.split(/\s+/).slice(0, 3).join(' ');
                    inputField.value = words; // Set the value to the first 3 words
                }
            } catch (error) {
                console.error("Unable to access clipboard", error);
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Restore saved value for group_name on page load
        const savedGroup = localStorage.getItem('selectedGroup');
        
        if (savedGroup) {
            const selectElement = document.getElementById('group_name');
            selectElement.value = savedGroup; // Set the saved value
        }

        // Save selected value when the dropdown changes
        document.getElementById('group_name').addEventListener('change', function () {
            localStorage.setItem('selectedGroup', this.value); // Save the selected group
        });
    });
</script>
<script>
function updateBudget() {
    const threshold = parseFloat(document.querySelector('input[name="account_threshold"]').value) || 0;
    const balance = parseFloat(document.querySelector('input[name="running_ads_balance"]').value) || 0;
    const budget = threshold - balance;

    document.querySelector('input[name="targeted_budget"]').value = budget >= 0 ? budget.toFixed(2) : 0;
}

function calculateRemainingDays() {
    const activeSince = new Date(document.querySelector('input[name="active_since"]').value);
    const thresholdDate = new Date(document.querySelector('input[name="threshold_reached_date"]').value || new Date());
    const today = new Date();
    const remainingDays = Math.max(0, Math.ceil((thresholdDate - today) / (1000 * 60 * 60 * 24)));

    document.querySelector('input[name="initial_remaining_days"]').value = remainingDays;
}

function editRow(id) {
    const row = document.querySelector(`#row-adaccount-${id}`);
    const fields = row.querySelectorAll(".editable");

    // Enable fields for editing
    fields.forEach(field => {
        if (field.classList.contains("date-field")) {
            $(field).datepicker({ dateFormat: "yy-mm-dd" });
        }
        field.contentEditable = "true";
    });

    // Toggle buttons
    row.querySelector(".edit-btn").style.display = "none";
    row.querySelector(".save-btn").style.display = "inline-block";
}

function saveRow(id) {
    const row = document.querySelector(`#row-adaccount-${id}`);
    const editableFields = row.querySelectorAll(".editable");
    const data = {};

    editableFields.forEach(field => {
        const fieldName = field.getAttribute("data-field");
        const value = field.innerText.trim();
        data[fieldName] = value;
    });

    // Calculate Remaining Days
    if (data['active_since'] && data['threshold_reached_date']) {
        const activeSince = new Date(data['active_since']);
        const thresholdDate = new Date(data['threshold_reached_date']);
        const today = new Date();
        data['initial_remaining_days'] = Math.max(0, Math.ceil((thresholdDate - today) / (1000 * 60 * 60 * 24)));
    }

    // Calculate Targeted Budget
    if (data['account_threshold'] && data['running_ads_balance']) {
        const threshold = parseFloat(data['account_threshold']) || 0;
        const balance = parseFloat(data['running_ads_balance']) || 0;
        data['targeted_budget'] = (threshold - balance).toFixed(2);
    }

    // Send AJAX request to update row
    fetch(`/ad-management/adaccount/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Update successful!');

            // Update the row visually with the new values
            row.querySelector('[data-field="initial_remaining_days"]').innerText = data['initial_remaining_days'] || '';
            row.querySelector('[data-field="targeted_budget"]').innerText = data['targeted_budget'] || '';
        } else {
            alert('Update failed!');
        }
    })
    .catch(error => alert('Error updating row.'));

    // Disable fields after saving
    editableFields.forEach(field => {
        field.contentEditable = "false";
    });

    // Toggle buttons
    row.querySelector(".edit-btn").style.display = "inline-block";
    row.querySelector(".save-btn").style.display = "none";
}

$(document).ready(function() {
    $('.js-example-basic-multiple').select2({
        placeholder: "Select Ad Account",
        allowClear: true
    });
});
</script>
@endsection
