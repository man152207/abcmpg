<?php

namespace App\Http\Controllers;

use App\Mail\AdReceipt;
use App\Models\Ad;
use App\Models\Card;
use App\Models\Client;
use App\Models\Customer;
use App\Models\CustomerRequirement;
use App\Models\Other_Exp;
use App\Models\OtherIncome;
use App\Models\StorredAdAccount;
use App\Models\Multimedia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use App\Models\BonusSeason;

/**
 * Controller for managing advertisements (ads) and related functionalities.
 */
class AdController extends Controller
{
    /**
     * Get common data required for ads_list view.
     *
     * @return array
     */
    private function getCommonViewData()
    {
        return [
            'storredAdAccounts' => StorredAdAccount::select('ad_account_name')->distinct()->get(),
            'customerNoteCounts' => CustomerRequirement::selectRaw('customer_id, COUNT(*) as note_count')
                ->groupBy('customer_id')
                ->pluck('note_count', 'customer_id')
                ->toArray(),
            'paused_amount' => Ad::where('Payment', 'Paused')->sum('USD'),
            'to_be_load' => Ad::sum('USD') - Ad::where('Payment', 'Paused')->sum('USD'),
            'formattedTotalToBeReceived' => number_format(
                Ad::whereIn('Payment', ['Pending', 'Paused', 'Informed'])->sum('NRP') +
                Ad::where('Payment', 'Baki')->sum('advance'),
                2
            ),
        ];
    }

    /**
     * Fetch ads by date range with common view data.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param int $perPage
     * @return array
     */
    private function getAdsByDateRange($startDate, $endDate, $perPage = 15)
    {
        $ads = Ad::with(['customerRelation' => function ($q) {
            $q->select('id', 'name', 'display_name', 'phone');
        }])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        foreach ($ads as $ad) {
            $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
        }

        return array_merge($this->getCommonViewData(), compact('ads'));
    }

    /**
     * Filter ads by payment status.
     *
     * @param string $status
     * @return \Illuminate\View\View
     */
    public function filterByStatus($status)
{
    Log::info('Filtering by status: ' . $status);

    // yo hamro special case
    if ($status === 'Pending Action') {
        $wanted = ['Pending', 'Paused', 'Baki', 'Overpaid', 'Informed'];

        $ads = Ad::with(['customerRelation' => function ($q) {
                $q->select('id', 'name', 'display_name', 'phone');
            }])
            ->whereIn('Payment', $wanted)
            ->orderBy('id', 'desc')
            ->paginate(15);

    } else {
        // purano behaviour
        $ads = Ad::with(['customerRelation' => function ($q) {
                $q->select('id', 'name', 'display_name', 'phone');
            }])
            ->where('Payment', $status)
            ->orderBy('id', 'desc')
            ->paginate(15);
    }

    // calculated status halne
    foreach ($ads as $ad) {
        $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
    }

    // tyo mathi banako common cards (paused_amount, to_be_load, etc.)
    return view('admin.ads_list', array_merge($this->getCommonViewData(), compact('ads')));
}


    /**
     * Paginate a collection for custom filtering.
     *
     * @param Collection $items
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginateCollection(Collection $items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }

    /**
     * Send email with ad receipt via AJAX.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmailAjax($id)
    {
        try {
            $ad = Ad::findOrFail($id);
            $customer = Customer::where('phone', $ad->customer)->first();
            if ($ad->Status != "On schedule") {
                Mail::to($customer->email)->send(new AdReceipt($ad));
                return response()->json(['success' => 'Email sent successfully']);
            }
            return response()->json(['error' => 'Email not sent. Ad is on schedule.']);
        } catch (\Throwable $th) {
            Log::error('Error in sendEmailAjax: ' . $th->getMessage());
            return response()->json(['error' => 'Failed to send email. Please try again.']);
        }
    }

    /**
     * Display ad creation form.
     *
     * @return \Illuminate\View\View|string
     */
    public function ad_form()
    {
        try {
            return view('admin.ads');
        } catch (\Throwable $th) {
            Log::error('Error in ad_form: ' . $th->getMessage());
            return 'Failed to load ad creation form.';
        }
    }

    /**
     * Show ads for the current month.
     *
     * @return \Illuminate\View\View
     */
    public function showAds()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $ads = Ad::with(['customerRelation' => function ($q) {
            $q->select('id', 'name', 'display_name', 'phone');
        }])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->orderBy('id', 'desc')
            ->paginate(15);

        foreach ($ads as $ad) {
            $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
        }

        $totalUSDAllTime = Ad::sum('USD');
        $totalNPRAllTime = Ad::sum('NRP');
        $totalQuantityAllTime = Ad::sum('Quantity');

