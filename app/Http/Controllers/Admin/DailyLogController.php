<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    public function index(Request $request)
{
    $admin = auth('admin')->user();
    $isSuper = $admin && $admin->id === 1; // super admin = id 1 (तपाईंको convention अनुसार)

    // Base query
    $q = DailyLog::with('admin')  // make sure relation exists: DailyLog belongsTo Admin as 'admin'
            ->orderByDesc('log_date')
            ->orderByDesc('id');

    // Date filter
    if ($request->filled('from')) {
        $q->whereDate('log_date', '>=', $request->date('from'));
    }
    if ($request->filled('to')) {
        $q->whereDate('log_date', '<=', $request->date('to'));
    }

    // Who's logs to show?
    // Super admin: default = ALL team logs (empty हुन नदिन)
    // - ?mine=1 राखेमा आफ्ना मात्र
    // Non-super: आफ्ना मात्र
    if ($isSuper) {
        if ($request->boolean('mine')) {
            $q->where('admin_id', $admin->id);
        } else {
            // keep ALL (default)
        }
    } else {
        $q->where('admin_id', $admin->id);
    }

    // (Optional) if चाहनुहुन्छ भने "कुनै पनि filter नहुँदा" last 30 days मा default
    if (!$request->hasAny(['from','to','mine','page']) && $isSuper) {
        // केही नहाले पनि सबै देखिन्छ; चाहनुहुन्छ भने uncomment:
        // $q->whereDate('log_date', '>=', now()->subDays(30));
    }

    $logs = $q->paginate(20)->withQueryString();

    return view('admin.daily_logs.index', [
        'logs'    => $logs,
        'isSuper' => $isSuper,
    ]);
}

    public function create()
    {
        $this->authorize('create', DailyLog::class);
        return view('admin.daily_logs.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', DailyLog::class);

        $data = $request->validate([
            'log_date'   => 'required|date',
            'production' => 'nullable|string',
            'reception'  => 'nullable|string',
            'operations' => 'nullable|string',
            'summary'    => 'nullable|string',
            'status'     => 'nullable|in:draft,submitted,approved',
        ]);

        // staff ले approved राख्न नपाओस्
        if (auth('admin')->id() !== 1 && ($data['status'] ?? 'submitted') === 'approved') {
            $data['status'] = 'submitted';
        }

        $log = new DailyLog($data);
        $log->admin_id = auth('admin')->id(); // request बाट कहिल्यै नलिने
        try {
            $log->save();
        } catch (\Illuminate\Database\QueryException $e) {
            // unique constraint message लाई user-friendly बनाउन
            if (str_contains($e->getMessage(), 'daily_logs_admin_id_log_date_unique')) {
                return back()
                    ->withErrors(['log_date'=>'You already have a log for this date.'])
                    ->withInput();
            }
            throw $e;
        }

        return redirect()->route('admin.daily-logs.index')->with('success','Log saved.');
    }

    public function edit(DailyLog $daily_log)
    {
        $this->authorize('update', $daily_log);
        return view('admin.daily_logs.edit', ['log'=>$daily_log]);
    }
    public function show(\App\Models\DailyLog $daily_log)
{
    $this->authorize('view', $daily_log); // Policy guard: owner or super (id=1)
    $isSuper = auth('admin')->id() === 1;

    return view('admin.daily_logs.show', [
        'log'     => $daily_log,
        'isSuper' => $isSuper,
    ]);
}

    public function update(Request $request, DailyLog $daily_log)
    {
        $this->authorize('update', $daily_log);

        $data = $request->validate([
            'log_date'   => 'required|date',
            'production' => 'nullable|string',
            'reception'  => 'nullable|string',
            'operations' => 'nullable|string',
            'summary'    => 'nullable|string',
            'status'     => 'nullable|in:draft,submitted,approved',
        ]);

        if (auth('admin')->id() !== 1 && ($data['status'] ?? null) === 'approved') {
            unset($data['status']);
        }

        $daily_log->update($data);
        return redirect()->route('admin.daily-logs.index')->with('success','Log updated.');
    }
    public function search(Request $request)
{
    $query = $request->get('q');

    $customers = \DB::table('customers')
        ->where('phone', 'like', "%{$query}%")
        ->orWhere('phone_2', 'like', "%{$query}%")
        ->limit(20)
        ->get(['id','name','display_name','phone']);

    return response()->json($customers);
}

    public function destroy(DailyLog $daily_log)
    {
        $this->authorize('delete', $daily_log);
        $daily_log->delete();
        return back()->with('success','Log deleted.');
    }
}
