@extends('admin.layout.layout')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/smmx/css/smmx.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/smmx/js/smmx.js') }}"></script>
<script>
    // Optional: enhance tooltips, etc.
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush


    <section class="content-header">
        <div class="container-fluid">
            <h1>SMMX Onboarding</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @include('admin.smmx.partials.filters')

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Onboarding List</h3>
                    <a href="{{ route('admin.smmx.onboarding.create') }}" class="btn btn-primary btn-sm">Add New</a>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Business</th>
                                <th>Goal</th>
                                <th>Status</th>
                                <th width="220">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->customer->name ?? '-' }}</td>
                                    <td>{{ $item->business_name }}</td>
                                    <td>{{ $item->primary_goal ?? '-' }}</td>
                                    <td>{{ ucfirst($item->status) }}</td>
                                    <td>
                                        <a href="{{ route('admin.smmx.onboarding.show', $item->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('admin.smmx.onboarding.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('admin.smmx.onboarding.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this onboarding?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No onboarding records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection