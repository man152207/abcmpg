<!-- resources/views/admin/customer/update.blade.php -->
<?php

use App\Models\Card;

$cards = Card::select('*')->get();
?>
@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Update Client</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/admin/dashboard/client/edit/'. $client->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $client->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="USD">USD:</label>
                        <input type="number" step="0.01" value="{{$client->USD}}" class="form-control" id="USD" name="USD" required>
                    </div>

                    <div class="form-group">
                        <label for="Rate">Rate:</label>
                        <input type="number" step="0.01" value="{{$client->Rate}}" class="form-control" id="Rate" name="Rate" required>
                    </div>

                    <div class="form-group">
                        <label for="NRP">NRP:</label>
                        <input type="number" step="0.01" value="{{$client->NRP}}" class="form-control" id="NRP" name="NRP" required>
                    </div>

                    <div class="form-group" style="color: black;">
                        <label for="Ad_Account">Account:</label>
                        <select class="form-control" id="Ad_Account" name="account" required>
                            @foreach($cards as $card)
                            <option value="{{$card->card_number}}" {{ @$client->account == $card->card_number ? 'selected' : '' }}>
                                {{$card->card_number}}
                            </option>
                            @endforeach
                        </select>
                        @error('account')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update Client</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var usdInput = document.getElementById('USD');
        var rateInput = document.getElementById('Rate');
        var nrpInput = document.getElementById('NRP');

        usdInput.addEventListener('input', calculateNRP);
        rateInput.addEventListener('input', calculateNRP);

        function calculateNRP() {
            var usd = parseFloat(usdInput.value) || 0;
            var rate = parseFloat(rateInput.value) || 0;
            var nrp = usd * rate;
            nrpInput.value = nrp.toFixed(2);
        }
    });
</script>
@endsection