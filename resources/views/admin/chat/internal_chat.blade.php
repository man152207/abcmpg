@extends('admin.layout.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --bg-color: #f0f2f5;
        --text-color: #1c1e21;
        --bubble-bg: #ffffff;
        --bubble-bg-admin: #0084ff;
        --bubble-text-admin: #ffffff;
        --border-color: #e4e6eb;
    }
    [data-theme="dark"] {
        --bg-color: #18191a;
        --text-color: #e4e6eb;
        --bubble-bg: #242526;
        --bubble-bg-admin: #3a3b3c;
        --bubble-text-admin: #e4e6eb;
        --border-color: #3a3b3c;
    }
    .chat-container {
        background-color: var(--bg-color);
        color: var(--text-color);
        height: calc(100vh - 117px);
    }
    .chat-message {
        background-color: var(--bubble-bg);
        border-radius: 18px;
        padding: 8px 12px;
        max-width: 70%;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        position: relative;
        margin-bottom: 4px;
    }
    .chat-message.admin {
        background-color: var(--bubble-bg-admin);
        color: var(--bubble-text-admin);
    }
    .message-content p {
        white-space: pre-wrap;
    }
    .message-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: 12px;
        margin-top: 4px;
    }
    .reaction {
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 12px;
        background-color: rgba(0, 0, 0, 0.1);
        margin-right: 4px;
        font-size: 14px;
    }
    .context-menu {
        position: absolute;
        background-color: var(--bubble-bg);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 10;
        padding: 8px 0;
        min-width: 120px;
    }
    .context-menu button, .context-menu a {
        display: block;
        width: 100%;
        padding: 8px 16px;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-color);
    }
    .context-menu button:hover, .context-menu a:hover {
        background-color: var(--border-color);
    }
    .input-container {
        background-color: var(--bubble-bg);
        border-radius: 20px;
        padding: 8px 12px;
    }
    .input-container textarea {
        height: auto;
        max-height: 80px;
    }
    .message-input {
        min-height: 28px;
        max-height: 60px;
        font-size: 13px;
        padding-top: 4px;
        padding-bottom: 4px;
    }
    .select2-container .select2-selection--single {
        border-radius: 20px;
        height: 40px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }
    @media (max-width: 768px) {
        .customer-list {
            display: none;
        }
        .conversation-pane {
            width: 100%;
        }
    }
</style>

