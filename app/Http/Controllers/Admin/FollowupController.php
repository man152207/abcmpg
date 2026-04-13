<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmContact;
use App\Models\CrmFollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowupController extends Controller
{
    public function index(Request $request)
    {
        // Blade page मात्र load; data AJAX बाट
        return view('admin.followups.index');
    }

    public function data(Request $request)
{
    $q = CrmContact::query()
        ->with('latestFollowUp')
        ->leftJoin('admins', 'admins.id', '=', 'crm_contacts.assigned_to')
        ->select('crm_contacts.*', 'admins.name as assigned_name')

        ->when($request->filled('status'), fn($x) => $x->whereIn('crm_contacts.status', (array)$request->status))

        ->when($request->filled('channel'), fn($x) =>
            $x->whereHas('latestFollowUp', fn($y) => $y->where('contact_channel', $request->channel))
        )

        ->when($request->filled('priority'), fn($x) => $x->where('crm_contacts.priority', $request->priority))
        ->when($request->filled('assignee'), fn($x) => $x->where('crm_contacts.assigned_to', $request->assignee))

        ->when($request->filled('search'), function ($x) use ($request) {
            $s = trim($request->search);
            $x->where(function ($z) use ($s) {
                $z->where('crm_contacts.name', 'like', "%$s%")
                  ->orWhere('crm_contacts.phone_primary', 'like', "%$s%")
                  ->orWhere('crm_contacts.city', 'like', "%$s%")
                  ->orWhere('crm_contacts.tags', 'like', "%$s%");
            });
        })

        ->when($request->filled('from'), fn($x) => $x->whereDate('crm_contacts.next_followup_at', '>=', $request->from))
        ->when($request->filled('to'), fn($x) => $x->whereDate('crm_contacts.next_followup_at', '<=', $request->to))

        ->when($request->boolean('due_today'), fn($x) =>
            $x->whereDate('crm_contacts.next_followup_at', now()->toDateString())
        )
        ->when($request->boolean('overdue'), fn($x) =>
            $x->whereNotNull('crm_contacts.next_followup_at')->where('crm_contacts.next_followup_at', '<', now())
        );

    $now = now();

    // ✅ BEST: New top + Overdue next + then rest, all newest-first inside group
    $contacts = $q
        ->orderByRaw("
            CASE
                WHEN crm_contacts.status = 'New' THEN 0
                WHEN crm_contacts.next_followup_at IS NOT NULL AND crm_contacts.next_followup_at < ? THEN 1
                WHEN crm_contacts.status = 'Follow-up Due' THEN 2
                WHEN crm_contacts.status = 'Warm' THEN 3
                WHEN crm_contacts.status = 'Negotiation' THEN 4
                WHEN crm_contacts.status = 'Won' THEN 5
                WHEN crm_contacts.status = 'Lost' THEN 6
                WHEN crm_contacts.status = 'Dormant' THEN 7
                ELSE 8
            END
        ", [$now])
        ->orderByDesc('crm_contacts.id')
        ->paginate(30);

    $counts = CrmContact::select('status', DB::raw('COUNT(*) as c'))
        ->groupBy('status')
        ->pluck('c', 'status');

    $admins = DB::table('admins')->select('id', 'name')->orderBy('name')->get();

    return response()->json([
        'ok'     => true,
        'data'   => $contacts,
        'counts' => $counts,
        'admins' => $admins,
    ]);
}

    public function storeContact(Request $request)
{
    if (!\Auth::guard('admin')->check()) {
        return response()->json(['ok' => false, 'msg' => 'Unauthenticated'], 401);
    }

    // अब New Lead बाट नाम र phone मात्रै आउँछ भने पनि ठीक — validate पनि हल्का
    $validated = $request->validate([
        'name' => 'nullable|string|max:190',
        'phone_primary' => 'required|string|max:30',
    ]);

    // === Phone normalization: +977 / 00977 / space/dash हटाएर 10-अंकको नेपाली mobile राख्ने ===
    $digits = preg_replace('/\D+/', '', $validated['phone_primary'] ?? '');
    if (strlen($digits) >= 10) {
        $last10 = substr($digits, -10);
        $normalized = ($last10[0] === '9') ? $last10 : $digits;
    } else {
        $normalized = $digits;
    }
    if (!$normalized || strlen($normalized) < 10) {
        return response()->json(['ok' => false, 'msg' => 'Invalid phone'], 422);
    }

    // === Duplicate रोक्ने: पहिले नै entry छ भने 409 conflict फिर्ता ===
    $existing = \App\Models\CrmContact::where('phone_primary', $normalized)->first();
    if ($existing) {
        return response()->json([
            'ok' => false,
            'msg' => 'This phone already exists. Duplicate entry blocked.',
            'contact' => $existing,
        ], 409);
    }

    $adminId = \Auth::guard('admin')->id();

    try {
        $contact = \DB::transaction(function () use ($validated, $adminId, $normalized) {
            return \App\Models\CrmContact::create([
                'name'          => $validated['name'] ?? null,
                'phone_primary' => $normalized,
                'status'        => 'New',
                'priority'      => 'Medium',
                'assigned_to'   => $adminId,
                'updated_by'    => $adminId,
                'created_by'    => $adminId,
            ]);
        });

        return response()->json(['ok' => true, 'msg' => 'Saved', 'contact' => $contact]);
    } catch (\Throwable $e) {
        \Log::error('storeContact error', ['e' => $e->getMessage()]);
        return response()->json(['ok' => false, 'msg' => 'Server error saving lead'], 500);
    }
}

    public function storeFollowup(Request $request)
    {
        $validated = $request->validate([
            'crm_contact_id' => 'required|exists:crm_contacts,id',
            'contact_channel' => 'required|in:WhatsApp,Messenger,Call,SMS',
            'planned_at' => 'nullable|date',
            'done_at' => 'nullable|date',
            'outcome' => 'nullable|in:No Answer,Interested,Not Now,Converted,Invalid,Other',
            'note' => 'nullable|string',
            'reminder_set' => 'nullable|boolean',
            'snooze_until' => 'nullable|date',
        ]);

        $adminId = Auth::guard('admin')->id();

        $fu = CrmFollowUp::create(array_merge($validated, [
            'created_by' => $adminId
        ]));

        // Update contact pipeline fields
        $contact = CrmContact::findOrFail($validated['crm_contact_id']);
        if (!empty($validated['done_at'])) {
            $contact->last_contact_at = $validated['done_at'];
        }
        if (!empty($validated['planned_at'])) {
            $contact->next_followup_at = $validated['planned_at'];
        }
        // Auto-status rule
        if (($validated['outcome'] ?? null) === 'Converted') {
            $contact->status = 'Won';
            $contact->next_followup_at = null;
        } elseif (($validated['outcome'] ?? null) === 'No Answer') {
            // push by +3 days if plan empty
            if (empty($validated['planned_at'])) {
                $contact->next_followup_at = now()->addDays(3);
            }
        } elseif (!empty($validated['planned_at'])) {
            $contact->status = 'Follow-up Due';
        }

        $contact->save();

        return response()->json(['ok' => true, 'msg' => 'Follow-up saved', 'followup' => $fu]);
    }

    public function updateInline(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:crm_contacts,id',
            'field' => 'required|string|in:status,priority,assigned_to,next_followup_at,notes_summary,tags',
            'value' => 'nullable'
        ]);

        $c = CrmContact::findOrFail($validated['id']);
        $field = $validated['field'];
        $val = $validated['value'];

        if ($field === 'next_followup_at' && $val) {
            $val = \Carbon\Carbon::parse($val);
        }

        $c->$field = $val;
        $c->save();

        return response()->json(['ok' => true, 'msg' => 'Updated']);
    }

    public function snooze(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:crm_contacts,id',
            'days' => 'required|integer|min:1|max:30'
        ]);

        $c = CrmContact::findOrFail($validated['id']);
        $c->next_followup_at = now()->addDays($validated['days']);
        $c->status = 'Follow-up Due';
        $c->save();

        return response()->json(['ok' => true, 'msg' => 'Snoozed', 'next_followup_at' => $c->next_followup_at]);
    }
}
