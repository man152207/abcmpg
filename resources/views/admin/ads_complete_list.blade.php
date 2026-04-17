@extends('admin.layout.layout')
@section('title', 'Old Records | MPG Solution')
@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<div class="container-fluid">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Previous Month Records</h3>
            <div class="dropdown" onclick="toggleDropdown()">
                <button class="dropdown-btn">Option</button>
                <div class="dropdown-menu" id="myDropdown">
                    <a href="{{ route('ads.yesterday_complete') }}" class="dropdown-item">Yesterday</a>
                    <a href="{{ route('ads.this_day_complete') }}" class="dropdown-item">Today</a>
                    <a href="{{ route('ads.this_week_complete') }}" class="dropdown-item">This Week</a>
                    <a href="{{ route('ads.this_month_complete') }}" class="dropdown-item">This Month</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('search_complete_ad') }}" method="get" class="search-form">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="start_date">Customer Contact</label>
                        <input type="number" name="customer" placeholder="Search by customer contact number" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <div><br></div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search fa-fw"></i> Search
                        </button>
                    </div>
                </div>
            </form>
            <table class="table-responsive tbl-cards">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>USD</th>
                        <th>Rate</th>
                        <th>NRP</th>
                        <th>Ad Account</th>
                        <th>Payment Method</th>
                        <th>Duration</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Baki</th>
                        <th>Ad Nature/Page</th>
                        <th>Admin</th>
                        <th>Created AT</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ads as $ad)
                    @if($ad->is_complete>0)
                    <tr>
                        <td>{{ $ad->id }}</td>
                        <td>{{ $ad->customer }}</td>
                        <td>{{ $ad->USD }}</td>
                        <td>{{ $ad->Rate }}</td>
                        <td>{{ $ad->NRP }}</td>
                        <td>{{ $ad->Ad_Account }}</td>
                        <td>{{ $ad->Payment }}</td>
                        <td>{{ $ad->Duration }}</td>
                        <td>{{ $ad->Quantity }}</td>
                        <td>{{ $ad->Status }}</td>
                        <td>{{ $ad->advance}}</td>
                        <td>{{ $ad->Ad_Nature_Page }}</td>
                        <td>{{ $ad->admin }}</td>
                        <td>{{ $ad->created_at}}</td>
                        <td>
                            <a href="{{ url('/admin/dashboard/ads/edit/'.$ad->id)}}"><i class="fa fa-edit"></i></a>
                            <a href="{{ route('ads.destroy', ['id' => $ad->id]) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $ad->id }}').submit();"><i class="fa fa-trash"></i></a>
                            <form id="delete-form-{{ $ad->id }}" action="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('POST')
                            </form>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td>{{ $ad->id }}</td>
                        <td>{{ $ad->customer }}</td>
                        <td>{{ $ad->USD }}</td>
                        <td>{{ $ad->Rate }}</td>
                        <td>{{ $ad->NRP }}</td>
                        <td>{{ $ad->Ad_Account }}</td>
                        <td>{{ $ad->Payment }}</td>
                        <td>{{ $ad->start_date }}</td>
                        <td>{{ $ad->end_date }}</td>
                        <td>{{ $ad->Quantity }}</td>
                        <td>{{ $ad->Status }}</td>
                        <td>{{ $ad->advance}}</td>
                        <td>{{ $ad->Ad_Nature_Page }}</td>
                        <td>{{ $ad->admin }}</td>
                        <td>
                            <a href="{{ url('/admin/dashboard/ads/edit/'.$ad->id)}}"><i class="fa fa-edit"></i></a>
                            <form id="delete-form-{{ $ad->id }}" action="{{ URL('/admin/dashboard/ads/delete/' . $ad->id) }}" method="POST">
                                <button style="background-color: transparent; border-color: transparent;" type="submit" onclick="return confirm('Are you sure you want to delete this invoice?')"><i class="fa fa-trash"></i></button>
                                @csrf
                                @method('POST')
                            </form>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $ads->appends(request()->query())->links('pagination::bootstrap-5')}}
</div>

<script>
    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-btn')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    }

</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection
