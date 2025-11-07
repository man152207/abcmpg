@extends('admin.layout.layout')

@section('content')
<div class="container">
    <h2>Create Other Exp</h2>

    <form method="post" action="{{ url('/admin/dashboard/exp/edit/'. $exp->id) }}">
        @csrf
        <div class="form-group">
            <label for="date">Date</label>
            <input class="form-control" value="{{$exp->date}}" type="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="customer">Title:</label>
            <input class="form-control" type="text" value="{{$exp->title}}" name="title" required>
        </div>
        <div class="form-group">
            <label for="customer">Amount:</label>
            <input class="form-control" type="number" value="{{$exp->amount}}" step="0.01" name="amount" required>
        </div>
        <div class="form-group">
            <label for="note">Note:</label>
            <textarea class="form-control" name="note" id="note" cols="30" rows="10">{{$exp->note}}</textarea>
        </div>
        <button class="btn btn-primary" type="submit">update Exp</button>
    </form>
</div>
@endsection