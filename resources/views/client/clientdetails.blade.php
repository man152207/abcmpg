<!-- /home/mpgcomnp/app.mpg.com.np/resources/views/client/clientdetails.blade.php -->
@extends('admin.layout.layout')

@section('title', 'Client Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Client Details for: {{ $clients->first()->name }}</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>USD</th>
                                <th>Rate</th>
                                <th>NRP</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->account }}</td>
                                    <td>{{ $client->USD }}</td>
                                    <td>{{ $client->Rate }}</td>
                                    <td>{{ $client->NRP }}</td>
                                    <td>{{ $client->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f2f2f2; font-weight: bold;">
                                <td><strong>Total</strong></td>
                                <td><strong>{{ $totalUSD }}</strong></td>
                                <td><strong>{{ $totalRate }}</strong></td>
                                <td><strong>{{ $totalNRP }}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