<div class="chat-container flex mx-auto rounded-xl shadow-lg overflow-hidden">
    <!-- Customer List -->
    <div class="customer-list w-1/4 border-r border-gray-200 flex flex-col" style="background-color: var(--bubble-bg);">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Customers</h2>
            <input type="text" id="customerSearch" class="w-full p-2 mt-2 rounded-full border focus:ring-2 focus:ring-blue-500" placeholder="Search customers..." aria-label="Search customers">
        </div>
        <div class="flex-1 overflow-y-auto">
            @foreach($customers as $customer)
                <div class="p-4 hover:bg-gray-100 cursor-pointer flex items-center" onclick="selectCustomer('{{ $customer->id }}', '{{ $customer->name }}')">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                        <span class="text-white font-semibold">{{ substr($customer->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold">{{ $customer->name }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Conversation Pane -->
    <div class="conversation-pane flex-1 flex flex-col">
        <!-- Header + Search + Dark Mode Combined -->
        <div class="flex justify-between items-center gap-2 p-3 border-b flex-wrap" style="border-color: var(--border-color);background-color: var(--bubble-bg);padding: 2px !important;">
            <h1 class="text-lg font-semibold whitespace-nowrap" style="margin-left: 7px;">Internal Chat</h1>
            <input type="text" id="searchInput" class="flex-1 min-w-[200px] p-2 rounded-full border focus:ring-2 focus:ring-blue-500 mx-2" placeholder="Search messages..." aria-label="Search chat messages">
            <button id="themeToggle" class="btn bg-gray-800 text-white px-3 py-1 rounded-full hover:bg-gray-900 focus:ring-2 focus:ring-blue-500" style="margin-right: 7px;" aria-label="Toggle dark mode">🌙</button>
        </div>

        <!-- Chat Messages -->
        <div class="chat-messages flex-1 p-4 overflow-y-auto flex flex-col gap-2" id="chatMessagesWrapper">
            <div class="flex justify-center mb-3">
                <button id="loadMoreBtn" class="bg-gray-300 hover:bg-gray-400 text-sm px-4 py-1 rounded" onclick="loadMoreMessages()">
                    Load More Messages
                </button>
            </div>
            <div id="chatMessages">
                @foreach ($chats as $c)
                    <div class="chat-message {{ $c->customer_id ? 'ml-auto admin' : 'mr-auto' }} relative" data-id="{{ $c->id }}" tabindex="0">
                        <div class="message-content">
                            @if($c->customer_id && $c->customer)
                                <p class="text-sm font-bold {{ $c->customer_id ? 'text-white' : 'text-gray-600' }}">For: {{ $c->customer->name }} | {{ $c->admin ? $c->admin->name : 'Unknown Admin' }}</p>
                            @else
                                <p class="text-sm font-bold text-gray-600">{{ $c->admin ? $c->admin->name : 'Unknown Admin' }}</p>
                            @endif
                            @if($c->message)
                                <p class="text-base whitespace-pre-wrap">{{ $c->message }}</p>
                            @endif
                            @if($c->image_paths)
                                @foreach($c->image_paths as $image_path)
                                    <img src="{{ asset('storage/' . $image_path) }}" class="message-image cursor-pointer" alt="Chat Image" loading="lazy" onclick="openImageModal('{{ asset('storage/' . $image_path) }}')">
                                @endforeach
                            @endif
                            <p class="text-sm text-gray-500 mt-1" style="justify-self: end;">
                                {{ $c->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <!-- Context Menu -->
                        <div class="context-menu hidden" id="contextMenu-{{ $c->id }}">
                            <button type="button" class="edit-message" data-id="{{ $c->id }}">Edit</button>
                            <button type="button" class="delete-message" data-id="{{ $c->id }}">Delete</button>
                            <button type="button" onclick="copyToClipboard('{{ addslashes(strip_tags($c->message ?? '')) }}')">Copy</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Input Form -->
        <div class="chat-input-section px-3 py-2 border-t" style="border-color: var(--border-color); background-color: var(--bubble-bg);">
            <form id="chatForm" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2" action="{{ route('admin.internal_chat.store') }}">
                @csrf
                <!-- Image Preview Always on Top -->
                <div id="imagePreviews" class="flex flex-wrap gap-2 mb-1"></div>
                <!-- File Input (Hidden) -->
                <input type="file" id="image" name="images[]" accept="image/*" class="hidden" multiple onchange="previewImages(event)">
                <!-- Customer + Camera + Input + Send -->
                <div class="flex items-center gap-2">
                    <!-- Camera Icon -->
                    <label for="image" class="bg-blue-600 text-white p-2 rounded-full cursor-pointer hover:bg-blue-700" aria-label="Upload images">
                        <i class="fas fa-camera"></i>
                    </label>
                    <!-- Customer Select -->
                    <select class="form-control w-40 rounded-lg border focus:ring-2 focus:ring-blue-500" id="customer" name="customer_id" aria-label="Select customer">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                        @endforeach
                    </select>
                    <!-- Message + Send -->
                    <div class="flex-1 input-container rounded-full flex items-center px-3 py-1 border border-gray-300 bg-white">
                        <textarea id="messageInput" name="message" rows="1" class="message-input flex-1 resize-none border-0 focus:ring-0 bg-transparent text-sm leading-4" style="height:32px;" placeholder="Type a message..." aria-label="Type a message"></textarea>
                        <button type="submit" class="text-blue-600 hover:text-blue-800" aria-label="Send message">
                            <i class="fas fa-paper-plane text-lg"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editChatModal">
    <div class="modal-box">
        <form id="editChatForm" method="POST">
            @csrf
            @method('PUT')
            <h3 class="font-bold text-lg">Edit Message</h3>
            <textarea name="message" class="textarea textarea-bordered w-full" rows="6"></textarea>
            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn" onclick="document.getElementById('editChatModal').classList.remove('modal-open')">Close</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    const CURRENT_ADMIN_ID = {{ auth('admin')->id() }};

    // Laravel Echo Setup
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env('PUSHER_APP_KEY') }}',
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        forceTLS: true
    });

    // Reusable function to append a new message to the chat UI
    function appendMessageToChat(response, message, customerId, timestamp) {
        const isAdmin = customerId ? true : false;
        const messageClasses = isAdmin ? 'ml-auto admin' : 'mr-auto';
        const textColorClass = isAdmin ? 'text-white' : 'text-gray-600';
        const customerText = customerId ? `For: ${response.customer_name} | ${response.admin_name}` : response.admin_name;
        const imagePaths = response.image_paths || [];

        let imageHtml = '';
        imagePaths.forEach(path => {
            const fullPath = `{{ asset('storage') }}/${path}`;
            imageHtml += `<img src="${fullPath}" class="message-image cursor-pointer" alt="Chat Image" loading="lazy" onclick="openImageModal('${fullPath}')">`;
        });

        const newMessage = `
            <div class="chat-message ${messageClasses} relative" data-id="${response.id}" tabindex="0">
                <div class="message-content">
                    <p class="text-sm font-bold ${textColorClass}">${customerText}</p>
                    ${message ? `<p class="text-base whitespace-pre-wrap">${escapeHtml(message)}</p>` : ''}
                    ${imageHtml}
                    <p class="text-sm text-gray-500 mt-1">${timestamp}</p>
                </div>
                <div class="context-menu hidden" id="contextMenu-${response.id}">
                    <button type="button" class="edit-message" data-id="${response.id}">Edit</button>
                    <button type="button" class="delete-message" data-id="${response.id}">Delete</button>
                    <button type="button" onclick="copyToClipboard('${strip_tags(message || '')}')">Copy</button>
                </div>
            </div>
        `;
        $('#chatMessages').append($(newMessage));
        scrollToBottom();
    }

    // Reusable function to handle successful AJAX response
    function handleChatSuccess(response, method, message, customerId) {
        if (response.success) {
            $('#messageInput').val('');
            $('#image').val('');
            $('#imagePreviews').empty().hide();
            $('#customer').val('').trigger('change');

            if (method === 'POST') {
                const timestamp = new Date().toLocaleString('en-US', {
                    month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true
                });
                appendMessageToChat(response, message, customerId, timestamp);
                scrollToBottom();
            } else if (method === 'PUT') {
                const messageElement = $(`.chat-message[data-id="${response.id}"] .message-content p.text-base`);
                if (message) {
                    messageElement.html(escapeHtml(message).replace(/\n/g, '<br>'));
                } else {
                    messageElement.remove();
                }
                document.getElementById('editChatModal').classList.remove('modal-open');
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.error || 'Failed to process request.',
                confirmButtonText: 'OK'
            });
        }
    }

    // Reusable function to handle AJAX errors
    function handleChatError(xhr) {
        let errorMsg = xhr.responseJSON?.error || 'Error submitting the form. Please try again.';
        if (xhr.status === 422) {
            errorMsg = xhr.responseJSON.error || 'Invalid input. Please check your message or images.';
        } else if (xhr.status === 500) {
            errorMsg = xhr.responseJSON.error || 'Server error occurred. Please try again or contact support.';
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMsg,
            confirmButtonText: 'OK'
        });
        console.error('AJAX Error:', xhr.responseText);
    }

    // Submit chat form via AJAX
    function submitChatForm() {
        const messageContent = $('#messageInput').val().trim();
        const files = $('#image')[0].files;
        const customerId = $('#customer').val();

        if (!messageContent && !files.length && !customerId) {
            Swal.fire({
                icon: 'warning',
                title: 'Empty Message',
                text: 'Please enter a message, select a customer, or upload an image.',
                confirmButtonText: 'OK'
            });
            return;
        }

        const formData = new FormData($('#chatForm')[0]);
        formData.set('message', messageContent);

        const url = $('#chatForm').attr('action');
        const method = $('#chatForm').find('input[name="_method"]').val() || 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => handleChatSuccess(response, method, messageContent, customerId),
            error: handleChatError
        });
    }

    // Handle Enter key to submit form
    $('#messageInput').on('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            submitChatForm();
        }
    });

    // Handle form submission (e.g., Send button click)
    $('#chatForm').on('submit', (e) => {
        e.preventDefault();
        submitChatForm();
    });

    // Select2 for Customer Selection
    $('#customer').select2({
        placeholder: 'Select or type to search customer',
        allowClear: true,
        minimumInputLength: 1,
        ajax: {
            url: '{{ route('admin.customers.search') }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return {
                    results: data.map(customer => ({
                        id: customer.id,
                        text: `${customer.name} (${customer.phone})`
                    }))
                };
            },
            cache: true
        },
        templateResult: function(customer) {
            if (!customer.id) return customer.text;
            return $('<span>' + customer.text + '</span>');
        },
        templateSelection: function(customer) {
            if (!customer.id) return customer.text;
            return $('<span>' + customer.text + '</span>');
        }
    });

    const customers = @json($customers);
    customers.forEach(customer => {
        const option = new Option(`${customer.name} (${customer.phone})`, customer.id, false, false);
        $('#customer').append(option);
    });
    $('#customer').trigger('change');

    // Theme Toggle
    $('#themeToggle').on('click', function() {
        const html = $('html');
        const currentTheme = html.attr('data-theme') === 'dark' ? 'light' : 'dark';
        html.attr('data-theme', currentTheme);
        localStorage.setItem('theme', currentTheme);
        $(this).html(currentTheme === 'dark' ? '☀️' : '🌙');
    });

    const savedTheme = localStorage.getItem('theme') || 'light';
    $('html').attr('data-theme', savedTheme);
    $('#themeToggle').html(savedTheme === 'dark' ? '☀️' : '🌙');

    // Image Preview
    function previewImages(event) {
        const files = event.target.files;
        const previewsContainer = $('#imagePreviews').empty();
        if (files.length > 0) {
            Array.from(files).forEach(file => {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: `${file.name} exceeds 2MB limit.`,
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = $('<img>').attr('src', e.target.result).addClass('w-16 h-16 object-cover rounded-lg');
                    previewsContainer.append(img);
                };
                reader.readAsDataURL(file);
            });
            previewsContainer.show();
        } else {
            previewsContainer.hide();
        }
    }

    // Image Modal
    function openImageModal(src) {
        Swal.fire({
            imageUrl: src,
            imageAlt: 'Chat Image',
            showConfirmButton: false,
            showCloseButton: true,
            background: 'rgba(0, 0, 0, 0.9)',
            padding: '0',
        });
    }

    // Scroll to Bottom
    function scrollToBottom() {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Copy to Clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Text copied to clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to copy text.',
                confirmButtonText: 'OK'
            });
        });
    }

    // Message Reactions
    function showReactionPicker(chatId) {
        Swal.fire({
            title: 'Add Reaction',
            html: `
                <button class="reaction-btn" data-emoji="👍">👍</button>
                <button class="reaction-btn" data-emoji="❤️">❤️</button>
                <button class="reaction-btn" data-emoji="😊">😊</button>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            didOpen: () => {
                $('.reaction-btn').on('click', function() {
                    const emoji = $(this).data('emoji');
                    $.ajax({
                        url: '{{ route("admin.internal_chat.addReaction") }}',
                        type: 'POST',
                        data: { chat_id: chatId, emoji: emoji, _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                const reactionSpan = $(`.chat-message[data-id="${chatId}"] .reaction[data-emoji="${emoji}"]`);
                                if (reactionSpan.length) {
                                    reactionSpan.text(`${emoji} (${response.count})`);
                                } else {
                                    $(`.chat-message[data-id="${chatId}"] .message-reactions`).prepend(`<span class="reaction" data-emoji="${emoji}">${emoji} (${response.count})</span>`);
                                }
                                Swal.close();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.error || 'Failed to add reaction.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            }
        });
    }

    // Edit Message (AJAX)
    $(document).on('click', '.edit-message', function() {
        const chatId = $(this).data('id');
        $.ajax({
            url: `{{ route('admin.internal_chat.show', '') }}/${chatId}`,
            type: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function(chat) {
                const form = $('#editChatForm');
                form.attr('action', `{{ route('admin.internal_chat.update', '') }}/${chat.id}`);
                form.find('textarea[name="message"]').val(chat.message || '');
                document.getElementById('editChatModal').classList.add('modal-open');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.error || 'Failed to load message for editing.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Handle Edit Form Submission
    $('#editChatForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const messageContent = form.find('textarea[name="message"]').val().trim();
        const formData = new FormData(this);
        formData.set('message', messageContent);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            success: (response) => handleChatSuccess(response, 'PUT', messageContent, null),
            error: handleChatError
        });
    });

    // Delete Message (AJAX)
    $(document).on('click', '.delete-message', function() {
        const chatId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this message?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route("admin.internal_chat.delete", "") }}/${chatId}`,
                    type: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            $(`#chatMessages .chat-message[data-id="${chatId}"]`).fadeOut(300, function() {
                                $(this).remove();
                            });
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.error || 'Error deleting the message.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Context Menu
    $(document).on('click', '.chat-message', function(e) {
        e.preventDefault();
        $('.context-menu').addClass('hidden');
        const menu = $(this).find('.context-menu');
        menu.removeClass('hidden');
        const offset = $(this).offset();
        menu.css({
            top: e.pageY - offset.top + 10,
            left: $(this).hasClass('admin') ? 'auto' : e.pageX - offset.left + 10,
            right: $(this).hasClass('admin') ? 10 : 'auto'
        });
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.chat-message, .context-menu').length) {
            $('.context-menu').addClass('hidden');
        }
    });

    // Handle image paste
    const msgInput = document.getElementById('messageInput');
    document.addEventListener('paste', (e) => {
        if (e.target !== msgInput) return;
        e.preventDefault();
        const items = e.clipboardData.items;
        const imageInput = $('#image')[0];
        const dataTransfer = new DataTransfer();
        let hasImage = false;

        for (let item of items) {
            if (item.kind === 'file' && item.type.startsWith('image/')) {
                const file = item.getAsFile();
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: `${file.name || 'Pasted image'} exceeds 2MB limit.`,
                        confirmButtonText: 'OK'
                    });
                    continue;
                }
                const extension = file.type.split('/')[1] || 'png';
                const renamedFile = new File([file], `pasted_image_${Date.now()}_${Math.random().toString(36).slice(2, 7)}.${extension}`, { type: file.type });
                dataTransfer.items.add(renamedFile);
                hasImage = true;
            } else if (item.kind === 'string' && item.type === 'text/plain') {
                item.getAsString((text) => {
                    const start = msgInput.selectionStart;
                    const end = msgInput.selectionEnd;
                    msgInput.value = msgInput.value.slice(0, start) + text + msgInput.value.slice(end);
                    msgInput.selectionStart = msgInput.selectionEnd = start + text.length;
                });
            }
        }

        if (hasImage) {
            imageInput.files = dataTransfer.files;
            previewImages({ target: { files: dataTransfer.files } });
        }
    });

    // Auto-resize Textarea
    $('#messageInput').on('input', function() {
        this.style.height = '40px';
        this.style.height = `${Math.min(this.scrollHeight, 80)}px`;
    });

    // Accessibility: Tab Navigation
    $('.chat-message').on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).trigger('click');
        }
    });

    // Escape to Close Modals
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            Swal.close();
            $('.context-menu').addClass('hidden');
            document.getElementById('editChatModal').classList.remove('modal-open');
        }
    });

    // Real-time messages via Laravel Echo
    Echo.channel('internal-chat').listen('ChatMessageSent', (e) => {
        const chat = e.chat;
        if (chat.admin_id == CURRENT_ADMIN_ID) return; // Prevent echo

        const timestamp = new Date(chat.created_at).toLocaleString('en-US', {
            month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true
        });
        appendMessageToChat(
            {
                id: chat.id,
                customer_name: chat.customer ? chat.customer.name : 'Unknown Customer',
                admin_name: chat.admin ? chat.admin.name : 'Unknown Admin',
                image_paths: chat.image_paths || []
            },
            chat.message || '',
            chat.customer_id,
            timestamp
        );
    });

    // Search Messages
    $('#searchInput').on('input', debounce(function() {
        const query = $(this).val();
        $.ajax({
            url: '{{ route("admin.internal_chat.search") }}',
            data: { q: query },
            success: function(response) {
                $('#chatMessages').html($(response).find('#chatMessages').html());
                scrollToBottom();
            }
        });
    }, 300));

    // Customer Search (Client-side for simplicity)
    $('#customerSearch').on('input', function() {
        const query = $(this).val().toLowerCase();
        $('.customer-list > div > div').each(function() {
            const name = $(this).find('p.font-semibold').text().toLowerCase();
            const phone = $(this).find('p.text-sm').text().toLowerCase();
            $(this).parent().toggle(name.includes(query) || phone.includes(query));
        });
    });

    // Load More Messages
    let page = 2; // Start from page 2 as page 1 is already loaded
    let loading = false;
    let allLoaded = false;

    function loadMoreMessages() {
        if (loading || allLoaded) return;
        loading = true;
        $('#loadMoreBtn').text('Loading...');

        $.ajax({
            url: '{{ route("admin.internal_chat.loadMore") }}',
            data: { page: page },
            success: function(response) {
                const newMessages = $(response).find('#chatMessages').html();
                if (!newMessages.trim()) {
                    $('#loadMoreBtn').text('No More Messages').prop('disabled', true);
                    allLoaded = true;
                } else {
                    $('#chatMessages').prepend(newMessages);
                    $('#loadMoreBtn').text('Load More Messages');
                    page++;
                }
                loading = false;
            },
            error: function() {
                $('#loadMoreBtn').text('Error. Retry?');
                loading = false;
            }
        });
    }

    function selectCustomer(id, name) {
        $('#customer').val(id).trigger('change');
        $('.conversation-pane h1').text(`Chat with ${name}`);
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;')
                 .replace(/</g, '&lt;')
                 .replace(/>/g, '&gt;');
    }

    function strip_tags(str) {
        return str.replace(/<\/?[^>]+(>|$)/g, "");
    }

    function debounce(func, wait) {
        let timeout;
        return function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, arguments), wait);
        };
    }

    // Initial Scroll and Focus
    $(document).ready(function() {
        scrollToBottom();
        $('#messageInput').focus();
    });
</script>
@endsection