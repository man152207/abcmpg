@extends('admin.layout.layout')

@section('title', 'Daily Card Spend Record Sheet')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />

@section('content')
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
