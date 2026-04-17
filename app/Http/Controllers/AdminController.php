<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Ad;
use App\Models\Invoice;
use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function login_form()
    {
        return view('admin.login');
    }

    public function register_form()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        $user = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
        ]);

        Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'));

        return redirect()->route('ads.showAllAds');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            $this->trackUserActivity(new Request(['activity_type' => 'login']));
            return redirect()->route('ads.showAllAds');
        } else {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
public function showUserDetails($id)
{
    $user = Admin::findOrFail($id);

    // user_activities row नहुँदा पनि view crash नहोस्
    $userActivity = UserActivity::firstOrCreate(['user_id' => $id]);

    // Online flag (last_activity 5 min भित्र भए online)
    $isOnline = $userActivity && $userActivity->last_activity
        ? now()->diffInMinutes($userActivity->last_activity) <= 5
        : false;

    // time windows
    $since7  = Carbon::now()->subDays(7)->startOfDay();
    $since30 = Carbon::now()->subDays(30)->startOfDay();

    // owner fields (by id) + name owner fields
    $ownerFields     = ['created_by','admin_id','user_id','created_user_id','added_by'];
    $nameOwnerFields = ['admin','created_by_name','created_by_username','added_by_name'];
    $adminName       = $user->name;

    // detect available columns per table (ads/invoices/clients)
    $ownerColsAds      = array_values(array_filter($ownerFields,     fn($c)=> Schema::hasColumn('ads', $c)));
    $nameColsAds       = array_values(array_filter($nameOwnerFields, fn($c)=> Schema::hasColumn('ads', $c)));

    $ownerColsInvoices = array_values(array_filter($ownerFields,     fn($c)=> Schema::hasColumn('invoices', $c)));
    $nameColsInvoices  = array_values(array_filter($nameOwnerFields, fn($c)=> Schema::hasColumn('invoices', $c)));

    $ownerColsClients  = array_values(array_filter($ownerFields,     fn($c)=> Schema::hasColumn('clients', $c)));
    $nameColsClients   = array_values(array_filter($nameOwnerFields, fn($c)=> Schema::hasColumn('clients', $c)));

    // ADS / INVOICES / CLIENTS base queries (id OR name columns)
    $adsBase = Ad::query()->where(function($q) use ($id, $adminName, $ownerColsAds, $nameColsAds){
        foreach($ownerColsAds as $col){ $q->orWhere($col, $id); }
        foreach($nameColsAds  as $col){ $q->orWhere($col, $adminName); }
    });

    $invBase = Invoice::query()->where(function($q) use ($id, $adminName, $ownerColsInvoices, $nameColsInvoices){
        foreach($ownerColsInvoices as $col){ $q->orWhere($col, $id); }
        foreach($nameColsInvoices  as $col){ $q->orWhere($col, $adminName); }
    });

    $cliBase = Client::query()->where(function($q) use ($id, $adminName, $ownerColsClients, $nameColsClients){
        foreach($ownerColsClients as $col){ $q->orWhere($col, $id); }
        foreach($nameColsClients  as $col){ $q->orWhere($col, $adminName); }
    });

    // KPIs (7 दिन)
    $adsCreated7      = (clone $adsBase)->where('created_at','>=',$since7)->count();
    $invoicesCreated7 = (clone $invBase)->where('created_at','>=',$since7)->count();
    $clientsAdded7    = (clone $cliBase)->where('created_at','>=',$since7)->count();

    $adsMoney7 = (clone $adsBase)->where('created_at','>=',$since7)
        ->selectRaw("SUM(COALESCE(\"NRP\",0)) as total_nrp, SUM(COALESCE(\"USD\",0)) as total_usd")
        ->first();

    $kpis = [
        'ads_created'      => (int) $adsCreated7,
        'invoices_created' => (int) $invoicesCreated7,
        'clients_added'    => (int) $clientsAdded7,
        'nrp'              => (float) ($adsMoney7->total_nrp ?? 0),
        'usd'              => (float) ($adsMoney7->total_usd ?? 0),
    ];

    /**
     * helper: generic base-query builder (customers/multimedia/crm_*)
     */
    // helper: generic base-query builder (customers/multimedia/crm_*)
$buildBase = function(string $table, array $ownerCols, array $nameCols) use ($id, $adminName) {
    $qb = DB::table($table);

    // --- Fallback 1: no detectable owner columns => return unfiltered (show all)
    if (empty($ownerCols) && empty($nameCols)) {
        return $qb;
    }

    return $qb->where(function($q) use ($id, $adminName, $ownerCols, $nameCols){
        foreach ($ownerCols as $col) { $q->orWhere($col, $id); }
        foreach ($nameCols  as $col) { $q->orWhere($col, $adminName); }
    });
};


    // detect columns & build bases for customers/multimedia/crm tables
    $ownerColsCustomers = array_values(array_filter($ownerFields,     fn($c)=> Schema::hasColumn('customers', $c)));
    $nameColsCustomers  = array_values(array_filter($nameOwnerFields, fn($c)=> Schema::hasColumn('customers', $c)));

    $ownerColsMedia = array_values(array_filter($ownerFields,     fn($c)=> Schema::hasColumn('multimedia', $c)));
    $nameColsMedia  = array_values(array_filter($nameOwnerFields, fn($c)=> Schema::hasColumn('multimedia', $c)));

    $ownerColsFUContacts = array_values(array_filter($ownerFields,     fn($c)=> Schema::hasColumn('crm_contacts', $c)));
    $nameColsFUContacts  = array_values(array_filter($nameOwnerFields, fn($c)=> Schema::hasColumn('crm_contacts', $c)));

    $ownerColsFUs = array_values(array_filter($ownerFields,     fn($c)=> Schema::hasColumn('crm_follow_ups', $c)));
    $nameColsFUs  = array_values(array_filter($nameOwnerFields, fn($c)=> Schema::hasColumn('crm_follow_ups', $c)));

    $cusBase       = $buildBase('customers',      $ownerColsCustomers,  $nameColsCustomers);
    $mediaBase     = $buildBase('multimedia',     $ownerColsMedia,      $nameColsMedia);
    $fuContactBase = $buildBase('crm_contacts',   $ownerColsFUContacts, $nameColsFUContacts);
    $followupBase  = $buildBase('crm_follow_ups', $ownerColsFUs,        $nameColsFUs);

    // totals + last 7 days (extra KPIs)
    $customers_total   = (clone $cusBase)->count();
    $customers_7d      = (clone $cusBase)->where('created_at','>=',$since7)->count();

    $multimedia_total  = (clone $mediaBase)->count();
    $multimedia_7d     = (clone $mediaBase)->where('created_at','>=',$since7)->count();

    $fu_contacts_total = (clone $fuContactBase)->count();
    $fu_contacts_7d    = (clone $fuContactBase)->where('created_at','>=',$since7)->count();

    $followups_total   = (clone $followupBase)->count();
    $followups_7d      = (clone $followupBase)->where('created_at','>=',$since7)->count();

    $kpis = array_merge($kpis, [
        'customers_total'   => (int) $customers_total,
        'customers_7d'      => (int) $customers_7d,
        'multimedia_total'  => (int) $multimedia_total,
        'multimedia_7d'     => (int) $multimedia_7d,
        'fu_contacts_total' => (int) $fu_contacts_total,
        'fu_contacts_7d'    => (int) $fu_contacts_7d,
        'followups_total'   => (int) $followups_total,
        'followups_7d'      => (int) $followups_7d,
    ]);

    // 30 दिनको गतिविधि (दिन–दिनमा Ads+Invoices+Clients को जोड)
    $adsDaily = (clone $adsBase)->where('created_at','>=',$since30)
        ->selectRaw('DATE(created_at) as d, COUNT(*) c')
        ->groupBy('d')->pluck('c','d')->toArray();

    $invDaily = (clone $invBase)->where('created_at','>=',$since30)
        ->selectRaw('DATE(created_at) as d, COUNT(*) c')
        ->groupBy('d')->pluck('c','d')->toArray();

    $cliDaily = (clone $cliBase)->where('created_at','>=',$since30)
        ->selectRaw('DATE(created_at) as d, COUNT(*) c')
        ->groupBy('d')->pluck('c','d')->toArray();

    $dailyLabels = [];
    $dailyCounts = [];
    for ($i = 30; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i)->toDateString();
        $dailyLabels[] = Carbon::parse($date)->format('d M');
        $dailyCounts[] = (int)($adsDaily[$date] ?? 0)
                       + (int)($invDaily[$date] ?? 0)
                       + (int)($cliDaily[$date] ?? 0);
    }

    // Recent items (latest 12)
    $recentAds = (clone $adsBase)->orderBy('created_at','desc')->limit(12)->get(['id','created_at']);
    $recentInv = (clone $invBase)->orderBy('created_at','desc')->limit(12)->get(['id','created_at']);
    $recentCli = (clone $cliBase)->orderBy('created_at','desc')->limit(12)->get(['id','created_at']);

    // Pretty map (optional)
    $pageNameMap = [
        'admin.user.details' => 'User Details',
        'admin.user.list'    => 'User List',
        'admin.dashboard'    => 'Dashboard',
        'ads.show'           => 'Daily Records',
        'ads_complete.show'  => 'Previous Records',
        'ads.summary'        => 'Monthly Summary',
        'invoice.list'       => 'Invoice List',
        'ads.showAllAds'          => 'Ad List',
        'admin.multimedia.index'  => 'Multimedia',
        'customer.show'           => 'Customer Detail',
        'calculate.dailySpend'    => 'Daily Spend (Calc)',
        'calculate.activeAds'     => 'Active Ads (Calc)',
        'calculate.arab'          => 'Arab Calc',
        '/api/getNote'            => 'Get Note (API)',
        '/admin/customer/getRate' => 'Customer Rate (API)',
    ];

    return view('admin.user.details', compact(
        'user','userActivity','isOnline',
        'kpis','dailyLabels','dailyCounts',
        'recentAds','recentInv','recentCli','pageNameMap'
    ));
}

    


    public function updateLocation(Request $request)
{
    // simply reuse trackUserActivity logic
    $request->merge([
        // flag to separate, if you want
        'activity_type' => 'page_visit',
    ]);
    return $this->trackUserActivity($request);
}

    public function trackUserActivity(Request $request)
{
    $userId = Auth::guard('admin')->id();
    if (!$userId) return response()->json(['status'=>'ignored'], 200);

    $ua = UserActivity::firstOrNew(['user_id' => $userId]);

    // 1) login/page_visit जस्तै discrete events (optional: जस्तो थियो त्यस्तै राख्नुस्)
    if ($request->has('activity_type')) {
        switch ($request->input('activity_type')) {
            case 'login':
                $ua->login_time = now();
                break;
            case 'page_visit':
                if ($request->has('visited_page')) {
                    $visitedPage = $request->input('visited_page');
                    $pages = json_decode($ua->frequent_page, true) ?? [];
                    // count-map बनाउँछौं: {'/route': count}
                    if (is_array($pages)) {
                        $pages[$visitedPage] = isset($pages[$visitedPage]) ? ((int)$pages[$visitedPage] + 1) : 1;
                        arsort($pages);
                        $pages = array_slice($pages, 0, 10, true);
                    }
                    $ua->frequent_page = json_encode($pages);
                }
                break;
        }
    }

    // 2) Heartbeat बाट आएको active/idle सेकेन्ड सम्हाल्ने (यो नै main fix)
    $activeDeltaSec = (int) $request->input('activeDelta', 0);
    $idleDeltaSec   = (int) $request->input('idleDelta', 0);

    if ($activeDeltaSec > 0 || $idleDeltaSec > 0) {
        // सेकेन्डलाई मिनेटमा रूपान्तरण (round वा floor – यहाँ round)
        $addActiveMin = (int) round($activeDeltaSec / 60);
        $addIdleMin   = (int) round($idleDeltaSec / 60);

        $ua->active_hours  = (int)($ua->active_hours ?? 0) + $addActiveMin;
        $ua->inactive_time = (int)($ua->inactive_time ?? 0) + $addIdleMin;

        // page path आए frequent_page मा पनि count थपिदिउँ (top-10 राखेर)
        if ($request->filled('path')) {
            $path = '/'.ltrim($request->input('path'), '/');
            $freq = json_decode($ua->frequent_page, true) ?? [];
            if (!is_array($freq)) $freq = [];
            $freq[$path] = isset($freq[$path]) ? ((int)$freq[$path] + 1) : 1;
            arsort($freq);
            $freq = array_slice($freq, 0, 10, true);
            $ua->frequent_page = json_encode($freq);
        }

        // refresh_rate हटाउन चाहनुहुन्छ भने यो line comment वा delete गर्नुस्
        // $ua->refresh_rate = (int)($ua->refresh_rate ?? 0) + 1;
    }

    // 3) पुरानो activity_start/activity_end logic चाहिन्चा भने राख्नुस् (optional)
    if ($request->has('activity_start')) {
        $ua->last_active_start = now();
    }
    if ($request->has('activity_end')) {
        if ($ua->last_active_start) {
            $activeDuration = $ua->last_active_start->diffInMinutes($ua->last_active_end ?? now());
            $ua->active_hours = (int)($ua->active_hours ?? 0) + (int)$activeDuration;
        }
        $ua->last_active_end = now();
    }

    // 4) location (optional)
    if ($request->has(['latitude','longitude'])) {
        $ua->latitude  = $request->input('latitude');
        $ua->longitude = $request->input('longitude');
    }

    // 5) data entry count — तपाईंले “हटाइदिनुस्” भन्नुभएको थियो, त्यसैले यहाँ increment नगर्ने
    // NOTE: कुनै वास्तविक “create” action मा मात्रै बढाउने—यो endpoint मा होइन।
    // $date = now()->toDateString();
    // $dataEntries = json_decode($ua->daily_data_entries, true) ?? [];
    // $dataEntries[$date] = ($dataEntries[$date] ?? 0) + 1;
    // $ua->daily_data_entries = json_encode($dataEntries);

    $ua->last_activity = now();
    $ua->save();

    return response()->json(['status' => 'success']);
}


    public function checkUserStatus($id)
    {
        $userActivity = UserActivity::where('user_id', $id)->first();
        $isOnline = false;

        if ($userActivity && $userActivity->last_activity) {
            $isOnline = now()->diffInMinutes($userActivity->last_activity) <= 5;
        }

        return response()->json(['isOnline' => $isOnline]);
    }
    public function showAdmin($id) {
    $user = \App\Models\Admin::with('departments')->findOrFail($id);
    $ua   = UserActivity::firstOrCreate(['user_id' => $user->id]);

    $ts = Cache::get("admin:last_seen:{$user->id}");
    $isOnline = $ts ? (now()->timestamp - $ts) <= 180 : false;

    // build $kpis, $dailyLabels, $dailyCounts, $recentAds, $recentInv, $recentCli here ...
    return view('admin.user.details', compact('user','ua','isOnline', /* ... */));
}

}
