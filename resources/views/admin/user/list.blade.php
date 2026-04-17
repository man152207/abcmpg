@extends('admin.layout.layout')

@section('content')
<div class="container-fluid py-2">

  {{-- Page Header --}}
  <div class="page-header mb-3">
    <div>
      <h2 class="mb-0"><i class="fas fa-users-cog text-primary mr-2"></i>Team Members</h2>
      <p class="text-muted small mb-0 mt-1">Manage admin users, roles and privileges</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap" style="gap:.5rem;">
      <span class="mpg-chip mpg-chip-primary">
        <i class="fas fa-user-check"></i>
        {{ $users->total() }} {{ Str::plural('Member', $users->total()) }}
      </span>
      <button id="exportButton" class="btn btn-success btn-sm">
        <i class="fas fa-file-export mr-1"></i>Export
      </button>
      @if(isset($isSuperAdmin) && $isSuperAdmin)
        <a href="{{ route('admin.user.add') }}" class="btn btn-primary btn-sm">
          <i class="fas fa-user-plus mr-1"></i>Add User
        </a>
      @endif
    </div>
  </div>

  {{-- Search --}}
  <div class="card mb-3">
    <div class="card-body py-2">
      <form action="{{ route('search_user') }}" method="get">
        @csrf
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
          <input type="text" name="search" value="{{ request('search') }}"
                 placeholder="Search by name, email or phone…" class="form-control">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
              <a href="{{ route('user.list') }}" class="btn btn-secondary">Clear</a>
            @endif
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Users Table --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fas fa-list mr-1"></i>Users</span>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive tbl-cards">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Profile</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Departments</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $user)
            <tr>
              <td>
                @php
                  $pp  = $user->profile_picture;
                  $img = $pp
                    ? (Str::startsWith($pp, ['http://','https://']) ? $pp : asset('storage/'.$pp))
                    : null;
                @endphp
                <a href="{{ route('admin.user.details', $user->id) }}">
                  @if($img)
                    <img src="{{ $img }}" alt="{{ $user->name }}"
                         style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid var(--mpg-border);">
                  @else
                    <span style="display:inline-flex;align-items:center;justify-content:center;
                                 width:40px;height:40px;border-radius:50%;
                                 background:var(--mpg-primary-bg);color:var(--mpg-primary);font-weight:700;">
                      {{ strtoupper(substr($user->name,0,1)) }}
                    </span>
                  @endif
                </a>
              </td>
              <td>
                <a href="{{ route('admin.user.details', $user->id) }}" class="fw-600">
                  <strong>{{ $user->name }}</strong>
                </a>
              </td>
              <td class="text-muted">{{ $user->email }}</td>
              <td>
                <a href="https://wa.me/+977{{ $user->phone }}" target="_blank"
                   class="text-success font-weight-bold">
                  <i class="fab fa-whatsapp mr-1"></i>{{ $user->phone }}
                </a>
              </td>
              <td>
                @php $deps = $user->departments ?? collect(); @endphp
                @forelse($deps as $d)
                  <span class="badge badge-info mr-1 mb-1">{{ $d->name }}</span>
                @empty
                  <span class="text-muted">—</span>
                @endforelse
              </td>
              <td class="text-center" style="white-space:nowrap;">
                <a href="{{ route('admin.user.details', $user->id) }}"
                   class="btn btn-primary btn-sm mr-1" title="Edit">
                  <i class="fas fa-edit"></i>
                </a>
                @if($user->email !== 'info@adsmpg.com')
                  <a href="{{ route('admin.user.privilege', $user->id) }}"
                     class="btn btn-warning btn-sm mr-1" title="Privilege">
                    <i class="fas fa-key"></i>
                  </a>
                  <form action="{{ route('admin.user.details', $user->id) }}" method="post" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete {{ addslashes($user->name) }}?')">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer">
      {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif
  </div>

</div>

<script>
document.getElementById('exportButton').addEventListener('click', function(){
  window.location.href = '/export-users';
});
</script>
@endsection
