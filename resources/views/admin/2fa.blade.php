@extends('admin.layout.layout')

@section('title', '2FA Auth Codes | MPG Solution')

@section('content')

    <div class="container-fluid tfa-container-fluid">
        <div class="row tfa-row">
            <div class="col-md-12 tfa-col-md-12">
                <div class="card tfa-card">
                    <div class="card-header tfa-card-header">
                        <h3 class="card-title tfa-card-title">Auth Codes Management</h3>
                    </div>
                    <div class="card-body tfa-card-body">
                        <!-- Form for adding new auth code -->
                        <form action="{{ route('admin.2fa.store') }}" method="POST" class="tfa-form">
                            @csrf
                            <div class="row tfa-row">
                                <div class="col-md-3 tfa-col-md-3">
                                    <div class="form-group tfa-form-group">
                                        <label for="account_name">Account Name</label>
                                        <input type="text" name="account_name" class="form-control tfa-form-control" value="{{ old('account_name') }}" required>
                                        @error('account_name')
                                            <span class="text-danger tfa-text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 tfa-col-md-3">
                                    <div class="form-group tfa-form-group">
                                        <label for="auth_token_code">Auth Token Code</label>
                                        <input type="text" name="auth_token_code" id="auth_token_code" class="form-control tfa-form-control tfa-auth-token-input" value="{{ old('auth_token_code') }}" required>
                                        @error('auth_token_code')
                                            <span class="text-danger tfa-text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 tfa-col-md-3">
                                    <div class="form-group tfa-form-group">
                                        <label for="recovery_code">Recovery Code</label>
                                        <input type="text" name="recovery_code" class="form-control tfa-form-control" value="{{ old('recovery_code') }}">
                                        @error('recovery_code')
                                            <span class="text-danger tfa-text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 tfa-col-md-3">
                                    <div class="form-group tfa-form-group" style="margin-top: 30px;">
                                        <button type="submit" class="btn btn-info tfa-btn tfa-btn-info">Add Auth Code</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Table for displaying auth codes -->
                        <table class="table table-bordered table-hover tfa-table">
                            <thead>
                                <tr class="tfa-table-row">
                                    <th>Account Name</th>
                                    <th>Auth Token Code</th>
                                    <th>Recovery Code</th>
                                    <th>Action</th>
                                    <th>Result</th>
                                    <th>Timer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($authCodes as $authCode)
                                    <tr class="tfa-table-row">
                                        <td><a href="{{ route('admin.2fa.logs', $authCode->id) }}" class="tfa-table-link">{{ $authCode->account_name }}</a></td>
                                        <td>{{ $authCode->auth_token_code }}</td>
                                        <td>{{ $authCode->recovery_code ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group tfa-btn-group">
                                                <!-- Generate Code Button -->
                                                <button type="button" class="btn btn-info btn-sm tfa-btn tfa-btn-info tfa-generate-code-btn" data-id="{{ $authCode->id }}" data-url="{{ route('admin.2fa.generate', $authCode->id) }}" data-reset-url="{{ route('admin.2fa.reset', $authCode->id) }}">Generate Code</button>
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-warning btn-sm tfa-btn tfa-btn-warning" data-toggle="modal" data-target="#editModal{{ $authCode->id }}">Edit</button>
                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.2fa.destroy', $authCode->id) }}" method="POST" class="tfa-form" onsubmit="return confirm('Are you sure you want to delete this auth code?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm tfa-btn tfa-btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="tfa-result-cell" data-id="{{ $authCode->id }}">{{ $authCode->result ?? 'N/A' }}</td>
                                        <td class="tfa-timer-cell" data-id="{{ $authCode->id }}">N/A</td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade tfa-modal" id="editModal{{ $authCode->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $authCode->id }}" aria-hidden="true">
                                        <div class="modal-dialog tfa-modal-dialog" role="document">
                                            <div class="modal-content tfa-modal-content">
                                                <div class="modal-header tfa-modal-header">
                                                    <h5 class="card-title tfa-card-title" id="editModalLabel{{ $authCode->id }}">Edit Auth Code</h5>
                                                    <button type="button" class="close tfa-close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.2fa.update', $authCode->id) }}" method="POST" class="tfa-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body tfa-modal-body">
                                                        <div class="form-group tfa-form-group">
                                                            <label for="account_name_{{ $authCode->id }}">Account Name</label>
                                                            <input type="text" name="account_name" id="account_name_{{ $authCode->id }}" class="form-control tfa-form-control" value="{{ $authCode->account_name }}" required>
                                                            @error('account_name')
                                                                <span class="text-danger tfa-text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group tfa-form-group">
                                                            <label for="auth_token_code_{{ $authCode->id }}">Auth Token Code</label>
                                                            <input type="text" name="auth_token_code" id="auth_token_code_{{ $authCode->id }}" class="form-control tfa-form-control tfa-auth-token-input" value="{{ $authCode->auth_token_code }}" required>
                                                            @error('auth_token_code')
                                                                <span class="text-danger tfa-text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group tfa-form-group">
                                                            <label for="recovery_code_{{ $authCode->id }}">Recovery Code</label>
                                                            <input type="text" name="recovery_code" id="recovery_code_{{ $authCode->id }}" class="form-control tfa-form-control" value="{{ $authCode->recovery_code }}">
                                                            @error('recovery_code')
                                                                <span class="text-danger tfa-text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer tfa-modal-footer">
                                                        <button type="button" class="btn btn-secondary tfa-btn tfa-btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary tfa-btn tfa-btn-primary">Update Auth Code</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr class="tfa-table-row">
                                        <td colspan="6" class="text-center">No auth codes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                showNotification('{{ session('success') }}');
            @endif
            @if (session('error'))
                showNotification('{{ session('error') }}', '#e53e3e');
            @endif

            // Clean auth token input on keyup
            $('.tfa-auth-token-input, #auth_token_code').on('input', function() {
                let value = $(this).val();
                // Remove spaces and invalid characters, convert to uppercase
                let cleaned = value.replace(/\s/g, '').replace(/[^A-Z2-7]/g, '').toUpperCase();
                $(this).val(cleaned);
            });

            // Handle Generate Code button click with AJAX
            $('.tfa-generate-code-btn').on('click', function() {
                let button = $(this);
                let authCodeId = button.data('id');
                let url = button.data('url');
                let resetUrl = button.data('reset-url');
                let resultCell = $('.tfa-result-cell[data-id="' + authCodeId + '"]');
                let timerCell = $('.tfa-timer-cell[data-id="' + authCodeId + '"]');

                // Disable button to prevent multiple clicks
                button.prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update result cell with new code
                            resultCell.text(response.code);

                            // Start 30-second countdown timer
                            let timeLeft = 30;
                            timerCell.text('Expiring in ' + timeLeft + 's');
                            let timer = setInterval(function() {
                                timeLeft--;
                                if (timeLeft <= 0) {
                                    clearInterval(timer);
                                    timerCell.text('Expired');
                                    resultCell.text('N/A');

                                    // Reset code in database via AJAX
                                    $.ajax({
                                        url: resetUrl,
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function() {
                                            button.prop('disabled', false);
                                        },
                                        error: function() {
                                            showNotification('Error resetting code', '#e53e3e');
                                            button.prop('disabled', false);
                                        }
                                    });
                                } else {
                                    timerCell.text('Expiring in ' + timeLeft + 's');
                                }
                            }, 1000);
                        } else {
                            showNotification(response.message, '#e53e3e');
                            button.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        showNotification('Error generating code', '#e53e3e');
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection