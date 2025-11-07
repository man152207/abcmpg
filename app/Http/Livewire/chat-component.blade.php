<div>
    <div id="chatPopup" class="chat-popup">
        <div class="chat-header">
            <strong>Internal Chat</strong>
            <button type="button" onclick="document.getElementById('chatPopup').style.display='none'">×</button>
        </div>
        <div class="chat-body" id="chatBody">
            @try
    @if($messages->isNotEmpty())
        @foreach($messages as $msg)
            <div class="mb-2">
                <small class="text-muted">{{ $msg->created_at->format('M d, h:i A') }}</small>
                <div class="px-3 py-2 rounded {{ $msg->admin_id ? 'bg-primary text-white' : 'bg-white border' }}">
                    {!! nl2br(e($msg->message)) !!}
                    @if($msg->image_paths)
                        @foreach($msg->image_paths as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Chat Image" style="max-width: 100%; margin-top: 5px;">
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p>No messages yet.</p>
    @endif
@catch(\Exception $e)
    <p>Error: {{ $e->getMessage() }}</p>
@endtry
        </div>
        <form wire:submit.prevent="send">
            <input type="hidden" wire:model="customer_id">
            <textarea wire:model.defer="body" placeholder="Type a message..." class="form-control"></textarea>
            <input type="file" wire:model="images" multiple accept="image/*" style="margin-top: 5px;">
            <button type="submit" class="btn btn-primary mt-2">Send</button>
        </form>
    </div>
</div>

<style>
.chat-popup {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 30px;
    width: 350px;
    height: 500px;
    background: white;
    border: 1px solid #ccc;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    z-index: 9999;
    border-radius: 10px;
    overflow: hidden;
}
.chat-header {
    background: #1e90ff;
    color: white;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.chat-body {
    height: 380px;
    overflow-y: auto;
    padding: 10px;
}
.chat-body::-webkit-scrollbar {
    width: 6px;
}
.chat-body::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}
form {
    padding: 10px;
    border-top: 1px solid #eee;
}
textarea {
    resize: none;
    height: 60px;
}
</style>