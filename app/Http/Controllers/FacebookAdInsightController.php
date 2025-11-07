<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CampaignInsight;
use App\Models\AdSetInsight;
use App\Models\AdInsight;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class FacebookAdInsightController extends Controller
{
    /**
     * Remove emojis and special Unicode characters from text.
     */
    private function removeEmoji($text)
    {
        if (class_exists('Normalizer')) {
            $text = \Normalizer::normalize($text, \Normalizer::FORM_C);
        }

        $regex = '/[\x{1F600}-\x{1F64F}' .  // Emoticons
                 '\x{1F300}-\x{1F5FF}' .   // Symbols & Pictographs
                 '\x{1F680}-\x{1F6FF}' .   // Transport & Map
                 '\x{1F1E0}-\x{1F1FF}' .   // Flags
                 '\x{1F900}-\x{1F9FF}' .   // Supplemental Symbols
                 '\x{1FA70}-\x{1FAFF}' .   // Extended-A
                 '\x{1D400}-\x{1D7FF}' .   // Math Alphanumeric
                 '\x{2460}-\x{24FF}' .     // Enclosed Alphanumerics
                 '\x{2600}-\x{26FF}' .     // Misc Symbols
                 '\x{2300}-\x{23FF}' .     // Misc technical
                 ']/u';

        return preg_replace($regex, '', $text);
    }

    /**
     * Update campaign name.
     */
    public function updateCampaignName(Request $request, $id)
    {
        $insight = CampaignInsight::findOrFail($id);
        $insight->campaign_name = $request->input('campaign_name');
        $insight->save();

        return $insight->campaign_name;
    }

    /**
     * Update ad set name.
     */
    public function updateAdSetName(Request $request, $id)
    {
        $insight = AdSetInsight::findOrFail($id);
        $insight->adset_name = $request->input('adset_name');
        $insight->save();

        return $insight->adset_name;
    }

    /**
     * Update ad name.
     */
    public function updateAdName(Request $request, $id)
    {
        $insight = AdInsight::findOrFail($id);
        $insight->ad_name = $request->input('ad_name');
        $insight->save();

        return $insight->ad_name;
    }

    /**
     * Delete an insight (campaign, ad set, or ad).
     */
    public function deleteInsight($id, $type = 'campaign')
    {
        $model = match ($type) {
            'adset' => AdSetInsight::class,
            'ad' => AdInsight::class,
            default => CampaignInsight::class,
        };

        $insight = $model::findOrFail($id);
        $customerId = $insight->customer_id;
        $insight->delete();

        return redirect()->route('insights.show', $customerId)->with('success', ucfirst($type) . ' deleted successfully.');
    }

    /**
     * Refetch an insight (campaign, ad set, or ad).
     */
    public function refetchInsight(Request $request, $id, $type = 'campaign')
    {
        try {
            $model = match ($type) {
                'adset' => AdSetInsight::class,
                'ad' => AdInsight::class,
                default => CampaignInsight::class,
            };

            $insight = $model::findOrFail($id);
            $identifier = match ($type) {
                'adset' => $insight->adset_id,
                'ad' => $insight->ad_id,
                default => $insight->campaign_id,
            };
            $customerId = $insight->customer_id;

            $insight->delete();

            $requestData = ['customer_id' => $customerId];
            if ($type === 'campaign') {
                $requestData['campaign_ids'] = $identifier;
            } elseif ($type === 'adset') {
                $requestData['adset_ids'] = $identifier;
            } else {
                $requestData['ad_ids'] = $identifier;
            }

            $response = $this->fetchInsights(new Request($requestData), $customerId);

            return $response;
        } catch (\Exception $e) {
            Log::error("Refetch insight failed: {$e->getMessage()}", ['id' => $id, 'type' => $type, 'customer_id' => $customerId]);
            return redirect()->route('insights.show', $customerId)->with('error', 'Failed to refetch ' . $type . '.');
        }
    }

    /**
     * Display insights for campaigns, ad sets, and ads.
     */
    public function showInsights($customerId = null)
    {
        $campaigns = $customerId
            ? CampaignInsight::where('customer_id', $customerId)->orderBy('created_at', 'desc')->get()
            : CampaignInsight::orderBy('created_at', 'desc')->get();

        $adsets = collect();
        $ads = collect();

        if (Schema::hasTable('adset_insights')) {
            $adsets = $customerId
                ? AdSetInsight::where('customer_id', $customerId)->orderBy('created_at', 'desc')->get()
                : AdSetInsight::orderBy('created_at', 'desc')->get();
            Log::info("Ad sets fetched for customer {$customerId}", ['count' => $adsets->count()]);
        } else {
            Log::warning("Table adset_insights does not exist");
        }

        if (Schema::hasTable('ad_insights')) {
            $ads = $customerId
                ? AdInsight::where('customer_id', $customerId)->orderBy('created_at', 'desc')->get()
                : AdInsight::orderBy('created_at', 'desc')->get();
            Log::info("Ads fetched for customer {$customerId}", ['count' => $ads->count()]);
        } else {
            Log::warning("Table ad_insights does not exist");
        }

        return view('customer.insights', compact('campaigns', 'adsets', 'ads', 'customerId'));
    }

    /**
     * Fetch insights from Meta API for campaigns, ad sets, and ads.
     */
     public function fetchInsightsFromApi(Request $request)
{
    return $this->fetchInsights($request);
}

    public function fetchInsights(Request $request, $customerId = null)
    {
        try {
            $customerId = $customerId ?? $request->input('customer_id');
            $campaignIds = $request->input('campaign_ids') ? array_map('trim', explode(',', $request->input('campaign_ids'))) : [];
            $adSetIds = $request->input('adset_ids') ? array_map('trim', explode(',', $request->input('adset_ids'))) : [];
            $adIds = $request->input('ad_ids') ? array_map('trim', explode(',', $request->input('ad_ids'))) : [];
            $accessToken = env('META_ACCESS_TOKEN');

            if (!$accessToken) {
                Log::error("META_ACCESS_TOKEN is not set in .env");
                return redirect()->route('insights.show', $customerId)->with('error', 'Meta API token is missing.');
            }

            if (empty($campaignIds) && empty($adSetIds) && empty($adIds)) {
                Log::warning("No IDs provided for fetching insights", ['customer_id' => $customerId]);
                return redirect()->route('insights.show', $customerId)->with('error', 'Please provide at least one Campaign, Ad Set, or Ad ID.');
            }

            $preferredTypes = [
                'onsite_conversion.messaging_conversation_started',
                'onsite_conversion.messaging_first_reply',
                'onsite_conversion.total_messaging_connection'
            ];

            // Process Campaign IDs
            foreach ($campaignIds as $campaignId) {
                Log::info("Fetching insights for campaign: {$campaignId}", ['customer_id' => $customerId]);

                // Fetch Campaign-level meta info
                $metaResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$campaignId}", [
                    'fields' => 'name,status,stop_time,daily_budget,lifetime_budget,bid_strategy'
                ]);

                if (!$metaResponse->successful() || !isset($metaResponse['name'])) {
                    Log::warning("Failed to fetch campaign meta: {$campaignId}", ['response' => $metaResponse->json()]);
                    continue;
                }

                $dataMeta = $metaResponse->json();
                $campaignName = $this->removeEmoji($dataMeta['name']);
                $now = Carbon::now();
                $stopTime = isset($dataMeta['stop_time']) ? Carbon::parse($dataMeta['stop_time']) : null;

                $deliveryStatus = $dataMeta['status'] ?? 'UNKNOWN';
                if ($dataMeta['status'] === 'ACTIVE' && $stopTime && $now->greaterThan($stopTime)) {
                    $deliveryStatus = 'COMPLETED';
                }

                $endDate = $stopTime ? $stopTime->format('Y-m-d') : null;
                $dailyBudget = $dataMeta['daily_budget'] ?? null;
                $lifetimeBudget = $dataMeta['lifetime_budget'] ?? null;
                $budget = $dailyBudget
                    ? '$' . number_format($dailyBudget / 100, 2) . ' Daily'
                    : ($lifetimeBudget ? '$' . number_format($lifetimeBudget / 100, 2) . ' Lifetime' : 'N/A');

                $bidStrategy = $dataMeta['bid_strategy'] ?? 'Unknown';

                // Fetch Campaign-level insights
                $campaignInsightResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$campaignId}/insights", [
                    'fields' => 'reach,impressions,spend,actions,date_start',
                    'limit' => 1
                ]);

                if (!$campaignInsightResponse->successful() || !isset($campaignInsightResponse['data'][0])) {
                    Log::warning("Failed to fetch campaign insights: {$campaignId}", ['response' => $campaignInsightResponse->json()]);
                    continue;
                }

                $data = $campaignInsightResponse['data'][0];
                $resultCount = 0;

                foreach ($data['actions'] ?? [] as $action) {
                    if (in_array($action['action_type'], $preferredTypes)) {
                        $resultCount = max($resultCount, (int) ($action['value'] ?? 0));
                    }
                }

                $start = $data['date_start'] ?? null;
                $stop = $endDate;
                $duration = ($start && $stop)
                    ? (Carbon::parse($start)->diffInDays(Carbon::parse($stop)) + 1) . ' days'
                    : 'Unknown';

                try {
                    CampaignInsight::updateOrCreate(
                        ['campaign_id' => $campaignId, 'customer_id' => $customerId],
                        [
                            'customer_id' => $customerId,
                            'campaign_name' => $campaignName,
                            'delivery' => strtoupper($deliveryStatus),
                            'actions' => json_encode($data['actions'] ?? []),
                            'bid_strategy' => $bidStrategy,
                            'budget' => $budget,
                            'last_edit' => now(),
                            'attribution_setting' => '7-day click or 1-day view',
                            'results' => $resultCount,
                            'reach' => $data['reach'] ?? null,
                            'impressions' => $data['impressions'] ?? null,
                            'cost_per_result' => ($resultCount > 0 && isset($data['spend'])) ? round($data['spend'] / $resultCount, 2) : null,
                            'spend' => $data['spend'] ?? null,
                            'ends' => $endDate,
                            'schedule' => $start ? ($start . ' - ' . $stop) : 'N/A',
                            'duration' => $duration,
                            'quality_rank' => 'N/A',
                            'engagement_rank' => 'N/A',
                            'conversion_rank' => 'N/A'
                        ]
                    );
                    Log::info("Campaign insight saved: {$campaignId}");
                } catch (\Exception $e) {
                    Log::error("Failed to save campaign insight: {$campaignId}", ['error' => $e->getMessage(), 'data' => $dataMeta]);
                    continue;
                }

                // Fetch Ad Sets
                $adSetsResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$campaignId}/adsets", [
                    'fields' => 'id,name,status,daily_budget,lifetime_budget,bid_strategy,start_time,end_time,updated_time'
                ]);

                if (!$adSetsResponse->successful() || !isset($adSetsResponse['data']) || empty($adSetsResponse['data'])) {
                    Log::warning("No ad sets found for campaign: {$campaignId}", ['response' => $adSetsResponse->json()]);
                    continue;
                }

                Log::info("Found " . count($adSetsResponse['data']) . " ad sets for campaign: {$campaignId}", ['ad_sets' => array_column($adSetsResponse['data'], 'id')]);

                foreach ($adSetsResponse['data'] as $adSet) {
                    $adSetId = $adSet['id'];
                    Log::info("Processing ad set: {$adSetId}");

                    // Fetch Ad Set insights
                    $adSetInsightResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adSetId}/insights", [
                        'fields' => 'reach,impressions,spend,actions,date_start',
                        'limit' => 1
                    ]);

                    if (!$adSetInsightResponse->successful() || !isset($adSetInsightResponse['data'][0])) {
                        Log::warning("Failed to fetch ad set insights: {$adSetId}", ['response' => $adSetInsightResponse->json()]);
                        continue;
                    }

                    $adSetData = $adSetInsightResponse['data'][0];
                    $adSetResultCount = 0;
                    foreach ($adSetData['actions'] ?? [] as $action) {
                        if (in_array($action['action_type'], $preferredTypes)) {
                            $adSetResultCount = max($adSetResultCount, (int) ($action['value'] ?? 0));
                        }
                    }

                    $adSetStart = isset($adSet['start_time']) ? Carbon::parse($adSet['start_time'])->format('Y-m-d') : ($adSetData['date_start'] ?? $start);
                    $adSetStop = isset($adSet['end_time']) ? Carbon::parse($adSet['end_time'])->format('Y-m-d') : $endDate;
                    $adSetDuration = ($adSetStart && $adSetStop)
                        ? (Carbon::parse($adSetStart)->diffInDays(Carbon::parse($adSetStop)) + 1) . ' days'
                        : 'Unknown';

                    $adSetBudget = isset($adSet['daily_budget'])
                        ? '$' . number_format($adSet['daily_budget'] / 100, 2) . ' Daily'
                        : (isset($adSet['lifetime_budget']) ? '$' . number_format($adSet['lifetime_budget'] / 100, 2) . ' Lifetime' : 'Using campaign budget');

                    try {
                        AdSetInsight::updateOrCreate(
                            ['adset_id' => $adSetId, 'customer_id' => $customerId],
                            [
                                'customer_id' => $customerId,
                                'campaign_id' => $campaignId,
                                'adset_name' => $this->removeEmoji($adSet['name']),
                                'delivery' => strtoupper($adSet['status'] ?? $deliveryStatus),
                                'actions' => json_encode($adSetData['actions'] ?? []),
                                'bid_strategy' => $adSet['bid_strategy'] ?? 'Using campaign bid strategy',
                                'budget' => $adSetBudget,
                                'last_edit' => $adSet['updated_time'] ?? now(),
                                'attribution_setting' => '7-day click or 1-day view',
                                'results' => $adSetResultCount,
                                'reach' => $adSetData['reach'] ?? null,
                                'impressions' => $adSetData['impressions'] ?? null,
                                'cost_per_result' => ($adSetResultCount > 0 && isset($adSetData['spend'])) ? round($adSetData['spend'] / $adSetResultCount, 2) : null,
                                'spend' => $adSetData['spend'] ?? null,
                                'ends' => $adSetStop,
                                'schedule' => $adSetStart ? ($adSetStart . ' - ' . $adSetStop) : 'N/A',
                                'duration' => $adSetDuration
                            ]
                        );
                        Log::info("Ad set insight saved: {$adSetId}");
                    } catch (\Exception $e) {
                        Log::error("Failed to save ad set insight: {$adSetId}", ['error' => $e->getMessage(), 'ad_set' => $adSet]);
                        continue;
                    }

                    // Fetch Ads
                    $adsResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adSetId}/ads", [
                        'fields' => 'id,name,status'
                    ]);

                    if (!$adsResponse->successful() || !isset($adsResponse['data']) || empty($adsResponse['data'])) {
                        Log::warning("No ads found for ad set: {$adSetId}", ['response' => $adsResponse->json()]);
                        continue; 
                    }

                    Log::info("Found " . count($adsResponse['data']) . " ads for ad set: {$adSetId}", ['ads' => array_column($adsResponse['data'], 'id')]);

                    foreach ($adsResponse['data'] as $ad) {
                        $adId = $ad['id'];
                        Log::info("Processing ad: {$adId}");

                        // Fetch Ad insights
                        $adInsightResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adId}/insights", [
                            'fields' => 'reach,impressions,spend,actions,quality_ranking,engagement_rate_ranking,conversion_rate_ranking,date_start',
                            'limit' => 1
                        ]);

                        if (!$adInsightResponse->successful() || !isset($adInsightResponse['data'][0])) {
                            Log::warning("Failed to fetch ad insights: {$adId}", ['response' => $adInsightResponse->json()]);
                            continue;
                        }

                        $adData = $adInsightResponse['data'][0];
                        $adResultCount = 0;
                        foreach ($adData['actions'] ?? [] as $action) {
                            if (in_array($action['action_type'], $preferredTypes)) {
                                $adResultCount = max($adResultCount, (int) ($action['value'] ?? 0));
                            }
                        }

                        try {
                            AdInsight::updateOrCreate(
                                ['ad_id' => $adId, 'customer_id' => $customerId],
                                [
                                    'customer_id' => $customerId,
                                    'campaign_id' => $campaignId,
                                    'adset_id' => $adSetId,
                                    'ad_name' => $this->removeEmoji($ad['name']),
                                    'delivery' => strtoupper($ad['status'] ?? $deliveryStatus),
                                    'actions' => json_encode($adData['actions'] ?? []),
                                    'bid_strategy' => 'Using campaign bid strategy',
                                    'budget' => 'Using campaign budget',
                                    'last_edit' => $adData['date_start'] ?? now(),
                                    'attribution_setting' => '7-day click or 1-day view',
                                    'results' => $adResultCount,
                                    'reach' => $adData['reach'] ?? null,
                                    'impressions' => $adData['impressions'] ?? null,
                                    'cost_per_result' => ($adResultCount > 0 && isset($adData['spend'])) ? round($adData['spend'] / $adResultCount, 2) : null,
                                    'spend' => $adData['spend'] ?? null,
                                    'ends' => $adSetStop,
                                    'schedule' => $adSetStart ? ($adSetStart . ' - ' . $adSetStop) : 'N/A',
                                    'duration' => $adSetDuration,
                                    'quality_rank' => $adData['quality_ranking'] ?? 'N/A',
                                    'engagement_rank' => $adData['engagement_rate_ranking'] ?? 'N/A',
                                    'conversion_rank' => $adData['conversion_rate_ranking'] ?? 'N/A'
                                ]
                            );
                            Log::info("Ad insight saved: {$adId}");
                        } catch (\Exception $e) {
                            Log::error("Failed to save ad insight: {$adId}", ['error' => $e->getMessage(), 'ad' => $ad]);
                            continue;
                        }
                    }
                }
            }

            // Process Ad Set IDs
            foreach ($adSetIds as $adSetId) {
                Log::info("Fetching insights for ad set: {$adSetId}", ['customer_id' => $customerId]);

                // Fetch Ad Set meta info
                $adSetResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adSetId}", [
                    'fields' => 'id,name,status,daily_budget,lifetime_budget,bid_strategy,start_time,end_time,updated_time,campaign_id'
                ]);

                if (!$adSetResponse->successful() || !isset($adSetResponse['id'])) {
                    Log::warning("Failed to fetch ad set meta: {$adSetId}", ['response' => $adSetResponse->json()]);
                    continue;
                }

                $adSet = $adSetResponse->json();
                $campaignId = $adSet['campaign_id'] ?? 'N/A';
                $adSetName = $this->removeEmoji($adSet['name']);
                $deliveryStatus = $adSet['status'] ?? 'UNKNOWN';
                $adSetStart = isset($adSet['start_time']) ? Carbon::parse($adSet['start_time'])->format('Y-m-d') : null;
                $adSetStop = isset($adSet['end_time']) ? Carbon::parse($adSet['end_time'])->format('Y-m-d') : null;
                $adSetDuration = ($adSetStart && $adSetStop)
                    ? (Carbon::parse($adSetStart)->diffInDays(Carbon::parse($adSetStop)) + 1) . ' days'
                    : 'Unknown';
                $adSetBudget = isset($adSet['daily_budget'])
                    ? '$' . number_format($adSet['daily_budget'] / 100, 2) . ' Daily'
                    : (isset($adSet['lifetime_budget']) ? '$' . number_format($adSet['lifetime_budget'] / 100, 2) . ' Lifetime' : 'N/A');

                // Fetch Ad Set insights
                $adSetInsightResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adSetId}/insights", [
                    'fields' => 'reach,impressions,spend,actions,date_start',
                    'limit' => 1
                ]);

                if (!$adSetInsightResponse->successful() || !isset($adSetInsightResponse['data'][0])) {
                    Log::warning("Failed to fetch ad set insights: {$adSetId}", ['response' => $adSetInsightResponse->json()]);
                    continue;
                }

                $adSetData = $adSetInsightResponse['data'][0];
                $adSetResultCount = 0;
                foreach ($adSetData['actions'] ?? [] as $action) {
                    if (in_array($action['action_type'], $preferredTypes)) {
                        $adSetResultCount = max($adSetResultCount, (int) ($action['value'] ?? 0));
                    }
                }

                try {
                    AdSetInsight::updateOrCreate(
                        ['adset_id' => $adSetId, 'customer_id' => $customerId],
                        [
                            'customer_id' => $customerId,
                            'campaign_id' => $campaignId,
                            'adset_name' => $adSetName,
                            'delivery' => strtoupper($deliveryStatus),
                            'actions' => json_encode($adSetData['actions'] ?? []),
                            'bid_strategy' => $adSet['bid_strategy'] ?? 'Unknown',
                            'budget' => $adSetBudget,
                            'last_edit' => $adSet['updated_time'] ?? now(),
                            'attribution_setting' => '7-day click or 1-day view',
                            'results' => $adSetResultCount,
                            'reach' => $adSetData['reach'] ?? null,
                            'impressions' => $adSetData['impressions'] ?? null,
                            'cost_per_result' => ($adSetResultCount > 0 && isset($adSetData['spend'])) ? round($adSetData['spend'] / $adSetResultCount, 2) : null,
                            'spend' => $adSetData['spend'] ?? null,
                            'ends' => $adSetStop,
                            'schedule' => $adSetStart ? ($adSetStart . ' - ' . $adSetStop) : 'N/A',
                            'duration' => $adSetDuration
                        ]
                    );
                    Log::info("Ad set insight saved: {$adSetId}");
                } catch (\Exception $e) {
                    Log::error("Failed to save ad set insight: {$adSetId}", ['error' => $e->getMessage(), 'ad_set' => $adSet]);
                    continue;
                }

                // Fetch Ads for this Ad Set
                $adsResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adSetId}/ads", [
                    'fields' => 'id,name,status'
                ]);

                if (!$adsResponse->successful() || !isset($adsResponse['data']) || empty($adsResponse['data'])) {
                    Log::warning("No ads found for ad set: {$adSetId}", ['response' => $adsResponse->json()]);
                    continue;
                }

                Log::info("Found " . count($adsResponse['data']) . " ads for ad set: {$adSetId}", ['ads' => array_column($adsResponse['data'], 'id')]);

                foreach ($adsResponse['data'] as $ad) {
                    $adId = $ad['id'];
                    Log::info("Processing ad: {$adId}");

                    // Fetch Ad insights
                    $adInsightResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adId}/insights", [
                        'fields' => 'reach,impressions,spend,actions,quality_ranking,engagement_rate_ranking,conversion_rate_ranking,date_start',
                        'limit' => 1
                    ]);

                    if (!$adInsightResponse->successful() || !isset($adInsightResponse['data'][0])) {
                        Log::warning("Failed to fetch ad insights: {$adId}", ['response' => $adInsightResponse->json()]);
                        continue;
                    }

                    $adData = $adInsightResponse['data'][0];
                    $adResultCount = 0;
                    foreach ($adData['actions'] ?? [] as $action) {
                        if (in_array($action['action_type'], $preferredTypes)) {
                            $adResultCount = max($adResultCount, (int) ($action['value'] ?? 0));
                        }
                    }

                    try {
                        AdInsight::updateOrCreate(
                            ['ad_id' => $adId, 'customer_id' => $customerId],
                            [
                                'customer_id' => $customerId,
                                'campaign_id' => $campaignId,
                                'adset_id' => $adSetId,
                                'ad_name' => $this->removeEmoji($ad['name']),
                                'delivery' => strtoupper($ad['status'] ?? $deliveryStatus),
                                'actions' => json_encode($adData['actions'] ?? []),
                                'bid_strategy' => 'Using campaign bid strategy',
                                'budget' => 'Using campaign budget',
                                'last_edit' => $adData['date_start'] ?? now(),
                                'attribution_setting' => '7-day click or 1-day view',
                                'results' => $adResultCount,
                                'reach' => $adData['reach'] ?? null,
                                'impressions' => $adData['impressions'] ?? null,
                                'cost_per_result' => ($adResultCount > 0 && isset($adData['spend'])) ? round($adData['spend'] / $adResultCount, 2) : null,
                                'spend' => $adData['spend'] ?? null,
                                'ends' => $adSetStop,
                                'schedule' => $adSetStart ? ($adSetStart . ' - ' . $adSetStop) : 'N/A',
                                'duration' => $adSetDuration,
                                'quality_rank' => $adData['quality_ranking'] ?? 'N/A',
                                'engagement_rank' => $adData['engagement_rate_ranking'] ?? 'N/A',
                                'conversion_rank' => $adData['conversion_rate_ranking'] ?? 'N/A'
                            ]
                        );
                        Log::info("Ad insight saved: {$adId}");
                    } catch (\Exception $e) {
                        Log::error("Failed to save ad insight: {$adId}", ['error' => $e->getMessage(), 'ad' => $ad]);
                        continue;
                    }
                }
            }

            // Process Ad IDs
            foreach ($adIds as $adId) {
                Log::info("Fetching insights for ad: {$adId}", ['customer_id' => $customerId]);

                // Fetch Ad meta info
                $adResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adId}", [
                    'fields' => 'id,name,status,adset_id,campaign_id'
                ]);

                if (!$adResponse->successful() || !isset($adResponse['id'])) {
                    Log::warning("Failed to fetch ad meta: {$adId}", ['response' => $adResponse->json()]);
                    continue;
                }

                $ad = $adResponse->json();
                $adSetId = $ad['adset_id'] ?? 'N/A';
                $campaignId = $ad['campaign_id'] ?? 'N/A';
                $adName = $this->removeEmoji($ad['name']);
                $deliveryStatus = $ad['status'] ?? 'UNKNOWN';

                // Fetch Ad insights
                $adInsightResponse = Http::withToken($accessToken)->get("https://graph.facebook.com/v22.0/{$adId}/insights", [
                    'fields' => 'reach,impressions,spend,actions,quality_ranking,engagement_rate_ranking,conversion_rate_ranking,date_start',
                    'limit' => 1
                ]);

                if (!$adInsightResponse->successful() || !isset($adInsightResponse['data'][0])) {
                    Log::warning("Failed to fetch ad insights: {$adId}", ['response' => $adInsightResponse->json()]);
                    continue;
                }

                $adData = $adInsightResponse['data'][0];
                $adResultCount = 0;
                foreach ($adData['actions'] ?? [] as $action) {
                    if (in_array($action['action_type'], $preferredTypes)) {
                        $adResultCount = max($adResultCount, (int) ($action['value'] ?? 0));
                    }
                }

                $adStart = $adData['date_start'] ?? null;
                $adStop = $adStart; // Ads typically inherit ad set end date, but use start date if unavailable
                $adDuration = $adStart ? 'Unknown' : 'Unknown';

                try {
                    AdInsight::updateOrCreate(
                        ['ad_id' => $adId, 'customer_id' => $customerId],
                        [
                            'customer_id' => $customerId,
                            'campaign_id' => $campaignId,
                            'adset_id' => $adSetId,
                            'ad_name' => $adName,
                            'delivery' => strtoupper($deliveryStatus),
                            'actions' => json_encode($adData['actions'] ?? []),
                            'bid_strategy' => 'Using campaign bid strategy',
                            'budget' => 'Using campaign budget',
                            'last_edit' => $adData['date_start'] ?? now(),
                            'attribution_setting' => '7-day click or 1-day view',
                            'results' => $adResultCount,
                            'reach' => $adData['reach'] ?? null,
                            'impressions' => $adData['impressions'] ?? null,
                            'cost_per_result' => ($adResultCount > 0 && isset($adData['spend'])) ? round($adData['spend'] / $adResultCount, 2) : null,
                            'spend' => $adData['spend'] ?? null,
                            'ends' => $adStop,
                            'schedule' => $adStart ? ($adStart . ' - ' . $adStop) : 'N/A',
                            'duration' => $adDuration,
                            'quality_rank' => $adData['quality_ranking'] ?? 'N/A',
                            'engagement_rank' => $adData['engagement_rate_ranking'] ?? 'N/A',
                            'conversion_rank' => $adData['conversion_rate_ranking'] ?? 'N/A'
                        ]
                    );
                    Log::info("Ad insight saved: {$adId}");
                } catch (\Exception $e) {
                    Log::error("Failed to save ad insight: {$adId}", ['error' => $e->getMessage(), 'ad' => $ad]);
                    continue;
                }
            }

            return redirect()->route('insights.show', $customerId)->with('success', 'Insights fetched successfully.');
        } catch (\Exception $e) {
            Log::error("Fetch insights failed: {$e->getMessage()}", [
                'customer_id' => $customerId,
                'campaign_ids' => $campaignIds,
                'adset_ids' => $adSetIds,
                'ad_ids' => $adIds,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('insights.show', $customerId)->with('error', 'Failed to fetch insights: ' . $e->getMessage());
        }
    }
}