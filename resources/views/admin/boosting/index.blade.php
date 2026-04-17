@extends('admin.layout.layout')
@section('title', 'Boosting Queue')

@section('content')
<div class="container-fluid mt-3">
    <h4>📋 Boosting Task Queue</h4>

    {{-- Quick Add Form --}}
    <form action="{{ route('boosting.store') }}" method="POST" class="mb-3" id="boostingForm">
        @csrf
        <div class="form-row form-row-flex tight">
            <div class="fg-md">
                <input type="text" id="customer_phone" name="customer_phone" class="form-control" placeholder="Customer Phone" required autocomplete="off">
                <div id="phoneSuggestions" class="list-group position-absolute suggestions-dropdown"></div>
            </div>
            <div class="fg-md">
                <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Customer Name" required>
            </div>
            <div class="fg-sm">
                <select name="priority" class="form-control">
                    <option value="Normal" selected>Normal</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>
            <div class="fg-md">
                <input type="datetime-local" name="eta_time" class="form-control" placeholder="ETA (optional)">
            </div>
            <div class="fg-lg">
                <input type="text" name="remarks" class="form-control" placeholder="Remarks (optional)">
            </div>
            <div class="fg-auto">
                <button type="submit" class="btn btn-success">+ Add Task</button>
            </div>
        </div>
    </form>

    {{-- Task Table --}}
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Requested</th>
                <th>ETA</th>
                <th>Dispatcher</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->customer_name }}</td>
                <td><a href="https://wa.me/+977{{ $t->customer_phone }}" target="_blank">{{ $t->customer_phone }}</a></td>
                <td>{{ $t->requested_time ? \Carbon\Carbon::parse($t->requested_time)->format('M d H:i') : '' }}</td>
                <td>{{ $t->eta_time ? \Carbon\Carbon::parse($t->eta_time)->format('M d H:i') : '' }}</td>
                <td>{{ $t->dispatcher?->name ?? '-' }}</td>
                <td>{{ $t->assignedUser?->name ?? '-' }}</td>
                <td><span class="badge {{ $t->status=='Pending'?'badge-warning': ($t->status=='In Progress'?'badge-info':'badge-success') }}">{{ $t->status }}</span></td>
                <td><span class="badge {{ $t->priority=='Urgent'?'badge-danger':'badge-secondary' }}">{{ $t->priority }}</span></td>
                <td>{{ $t->remarks ?? '-' }}</td>
                <td>
                    @if($t->status=='Pending')
                    <form action="{{ route('boosting.assign',$t->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-info">Claim</button>
                    </form>
                    @endif
                    @if($t->status=='In Progress')
                    <form action="{{ route('boosting.complete',$t->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Done</button>
                    </form>
                    @endif
                    <form action="{{ route('boosting.destroy',$t->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')">Del</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $tasks->links() }}
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#customer_phone').on('input', function(){
        let query = $(this).val();
        if(query.length >= 3){
            $.ajax({
                url: "{{ route('search_user') }}",
                method: "GET",
                data: { search: query },
                success: function(data){
                    let list = $('#phoneSuggestions');
                    list.empty().show();
                    if(data.length > 0){
                        data.forEach(function(item){
                            list.append('<a href="#" class="list-group-item list-group-item-action phone-select" data-phone="'+item.phone+'" data-name="'+item.name+'">'+item.phone+' - '+item.name+'</a>');
                        });
                    } else {
                        list.append('<span class="list-group-item">No match found</span>');
                    }
                }
            });
        } else {
            $('#phoneSuggestions').hide();
        }
    });

    $(document).on('click','.phone-select',function(e){
        e.preventDefault();
        let phone = $(this).data('phone');
        let name = $(this).data('name');
        $('#customer_phone').val(phone);
        $('#customer_name').val(name);
        $('#phoneSuggestions').hide();
    });
});
</script>
@endsection