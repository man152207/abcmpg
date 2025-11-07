@extends('admin.layout.layout')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/customer-details.css') }}">

<div class="container py-4" style="max-width: 100%;">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">
                <i class="fas fa-user-circle me-2"></i>
                {{ $requirement->customer->display_name ?? $requirement->customer->name }}
                <small class="text-light">#{{ $requirement->customer->id }}</small>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Profile --}}
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="{{ asset('uploads/customers/' . ($requirement->customer->profile_picture ?? 'default.jpg')) }}"
                         alt="Profile Picture" class="img-fluid rounded" style="max-height:200px">
                </div>

                {{-- Note --}}
                <div class="col-md-9">
                    <h4 class="fw-bold">Requirements & Suggestions</h4>

                    <div class="mb-2">
                        <span class="badge bg-info">{{ ucfirst($requirement->note_type) }}</span>
                        <span class="badge
                              {{ $requirement->priority=='high' ? 'bg-danger' :
                                 ($requirement->priority=='medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                            {{ ucfirst($requirement->priority) }}
                        </span>
                    </div>

                    <p class="fs-6" style="white-space:pre-line">{{ $requirement->body }}</p>
                    <p class="text-muted small mb-3">
                        Added on {{ $requirement->created_at->format('F j, Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========= EDIT MODAL ========= --}}
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="noteForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="noteBody" class="form-control mb-3" rows="5">{{ $requirement->body }}</textarea>
                    <div class="row g-2">
                        <div class="col">
                            <select id="noteType" class="form-select">
                                <option value="requirement" {{ $requirement->note_type=='requirement'?'selected':'' }}>Requirement</option>
                                <option value="suggestion" {{ $requirement->note_type=='suggestion'?'selected':'' }}>Suggestion</option>
                            </select>
                        </div>
                        <div class="col">
                            <select id="notePriority" class="form-select">
                                <option value="high" {{ $requirement->priority=='high'?'selected':'' }}>High</option>
                                <option value="medium" {{ $requirement->priority=='medium'?'selected':'' }}>Medium</option>
                                <option value="low" {{ $requirement->priority=='low'?'selected':'' }}>Low</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // CSRF टोकन सेटअप
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const noteId = {{ $requirement->id }};
    const updateUrl = "{{ route('customer.requirements.update', $requirement->id) }}";
    const deleteUrl = "{{ route('customer.requirements.delete', $requirement->id) }}";
    const noteModal = new bootstrap.Modal('#noteModal');

    // Edit बटन क्लिक इभेन्ट
    $('#edit-note').on('click', function() {
        console.log('Edit button clicked for note ID:', noteId); // डिबगिङका लागि
        noteModal.show();
    });

    // Update फारम सबमिट
    $('#noteForm').on('submit', function (e) {
        e.preventDefault();
        const payload = {
            body: $('#noteBody').val().trim(),
            note_type: $('#noteType').val(),
            priority: $('#notePriority').val()
        };

        if (!payload.body) {
            Swal.fire('Oops', 'Note body cannot be empty.', 'warning');
            return;
        }

        $.ajax({
            url: updateUrl,
            method: 'PUT',
            data: payload,
            success: function () {
                noteModal.hide();
                Swal.fire('Saved!', 'Note updated.', 'success').then(() => location.reload());
            },
            error: function (xhr) {
                console.log('Update error:', xhr.responseText); // त्रुटि सन्देश कन्सोलमा देखाउन
                Swal.fire('Error', 'Failed to update note: ' + xhr.responseText, 'error');
            }
        });
    });

    // Delete बटन क्लिक इभेन्ट
    $('#delete-note').on('click', function() {
        console.log('Delete button clicked for note ID:', noteId); // डिबगिङका लागि
        Swal.fire({
            title: 'Delete this note?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(res => {
            if (!res.isConfirmed) return;

            $.ajax({
                url: deleteUrl,
                method: 'DELETE',
                success: function () {
                    Swal.fire('Deleted!', 'The note has been removed.', 'success')
                        .then(() => window.location.href = '/admin/dashboard/customer_list'); // रिडाइरेक्ट
                },
                error: function (xhr) {
                    console.log('Delete error:', xhr.responseText); // त्रुटि सन्देश कन्सोलमा देखाउन
                    Swal.fire('Error', 'Failed to delete note: ' + xhr.responseText, 'error');
                }
            });
        });
    });
});
</script>
@endpush