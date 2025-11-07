<!-- resources/views/update.blade.php -->

@extends('admin.layout.layout')

@section('content')
<div class="container">
    <h2>Update Invoice</h2>

    <form method="post" action="{{ URL('/admin/dashboard/invoice/update/' . $invoice->id) }}">
        @csrf
        @method('POST') <!-- Use the appropriate HTTP method for updates -->

        <div class="form-group">
            <label for="customer">Customer:</label>
            <select class="form-control" id="customer" name="customer" required>
                <option value="">Select Customer</option>
                <!-- Add options dynamically from your database -->
                <!-- Example: -->
                @foreach($customers as $customer)
                <option value="{{ $customer->phone }}" {{ $invoice->customer == $customer->phone ? 'selected' : '' }}>{{ $customer->phone }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input class="form-control" type="date" name="date" value="{{ $invoice->date }}" required>
        </div>

        <table style=" width:100%">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Tax</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="invoice_items">
                <!-- Rows for dynamically added items will go here -->
                @foreach($invoice_items as $item)
                <tr>
                    <td>
                        <select class="form-control" name="items[]">
                            <option value="">Select Item</option>
                            @foreach($items as $itemOption)
                            <option value="{{ $itemOption->id }}" {{ $item->Item_id == $itemOption->id ? 'selected' : '' }}>{{ $itemOption->name }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td><input class="form-control" type="number" name="quantities[]" value="{{ $item->quantity }}"></td>
                    <td><input class="form-control" type="number" step="0.01" name="rate[]" value="{{ $item->rate }}"></td>
                    <td><input class="form-control" type="number" step="0.01" name="tax[]" value="{{ $item->tax }}"></td>
                    <td><input class="form-control" type="number" step="0.01" name="amount[]" value="{{ $item->amount }}"></td>
                    <td><button class="form-control" type="button" onclick="removeRow(this)">Remove</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button class="btn btn-success" type="button" onclick="addRow()">Add Item</button>

        <div class="form-group">
            <label for="Description">Description:</label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10" required>{{$invoice->description}}</textarea>
        </div>
        <button class="btn btn-primary" type="submit">Update Invoice</button>
    </form>
</div>

<script>
    // Assuming you have a variable $items containing the list of items from the backend
    var items = @json($items);

    function addRow() {
        // Create a new row with select option for items
        var newRow = '<tr>' +
            '<td><select class="form-control" name="items[]">' +
            '<option value="">Select Item</option>';

        // Add options for each item
        items.forEach(function(item) {
            newRow += '<option value="' + item.id + '">' + item.name + '</option>';
        });

        newRow += '</select></td>' +
            '<td><input class="form-control" type="number" name="quantities[]"></td>' +
            '<td><input class="form-control" type="number" step="0.01" name="rate[]"></td>' +
            '<td><input class="form-control" type="number" step="0.01" name="tax[]"></td>' +
            '<td><input class="form-control" type="number" name="amount[]"></td>' +
            '<td><button type="button" onclick="removeRow(this)">Remove</button></td>' +
            '</tr>';

        // Append the new row to the table
        document.getElementById('invoice_items').insertAdjacentHTML('beforeend', newRow);
    }

    function removeRow(button) {
        // Remove the row when the "Remove" button is clicked
        button.closest('tr').remove();
    }
</script>
@endsection
@section('js_')
<!-- Include Select2 CSS -->
<link href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css')}}" rel="stylesheet" />

<!-- Include jQuery (required for Select2) -->
<script src="{{asset('https://code.jquery.com/jquery-3.6.4.min.js')}}"></script>

<!-- Include Select2 JS -->
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#customer').select2({
            placeholder: 'Select Customer',
            allowClear: true,
            data: @json($customers),
            // minimumInputLength: 1 // Minimum characters to start a search
        });
    });
</script>

@endsection