<?php

namespace App\Http\Controllers\Admin\Smmx;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SmmxOnboarding;
use Illuminate\Http\Request;

class SmmxOnboardingController extends Controller
{
    public function index()
    {
        $items = SmmxOnboarding::with('customer')
            ->latest()
            ->paginate(15);

        return view('admin.smmx.onboarding.index', compact('items'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $packages = class_exists(\App\Models\Package::class)
            ? \App\Models\Package::where('active', 1)->orderBy('price')->get()
            : collect();

        return view('admin.smmx.onboarding.create', compact('customers', 'packages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|integer',
            'package_id' => 'nullable|integer',
            'business_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string',
            'facebook_link' => 'nullable|string|max:255',
            'instagram_link' => 'nullable|string|max:255',
            'tiktok_link' => 'nullable|string|max:255',
            'website_link' => 'nullable|string|max:255',
            'page_access_status' => 'nullable|string|max:255',
            'business_manager_status' => 'nullable|string|max:255',
            'primary_goal' => 'nullable|string|max:255',
            'target_location' => 'nullable|string|max:255',
            'target_age_group' => 'nullable|string|max:255',
            'target_gender' => 'nullable|string|max:255',
            'target_interests' => 'nullable|string',
            'competitors' => 'nullable|string',
            'brand_colors' => 'nullable|string|max:255',
            'preferred_language' => 'nullable|string|max:255',
            'content_preferences' => 'nullable|string',
            'monthly_budget' => 'nullable|string|max:255',
            'approval_required' => 'nullable|boolean',
            'approval_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        $data['approval_required'] = $request->has('approval_required');
        $data['created_by'] = auth('admin')->id();
        $data['status'] = $data['status'] ?? 'draft';

        SmmxOnboarding::create($data);

        return redirect()
            ->route('admin.smmx.onboarding.index')
            ->with('success', 'Social media onboarding created successfully.');
    }

    public function show($id)
    {
        $item = SmmxOnboarding::with('customer', 'package')->findOrFail($id);

        return view('admin.smmx.onboarding.show', compact('item'));
    }

    public function edit($id)
    {
        $item = SmmxOnboarding::findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $packages = class_exists(\App\Models\Package::class)
            ? \App\Models\Package::where('active', 1)->orderBy('price')->get()
            : collect();

        return view('admin.smmx.onboarding.edit', compact('item', 'customers', 'packages'));
    }

    public function update(Request $request, $id)
    {
        $item = SmmxOnboarding::findOrFail($id);

        $data = $request->validate([
            'customer_id' => 'required|integer',
            'package_id' => 'nullable|integer',
            'business_name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string',
            'facebook_link' => 'nullable|string|max:255',
            'instagram_link' => 'nullable|string|max:255',
            'tiktok_link' => 'nullable|string|max:255',
            'website_link' => 'nullable|string|max:255',
            'page_access_status' => 'nullable|string|max:255',
            'business_manager_status' => 'nullable|string|max:255',
            'primary_goal' => 'nullable|string|max:255',
            'target_location' => 'nullable|string|max:255',
            'target_age_group' => 'nullable|string|max:255',
            'target_gender' => 'nullable|string|max:255',
            'target_interests' => 'nullable|string',
            'competitors' => 'nullable|string',
            'brand_colors' => 'nullable|string|max:255',
            'preferred_language' => 'nullable|string|max:255',
            'content_preferences' => 'nullable|string',
            'monthly_budget' => 'nullable|string|max:255',
            'approval_required' => 'nullable|boolean',
            'approval_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        $data['approval_required'] = $request->has('approval_required');

        $item->update($data);

        return redirect()
            ->route('admin.smmx.onboarding.index')
            ->with('success', 'Social media onboarding updated successfully.');
    }

    public function destroy($id)
    {
        $item = SmmxOnboarding::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('admin.smmx.onboarding.index')
            ->with('success', 'Social media onboarding deleted successfully.');
    }
}