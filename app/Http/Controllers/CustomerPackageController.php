<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerPackageController extends Controller
{
    // Assigned packages JSON (Customer details page ले खिच्ने)
    public function list($customerId)
{
    // 👇 यहाँ $id होइन, $customerId प्रयोग गर
    $customer = \App\Models\Customer::with(['packages' => function ($q) {
        // pivot fields eager-load हुनुपर्छ (नत्र pivot null देखिन सक्छ)
        $q->withPivot(['start_date', 'end_date', 'status'])->orderBy('name');
    }])->findOrFail($customerId);

    $data = $customer->packages->map(function ($p) {
        return [
            'id'         => $p->id,
            'name'       => $p->name,
            'code'       => $p->code,
            'price'      => $p->price,
            'is_popular' => (bool) $p->is_popular,
            'active'     => (bool) $p->active,
            'pivot'      => [
                'start_date' => $p->pivot?->start_date ? (string) $p->pivot->start_date : null,
                'end_date'   => $p->pivot?->end_date ? (string) $p->pivot->end_date : null,
                'status'     => $p->pivot?->status,
            ],
        ];
    })->values();

    return response()
        ->json(['data' => $data])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache');
}

    // Single वा multiple package assign
    public function assign(Request $req, Customer $customer)
    {
        $validated = $req->validate([
            'package_ids'   => 'required|array|min:1',
            'package_ids.*' => 'integer|exists:packages,id',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'status'        => 'nullable|in:active,paused,completed',
        ]);

        $payload = [];
        foreach ($validated['package_ids'] as $pid) {
            $payload[$pid] = [
                'start_date' => $validated['start_date'] ?? null,
                'end_date'   => $validated['end_date'] ?? null,
                'status'     => $validated['status'] ?? 'active',
            ];
        }

        $customer->packages()->syncWithoutDetaching($payload);

        if ($req->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('status', 'Packages assigned.');
    }

    // Assigned package को date/status अपडेट
    public function update(Request $req, Customer $customer, $packageId)
    {
        $validated = $req->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|in:active,paused,completed',
        ]);

        $customer->packages()->updateExistingPivot($packageId, $validated);
        return response()->json(['ok' => true]);
    }

    // Assigned package हटाउने
    public function destroy(Customer $customer, $packageId)
    {
        $customer->packages()->detach($packageId);
        return response()->json(['ok' => true]);
    }
}
