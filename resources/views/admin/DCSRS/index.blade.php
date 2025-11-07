@extends('admin.layout.layout')

@section('title', 'Daily Card Spend Record Sheet')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />

@section('content')
<style>
/* General Styling */
body {
    font-family: 'Roboto', Arial, sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

h2 {
    color: #2c3e50;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

/* Container Styling */
.container {
    max-width: 100%;
    margin: 3px auto;
    padding: 10px;
    background: #ffffff;
    border-radius: 0px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    flex: 1; /* Ensures the content stretches to keep the footer at the bottom */
}

/* Card Totals Row */
.card-totals-row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
    justify-content: center;
}

.card-box {
    flex: 1;
    min-width: 150px;
    padding: 20px;
    background: linear-gradient(135deg, #6a89cc, #b8e994);
    color: #ffffff;
    text-align: center;
    border-radius: 12px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.card-box h5 {
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.card-box p {
    font-size: 22px;
    font-weight: bold;
    margin: 0;
}

.card-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Form Styling */
form {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

form label {
    font-weight: 600;
    color: #2c3e50;
}

form input, form select, form button {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #dcdde1;
    font-size: 14px;
    box-sizing: border-box;
}

form input:focus, form select:focus {
    outline: none;
    border-color: #6a89cc;
    box-shadow: 0 0 5px rgba(106, 137, 204, 0.5);
}

form button {
    background: #6a89cc;
    color: #ffffff;
    border: none;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}

form button:hover {
    background: #4a69bd;
}

/* Table Styling */
table {
    width: 100%;
    margin-bottom: 20px;
    background: #ffffff;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #f4f7fa;
    font-size: 14px;
    color: #2c3e50;
}

table thead {
    background: #6a89cc;
    color: #ffffff;
}

table tbody tr:hover {
    background: #f9f9f9;
}

/* Buttons Styling */
.btn {
    padding: 10px 10px;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
    display: inline-block;
    transition: background 0.3s ease;
}

.btn-primary {
    background-color: #6a89cc;
    color: #ffffff;
    border: none;
}

.btn-primary:hover {
    background-color: #4a69bd;
}

.btn-secondary {
    background-color: #dfe6e9;
    color: #2c3e50;
}

.btn-secondary:hover {
    background-color: #b2bec3;
}

/* Responsive Styling */
@media (max-width: 768px) {
    .card-totals-row {
        flex-direction: column;
    }

    table th, table td {
        font-size: 12px;
    }
}

/* Footer Styling */
footer {
    text-align: center;
    padding: 10px 0;
    background: #6a89cc;
    color: #ffffff;
    position: relative;
    bottom: 0;
    width: 100%;
}
</style>

<div class="container">
    <h2>Daily Card Spend Record Sheet</h2>
    
    <!-- Card Totals Row -->
    <div class="card-totals-row">
        @foreach ($cardTotals as $cardName => $total)
            <a href="{{ route('daily-card-spends.view', $cardName) }}" style="text-decoration: none;">
                <div class="card-box">
                    <h5>{{ $cardName }}</h5>
                    <p>${{ number_format($total, 2) }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Form to Add New Record -->
    <form action="{{ route('daily-card-spends.store') }}" method="POST">
    @csrf
    <div class="form-row" style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">
        <!-- Card Name -->
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label for="card_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Card Name</label>
            <select name="card_name" id="card_name" class="form-control js-example-basic-single" required>
                <option value="">Select Card</option>
                @foreach($cards as $card)
                    <option value="{{ $card->name }}">{{ $card->name }} - ${{ $card->USD }}</option>
                @endforeach
            </select>
        </div>

        <!-- Ad Account -->
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label for="ad_account" style="display: block; margin-bottom: 5px; font-weight: bold;">Ad Account</label>
            <select name="ad_account" id="ad_account" class="form-control js-example-basic-single" required>
                <option value="">Select Ad Account</option>
                @foreach($groupedAdAccounts as $groupName => $accounts)
                    <optgroup label="{{ $groupName }}">
                        @foreach($accounts as $account)
                            <option value="{{ $account->ad_account_name }}">{{ $account->ad_account_name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <!-- Date -->
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label for="date" style="display: block; margin-bottom: 5px; font-weight: bold;">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ now()->toDateString() }}" required>
        </div>

        <!-- Amount -->
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label for="amount_usd" style="display: block; margin-bottom: 5px; font-weight: bold;">Amount (USD)</label>
            <input type="number" step="0.01" name="amount_usd" id="amount_usd" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label for="description" style="display: block; margin-bottom: 5px; font-weight: bold;">Description</label>
            <input type="text" name="description" id="description" class="form-control">
        </div>

        <!-- Submit Button -->
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Add Record</button>
        </div>
    </div>
</form>


    <!-- Display Records -->
    <table>
        <thead>
            <tr>
                <th>Card Name</th>
                <th>Ad Account</th>
                <th>Date</th>
                <th>Amount (USD)</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr id="record-{{ $record->id }}">
                    <td>{{ $record->card_name }}</td>
                    <td>{{ $record->ad_account }}</td>
                    <td>{{ $record->date }}</td>
                    <td><input type="number" step="0.01" id="amount-{{ $record->id }}" value="{{ $record->amount_usd }}" class="form-control"></td>
                    <td><input type="text" id="description-{{ $record->id }}" value="{{ $record->description }}" class="form-control"></td>
                    <td>
                        <button class="btn btn-primary btn-sm update-record" data-id="{{ $record->id }}">Update</button>
                        <button class="btn btn-danger btn-sm delete-button" data-id="{{ $record->id }}">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle Update Button Click
        document.querySelectorAll('.update-record').forEach(button => {
            button.addEventListener('click', function () {
                const recordId = this.getAttribute('data-id');
                const amount = document.querySelector(`#amount-${recordId}`).value;
                const description = document.querySelector(`#description-${recordId}`).value;

                // Perform AJAX request for update
                fetch(`{{ url('daily-card-spends') }}/${recordId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        amount_usd: amount,
                        description: description,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        Swal.fire('Updated!', data.message, 'success');
                    } else {
                        Swal.fire('Error!', 'Unable to update the record.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                });
            });
        });

        // Handle Delete Button Click
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const recordId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform AJAX request for delete
                        fetch(`{{ url('daily-card-spends') }}/${recordId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                Swal.fire('Deleted!', data.message, 'success');
                                // Remove the record row from the table
                                document.querySelector(`#record-${recordId}`).remove();
                            } else {
                                Swal.fire('Error!', 'Unable to delete the record.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        });
                    }
                });
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const updateTotals = () => {
        const cardTotals = {};

        document.querySelectorAll('tbody tr').forEach(row => {
            const cardName = row.querySelector('td:first-child').innerText.trim();
            const amount = parseFloat(row.querySelector('td:nth-child(4) input').value) || 0;

            cardTotals[cardName] = (cardTotals[cardName] || 0) + amount;
        });

        // Update the card totals
        document.querySelectorAll('.card-box').forEach(box => {
            const cardName = box.querySelector('h5').innerText.trim();
            const total = cardTotals[cardName] || 0;

            box.querySelector('p').innerText = `$${total.toFixed(2)}`;
        });
    };

    document.querySelectorAll('.update-record, .delete-button').forEach(button => {
        button.addEventListener('click', updateTotals);
    });

    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', updateTotals);
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('card-records-modal');
    const modalCardName = document.getElementById('modal-card-name');
    const modalRecordsBody = document.getElementById('modal-records-body');
    const filterOptions = document.getElementById('filter-options');
    const downloadPdfButton = document.getElementById('download-pdf');

    document.querySelectorAll('.card-box').forEach(box => {
        box.addEventListener('click', function () {
            const cardName = this.getAttribute('data-card');
            modalCardName.textContent = cardName;
            loadRecords(cardName);
            modal.style.display = 'block';
        });
    });

    document.getElementById('close-modal').addEventListener('click', function () {
        modal.style.display = 'none';
    });

    filterOptions.addEventListener('change', function () {
        const cardName = modalCardName.textContent;
        loadRecords(cardName, this.value);
    });

    downloadPdfButton.addEventListener('click', function () {
        const cardName = modalCardName.textContent;
        const filter = filterOptions.value;

        window.location.href = `/daily-card-spends/download/${cardName}?filter=${filter}`;
    });

    function loadRecords(cardName, filter = 'all') {
    fetch(`/daily-card-spends/${cardName}?filter=${filter}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            modalRecordsBody.innerHTML = '';
            if (data.records.length === 0) {
                modalRecordsBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center">No records found</td>
                    </tr>
                `;
                return;
            }
            data.records.forEach(record => {
                modalRecordsBody.innerHTML += `
                    <tr>
                        <td>${record.date}</td>
                        <td>$${record.amount_usd.toFixed(2)}</td>
                        <td>${record.description || ''}</td>
                    </tr>
                `;
            });
        })
}
});

</script>
@endsection
