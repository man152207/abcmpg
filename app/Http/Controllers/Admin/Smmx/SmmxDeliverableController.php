<?php

namespace App\Http\Controllers\Admin\Smmx;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SmmxDeliverable;
use App\Models\SmmxOnboarding;
use Illuminate\Http\Request;

class SmmxDeliverableController extends Controller
{
    public function index()
    {
        $items = SmmxDeliverable::with(['customer', 'package'])
            ->orderByDesc('report_year')
            ->orderByDesc('report_month')
            ->latest()
            ->paginate(15);

        return view('admin.smmx.deliverables.index', compact('items'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $packages = class_exists(\App\Models\Package::class)
            ? \App\Models\Package::where('active', 1)->orderBy('price')->get()
            : collect();
        $onboardings = SmmxOnboarding::latest()->get();

        return view('admin.smmx.deliverables.create', compact('customers', 'packages', 'onboardings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|integer',
            'package_id' => 'nullable|integer',
            'onboarding_id' => 'nullable|integer',
            'report_month' => 'required|integer|min:1|max:12',
            'report_year' => 'required|integer|min:2000|max:2100',

            'posts_planned' => 'nullable|integer|min:0',
            'posts_completed' => 'nullable|integer|min:0',
            'graphics_planned' => 'nullable|integer|min:0',
            'graphics_completed' => 'nullable|integer|min:0',
            'reels_planned' => 'nullable|integer|min:0',
            'reels_completed' => 'nullable|integer|min:0',
            'stories_planned' => 'nullable|integer|min:0',
            'stories_completed' => 'nullable|integer|min:0',

            'ad_spend_planned' => 'nullable|numeric',
            'ad_spend_used' => 'nullable|numeric',
            'campaign_objective' => 'nullable|string|max:255',
            'approval_status' => 'nullable|string|max:255',

            'assigned_staff_text' => 'nullable|string',
            'canva_link' => 'nullable|string|max:255',
            'drive_link' => 'nullable|string|max:255',
            'final_link' => 'nullable|string|max:255',

            'planned_date' => 'nullable|date',
            'published_date' => 'nullable|date',

            'pending_items' => 'nullable|string',
            'next_action' => 'nullable|string',
            'notes' => 'nullable|string',
            'report_sent' => 'nullable|boolean',
            'status' => 'nullable|string|max:50',
        ]);

        $data['assigned_staff'] = $this->parseLines($request->assigned_staff_text);
        $data['asset_links'] = [
            'canva_link' => $request->canva_link,
            'drive_link' => $request->drive_link,
            'final_link' => $request->final_link,
        ];
        $data['report_sent'] = $request->has('report_sent');
        $data['status'] = $data['status'] ?? 'pending';

        unset($data['assigned_staff_text'], $data['canva_link'], $data['drive_link'], $data['final_link']);

        SmmxDeliverable::create($data);

        return redirect()
            ->route('admin.smmx.deliverables.index')
            ->with('success', 'Monthly deliverable created successfully.');
    }

    public function show($id)
    {
        $item = SmmxDeliverable::with(['customer', 'package', 'onboarding', 'report'])->findOrFail($id);

        return view('admin.smmx.deliverables.show', compact('item'));
    }

    public function edit($id)
    {
        $item = SmmxDeliverable::findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $packages = class_exists(\App\Models\Package::class)
            ? \App\Models\Package::where('active', 1)->orderBy('price')->get()
            : collect();
        $onboardings = SmmxOnboarding::latest()->get();

        return view('admin.smmx.deliverables.edit', compact('item', 'customers', 'packages', 'onboardings'));
    }

    public function update(Request $request, $id)
    {
        $item = SmmxDeliverable::findOrFail($id);

        $data = $request->validate([
            'customer_id' => 'required|integer',
            'package_id' => 'nullable|integer',
            'onboarding_id' => 'nullable|integer',
            'report_month' => 'required|integer|min:1|max:12',
            'report_year' => 'required|integer|min:2000|max:2100',

            'posts_planned' => 'nullable|integer|min:0',
            'posts_completed' => 'nullable|integer|min:0',
            'graphics_planned' => 'nullable|integer|min:0',
            'graphics_completed' => 'nullable|integer|min:0',
            'reels_planned' => 'nullable|integer|min:0',
            'reels_completed' => 'nullable|integer|min:0',
            'stories_planned' => 'nullable|integer|min:0',
            'stories_completed' => 'nullable|integer|min:0',

            'ad_spend_planned' => 'nullable|numeric',
            'ad_spend_used' => 'nullable|numeric',
            'campaign_objective' => 'nullable|string|max:255',
            'approval_status' => 'nullable|string|max:255',

            'assigned_staff_text' => 'nullable|string',
            'canva_link' => 'nullable|string|max:255',
            'drive_link' => 'nullable|string|max:255',
            'final_link' => 'nullable|string|max:255',

            'planned_date' => 'nullable|date',
            'published_date' => 'nullable|date',

            'pending_items' => 'nullable|string',
            'next_action' => 'nullable|string',
            'notes' => 'nullable|string',
            'report_sent' => 'nullable|boolean',
            'status' => 'nullable|string|max:50',
        ]);

        $data['assigned_staff'] = $this->parseLines($request->assigned_staff_text);
        $data['asset_links'] = [
            'canva_link' => $request->canva_link,
            'drive_link' => $request->drive_link,
            'final_link' => $request->final_link,
        ];
        $data['report_sent'] = $request->has('report_sent');

        unset($data['assigned_staff_text'], $data['canva_link'], $data['drive_link'], $data['final_link']);

        $item->update($data);

        return redirect()
            ->route('admin.smmx.deliverables.index')
            ->with('success', 'Monthly deliverable updated successfully.');
    }

    public function destroy($id)
    {
        $item = SmmxDeliverable::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('admin.smmx.deliverables.index')
            ->with('success', 'Monthly deliverable deleted successfully.');
    }

    private function parseLines(?string $text): array
    {
        if (!$text) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
    }
}