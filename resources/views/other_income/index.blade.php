@extends('admin.layout.layout')

@section('content')
<style>
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .form-control, .btnn {
        border-radius: 0;
        margin-bottom: 10px;
    }

    .btnn {
        background-color: #093b7b;
        color: white;
    }

    .btnn:hover {
        background-color: #646564;
    }

    .alert-info {
        background-color: #646564;
        color: white;
        border: none;
    }

    .alert-success {
        background-color: #646564;
        color: white;
        border: none;
    }

    .filters {
        display: flex;
        justify-content: right;
        margin-bottom: 0px;
        width: 100%;
    }

    .filters .form-control {
        width: 220px;
        margin-right: 15px;
    }

    .filters .form-control:last-child {
        margin-right: 0;
    }
        
    .tfund {
        width: 20%;
        padding-top: 5px;
        font-size: 20px;
    }
</style>
<div class="container-fluid">
    <div class="alert alert-info d-flex align-items-right" style="background-color: #646564; color: white;">
        <div class="tfund"><strong>Total Other Income: </strong> Rs {{ number_format($totalOtherIncome, 2) }}</div>
        <div class="tfund ms-3"><strong>Total Opening Balance: </strong> Rs {{ number_format($totalOpeningBalance, 2) }}</div>
        <div class="filters d-flex ms-auto">
            <input type="text" id="searchBox" class="form-control me-2" placeholder="Search...">
            <input type="date" id="startDateFilter" class="form-control me-2">
            <input type="date" id="endDateFilter" class="form-control me-2">
            <select id="periodFilter" class="form-control">
                <option value="">Select Period</option>
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="this_week">This Week</option>
                <option value="this_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="last_3_months">Last 3 Months</option>
            </select>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered table-responsive-md w-100" style="font-family: 'Arial', sans-serif;">
        <thead style="background-color: #093b7b; color: white;">
            <tr>
                <th>Date</th>
                <th>Contact Number</th>
                <th>Customer Name</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Income Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="otherIncomeTable">
            <tr>
                <form id="addOtherIncomeForm">
                    @csrf
                    <td><input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required></td>
                    <td><input type="text" name="contact_number" class="form-control" list="customers" required></td>
                    <td><input type="text" name="customer_name" class="form-control" list="customers" required></td>
                    <td><input type="text" name="amount" class="form-control" required></td>
                    <td><input type="text" name="remarks" class="form-control"></td>
                    <td>
                        <select name="income_type" class="form-control" required>
                            <option value="Opening Balance">Opening Balance</option>
                            <option value="Other Income">Other Income</option>
                        </select>
                    </td>
                    <td><button type="submit" class="btnn btnn-success">Add Income</button></td>
                </form>
            </tr>
            @foreach($other_incomes as $income)
                <tr id="row-{{ $income->id }}">
                    <td>{{ $income->date }}</td>
                    <td>{{ $income->contact_number }}</td>
                    <td>{{ $income->customer_name }}</td>
                    <td>{{ $income->amount }}</td>
                    <td>{{ $income->remarks }}</td>
                    <td>{{ $income->income_type }}</td>
                    <td>
                        <button class="btnn btnn-primary edit-btnn" onclick="editRow({{ $income->id }})">Edit</button>
                        <button class="btnn btnn-danger" onclick="deleteRow({{ $income->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="previous-months">
        <h5>Previous Months:</h5>
        @foreach($previousMonths as $month)
            <button class="btnn btnn-primary" onclick="loadMonthData('{{ $month->year }}', '{{ $month->month }}')">
                {{ \Carbon\Carbon::create($month->year, $month->month)->format('F Y') }}
            </button>
        @endforeach
    </div>
</div>

<datalist id="customers">
    @foreach($customers as $customer)
        <option value="{{ $customer->contact_number }}">{{ $customer->name }}</option>
    @endforeach
</datalist>