        return view('admin.ads_list', array_merge($this->getCommonViewData(), compact(
            'ads',
            'totalUSDAllTime',
            'totalNPRAllTime',
            'totalQuantityAllTime'
        )));
    }

    /**
     * Show completed ads.
     *
     * @return \Illuminate\View\View|string
     */
    public function showCompleteAds()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $ads = Ad::where('created_at', '<', $startOfMonth)
                ->where('is_complete', 1)
                ->orderBy('id', 'desc')
                ->paginate(10);
            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            Log::error('Error in showCompleteAds: ' . $th->getMessage());
            return 'Failed to load completed ads.';
        }
    }

    /**
     * Store a new ad.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function storeAd(Request $request)
{
    try {
        // 1) Validate
        $request->validate([
            'customer'         => 'required',
            'USD'              => 'required|numeric',
            'Rate'             => 'required|numeric',
            'NRP'              => 'required|numeric',
            'Ad_Account'       => 'required',
            'Payment'          => 'required',
            'Duration'         => 'required|integer',
            'Quantity'         => 'required|integer',
            'Status'           => 'required',
            'Ad_Nature_Page'   => 'required',
            // form बाट नआउने भएकाले हटाइयो:
            // 'admin'         => 'required',
            // hidden input (json string)
            'addons_selected'  => 'nullable|string',
            // direct array आयो भने पनि ok
            'add_on'           => 'nullable|array',
            // 'advance' optional
            'advance'          => 'nullable|numeric',
        ]);

        // 2) addons_selected JSON decode
        $addons = [];
        if ($request->filled('addons_selected')) {
            $decoded = json_decode($request->input('addons_selected'), true);
            if (is_array($decoded)) {
                $addons = $decoded; // [ {service_name, amount}, ... ]
            }
        }

        // 3) Customer check
        $customer = Customer::where('phone', $request->customer)->first();
        if (!$customer) {
            Log::warning("Customer with phone {$request->customer} not found during ad creation.");
            return redirect()->route('customer.add')
                ->with('status', "Customer with phone number {$request->customer} does not exist! Create a new customer with this phone number!");
        }

        // 4) Create
        $ad = Ad::create([
            'customer'        => $request->customer,
            'USD'             => $request->USD,
            'Rate'            => $request->Rate,
            'NRP'             => $request->NRP,
            'Ad_Account'      => $request->Ad_Account,
            'Payment'         => $request->Payment,
            'Duration'        => $request->Duration,
            'Quantity'        => $request->Quantity,
            'Ad_Nature_Page'  => $request->Ad_Nature_Page,
            'Status'          => $request->Status,
            'advance'         => $request->advance,
            'admin'           => auth('admin')->user()->name,
            'is_complete'     => 0,
            'created_by'    => auth('admin')->id(),

            // ✅ DB मा JSON/text छ, Model cast छ → array राख्नुस्
            'add_on'          => $addons ?: null,
        ]);

        if ($request->Status != "On schedule" && !empty($customer->email)) {
            Mail::to($customer->email)->send(new AdReceipt($ad));
        }

        return redirect()->route('ads.show')->with('success', 'Ad created successfully');
        
    } catch (\Throwable $th) {
        Log::error('Error in storeAd: ' . $th->getMessage());
        return redirect()->back()->with('error', 'विज्ञापन सिर्जना गर्न असफल! कृपया पुन: प्रयास गर्नुहोस्।');
    }
}
public function destroy($id)
{
    try {
        $ad = Ad::findOrFail($id);
        $ad->delete(); // Soft delete छ भने यो पर्याप्त; नभए hard delete पनि यहीँ हुन्छ

        return redirect()->back()->with('success', 'Campaign deleted successfully.');
    } catch (QueryException $e) {
        // FK constraint आदि का कारण नडिलिट भएमा
        Log::error('Ad delete DB error: '.$e->getMessage());
        return redirect()->back()->with('error', 'Cannot delete this campaign due to related data.');
    } catch (\Throwable $th) {
        Log::error('Ad delete error: '.$th->getMessage());
        return redirect()->back()->with('error', 'Failed to delete campaign. Please try again.');
    }
}

    /**
     * Edit an existing ad.
     *
     * @param int $id
     * @return \Illuminate\View\View|string
     */
    public function edit($id)
    {
        try {
            $ad = Ad::findOrFail($id);
            $customers = Customer::all();
            return view('admin.ads_update', compact('ad', 'customers'));
        } catch (\Throwable $th) {
            Log::error('Error in edit: ' . $th->getMessage());
            return 'Failed to load ad edit form.';
        }
    }

    /**
     * Update an existing ad.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function update(Request $request, $id)
{
    try {
        $ad = Ad::findOrFail($id);

        // ✅ 1) पुरानो payment (update अघि)
        $oldPayment = $ad->Payment;

        $request->validate([
            'USD'             => 'required|numeric',
            'Rate'            => 'required|numeric',
            'NRP'             => 'required|numeric',
            'Ad_Account'      => 'required',
            'Payment'         => 'required|in:Pending,Paused,FPY Received,eSewa Received,Baki,Paid,Refunded,Cancelled,Overpaid,PV Adjusted,Informed',
            'Duration'        => 'required|integer',
            'Quantity'        => 'required|integer',
            'Ad_Nature_Page'  => 'required',
            'Status'          => 'required',
            'advance'         => 'nullable|numeric',
            'addons_selected' => 'nullable|string',
            'redirect_to'     => 'nullable|string',
        ]);

        // advance logic
        $advance = in_array($request->Payment, ["Baki", "Refunded", "Overpaid"], true)
            ? $request->advance
            : null;

        // decode addons_selected -> array
        $newAddons = null;
        if ($request->filled('addons_selected')) {
            $decoded = json_decode($request->input('addons_selected'), true);
            if (is_array($decoded)) {
                $newAddons = $decoded;
            }
        }

        // payload
        $payload = [
            'USD'             => $request->USD,
            'Rate'            => $request->Rate,
            'NRP'             => $request->NRP,
            'Ad_Account'      => $request->Ad_Account,
            'Payment'         => $request->Payment,
            'Duration'        => $request->Duration,
            'Quantity'        => $request->Quantity,
            'Ad_Nature_Page'  => $request->Ad_Nature_Page,
            'Status'          => $request->Status,
            'advance'         => $advance,
        ];

        if ($newAddons !== null) {
            $payload['add_on'] = $newAddons;
        }

        // ✅ 2) update
        $ad->update($payload);

        // ✅ 3) payment change detect (update पछि)
        $newPayment = $request->Payment;

        // ✅ 4) mail trigger list
        $triggerPayments = [
            'Paid',
            'FPY Received',
            'eSewa Received',
            'PV Adjusted',
            'Overpaid',
        ];

        // ✅ 5) mail send (only when changed + triggers)
        if ($oldPayment !== $newPayment) {
            $customer = Customer::where('phone', $ad->customer)->first();

            if (
                in_array($newPayment, $triggerPayments, true) &&
                $customer && !empty($customer->email) &&
                $ad->Status != "On schedule"
            ) {
                Mail::to($customer->email)->send(new AdReceipt($ad->fresh()));
            }
        }

        // ✅ 6) redirect (यही नै missing थियो)
        $redirectTo = $request->input('redirect_to');
        if (!empty($redirectTo)) {
            return redirect($redirectTo)->with('success', 'Ad updated successfully');
        }

        return redirect()->route('ads.show')->with('success', 'Ad updated successfully');

    } catch (\Throwable $th) {
        Log::error('Error in update: ' . $th->getMessage());
        return redirect()->back()->with('error', 'विज्ञापन अपडेट गर्न असफल! कृपया पुन: प्रयास गर्नुहोस्।');
    }
}

    /**
     * Search ads based on query and date range.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
{
    // raw inputs from the GET form(s)
    $textQuery  = trim($request->input('search_query', ''));
    $dateRange  = $request->input('date_range', '');

    // parse date range safely (single date पनि हुन्छ)
    [$startDate, $endDate] = $this->parseDateRange($dateRange);

    // base query
    $adsQuery = Ad::with(['customerRelation' => function ($q) {
        $q->select('id', 'name', 'display_name', 'phone', 'phone_2', 'email', 'address', 'profile_picture', 'requires_bill');
    }]);

    // ---- TEXT FILTER ----
    if ($textQuery !== '') {
        $adsQuery->where(function ($q) use ($textQuery) {
            // match on related customer
            $q->whereHas('customerRelation', function ($cq) use ($textQuery) {
                $cq->where('phone',        'LIKE', "%{$textQuery}%")
                   ->orWhere('phone_2',    'LIKE', "%{$textQuery}%")
                   ->orWhere('name',       'LIKE', "%{$textQuery}%")
                   ->orWhere('display_name','LIKE', "%{$textQuery}%");
            })
            // OR match self fields (Ad_Account, admin, Ad_Nature_Page)
            ->orWhere('Ad_Account',     'LIKE', "%{$textQuery}%")
            ->orWhere('admin',          'LIKE', "%{$textQuery}%")
            ->orWhere('Ad_Nature_Page', 'LIKE', "%{$textQuery}%");
        });
    }

    // ---- DATE FILTER ----
    if ($startDate && $endDate) {
        $adsQuery->whereBetween('created_at', [$startDate, $endDate]);
    }

    // final sort + paginate
    $ads = $adsQuery
        ->orderBy('id', 'DESC')
        ->paginate(15)
        ->appends($request->query()); // so query params stay on pagination links

    // attach calculated status for each row (Running / Ending today / etc.)
    foreach ($ads as $ad) {
        $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
    }

    // reuse the shared header cards (Paused Balance, To Be Loaded, Receivable...)
    // from your existing helper
    $viewData = array_merge(
        $this->getCommonViewData(),
        compact('ads')
    );

    // very important:
    // We RETURN THE BLADE, not JSON, even on error-type searches.
    return view('admin.ads_list', $viewData);
}
    private function parseDateRange(?string $raw): array
{
    if (!$raw) {
        return [null, null];
    }

    $raw = trim($raw);

    // Split on " - " (with optional spaces)
    $parts = preg_split('/\s*-\s*/', $raw);

    try {
        if (count($parts) === 1) {
            // single date => whole day
            $start = Carbon::parse($parts[0])->startOfDay();
            $end   = Carbon::parse($parts[0])->endOfDay();
            return [$start, $end];
        } elseif (count($parts) >= 2) {
            $start = Carbon::parse($parts[0])->startOfDay();
            $end   = Carbon::parse($parts[1])->endOfDay();
            return [$start, $end];
        }
    } catch (\Throwable $th) {
        // invalid date format
        return [null, null];
    }

    return [null, null];
}


    /**
     * Search completed ads.
     *
     * @param Request $request
     * @return \Illuminate\View\View|string
     */
    public function search_ad_complete(Request $request)
    {
        try {
            $query = Ad::where('is_complete', 1);

            if ($request->has('customer')) {
                $query->where('customer', 'like', '%' . $request->customer . '%')->orderBy('id', 'DESC');
            }

            if ($request->start_date != 0 && $request->end_date != 0) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59'])->orderBy('id', 'DESC');
            }

            $ads = $query->paginate(15);
            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            Log::error('Error in search_ad_complete: ' . $th->getMessage());
            return 'सम्पन्न विज्ञापन खोजी गर्न असफल!';
        }
    }

    /**
     * Display the summary dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function summarydashboard()
{
    $currentMonth       = Carbon::now()->format('Y-m');
    $currentMonthStart  = Carbon::now()->startOfMonth();
    $currentMonthEnd    = Carbon::now()->endOfMonth();
    $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $previousMonthEnd   = Carbon::now()->subMonth()->endOfMonth();

    // 🔹 पुरानो summary data जस्ताको तस्तै
    $data = [
        'monthlyAdIncomeSummaries' => Ad::selectRaw('SUM(USD) as totalUSD, SUM(NRP) as totalNRP')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->first(),
        'previousMonthlyAdIncomeSummaries' => Ad::selectRaw('SUM(USD) as totalUSD, SUM(NRP) as totalNRP')
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->first(),
        'monthlyClientSummaries' => Client::selectRaw('SUM(USD) as totalUSD, SUM(NRP) as totalNRP')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->first(),
        'previousMonthlyClientSummaries' => Client::selectRaw('SUM(USD) as totalUSD, SUM(NRP) as totalNRP')
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->first(),
        'monthlyExp' => Other_Exp::selectRaw('SUM(amount) as totalAmt')
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->first(),
        'previousMonthlyExp' => Other_Exp::selectRaw('SUM(amount) as totalAmt')
            ->whereBetween('date', [$previousMonthStart, $previousMonthEnd])
            ->first(),
        'Cardsummary' => Card::selectRaw('SUM(USD) as totalUSD')->first(),
        'previousCardsummary' => Card::selectRaw('SUM(USD) as totalUSD')->first(),
        'totalOtherIncome' => OtherIncome::where('income_type', 'Other Income')
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount'),
        'totalOpeningBalance' => OtherIncome::where('income_type', 'Opening Balance')
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount'),
        'totalNRP' => Ad::whereIn('Payment', ['Pending', 'Paused', 'Informed'])->sum('NRP'),
        'totalAdvance' => Ad::where('Payment', 'Baki')->sum('advance'),
        'currentMonthExpenses' => Other_Exp::selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as total_amount")
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$currentMonth])
            ->groupBy('month')
            ->first(),
        'other_incomes' => OtherIncome::all(),
        'customers' => Client::all(),
    ];

    // 🔹 Bonus Season + Total Bonus गणना (USD मा – card ले यो नै देख्छ)
    $activeBonusSeason = BonusSeason::where('is_active', true)->first();
    $totalBonusCredit  = 0;

    if ($activeBonusSeason) {
        // DB मा छ भने field नाम adjust गर्नुहोस् (start_date / end_date / bonus_rate आदि)
        $seasonStart = Carbon::parse($activeBonusSeason->start_date)->startOfDay();
        $seasonEnd   = Carbon::parse($activeBonusSeason->end_date)->endOfDay();

        // यदि bonus प्रतिशत field छ भने; नभए default 1% मानिदिएको
        $bonusRate = $activeBonusSeason->bonus_rate ?? 1; // 1 = 1%

        // प्रति ग्राहक प्रति महिना spend → threshold पुगेपछि मात्र bonus
        $rows = Ad::selectRaw("customer, DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(USD) as total_usd")
            ->whereBetween('created_at', [$seasonStart, $seasonEnd])
            ->groupBy('customer', 'ym')
            ->get();

        foreach ($rows as $row) {
            // उदाहरण: 300 USD भन्दा माथि भएमा 1% bonus
            if ($row->total_usd >= 300) {
                $totalBonusCredit += $row->total_usd * ($bonusRate / 100);
            }
        }
    }

    // View मा पठाउने extra keys
    $data['activeBonusSeason'] = $activeBonusSeason;
    $data['totalBonusCredit']  = $totalBonusCredit;

    // 🔹 पहिलेदेखि भएको additional calculations
    $data['totalToBeReceived']         = $data['totalNRP'] + $data['totalAdvance'];
    $data['formattedTotalToBeReceived'] = number_format($data['totalToBeReceived'], 2, ".", ",");
    $data['totalIncome']               = $data['totalOtherIncome'] + $data['totalOpeningBalance'];
    $data['combinedNPRBalance']        = ($data['monthlyAdIncomeSummaries']->totalNRP ?? 0)
        - (($data['monthlyClientSummaries']->totalNRP ?? 0) + ($data['monthlyExp']->totalAmt ?? 0))
        + ($data['totalOtherIncome'] ?? 0);
    $data['finalNPRBalance']           = $data['combinedNPRBalance']
        + $data['totalOpeningBalance']
        - $data['totalToBeReceived'];

    return view('admin.dashboard', $data);
}

    /**
     * Display the ad summary.
     *
     * @return \Illuminate\View\View|string
     */
    public function summary()
    {
        try {
            $monthlySummaries = Ad::select(
                DB::raw('SUM(USD) as totalUSD'),
                DB::raw('SUM(NRP) as totalNRP'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
            )
                ->groupBy('monthYear')
                ->orderBy('monthYear', 'desc')
                ->paginate(15);

            $monthlySummaries_paid = Ad::where('is_complete', '>', 0)
                ->where('Status', 'Paid')
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    DB::raw('SUM(NRP) as totalNRP'),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->orderBy('monthYear', 'desc')
                ->paginate(15);

            $monthlySummaries_due = Ad::where('is_complete', '>', 0)
                ->where('Status', '!=', 'Paid')
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    DB::raw('SUM(NRP) as totalNRP'),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->orderBy('monthYear', 'desc')
                ->paginate(15);

            return view('admin.ads_summary', compact('monthlySummaries', 'monthlySummaries_due', 'monthlySummaries_paid'));
        } catch (\Throwable $th) {
            Log::error('Error in summary: ' . $th->getMessage());
            return 'Failed to load ad summary.';
        }
    }

    /**
     * Display ads created today.
     *
     * @return \Illuminate\View\View|string
     */
    public function thisDay()
    {
        try {
            return view('admin.ads_list', $this->getAdsByDateRange(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()));
        } catch (\Throwable $th) {
            Log::error('Error in thisDay: ' . $th->getMessage());
            return 'Failed to load ads for today.';
        }
    }

    /**
     * Display ads created yesterday.
     *
     * @return \Illuminate\View\View|string
     */
    public function yesterday()
    {
        try {
            return view('admin.ads_list', $this->getAdsByDateRange(Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()));
        } catch (\Throwable $th) {
            Log::error('Error in yesterday: ' . $th->getMessage());
            return 'Failed to load ads for yesterday.';
        }
    }

    /**
     * Display ads created this week.
     *
     * @return \Illuminate\View\View|string
     */
    public function thisWeek()
    {
        try {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            return view('admin.ads_list', $this->getAdsByDateRange(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()));
        } catch (\Throwable $th) {
            Log::error('Error in thisWeek: ' . $th->getMessage());
            return 'Failed to load ads for this week.';
        }
    }

    /**
     * Display ads created this month.
     *
     * @return \Illuminate\View\View|string
     */
    public function thisMonth()
    {
        try {
            return view('admin.ads_list', $this->getAdsByDateRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()));
        } catch (\Throwable $th) {
            Log::error('Error in thisMonth: ' . $th->getMessage());
            return 'Failed to load ads for this month.';
        }
    }

    /**
     * Display completed ads created this week.
     *
     * @return \Illuminate\View\View|string
     */
    public function thisWeek_complete()
    {
        try {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $ads = Ad::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where('is_complete', 1)
                ->orderBy('id', 'desc')
                ->paginate(15);

            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            Log::error('Error in thisWeek_complete: ' . $th->getMessage());
            return 'Failed to load completed ads for this week.';
        }
    }

    /**
     * Display completed ads created this month.
     *
     * @return \Illuminate\View\View|string
     */
    public function thisMonth_complete()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $ads = Ad::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('is_complete', 1)
                ->orderBy('id', 'desc')
                ->paginate(15);

            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            Log::error('Error in thisMonth_complete: ' . $th->getMessage());
            return 'Failed to load completed ads for this month.';
        }
    }

    /**
     * Display completed ads created today.
     *
     * @return \Illuminate\View\View|string
     */
    public function thisDay_complete()
    {
        try {
            $startOfDay = Carbon::now()->startOfDay();
            $endOfDay = Carbon::now()->endOfDay();

            $ads = Ad::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->where('is_complete', 1)
                ->orderBy('id', 'desc')
                ->paginate(15);

            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            Log::error('Error in thisDay_complete: ' . $th->getMessage());
            return 'Failed to load completed ads for today.';
        }
    }

    /**
     * Display completed ads created yesterday.
     *
     * @return \Illuminate\View\View|string
     */
    public function yesterday_complete()
    {
        try {
            $startOfYesterday = Carbon::yesterday()->startOfDay();
            $endOfYesterday = Carbon::yesterday()->endOfDay();

            $ads = Ad::whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
                ->where('is_complete', 1)
                ->orderBy('id', 'desc')
                ->paginate(15);

            return view('admin.ads_complete_list', compact('ads'));
        } catch (\Throwable $th) {
            Log::error('Error in yesterday_complete: ' . $th->getMessage());
            return 'Failed to load completed ads for yesterday.';
        }
    }

    /**
     * Send email with ad receipt.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function email_to_send($id)
    {
        try {
            $ad = Ad::findOrFail($id);
            $customer = Customer::where('phone', $ad->customer)->first();
            if ($ad->Status != "On schedule") {
                Mail::to($customer->email)->send(new AdReceipt($ad));
            }
            return redirect()->route('ads.show')->with('success', 'Email sent successfully.');
        } catch (\Throwable $th) {
            Log::error('Error in email_to_send: ' . $th->getMessage());
            return redirect()->route('ads.show')->with('error', 'Failed to send email.');
        }
    }

    /**
     * Display monthly ad details.
     *
     * @param string $monthYear
     * @return \Illuminate\View\View
     */
    public function monthlyDetails($monthYear)
    {
        $date = Carbon::createFromFormat('F Y', $monthYear);

        $dailySummaries = Ad::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->select(
                DB::raw('SUM(USD) as totalUSD'),
                DB::raw('SUM(NRP) as totalNRP'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as day")
            )
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();

        return view('admin.ads_daily_summary', compact('dailySummaries', 'monthYear'));
    }

    /**
     * Display all ads.
     *
     * @return \Illuminate\View\View
     */
    public function showAllAds()
    {
        $ads = Ad::with(['customerRelation' => function ($q) {
            $q->select('id', 'name', 'display_name', 'phone');
        }])
            ->orderBy('id', 'desc')
            ->paginate(15);

        foreach ($ads as $ad) {
            $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
        }

        return view('admin.ads_list', array_merge($this->getCommonViewData(), compact('ads')));
    }

    /**
     * Display customer details.
     *
     * @param int $id
     * @param int $startMonthOffset
     * @return \Illuminate\View\View
     */
    public function showDetails($id, $startMonthOffset = 0)
    {
        Log::info("Fetching details for Customer ID: " . $id);

        $customer = Customer::find($id);

        if (!$customer) {
            Log::error("Customer not found for ID: " . $id);
            return view('customer.details')->with('error', 'Customer not found');
        }

        Log::info("Customer found: " . print_r($customer->toArray(), true));

        $totalUSDAllTime = Ad::where('customer', $customer->phone)->sum('USD');
        $totalNPRAllTime = Ad::where('customer', $customer->phone)->sum('NRP');
        $totalQuantityAllTime = Ad::where('customer', $customer->phone)->sum('Quantity');

        Log::info("Total USD All Time: $totalUSDAllTime, Total NPR All Time: $totalNPRAllTime, Total Quantity All Time: $totalQuantityAllTime");

        $totalUSDThisMonth = Ad::where('customer', $customer->phone)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('USD');
        $totalNPRThisMonth = Ad::where('customer', $customer->phone)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('NRP');
        $totalQuantityThisMonth = Ad::where('customer', $customer->phone)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('Quantity');

        Log::info("Total USD This Month: $totalUSDThisMonth, Total NPR This Month: $totalNPRThisMonth, Total Quantity This Month: $totalQuantityThisMonth");

        $totalUSDThisToday = Ad::where('customer', $customer->phone)
            ->whereDate('created_at', Carbon::today())
            ->sum('USD');
        $totalNPRThisToday = Ad::where('customer', $customer->phone)
            ->whereDate('created_at', Carbon::today())
            ->sum('NRP');
        $totalQuantityThisToday = Ad::where('customer', $customer->phone)
            ->whereDate('created_at', Carbon::today())
            ->sum('Quantity');

        Log::info("Total USD Today: $totalUSDThisToday, Total NPR Today: $totalNPRThisToday, Total Quantity Today: $totalQuantityThisToday");

        $months = [];
        $previousMonthsData = [];

        for ($i = 0; $i < 5; $i++) {
            $month = Carbon::now()->subMonths($i + $startMonthOffset)->format('F Y');
            $months[] = $month;

            $usd = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', Carbon::now()->subMonths($i + $startMonthOffset)->month)
                ->whereYear('created_at', Carbon::now()->subMonths($i + $startMonthOffset)->year)
                ->sum('USD');

            $npr = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', Carbon::now()->subMonths($i + $startMonthOffset)->month)
                ->whereYear('created_at', Carbon::now()->subMonths($i + $startMonthOffset)->year)
                ->sum('NRP');

            $quantity = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', Carbon::now()->subMonths($i + $startMonthOffset)->month)
                ->whereYear('created_at', Carbon::now()->subMonths($i + $startMonthOffset)->year)
                ->sum('Quantity');

            $previousMonthsData[$month] = [
                'usd' => $usd,
                'npr' => $npr,
                'quantity' => $quantity,
            ];

            Log::info("Data for $month: USD = $usd, NPR = $npr, Quantity = $quantity");
        }

        return view('customer.details', compact(
            'customer',
            'totalUSDAllTime',
            'totalNPRAllTime',
            'totalQuantityAllTime',
            'totalUSDThisMonth',
            'totalNPRThisMonth',
            'totalQuantityThisMonth',
            'totalUSDThisToday',
            'totalNPRThisToday',
            'totalQuantityThisToday',
            'months',
            'previousMonthsData',
            'startMonthOffset'
        ));
    }

    /**
     * Calculate ad status based on creation date and duration.
     *
     * @param string $created_at
     * @param int $duration
     * @return string|null
     */
    public function calculateStatus($created_at, $duration)
    {
        $endDate = Carbon::parse($created_at)->addDays($duration);
        $now = Carbon::now();

        if ($now->isAfter($endDate)) {
            $daysDifference = $now->diffInDays($endDate, false);

            if ($daysDifference < -7) {
                return null;
            }

            if ($daysDifference < 0) {
                return "Ended " . abs($daysDifference) . " days ago";
            } else {
                $hoursDifference = $now->diffInHours($endDate, false);
                return "Ended " . abs($hoursDifference) . " hours ago";
            }
        } elseif ($endDate->isToday()) {
            return "Ending today at " . $endDate->format('H:i:s');
        } elseif ($endDate->isTomorrow()) {
            return "Ending tomorrow at " . $endDate->format('H:i:s');
        } else {
            return "Running";
        }
    }

    /**
     * Filter ads by calculated status.
     *
     * @param string $status
     * @return \Illuminate\View\View
     */
    public function filterByCalculatedStatus($status)
    {
        $ads = Ad::with(['customerRelation' => function ($q) {
            $q->select('id', 'name', 'display_name', 'phone');
        }])->get();

        $filteredAds = $ads->filter(function ($ad) use ($status) {
            $calculatedStatus = $this->calculateStatus($ad->created_at, $ad->Duration);

            if ($status == 'Ended') {
                return strpos($calculatedStatus, 'Ended') !== false;
            } elseif ($status == 'Ending tomorrow') {
                return strpos($calculatedStatus, 'Ending tomorrow') !== false;
            } elseif ($status == 'Ending today') {
                return strpos($calculatedStatus, 'Ending today') !== false;
            } elseif ($status == 'Running') {
                return $calculatedStatus === 'Running';
            } else {
                return false;
            }
        });

        $paginatedAds = $this->paginateCollection($filteredAds, 15, null, [
            'path' => url()->current(),
            'query' => ['status' => $status]
        ]);

        foreach ($paginatedAds as $ad) {
            $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
        }

        $totalUSDAllTime = $filteredAds->sum('USD');
        $totalNPRAllTime = $filteredAds->sum('NRP');
        $totalQuantityAllTime = $filteredAds->sum('Quantity');

        return view('admin.ads_list', array_merge($this->getCommonViewData(), [
    'ads' => $paginatedAds,
    'totalUSDAllTime' => $totalUSDAllTime,
    'totalNPRAllTime' => $totalNPRAllTime,
    'totalQuantityAllTime' => $totalQuantityAllTime,
    'status' => $status
]));
    }

    /**
     * Filter ads by monitoring status.
     *
     * @return \Illuminate\View\View
     */
    public function filterByMonitoringStatus()
    {
        $ads = Ad::with(['customerRelation' => function ($q) {
            $q->select('id', 'name', 'display_name', 'phone');
        }])
            ->where('Status', 'Monitoring')
            ->paginate(15);

        foreach ($ads as $ad) {
            $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
        }

        return view('admin.ads_list', array_merge($this->getCommonViewData(), compact('ads')));
    }

    /**
     * Filter dashboard data by month and year.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterDashboardData(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');

        try {
            $adIncomeSummaryUSD = Ad::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('USD');

            $adIncomeSummaryNRP = Ad::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('NRP');

            $clientSummaryUSD = Client::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('USD');

            $clientSummaryNRP = Client::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('NRP');

            return response()->json([
                'adIncomeSummaryUSD' => number_format($adIncomeSummaryUSD, 2),
                'adIncomeSummaryNRP' => number_format($adIncomeSummaryNRP, 2),
                'clientSummaryUSD' => number_format($clientSummaryUSD, 2),
                'clientSummaryNRP' => number_format($clientSummaryNRP, 2),
            ]);
        } catch (\Exception $e) {
            Log::error('Error filtering dashboard data: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch data'], 500);
        }
    }

    /**
     * Get filtered dashboard data (example implementation).
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    private function getFilteredDashboardData($month, $year)
    {
        $totalUSD = Ad::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('USD');

        $totalNRP = Ad::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('NRP');

        return [
            'totalUSD' => number_format($totalUSD, 2),
            'totalNRP' => number_format($totalNRP, 2),
        ];
    }

    /**
     * Calculate Actively Running Ads Budget (ARAB).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateARAB()
    {
        $currentDate = Carbon::now();

        $activeAds = Ad::whereRaw('DATE_ADD(created_at, INTERVAL Duration DAY) >= ?', [$currentDate])
            ->where('Payment', '!=', 'Paused')
            ->get(['id', 'USD', 'created_at', 'Duration']);

        $totalARAB = $activeAds->sum('USD');

        return response()->json([
            'totalARAB' => $totalARAB,
        ]);
    }

    /**
     * Calculate daily spend for running ads.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDailySpend()
    {
        $currentDate = Carbon::now();

        $runningAds = Ad::whereRaw('DATE_ADD(created_at, INTERVAL Duration DAY) >= ?', [$currentDate])
            ->where('Payment', '!=', 'Paused')
            ->get(['USD', 'Duration']);

        $totalDailySpend = $runningAds->reduce(function ($carry, $ad) {
            $usd = $ad->USD ?: 0;
            $duration = $ad->Duration ?: 1;
            return $carry + ($usd / $duration);
        }, 0);

        return response()->json([
            'totalDailySpend' => $totalDailySpend,
        ]);
    }

    /**
     * Calculate total active ads.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateActiveAds()
    {
        $currentDate = Carbon::now();

        $runningAds = Ad::whereRaw('DATE_ADD(created_at, INTERVAL Duration DAY) >= ?', [$currentDate])
            ->where('Payment', '!=', 'Paused')
            ->get(['Quantity']);

        $totalActiveAds = $runningAds->sum('Quantity');

        return response()->json([
            'totalActiveAds' => $totalActiveAds,
        ]);
    }
    public function index(Request $request)
{
    $ads = Ad::with('customerRelation');

    // Volume Filtering logic
    if ($request->has('volume')) {
        if ($request->volume === 'high') {
            $ads = $ads->where('USD', '>=', 100);
        } elseif ($request->volume === 'low') {
            $ads = $ads->where('USD', '<=', 20);
        }
    }

    $ads = $ads->orderBy('id', 'desc')->paginate(15);

    // Add status calculation if needed
    foreach ($ads as $ad) {
        $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
    }

    return view('admin.ads_list', [
        'ads' => $ads,
        'volume' => $request->volume,
    ]);
}
public function filterByVolume(string $range)
{
    // 👉 आजको महिनाको पहिलो मिति
    $startOfCurrentMonth = Carbon::now()->startOfMonth();

    // 👉 आधार query : १) relational eager-load, २) current month भन्दा अघिको मात्र
    $adsQuery = Ad::with('customerRelation')
                  ->where('created_at', '<', $startOfCurrentMonth);

    // 👉 Range अनुसार filter + sorting
    if ($range === 'high') {

        // High = USD ≥ 100  (कुनै upper-cap छैन)
        $adsQuery->where('USD', '>=', 80)
                 ->orderByDesc('USD');                // महङ्गोदेखि सस्तो

    } elseif ($range === 'low') {

        // Low = USD ≤ 1000  (strict, 1000.01 पनि समावेश हुँदैन)
        $adsQuery->whereRaw('CAST(`USD` AS DECIMAL(10,2)) <= 1000.00')
                 ->orderBy('USD');                    // सस्तोदेखि महङ्गो
    }

    // 👉 सबै case मा पुरानो record मिति अनुसार पछिल्लोदेखि अगाडि
    $ads = $adsQuery->orderByDesc('created_at')
                    ->paginate(15);

    // 👉 Running / Ended status गणना
    foreach ($ads as $ad) {
        $ad->status = $this->calculateStatus($ad->created_at, $ad->Duration);
    }

    // 👉 view पठाउने
    return view('admin.ads_list', [
        'ads'    => $ads,
        'volume' => $range,
    ]);
}
    public function getCustomerAddons($whatsapp)
{
    $clean = preg_replace('/\D+/', '', $whatsapp);
    $clean = preg_replace('/^977/', '', $clean);

    $addons = \App\Models\Multimedia::where(function ($q) use ($clean) {
                    $q->where('whatsapp', $clean)
                      ->orWhere('whatsapp', '977'.$clean)
                      ->orWhere('whatsapp', '+977'.$clean);
                })
                ->latest()
                ->take(20)
                ->get([
                    'id',
                    'project as service_name',
                    'project_type',
                    'cost_npr as amount',
                    'date'
                ]);

    return response()->json(['data' => $addons]);
}

}