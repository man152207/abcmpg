@extends('admin.layout.layout')

@section('title', '2FA Auth Code Logs | MPG Solution')

@section('content')
    <div class="container-fluid mpg-layout">
        <div class="row mpg-layout">
            <div class="col-md-12 mpg-layout">
                <div class="card mpg-layout">
                    <div class="card-header mpg-layout">
                        <h3 class="card-title mpg-layout">Logs for Auth Code: {{ $authCode->account_name }}</h3>
                        <div class="card-tools mpg-layout">
                            <a href="{{ route('admin.2fa.index') }}" class="btn btn-info btn-sm mpg-layout">Back to Auth Codes</a>
                        </div>
                    </div>
                    <div class="card-body mpg-layout">
                        <table class="table table-bordered table-hover mpg-layout">
                            <thead>
                                <tr class="mpg-layout">
                                    <th>Admin</th>
                                    <th>Device</th>
                                    <th>Location</th>
                                    <th>Generated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr class="mpg-layout">
                                        <td>{{ $log->admin->name ?? 'N/A' }}</td>
                                        <td>{{ $log->device ?? 'N/A' }}</td>
                                        <td>{{ $log->location ?? 'N/A' }}</td>
                                        <td>{{ $log->generated_at ? $log->generated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr class="mpg-layout">
                                        <td colspan="4" class="text-center">No logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card-footer mpg-layout">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
