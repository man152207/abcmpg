@extends('admin.layout.layout')

@section('content')
<!-- Bootstrap CSS for styling and responsiveness -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>
    /* Custom Styles for a Modern Look */
    body {
        background-color: #f4f4f4;
        font-family: 'Arial', sans-serif;
    }
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        border-radius: 5px; 
    }
    .card-header2 {
        background-color: #0d3569;
        color: white;
        font-size: 20px;
        padding: 10px 15px;
    }
    .card-body2 {
        padding: 15px;
    }
    .btn-primary, .btn-success {
        margin-bottom: 10px;
    }
    .table-responsive {
        margin-top: 20px;
    }
    .table thead th {
        background-color: #0d3569;
        color: white;
    }
    @media screen and (max-width: 768px) {
        .form-custom-style .col-md-2, .form-custom-style .col-md-3, .form-custom-style .col-md-1 {
            width: 100%;
            max-width: none;
        }
        .form-control {
            width: 100%;
        }
        .card-header2 h3 {
            font-size: 18px;
        }
    }
    .form-custom-style .form-group {
        margin-bottom: 15px;
    }
    .form-custom-style .form-label {
        color: #fff;
    }
    .form-custom-style .form-control {
        background-color: #f8f9fa;
        color: #495057;
    }
    .btn-primary {
        color: #fff;
        background-color: #646564;
        border-color: #0d3569;
    }
    .btn-primary:hover {
        background-color: #0b2e5a;
        border-color: #0b2e5a;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header2">
            <h2>Create Invoice</h2>
        </div>
        <div class="card-body2">
            <form method="post" action="{{ route('invoice.store') }}" class="form-custom-style">
                @csrf
                <div class="row">
                    <!-- Customer Select -->
                    <div class="col-md-3 form-group">
                        <label for="customer">Customer:</label>
                        <select class="form-control" id="customer" name="customer" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{$customer->phone}}">{{$customer->phone}}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Input -->
                    <div class="col-md-3 form-group">
                        <label for="date">Date</label>
                        <input class="form-control" type="date" name="date" required>
                    </div>
                </div>

                <!-- Invoice Items Table -->
                <div class="table-responsive">
                    <table class="table">
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
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-success" type="button" onclick="addRow()">Add Item</button>

                <!-- Description Textarea -->
                <div class="form-group">
                    <label for="Description">Description:</label>
                    <textarea class="form-control" name="description" id="description" cols="30" rows="4" required></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Save Invoice</button>
            </form>
        </div>
    </div>
</div>

<script>
    var items = @json($items);
    function addRow() {
        var newRow = '<tr>' +
            '<td><select class="form-control" name="items[]">' +
            '<option value="">Select Item</option>';
        items.forEach(function(item) {
            newRow += '<option value="' + item.id + '">' + item.name + '</option>';
        });
        newRow += '</select></td>' +
            '<td><input class="form-control" type="number" name="quantities[]"></td>' +
            '<td><input class="form-control" type="number" step="0.01" name="rate[]"></td>' +
            '<td><input class="form-control" type="number" step="0.01" name="tax[]"></td>' +
            '<td><input class="form-control" type="number" name="amount[]"></td>' +
            '<td><button class="btn btn-danger" type="button" onclick="removeRow(this)">Remove</button></td>' +
            '</tr>';
        document.getElementById('invoice_items').insertAdjacentHTML('beforeend', newRow);
    }

    function removeRow(button) {
        button.closest('tr').remove();
    }
</script>

@endsection

@section('js_')
<!-- Include Select2 CSS -->
<link href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css')}}" rel="stylesheet" />

<!-- Include jQuery -->
<script src="{{asset('https://code.jquery.com/jquery-3.6.4.min.js')}}"></script>

<!-- Include Select2 JS -->
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $('#customer').select2({
            placeholder: 'Select Customer',
            allowClear: true,
            data: @json($customers)
        });
    });
</script>
@endsection
