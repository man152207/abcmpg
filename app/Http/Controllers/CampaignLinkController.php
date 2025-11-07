<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignLink;
use App\Models\Customer;
use Carbon\Carbon;

class CampaignLinkController extends Controller
{
    /**
     * Display the link management page for a specific customer.
     */
    public function linkStoreRoom($customer_id = null)
    {
        $customer = Customer::find($customer_id);

        // Start with a query builder instance
        $query = CampaignLink::query();

        if ($customer) {
            $query->where('customer_id', $customer_id);
        } else {
            // If no customer, return an empty paginated result
            return view('admin.link-store-room', [
                'customer' => null,
                'campaignLinks' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1, [])
            ]);
        }

        // Handle date range filtering
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');

        if ($startDate && $endDate) {
            try {
                // Validate and parse dates using Carbon
                $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
                
                // Ensure end date is not before start date
                if ($end->gte($start)) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
            } catch (\Exception $e) {
                // Invalid date format; ignore the filter
                \Log::warning("Invalid date range provided: start_date={$startDate}, end_date={$endDate}");
            }
        }

        $campaignLinks = $query->orderBy('created_at', 'desc')->paginate(10);

        // Append query parameters to pagination links
        $campaignLinks->appends([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return view('admin.link-store-room', compact('customer', 'campaignLinks'));
    }

    /**
     * Store new campaign links.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'campaign_links' => 'required|array',
            'campaign_links.*' => 'required|url',
        ]);

        foreach ($request->campaign_links as $link) {
            CampaignLink::create([
                'customer_id' => $request->customer_id,
                'campaign_link' => $link,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Links added successfully.'
        ]);
    }

    /**
     * Update an existing campaign link.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'campaign_link' => 'required|url',
        ]);

        $campaignLink = CampaignLink::findOrFail($id);
        $campaignLink->update([
            'campaign_link' => $request->campaign_link,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Campaign link updated successfully.'
        ]);
    }

    /**
     * Delete multiple campaign links.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:campaign_links,id',
        ]);

        CampaignLink::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected links deleted successfully.'
        ]);
    }

    /**
     * Delete a single campaign link.
     */
    public function destroy($id)
    {
        $link = CampaignLink::findOrFail($id);
        $link->delete();

        return response()->json([
            'success' => true,
            'message' => 'Link deleted successfully.'
        ]);
    }

    /**
     * Fetch all campaign links for a customer.
     */
    public function fetchLinks($customer_id)
    {
        $links = CampaignLink::where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'links' => $links,
        ]);
    }
}