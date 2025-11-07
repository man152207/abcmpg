<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Multimedia;
use App\Models\Customer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MultimediaController extends Controller
{
    public function index(Request $req)
    {
        $q = Multimedia::query();

        // Filters
        if ($req->filled('status')) {
            $q->where('status', $req->status);
        }
        if ($req->filled('project_type')) {
            $q->where('project_type', $req->project_type);
        }
        if ($req->filled('priority')) {
            $q->where('priority', $req->priority);
        }
        if ($req->filled('from')) {
            $q->whereDate('date', '>=', $req->from);
        }
        if ($req->filled('to')) {
            $q->whereDate('date', '<=', $req->to);
        }
        if ($req->filled('search')) {
            $s = $req->search;
            $q->where(function ($w) use ($s) {
                $w->where('customer_name', 'like', "%$s%")
                  ->orWhere('project', 'like', "%$s%")
                  ->orWhere('whatsapp', 'like', "%$s%");
            });
        }

        $items = $q->with('assignedTo')->latest('created_at')->paginate(20)->withQueryString();

        // Fetch customers and admins for dropdowns
        $customers = Customer::select('phone', 'name', 'display_name')->get();
        $admins = Admin::select('id', 'name')->get();

        return view('admin.multimedia', compact('items', 'customers', 'admins'));
    }

    public function show($id)
    {
        $row = Multimedia::with('assignedTo')->findOrFail($id);
        return response()->json($row);
    }

    public function getCustomer(Request $req)
    {
        $req->validate([
            'phone' => 'required|string|max:30'
        ]);

        $customer = Customer::where('phone', $req->phone)->first();
        return response()->json([
            'customer_name' => $customer ? ($customer->display_name ?? $customer->name) : null
        ]);
    }

    public function save(Request $req)
    {
        $data = $req->validate([
            'id' => ['nullable', 'integer', 'exists:multimedia,id'],
            'date' => ['required', 'date'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'customer_name' => ['required', 'string', 'max:255'],
            'project' => ['required', 'string'],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed', 'on_hold'])],
            'project_by' => ['nullable', 'string', 'max:255'],
            'project_type' => ['required', Rule::in(['Graphics', 'Video'])],
            'notes' => ['nullable', 'string'],
            'asset_link' => ['nullable', 'url', 'max:2048'],
            'asset_provider' => ['required_if:status,completed', Rule::in(['Drive', 'Dropbox', 'OneDrive', 'YouTube', 'Vimeo', 'Other'])],
            'asset_access' => ['required_if:status,completed', Rule::in(['view_only', 'comment', 'edit'])],
            'asset_type' => ['required_if:status,completed', Rule::in(['Image', 'Video', 'PSD/AI', 'Doc', 'Other'])],
            'asset_version' => ['nullable', 'string', 'max:20'],
            'asset_size_mb' => ['nullable', 'numeric', 'min:0'],
            'client_id' => ['nullable', 'integer'],
            'assigned_to' => ['nullable', 'integer', 'exists:admins,id'],
            'priority' => ['required', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'due_date' => ['nullable', 'date', 'after_or_equal:date'],
            'platforms' => ['nullable', 'array'],
            'platforms.*' => ['string', 'max:30'],
            'caption_link' => ['nullable', 'url', 'max:2048'],
            'publish_url' => ['nullable', 'url', 'max:2048'],
            'revision_count' => ['nullable', 'integer', 'min:0'],
            'approved_by_client' => ['nullable', 'boolean'],
            'qa_checked' => ['nullable', 'boolean'],
            'billing_code' => ['nullable', 'string', 'max:50'],
            'estimate_hours' => ['nullable', 'numeric', 'min:0'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],
            'cost_npr' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Normalize booleans
        $data['approved_by_client'] = (bool) ($req->filled('approved_by_client'));
        $data['qa_checked'] = (bool) ($req->filled('qa_checked'));

        // Set created_by and updated_by
        $data['created_by'] = $data['created_by'] ?? auth('admin')->id();
        $data['updated_by'] = auth('admin')->id();

        // Handle platforms as comma-separated string
        if (isset($data['platforms'])) {
            $data['platforms'] = implode(',', $data['platforms']);
        }

        if (!empty($data['id'])) {
            $row = Multimedia::findOrFail($data['id']);
            $row->update($data);
            $msg = 'Updated successfully.';
        } else {
            // Default values
            $data['asset_provider'] = $data['asset_provider'] ?? 'Drive';
            $data['asset_type'] = $data['asset_type'] ?? 'Other';
            $data['priority'] = $data['priority'] ?? 'normal';
            $row = Multimedia::create($data);
            $msg = 'Created successfully.';
        }

        return redirect()->route('admin.multimedia.index')->with('success', $msg);
    }

    public function destroy($id)
    {
        Multimedia::findOrFail($id)->delete();
        return back()->with('success', 'Deleted successfully.');
    }
}