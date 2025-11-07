<!-- resources/views/admin/customer/add.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>ADD Debit</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/admin/dashboard/debit/add') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <select class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" required>
                            <option value="" disabled selected>Select Card Number</option>
                            @foreach($cards as $card)
                            <option value="{{ $card->card_number }}" {{ old('card_number') == $card->card_number ? 'selected' : '' }}>{{ $card->card_number }}</option>
                            @endforeach
                        </select>
                        @error('card_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="USD" class="form-label">Debited BY:</label>
                        <input type="number" step="0.01" class="form-control @error('USD') is-invalid @enderror" id="USD" name="USD" value="{{ old('USD') }}" required>
                        @error('USD')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Debit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection