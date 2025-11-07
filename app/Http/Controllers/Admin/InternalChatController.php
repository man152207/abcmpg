<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\InternalChat;
use App\Models\Item;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InternalChatController extends Controller
{
    public function index()
    {
        $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();
        $chats = InternalChat::with('customer', 'reactions', 'admin') ->orderBy('created_at', 'asc') ->paginate(20);
        $items = Item::select('id', 'name')->get();

        return view('admin.chat.internal_chat', compact('customers', 'chats', 'items'));
    }

    public function store(Request $request)
{
    try {
        // Log::info('Starting InternalChat store', ['request' => $request->except('images'), 'has_files' => $request->hasFile('images')]);

        $data = $request->validate([
            'message' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data['customer_id'] = $request->filled('customer_id') ? $data['customer_id'] : null;
        $data['admin_id'] = auth('admin')->id();

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $uploadPath = storage_path('app/public/uploads/chats');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
                // Log::info('Created directory: ' . $uploadPath);
            }
            if (!is_writable($uploadPath)) {
                Log::error('Storage directory is not writable', ['path' => $uploadPath]);
                return response()->json([
                    'success' => false,
                    'error' => 'Storage directory is not writable. Please contact support.',
                ], 500);
            }

            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = 'uploads/chats/' . $filename;
                    $image->storeAs('public/uploads/chats', $filename);
                    if (Storage::disk('public')->exists($path)) {
                        $imagePaths[] = $path;
                        // Log::info('Image stored successfully', ['path' => $path]);
                    } else {
                        Log::error('Image storage failed', ['path' => $path]);
                        throw new \Exception('Failed to store image: ' . $image->getClientOriginalName());
                    }
                } else {
                    Log::error('Invalid image file', ['error' => $image->getError(), 'name' => $image->getClientOriginalName()]);
                    throw new \Exception('Invalid image file: ' . $image->getClientOriginalName());
                }
            }
        }

        $data['image_paths'] = $imagePaths;
        $chat = InternalChat::create($data);

        broadcast(new ChatMessageSent($chat));
        // Log::info('Chat message stored and broadcasted successfully', ['chat_id' => $chat->id]);

        $customerName = $data['customer_id'] ? Customer::find($data['customer_id'])?->name : null;

        return response()->json([
            'success' => true,
            'message' => 'Message saved successfully',
            'id' => $chat->id,
            'image_paths' => $imagePaths,
            'customer_name' => $customerName,
            'admin_name' => auth('admin')->user()->name,
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed in InternalChat store', ['errors' => $e->errors()]);
        $errorMessages = array_merge(...array_values($e->errors()));
        $errorMessage = 'Validation failed: ' . implode(', ', $errorMessages);
        if (array_key_exists('images.*', $e->errors())) {
            $errorMessage = 'Invalid or unsupported image file(s). Please ensure images are JPEG, PNG, JPG, GIF, or WEBP and under 2MB.';
        }
        return response()->json([
            'success' => false,
            'error' => $errorMessage,
        ], 422);
    } catch (\Exception $e) {
        Log::error('InternalChat store error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json([
            'success' => false,
            'error' => 'Server error: Unable to save message. ' . $e->getMessage(),
        ], 500);
    }
}

    public function edit($id)
    {
        try {
            $chat = InternalChat::with('customer', 'admin')->findOrFail($id);
            $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();
            $chats = InternalChat::with('customer', 'reactions', 'admin') ->orderBy('created_at', 'asc') ->paginate(20);
            $items = Item::select('id', 'name')->get();

            return view('admin.chat.internal_chat', compact('chat', 'customers', 'chats', 'items'));
        } catch (\Exception $e) {
            Log::error('InternalChat edit error', ['id' => $id, 'message' => $e->getMessage()]);
            return redirect()->route('admin.internal_chat')->with('error', 'Unable to load message for editing.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Log::info('Starting InternalChat update', ['id' => $id, 'request' => $request->except('images'), 'has_files' => $request->hasFile('images')]);

            $chat = InternalChat::findOrFail($id);

            $data = $request->validate([
                'message' => 'nullable|string',
                'customer_id' => 'nullable|exists:customers,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $data['customer_id'] = $request->filled('customer_id') ? $data['customer_id'] : null;
            $data['admin_id'] = auth('admin')->id();

            $imagePaths = $chat->image_paths ?? [];
            if ($request->hasFile('images')) {
                $uploadPath = storage_path('app/public/uploads/chats');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                    // Log::info('Created directory: ' . $uploadPath);
                }
                if (!is_writable($uploadPath)) {
                    Log::error('Storage directory is not writable', ['path' => $uploadPath]);
                    return response()->json([
                        'success' => false,
                        'error' => 'Storage directory is not writable. Please contact support.',
                    ], 500);
                }

                foreach ($imagePaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                        // Log::info('Deleted old image', ['path' => $path]);
                    }
                }
                $imagePaths = [];

                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                        $path = 'uploads/chats/' . $filename;
                        $image->storeAs('public/uploads/chats', $filename);
                        if (Storage::disk('public')->exists($path)) {
                            $imagePaths[] = $path;
                            // Log::info('Image stored successfully', ['path' => $path]);
                        } else {
                            Log::error('Image storage failed', ['path' => $path]);
                            throw new \Exception('Failed to store image: ' . $image->getClientOriginalName());
                        }
                    } else {
                        Log::error('Invalid image file', ['error' => $image->getError(), 'name' => $image->getClientOriginalName()]);
                        throw new \Exception('Invalid image file: ' . $image->getClientOriginalName());
                    }
                }
            }

            $data['image_paths'] = $imagePaths;
            $chat->update($data);

            broadcast(new ChatMessageSent($chat));

            $customerName = $data['customer_id'] ? Customer::find($data['customer_id'])?->name : null;

            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully',
                'id' => $chat->id,
                'image_paths' => $imagePaths,
                'customer_name' => $customerName,
                'admin_name' => auth('admin')->user()->name,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in InternalChat update', ['id' => $id, 'errors' => $e->errors()]);
            $errorMessages = array_merge(...array_values($e->errors()));
            $errorMessage = 'Validation failed: ' . implode(', ', $errorMessages);
            if (array_key_exists('images.*', $e->errors())) {
                $errorMessage = 'Invalid or unsupported image file(s). Please ensure images are JPEG, PNG, JPG, GIF, or WEBP and under 2MB.';
            }
            return response()->json([
                'success' => false,
                'error' => $errorMessage,
            ], 422);
        } catch (\Exception $e) {
            Log::error('InternalChat update error', ['id' => $id, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Server error: Unable to update message. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Log::info('Starting InternalChat destroy', ['id' => $id]);

            $chat = InternalChat::findOrFail($id);
            if ($chat->image_paths) {
                foreach ($chat->image_paths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                        // Log::info('Deleted image', ['path' => $path]);
                    }
                }
            }
            $chat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('InternalChat destroy error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Server error: Unable to delete message. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function searchCustomers(Request $request)
    {
        try {
            $query = $request->input('q');
            $customers = Customer::where('name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->select('id', 'name', 'phone')
                ->orderBy('name')
                ->get();

            return response()->json($customers);
        } catch (\Exception $e) {
            Log::error('Search customers error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Server error while searching customers.'], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('q');
            $chats = InternalChat::with('customer', 'reactions', 'admin')
                ->where('message', 'LIKE', "%$query%")
                ->orWhereHas('customer', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%")->orWhere('phone', 'LIKE', "%$query%");
                })
                ->latest()
                ->paginate(20);

            return view('admin.chat.internal_chat', [
                'chats' => $chats,
                'customers' => Customer::select('id', 'name', 'phone')->orderBy('name')->get(),
                'items' => Item::select('id', 'name')->get(),
            ]);
        } catch (\Exception $e) {
            Log::error('Search chats error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Server error while searching chats.'], 500);
        }
    }

    public function filter(Request $request)
    {
        try {
            $chats = InternalChat::with('customer', 'reactions', 'admin')
                ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
                ->latest()
                ->paginate(20);

            return view('admin.chat.internal_chat', [
                'chats' => $chats,
                'customers' => Customer::select('id', 'name', 'phone')->orderBy('name')->get(),
                'items' => Item::select('id', 'name')->get(),
            ]);
        } catch (\Exception $e) {
            Log::error('Filter chats error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Server error while filtering chats.'], 500);
        }
    }

    public function loadMore(Request $request)
    {
        try {
            $chats = InternalChat::with('customer', 'reactions', 'admin')->latest()->paginate(20, ['*'], 'page', $request->page);

            return view('admin.chat.internal_chat', [
                'chats' => $chats,
                'customers' => Customer::select('id', 'name', 'phone')->orderBy('name')->get(),
                'items' => Item::select('id', 'name')->get(),
            ]);
        } catch (\Exception $e) {
            Log::error('Load more chats error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Server error while loading more chats.'], 500);
        }
    }

    public function addReaction(Request $request)
    {
        try {
            $validated = $request->validate([
                'chat_id' => 'required|exists:internal_chats,id',
                'emoji' => 'required|string|in:👍,❤️,😊',
            ]);

            $reaction = Reaction::firstOrNew([
                'chat_id' => $validated['chat_id'],
                'emoji' => $validated['emoji'],
            ]);
            $reaction->count = ($reaction->count ?? 0) + 1;
            $reaction->save();

            return response()->json([
                'success' => true,
                'count' => $reaction->count,
                'message' => 'Reaction added.',
            ]);
        } catch (\Exception $e) {
            Log::error('Add reaction error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Server error: Unable to add reaction. ' . $e->getMessage(),
            ], 500);
        }
    }
    public function show($id)
{
    $chat = InternalChat::with(['admin', 'customer'])->find($id);

    if (!$chat) {
        return response()->json(['error' => 'Chat message not found.'], 404);
    }

    return response()->json($chat);
}

}