<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CampaignInsight;

class AdsInsightsController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $usdRate = $customer->usd_rate ?? 'N/A';

        $results = CampaignInsight::where('customer_id', $customer->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('auth.adsinsights', compact('customer', 'usdRate', 'results'));
    }

    public function fetchInsights(Request $request, $customerId)
    {
        // API fetch logic here
        // Aba yasla normally Facebook fetch ya mock data banaunu sakinchha

        // Dummy success
        return redirect()->route('portal.adsinsights')->with('success', 'Insights fetched successfully.');
    }
    public function deletePortalInsight($id)
{
    $insight = \App\Models\CampaignInsight::find($id);
    if ($insight) {
        $insight->delete();
        return redirect()->route('portal.adsinsights')->with('success', 'Insight deleted successfully.');
    }
    return redirect()->route('portal.adsinsights')->with('error', 'Insight not found.');
}

}
