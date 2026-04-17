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
            <h1>SMMX Deliverables</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Monthly Deliverables</h3>
                    <a href="{{ route('admin.smmx.deliverables.create') }}" class="btn btn-primary btn-sm">Add New</a>
                </div>

                <div class="card-body table-responsive tbl-cards">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Month</th>
                                <th>Posts</th>
                                <th>Graphics</th>
                                <th>Reels</th>
                                <th>Stories</th>
                                <th>Spend</th>
                                <th>Completion</th>
                                <th>Status</th>
                                <th width="220">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $item->customer->name ?? '-' }}</td>
                                    <td>{{ $item->report_month }}/{{ $item->report_year }}</td>
                                    <td>{{ $item->posts_completed }}/{{ $item->posts_planned }}</td>
                                    <td>{{ $item->graphics_completed }}/{{ $item->graphics_planned }}</td>
                                    <td>{{ $item->reels_completed }}/{{ $item->reels_planned }}</td>
                                    <td>{{ $item->stories_completed }}/{{ $item->stories_planned }}</td>
                                    <td>{{ $item->ad_spend_used ?? 0 }}/{{ $item->ad_spend_planned ?? 0 }}</td>
                                    <td>{{ $item->completion_rate }}%</td>
                                    <td>{{ ucfirst($item->status) }}</td>
                                    <td>
                                        <a href="{{ route('admin.smmx.deliverables.show', $item->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('admin.smmx.deliverables.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('admin.smmx.deliverables.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this deliverable?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center">No deliverables found.</td></tr>
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