@extends('admin.layout.layout')

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
    <h5 class="mb-0">Multimedia Management</h5>
    <button class="btn btn-light btn-sm" id="btnAdd" data-toggle="tooltip" title="Add new multimedia record">
      <i class="fas fa-plus"></i> New
    </button>
  </div>

  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    {{-- Filters --}}
    <form class="row g-3 mb-4" method="GET" action="{{ route('admin.multimedia.index') }}">
      <div class="col-md-2">
        <label class="form-label">From Date</label>
        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label">To Date</label>
        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-control select2">
          <option value="">All Status</option>
          @foreach(['pending', 'in_progress', 'completed', 'on_hold'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Type</label>
        <select name="project_type" class="form-control select2">
          <option value="">All Types</option>
          @foreach(['Graphics', 'Video'] as $t)
            <option value="{{ $t }}" @selected(request('project_type') === $t)>{{ $t }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Priority</label>
        <select name="priority" class="form-control select2">
          <option value="">All Priorities</option>
          @foreach(['low', 'normal', 'high', 'urgent'] as $p)
            <option value="{{ $p }}" @selected(request('priority') === $p)>{{ ucfirst($p) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Search</label>
        <input name="search" class="form-control" value="{{ request('search') }}" placeholder="Search...">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary me-2" data-toggle="tooltip" title="Apply filters"><i class="fas fa-filter"></i> Apply</button>
        <a href="{{ route('admin.multimedia.index') }}" class="btn btn-secondary" data-toggle="tooltip" title="Reset filters"><i class="fas fa-undo"></i> Reset</a>
      </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle table-hover">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>WhatsApp</th>
            <th>Customer</th>
            <th>Project</th>
            <th>Type</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Project By</th>
            <th>Assigned To</th>
            <th>Due</th>
            <th>Link</th>
            <th>Cost (NPR)</th>
            <th class="tbl-action-col">Action</th>
          </tr>
        </thead>
        <tbody>
        @forelse($items as $i => $row)
          <tr class="@if($row->status === 'pending') bg-warning-light @elseif($row->status === 'in_progress') bg-info-light @elseif($row->status === 'on_hold') bg-danger-light @endif">
            <td>{{ $items->firstItem() + $i }}</td>
            <td>{{ $row->date?->format('Y-m-d') }}</td>
            <td>
              @if($row->whatsapp)
                <a href="https://wa.me/+977{{ $row->whatsapp }}" target="_blank" class="text-decoration-none" data-toggle="tooltip" title="Open WhatsApp">
                  <strong>{{ $row->whatsapp }}</strong>
                </a>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>{{ $row->customer_name ?? 'Unknown Customer' }}</td>
            <td>
              <span class="expandable-project expandable-text" data-full-text="{{ $row->project }}" data-toggle="tooltip" title="Click to view full project">
                {{ Str::limit($row->project, 20) }}
              </span>
            </td>
            <td>{{ $row->project_type }}</td>
            <td>
              <span class="badge 
                @class([
                  'bg-secondary' => $row->status === 'pending',
                  'bg-info' => $row->status === 'in_progress',
                  'bg-success' => $row->status === 'completed',
                  'bg-warning' => $row->status === 'on_hold',
                ])">
                {{ ucfirst(str_replace('_', ' ', $row->status)) }}
              </span>
            </td>
            <td>
              <span class="badge 
                @class([
                  'bg-light text-dark' => $row->priority === 'low',
                  'bg-secondary' => $row->priority === 'normal',
                  'bg-primary' => $row->priority === 'high',
                  'bg-danger' => $row->priority === 'urgent',
                ])">
                {{ ucfirst($row->priority) }}
              </span>
            </td>
            <td>{{ $row->project_by }}</td>
            <td>{{ optional($row->assignedTo)->name ?? 'Not Assigned' }}</td>
            <td>
              @if($row->due_date)
                {{ $row->due_date->format('Y-m-d') }}
                @if($row->status !== 'completed' && now()->gt($row->due_date))
                  <span data-toggle="tooltip" title="Overdue">⏰</span>
                @endif
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              @if($row->asset_link)
                <a href="{{ $row->asset_link }}" target="_blank" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" title="Open link">
                  <i class="fas fa-link"></i> {{ Str::limit($row->asset_provider, 10) }}
                </a>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              <span class="cost-npr" data-cost="{{ $row->cost_npr ?? '' }}">{{ $row->cost_npr ? '****' : '—' }}</span>
            </td>
            <td>
              <button class="btn btn-sm btn-outline-primary btnEdit" data-id="{{ $row->id }}" data-toggle="tooltip" title="Edit record">
                <i class="fas fa-edit"></i> Edit
              </button>
              <form action="{{ route('admin.multimedia.destroy', $row->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-sm btn-outline-danger btnDelete" data-toggle="tooltip" title="Delete record">
                  <i class="fas fa-trash"></i> Del
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="14" class="text-center text-muted">No records found.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{ $items->links('pagination::bootstrap-4') }}
  </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="mmModal" tabindex="-1" role="dialog" aria-labelledby="mmTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.multimedia.save') }}" id="mmForm">
        @csrf
        <input type="hidden" name="id" id="mm_id">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="mmTitle">Add Multimedia</h5>
          <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          {{-- split layout --}}
          <div class="mm-flex">
            {{-- LEFT SIDE --}}
            <div class="mm-left">
              <div class="row g-3">

                <div class="col-md-3">
                  <label class="form-label">Date</label>
                  <input type="date" name="date" id="mm_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label">WhatsApp</label>
                  <input type="text" name="whatsapp" id="mm_whatsapp" class="form-control" placeholder="Enter WhatsApp Number" required>
                  <small class="text-muted">Enter a valid phone number</small>
                </div>

                <div class="col-md-5">
                  <label class="form-label">Customer Name</label>
                  <input type="text" name="customer_name" id="mm_customer_name" class="form-control" readonly>
                </div>

                {{-- Cost right after customer name --}}
                <div class="col-md-3">
                  <label class="form-label">Cost (NPR)</label>
                  <input type="number" step="0.01" name="cost_npr" id="mm_cost_npr" class="form-control">
                </div>

                <div class="col-md-3">
                  <label class="form-label">Status</label>
                  <select name="status" id="mm_status" class="form-control select2" required>
  @foreach(['pending', 'in_progress', 'completed', 'on_hold'] as $s)
    <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
  @endforeach
</select>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Project Type</label>
                  <select name="project_type" id="mm_project_type" class="form-control select2" required>
                    @foreach(['Graphics', 'Video'] as $t)
                      <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Priority</label>
                  <select name="priority" id="mm_priority" class="form-control select2" required>
                    @foreach(['low', 'normal', 'high', 'urgent'] as $p)
                      <option value="{{ $p }}">{{ ucfirst($p) }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Due Date</label>
                  <input type="date" name="due_date" id="mm_due_date" class="form-control">
                </div>

                <div class="col-md-4">
                  <label class="form-label">Project By</label>
                  <input type="text" name="project_by" id="mm_project_by" class="form-control" value="{{ auth('admin')->user()->name }}" readonly>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Assigned To</label>
                  <select name="assigned_to" id="mm_assigned_to" class="form-control select2">
                    <option value="">Select User</option>
                    @forelse($admins as $admin)
                      <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                    @empty
                      <option value="" disabled>No users available</option>
                    @endforelse
                  </select>
                </div>

                {{-- Platforms single dropdown --}}
                <div class="col-md-6">
                  <label class="form-label">Platforms</label>
                  <select name="platforms" id="mm_platforms" class="form-control select2" data-placeholder="Select Platform">
                    <option value=""></option>
                    @foreach(['Facebook', 'Instagram', 'YouTube', 'TikTok', 'Print'] as $pl)
                      <option value="{{ $pl }}">{{ $pl }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- Asset fields (visible only when completed) --}}
                <div class="col-md-6 asset-field d-none">
                  <label class="form-label">Asset Link (Drive/Dropbox/YouTube…)</label>
                  <input type="url" name="asset_link" id="mm_asset_link" class="form-control" placeholder="https://drive.google.com/...">
                </div>

                <div class="col-md-3 asset-field d-none">
                  <label class="form-label">Provider</label>
                  <select name="asset_provider" id="mm_asset_provider" class="form-control">
                    @foreach(['Drive', 'Dropbox', 'OneDrive', 'YouTube', 'Vimeo', 'Other'] as $prov)
                      <option value="{{ $prov }}">{{ $prov }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-3 asset-field d-none">
                  <label class="form-label">Access</label>
                  <select name="asset_access" id="mm_asset_access" class="form-control">
                    @foreach(['view_only', 'comment', 'edit'] as $acc)
                      <option value="{{ $acc }}">{{ ucfirst(str_replace('_', ' ', $acc)) }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-3 asset-field d-none">
                  <label class="form-label">Asset Type</label>
                  <select name="asset_type" id="mm_asset_type" class="form-control">
                    @foreach(['Image', 'Video', 'PSD/AI', 'Doc', 'Other'] as $at)
                      <option value="{{ $at }}">{{ $at }}</option>
                    @endforeach
                  </select>
                </div>

                {{-- ONLY keep caption doc link; removed Version / Size (MB) / Publish URL --}}
                <div class="col-md-6 asset-field d-none">
                  <label class="form-label">Caption Doc Link</label>
                  <input type="url" name="caption_link" id="mm_caption_link" class="form-control" placeholder="https://docs.google.com/...">
                </div>

                <div class="col-md-3">
                  <label class="form-label">Revisions</label>
                  <input type="number" name="revision_count" id="mm_revision_count" class="form-control" min="0" value="0">
                </div>

                <div class="col-md-3 d-flex align-items-center">
                  <div class="form-check mt-4">
                    <input type="checkbox" name="approved_by_client" id="mm_approved" value="1" class="form-check-input">
                    <label class="form-check-label" for="mm_approved">Approved?</label>
                  </div>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                  <div class="form-check mt-4">
                    <input type="checkbox" name="qa_checked" id="mm_qa" value="1" class="form-check-input">
                    <label class="form-check-label" for="mm_qa">QA Checked?</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <label class="form-label">Estimate (hrs)</label>
                  <input type="number" step="0.01" name="estimate_hours" id="mm_estimate_hours" class="form-control">
                </div>

                <div class="col-md-3">
                  <label class="form-label">Actual (hrs)</label>
                  <input type="number" step="0.01" name="actual_hours" id="mm_actual_hours" class="form-control">
                </div>

              </div>
            </div>

            {{-- RIGHT SIDE: full-height Project textarea --}}
            <div class="mm-right">
              <div class="mm-project-wrap">
                <label class="mm-project-label">Project</label>
                <textarea name="project" id="mm_project" class="form-control" placeholder="Write full project brief..." required></textarea>
              </div>
            </div>

          </div>

          @if ($errors->any())
            <div class="alert alert-danger mt-3">
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Save changes">
            <i class="fas fa-save"></i> Save
          </button>
          <button type="button" class="btn btn-light" data-dismiss="modal" data-toggle="tooltip" title="Cancel">
            <i class="fas fa-times"></i> Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Project preview modal --}}
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="projectModalTitle">Project Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="projectFullText" class="prewrap"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css_')
<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
@endsection

@section('js_')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
<script>
window.addEventListener('load', function() {
  if (typeof jQuery === 'undefined') { console.error('jQuery is not loaded'); return; }

  // tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // Select2
  try {
    $('.select2').select2({
      placeholder: function() { return $(this).data('placeholder') || 'Select an option'; },
      allowClear: true,
      width: '100%'
    });
  } catch (err) { console.error('Error initializing Select2:', err); }

  var $modal = $('#mmModal');
  var $projectModal = $('#projectModal');
  if (!$modal.length) { console.error('Modal element #mmModal not found'); return; }

  var showCost = false;
  // Ctrl+Shift+Q toggle cost mask
  $(document).on('keydown', function(e) {
    if (e.ctrlKey && e.shiftKey && e.which === 81) {
      showCost = !showCost;
      $('.cost-npr').each(function() {
        $(this).text(showCost ? $(this).data('cost') : '****');
      });
    }
  });

  var F = {
    id: $('#mm_id'),
    date: $('#mm_date'),
    whatsapp: $('#mm_whatsapp'),
    customer_name: $('#mm_customer_name'),
    project: $('#mm_project'),
    status: $('#mm_status'),
    project_by: $('#mm_project_by'),
    project_type: $('#mm_project_type'),
    priority: $('#mm_priority'),
    due_date: $('#mm_due_date'),
    asset_link: $('#mm_asset_link'),
    asset_provider: $('#mm_asset_provider'),
    asset_access: $('#mm_asset_access'),
    asset_type: $('#mm_asset_type'),
    platforms: $('#mm_platforms'),      // single select
    caption_link: $('#mm_caption_link'),
    revision_count: $('#mm_revision_count'),
    approved: $('#mm_approved'),
    qa: $('#mm_qa'),
    assigned_to: $('#mm_assigned_to'),
    cost_npr: $('#mm_cost_npr'),
    title: $('#mmTitle'),
  };

  // auto fetch customer from WhatsApp
  F.whatsapp.on('input', function() {
    var phone = $(this).val().trim();
    if (phone.length >= 9) {
      $.ajax({
        url: '{{ route('admin.multimedia.get-customer') }}',
        method: 'POST',
        data: { phone: phone, _token: '{{ csrf_token() }}' },
        success: function(res) { F.customer_name.val(res.customer_name || 'Unknown Customer'); },
        error: function() { F.customer_name.val('Unknown Customer'); }
      });
    } else {
      F.customer_name.val('');
    }
  });

  // show/hide asset fields by status
  function toggleAssetFields() {
    var $assetFields = $('.asset-field');
    if (F.status.val() === 'completed') {
      $assetFields.removeClass('d-none');
    } else {
      $assetFields.addClass('d-none');
      F.asset_link.val('');
      F.asset_provider.val('Drive');
      F.asset_access.val('view_only');
      F.asset_type.val('Other');
      F.caption_link.val('');
    }
  }
  F.status.on('change', toggleAssetFields);
  toggleAssetFields();

  // project preview in table
  $('.expandable-project').on('click', function() {
    $('#projectFullText').text($(this).data('full-text'));
    $projectModal.modal('show');
  });

  function resetForm() {
    F.id.val('');
    F.date.val('{{ now()->format('Y-m-d') }}').prop('disabled', false);
    F.whatsapp.val('');
    F.customer_name.val('');
    F.project.val('');
    F.status.val('pending').trigger('change');
    F.project_by.val('{{ auth('admin')->user()->name }}');
    F.project_type.val('Graphics').trigger('change');
    F.priority.val('normal').trigger('change');
    F.due_date.val('').prop('disabled', false);
    F.asset_link.val('');
    F.asset_provider.val('Drive');
    F.asset_access.val('view_only');
    F.asset_type.val('Other');
    F.platforms.val('').trigger('change'); // single select
    F.caption_link.val('');
    F.revision_count.val(0);
    F.approved.prop('checked', false);
    F.qa.prop('checked', false);
    F.assigned_to.val('').trigger('change');
    F.cost_npr.val('');
    toggleAssetFields();
  }

  // NEW click
  $('#btnAdd').on('click', function(e) {
    e.preventDefault();
    F.title.text('Add Multimedia');
    resetForm();
    $modal.modal('show');
  });

  // EDIT click
  $('.btnEdit').on('click', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
      url: '{{ route('admin.multimedia.show', ':id') }}'.replace(':id', id),
      method: 'GET',
      success: function(d) {
        F.title.text('Edit Multimedia');
        F.id.val(d.id || '');
        F.date.val(d.date || '').prop('disabled', true);
        F.whatsapp.val(d.whatsapp || '');
        F.customer_name.val(d.customer_name || 'Unknown Customer');
        F.project.val(d.project || '');
        F.status.val(d.status || 'pending').trigger('change');
        F.project_by.val(d.project_by || '{{ auth('admin')->user()->name }}');
        F.project_type.val(d.project_type || 'Graphics').trigger('change');
        F.priority.val(d.priority || 'normal').trigger('change');
        F.due_date.val(d.due_date || '').prop('disabled', true);

        F.asset_link.val(d.asset_link || '');
        F.asset_provider.val(d.asset_provider || 'Drive');
        F.asset_access.val(d.asset_access || 'view_only');
        F.asset_type.val(d.asset_type || 'Other');
        F.caption_link.val(d.caption_link || '');

        // platforms (single)
        if (Array.isArray(d.platforms)) {
          F.platforms.val(d.platforms[0] || '').trigger('change');
        } else {
          F.platforms.val(d.platforms || '').trigger('change');
        }

        F.revision_count.val(d.revision_count ?? 0);
        F.approved.prop('checked', !!d.approved_by_client);
        F.qa.prop('checked', !!d.qa_checked);
        F.assigned_to.val(d.assigned_to || '').trigger('change');
        F.cost_npr.val(d.cost_npr || '');

        toggleAssetFields();
        $modal.modal('show');
      },
      error: function(err) {
        console.error('Error fetching edit data:', err);
        alert('Failed to load record. Please try again.');
      }
    });
  });

  // enable disabled dates before submit
  $('#mmForm').on('submit', function() {
    F.date.prop('disabled', false);
    F.due_date.prop('disabled', false);
  });

  // delete confirm
  $('.btnDelete').on('click', function(e) {
    e.preventDefault();
    var form = $(this).closest('form');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) form.submit();
    });
  });
});
</script>
@endsection
