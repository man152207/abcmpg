<?php

namespace App\Http\Livewire;

use App\Models\InternalChat;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Events\ChatMessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChatComponent extends Component
{
    use WithFileUploads;

    public $body;
    public $customer_id;
    public $images = [];
    public $messages;

    /**
     * Mount the component with the given customer_id
     *
     * @param mixed $customer_id
     * @return void
     */
    public function mount($customer_id = null)
    {
        $this->customer_id = $customer_id;
        Log::info('Mounting ChatComponent with customer_id: ' . $this->customer_id);

        try {
            $this->loadMessages();
            Log::info('Messages loaded successfully. Count: ' . ($this->messages ? $this->messages->count() : 'null'));
        } catch (\Exception $e) {
            Log::error('Error loading messages in ChatComponent: ' . $e->getMessage());
        }

        $this->dispatchBrowserEvent('chat-component-initialized');
    }

    /**
     * Load messages based on customer_id
     *
     * @return void
     */
    public function loadMessages()
    {
        try {
            $this->messages = InternalChat::with(['customer', 'admin', 'reactions'])
                ->when($this->customer_id, fn($q) => $q->where('customer_id', $this->customer_id))
                ->latest()
                ->take(20)
                ->get();

            Log::info('Query result for messages: ' . json_encode($this->messages->toArray()));
        } catch (\Exception $e) {
            Log::error('Error fetching messages: ' . $e->getMessage());
            $this->messages = collect(); // Empty collection if error occurs
        }
    }

    /**
     * Send a new chat message
     *
     * @return void
     */
    public function send()
{
    try {
        $data = $this->validate([
            'body' => 'nullable|string|max:1000',
            'customer_id' => 'nullable|exists:customers,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imagePaths = [];
        if (!empty($this->images)) {
            foreach ($this->images as $index => $image) {
                try {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('uploads/chats', $filename, 'public');

                    if ($path && Storage::disk('public')->exists($path)) {
                        $imagePaths[] = $path;
                        Log::info('Image stored successfully: ' . $path);
                    } else {
                        Log::warning('Failed to store image at index ' . $index . ': ' . $filename);
                        throw new \Exception('Image storage failed for ' . $filename);
                    }
                } catch (\Exception $e) {
                    Log::error('Image upload error: ' . $e->getMessage());
                }
            }
        }

        $adminId = auth('admin')->id();
        if (!$adminId) {
            Log::warning('No admin authenticated while sending message');
            throw new \Exception('Admin authentication required');
        }

        $chat = InternalChat::create([
            'message' => $this->body,
            'customer_id' => $this->customer_id,
            'admin_id' => $adminId,
            'image_paths' => $imagePaths,
        ]);

        broadcast(new ChatMessageSent($chat))->toOthers();
        Log::info('Chat message stored and broadcasted successfully', ['chat_id' => $chat->id]);

        $this->reset(['body', 'images']);
        $this->loadMessages();
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed while sending message: ' . json_encode($e->errors()));
        $this->addError('validation', 'Validation failed. Please check your input.');
    } catch (\Exception $e) {
        Log::error('Error sending message: ' . $e->getMessage());
        $this->addError('send', 'Failed to send message. Please try again.');
    }
}
    public function render()
    {
        return view('livewire.chat-component');
    }
}