@extends('admin.layout.layout')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container">
    <h2>Create Other Exp</h2>

    <form method="post" action="{{ route('exp.store') }}">
        @csrf
        <div class="form-group">
            <label for="date">Date</label>
            <input class="form-control" type="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="title">Title:</label>
            <input class="form-control" type="text" name="title" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input class="form-control" type="number" step="0.01" name="amount" required>
        </div>
        <div class="form-group">
            <label for="note">Note:</label>
            <textarea class="form-control" name="note" id="note" cols="30" rows="10"></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Save Exp</button>
    </form>
</div>
@endsection