<script>
    document.getElementById('addOtherIncomeForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch('/other_income', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                return response.json().then(data => {
                    alert(data.error || 'Error adding income');
                });
            }
        }).catch(error => {
            alert('Error adding income');
        });
    });

    function editRow(id) {
        let row = document.getElementById('row-' + id);
        
        let date = row.querySelector('td:nth-child(1)').innerText.trim();
        let contactNumber = row.querySelector('td:nth-child(2)').innerText.trim();
        let customerName = row.querySelector('td:nth-child(3)').innerText.trim();
        let amount = row.querySelector('td:nth-child(4)').innerText.trim();
        let remarks = row.querySelector('td:nth-child(5)').innerText.trim();
        let incomeType = row.querySelector('td:nth-child(6)').innerText.trim();

        row.innerHTML = `
            <td><input type="date" class="form-control" value="${date}" required></td>
            <td><input type="text" class="form-control" value="${contactNumber}" list="customers" required></td>
            <td><input type="text" class="form-control" value="${customerName}" required></td>
            <td><input type="text" class="form-control" value="${amount}" required></td>
            <td><input type="text" class="form-control" value="${remarks}"></td>
            <td>
                <select class="form-control" required>
                    <option value="Opening Balance" ${incomeType === 'Opening Balance' ? 'selected' : ''}>Opening Balance</option>
                    <option value="Other Income" ${incomeType === 'Other Income' ? 'selected' : ''}>Other Income</option>
                </select>
            </td>
            <td>
                <button class="btnn btnn-success" onclick="saveRow(${id})">Save</button>
                <button class="btnn btnn-secondary" onclick="cancelEdit(${id}, '${date}', '${contactNumber}', '${customerName}', '${amount}', '${remarks}', '${incomeType}')">Cancel</button>
            </td>
        `;
    }

    function cancelEdit(id, date, contactNumber, customerName, amount, remarks, incomeType) {
        let row = document.getElementById('row-' + id);
        row.innerHTML = `
            <td>${date}</td>
            <td>${contactNumber}</td>
            <td>${customerName}</td>
            <td>${amount}</td>
            <td>${remarks}</td>
            <td>${incomeType}</td>
            <td>
                <button class="btnn btnn-primary edit-btnn" onclick="editRow(${id})">Edit</button>
                <button class="btnn btnn-danger" onclick="deleteRow(${id})">Delete</button>
            </td>
        `;
    }

    function saveRow(id) {
        let row = document.getElementById('row-' + id);
        let formData = new FormData();
        
        formData.append('date', row.querySelector('td:nth-child(1) input').value);
        formData.append('contact_number', row.querySelector('td:nth-child(2) input').value);
        formData.append('customer_name', row.querySelector('td:nth-child(3) input').value);
        formData.append('amount', row.querySelector('td:nth-child(4) input').value);
        formData.append('remarks', row.querySelector('td:nth-child(5) input').value);
        formData.append('income_type', row.querySelector('td:nth-child(6) select').value);
        formData.append('_method', 'PUT');

        fetch(`/other_income/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                return response.json().then(data => {
                    alert(data.error || 'Error updating income');
                });
            }
        }).catch(error => {
            alert('Error updating income');
        });
    }

    function deleteRow(id) {
        if (!confirm('Are you sure you want to delete this income entry?')) {
            return;
        }

        fetch(`/other_income/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                document.getElementById('row-' + id).remove();
            } else {
                return response.json().then(data => {
                    alert(data.error || 'Error deleting income');
                });
            }
        }).catch(error => {
            alert('Error deleting income');
        });
    }

    function loadMonthData(year, month) {
        fetch(`/other_income/month/${year}-${month}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(data => {
            let tableBody = document.getElementById('otherIncomeTable');
            tableBody.innerHTML = `
                <tr>
                    <form id="addOtherIncomeForm">
                        @csrf
                        <td><input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required></td>
                        <td><input type="text" name="contact_number" class="form-control" list="customers" required></td>
                        <td><input type="text" name="customer_name" class="form-control" list="customers" required></td>
                        <td><input type="text" name="amount" class="form-control" required></td>
                        <td><input type="text" name="remarks" class="form-control"></td>
                        <td>
                            <select name="income_type" class="form-control" required>
                                <option value="Opening Balance">Opening Balance</option>
                                <option value="Other Income">Other Income</option>
                            </select>
                        </td>
                        <td><button type="submit" class="btnn btnn-success">Add Income</button></td>
                    </form>
                </tr>
            `;
            data.other_incomes.forEach(income => {
                tableBody.innerHTML += `
                    <tr id="row-${income.id}">
                        <td>${income.date}</td>
                        <td>${income.contact_number}</td>
                        <td>${income.customer_name}</td>
                        <td>${income.amount}</td>
                        <td>${income.remarks}</td>
                        <td>${income.income_type}</td>
                        <td>
                            <button class="btnn btnn-primary edit-btnn" onclick="editRow(${income.id})">Edit</button>
                            <button class="btnn btnn-danger" onclick="deleteRow(${income.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        }).catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('Error loading data for the selected month');
        });
    }
</script>
@endsection
