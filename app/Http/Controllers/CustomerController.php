<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Mail\WelcomeCustomer;
use App\Models\Ad;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\CustomerRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Requirement;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\BonusSeason;
use App\Http\Controllers\Api\BonusController as ApiBonusController;
use Illuminate\Http\JsonResponse;
use App\Models\BonusClaim;

class CustomerController extends Controller
{
    // Export customers to Excel
    public function exportToExcel()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    // Get total customer count
    public function getTotalCustomerCount()
    {
        $count = Customer::count();
        return response()->json(['totalCount' => $count]);
    }

    // Export customers to XML
    public function exportCustomers()
    {
        $customers = Customer::all();
        $xml = new \SimpleXMLElement('<customers/>');
        foreach ($customers as $customer) {
            $xmlCustomer = $xml->addChild('customer');
            $xmlCustomer->addChild('name', $customer->name);
            $xmlCustomer->addChild('display_name', $customer->display_name ?? '');
            $xmlCustomer->addChild('email', $customer->email);
            $xmlCustomer->addChild('address', $customer->address ?? '');
            $xmlCustomer->addChild('phone', $customer->phone);
            $xmlCustomer->addChild('phone_2', $customer->phone_2 ?? '');
        }

        Header('Content-type: text/xml');
        Header('Content-Disposition: attachment; filename="customers.xml"');
        echo $xml->asXML();
        exit;
    }

    // Display add customer form
    public function add_form()
    {
        try {
            return view('customer.add');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    // Show customer list
    public function show()
    {
        $customers = Customer::with('createdByAdmin')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $totalCustomers = Customer::count();

        $currentAdmin = auth('admin')->user(); // null हुन सक्छ
        $admins = Admin::select('id', 'name')->orderBy('name')->get();

        return view('customer.list', compact('customers', 'totalCustomers', 'admins', 'currentAdmin'));
    }

    // Store new customer
    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:' . Customer::class],
            'address'      => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'numeric', 'unique:' . Customer::class],
            'phone_2'      => ['nullable', 'numeric', 'unique:' . Customer::class],
            'usd_rate'     => ['required', 'numeric', 'min:0'],
            'requires_bill'=> ['nullable', 'boolean'],
        ]);

        $adminId = Auth::guard('admin')->id() ?? Auth::id();   // guard सुरक्षित

        $customerData = $request->all();
        $customerData['password']      = bcrypt($customerData['phone']);
        $customerData['requires_bill'] = (bool)($request->requires_bill ?? false);
        $customerData['created_by']    = $adminId;

        \Log::info('Creating customer', [
            'admin_id'    => $adminId,
            'admin_guard' => Auth::guard('admin')->check()
        ]);

        $customer = Customer::create($customerData);

        Mail::to($customer->email)->send(new WelcomeCustomer($customer));

        return redirect('/admin/dashboard/customer_list')->with('success', 'Customer added successfully.');
    }

    // Delete customer
    public function delete($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $ads      = Ad::where('customer', $customer->phone)->get();
            $invoices = Invoice::where('customer', $customer->phone)->get();

            foreach ($invoices as $invoice) {
                $invoice->delete();
            }

            foreach ($ads as $ad) {
                $ad->delete();
            }

            $customer->delete();

            return redirect('/admin/dashboard/customer_list');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    // Update customer
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:255',
            'display_name'    => 'nullable|string|max:255',
            'email'           => 'required|email',
            'phone'           => 'required|numeric|unique:' . Customer::class . ',phone,' . $customer->id,
            'phone_2'         => 'nullable|numeric|unique:' . Customer::class . ',phone_2,' . $customer->id,
            'address'         => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'usd_rate'        => 'required|numeric|min:0',
            'requires_bill'   => 'nullable|boolean',
        ]);

        $customer->name         = $request->name;
        $customer->display_name = $request->display_name;
        $customer->email        = $request->email;
        $customer->phone        = $request->phone;
        $customer->phone_2      = $request->phone_2;
        $customer->address      = $request->address;
        $customer->usd_rate     = $request->usd_rate;
        $customer->requires_bill= (bool)($request->requires_bill ?? false);

        if ($request->hasFile('profile_picture')) {
            if ($customer->profile_picture && Storage::exists('uploads/customers/' . $customer->profile_picture)) {
                Storage::delete('uploads/customers/' . $customer->profile_picture);
            }

            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('Uploads/customers'), $imageName);
            $customer->profile_picture = $imageName;
        }

        if ($request->remove_profile_picture == 'yes') {
            if ($customer->profile_picture && Storage::exists('uploads/customers/' . $customer->profile_picture)) {
                Storage::delete('uploads/customers/' . $customer->profile_picture);
            }
            $customer->profile_picture = null;
        }

        $customer->requires_bill = $request->boolean('requires_bill');
        $customer->save();

        return redirect('/admin/dashboard/customer_list')->with('success', 'Customer updated successfully.');
    }

    // Show customer update form with financial data
    public function update_form($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $totalUSDAllTime      = Ad::where('customer', $customer->phone)->sum('USD');
            $totalNPRAllTime      = Ad::where('customer', $customer->phone)->sum('NRP');
            $totalQuantityAllTime = Ad::where('customer', $customer->phone)->sum('Quantity');

            $totalUSDThisMonth = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('USD');
            $totalNPRThisMonth = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('NRP');
            $totalQuantityThisMonth = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('Quantity');

            $totalUSDThisToday = Ad::where('customer', $customer->phone)
                ->whereDate('created_at', Carbon::today())
                ->sum('USD');
            $totalNPRThisToday = Ad::where('customer', $customer->phone)
                ->whereDate('created_at', Carbon::today())
                ->sum('NRP');
            $totalQuantityThisToday = Ad::where('customer', $customer->phone)
                ->whereDate('created_at', Carbon::today())
                ->sum('Quantity');

            $currentMonthName = Carbon::now()->format('F Y');
            $currentMonth     = Carbon::now()->month;
            $currentYear      = Carbon::now()->year;

            return view('customer.update', compact(
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
                'currentMonthName',
                'currentMonth',
                'currentYear'
            ));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    // Search for customers
    public function search(Request $request)
    {
        try {
            $customers = Customer::query()->with('createdByAdmin');

            if ($request->filled('search')) {
                $search = $request->search;

                $customers->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('display_name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('phone_2', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%')
                        ->orWhere('usd_rate', 'like', '%' . $search . '%');
                })
                ->orWhereHas('createdByAdmin', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            if ($request->filled('date_range')) {
                [$startDate, $endDate] = explode(' - ', $request->date_range);
                $customers->whereHas('ads', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                })->withSum(['ads as total_npr' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                }], 'NRP')->orderBy('total_npr', 'desc');
            } else {
                $customers->withSum('ads as total_npr', 'NRP')->orderBy('total_npr', 'desc');
            }

            $customers      = $customers->paginate(10);
            $totalCustomers = Customer::count();

            $currentAdmin = auth('admin')->user();
            $admins       = Admin::select('id', 'name')->orderBy('name')->get();

            return view('customer.list', compact('customers', 'totalCustomers', 'admins', 'currentAdmin'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    // Show customer details with pagination for months and receipts
    public function showDetails($id, $startMonthOffset = 0)
    {
        $customer = Customer::findOrFail($id);

        // base query
        $baseAdsQuery = Ad::where('customer', $customer->phone)
            ->orderBy('created_at', 'desc');

        // Pagination + full collection (clone प्रयोग)
        $paginatedAds = (clone $baseAdsQuery)->paginate(5);
        $ads          = (clone $baseAdsQuery)->get();

        /** ===== All Time Totals ===== */
        $totalUSDAllTime      = $ads->sum('USD');
        $totalNPRAllTime      = $ads->sum('NRP');
        $totalQuantityAllTime = $ads->sum('Quantity');

        /** ===== This Month Totals ===== */
        $totalUSDThisMonth = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('USD');

        $totalNPRThisMonth = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('NRP');

        $totalQuantityThisMonth = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('Quantity');

        /** ===== Today Totals ===== */
        $totalUSDThisToday = Ad::where('customer', $customer->phone)
            ->whereDate('created_at', Carbon::today())
            ->sum('USD');

        $totalNPRThisToday = Ad::where('customer', $customer->phone)
            ->whereDate('created_at', Carbon::today())
            ->sum('NRP');

        $totalQuantityThisToday = Ad::where('customer', $customer->phone)
            ->whereDate('created_at', Carbon::today())
            ->sum('Quantity');

        /** ===== Due / Paid / Latest Ad Info ===== */
        $myOrderAmount = $ads->sum('NRP');
        $latestAdDate  = $ads->max('created_at');

        $latestAds = $ads->filter(function ($ad) use ($latestAdDate) {
            return $ad->created_at == $latestAdDate;
        });

        $quantity = $latestAds->sum('Quantity');

        $dueAmount   = 0;
        $paidInvoice = 0;

        foreach ($ads as $ad) {
            if (in_array($ad->Payment, ['Pending', 'Paused'])) {
                $dueAmount += $ad->NRP;
            } elseif ($ad->Payment === 'Baki') {
                $dueAmount   += $ad->advance;
                $paidInvoice += $ad->NRP;
            } elseif (in_array($ad->Payment, ['FPY Received', 'eSewa Received', 'Paid', 'PV Adjusted'])) {
                $paidInvoice += $ad->NRP;
            }
        }

        $dueDate = $ads->where('Payment', 'Baki')->max('due_date') ?? 'N/A';

        /** ===== Previous Months Data ===== */
        $months             = [];
        $previousMonthsData = [];

        for ($i = 0; $i < 5; $i++) {
            $date   = now()->subMonths($i + $startMonthOffset);
            $month  = $date->format('F Y');
            $months[] = $month;

            $previousMonthsData[$month] = [
                'usd'      => Ad::where('customer', $customer->phone)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('USD'),
                'npr'      => Ad::where('customer', $customer->phone)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('NRP'),
                'quantity' => Ad::where('customer', $customer->phone)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('Quantity'),
            ];
        }

        /** ===========================
         *  BONUS SEASON LOGIC
         *  =========================== */

        // DB बाट active season (UI मा देखाउनेका लागि)
        $activeBonusSeason = BonusSeason::where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        $bonusCredit    = 0;
        $bonusBreakdown = [];

        if ($activeBonusSeason) {
            $start = Carbon::parse($activeBonusSeason->start_date)->startOfDay();
            $end   = Carbon::parse($activeBonusSeason->end_date)->endOfDay();

            $percent   = (float) ($activeBonusSeason->bonus_rate ?? 0);  // e.g. 20
            $rate      = $percent / 100;                                 // e.g. 0.20
            $threshold = (float) ($activeBonusSeason->min_spend ?? 0);   // e.g. 300

            $adTotals = Ad::selectRaw("TO_CHAR(created_at, 'YYYY-MM') as ym, SUM(\"USD\") as total_usd")
                ->where('customer', $customer->phone)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('ym')
                ->orderBy('ym')
                ->get();

            foreach ($adTotals as $row) {
                if ($row->total_usd >= $threshold && $rate > 0) {
                    $monthBonus   = $row->total_usd * $rate;
                    $bonusCredit += $monthBonus;

                    $bonusBreakdown[] = [
                        'month' => $row->ym,
                        'spend' => $row->total_usd,
                        'bonus' => $monthBonus,
                    ];
                }
            }
        }

        // API BonusController बाट summary reuse
        $bonusSummary = null;

        if ($activeBonusSeason) {
            try {
                /** @var \App\Http\Controllers\Api\BonusController $bonusApiCtrl */
                $bonusApiCtrl    = app(ApiBonusController::class);
                $summaryResponse = $bonusApiCtrl->summary($customer);

                if ($summaryResponse instanceof JsonResponse) {
                    $payload = $summaryResponse->getData(true); // associative array
                } else {
                    // future-proof: यदि कसैले summary() लाई array return गरिदियो भने
                    $payload = $summaryResponse;
                }

                if (is_array($payload) && isset($payload['data']) && is_array($payload['data'])) {
                    $bonusSummary = $payload['data'];
                }

            } catch (\Throwable $e) {
                \Log::error('Bonus summary error for customer ' . $customer->id . ': ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                $bonusSummary = null;
            }
        }

        return view('customer.details', compact(
            'customer',
            'ads',
            'paginatedAds',
            'myOrderAmount',
            'quantity',
            'dueAmount',
            'paidInvoice',
            'dueDate',
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
            'startMonthOffset',
            'activeBonusSeason',
            'bonusCredit',
            'bonusBreakdown',
            'bonusSummary'
        ));
    }

    // Fetch financial year data
    public function getFinancialYearData(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $startDate   = $request->input('start_date');
        $endDate     = $request->input('end_date');

        $customer = Customer::findOrFail($customer_id);

        $usd = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('USD');

        $npr = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('NRP');

        $quantity = Ad::where('customer', $customer->phone)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('Quantity');

        return response()->json([
            'data' => [
                'usd'      => $usd,
                'npr'      => $npr,
                'quantity' => $quantity,
            ]
        ]);
    }

    // Get customer rate
    public function getCustomerRate(Request $request)
    {
        $phone    = $request->query('phone');
        $customer = Customer::where('phone', $phone)->first();

        if ($customer) {
            return response()->json(['success' => true, 'rate' => $customer->usd_rate]);
        }

        return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);
    }

    // Download all receipts
    public function downloadAllReceipts(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $ads = Ad::where('customer', $customer->phone)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAmount = $ads->sum('amount');

        $daterange = $startDate && $endDate ? "$startDate - $endDate" : 'All Time';

        $pdf = Pdf::loadView('downloadable.all_receipts_pdf', compact('ads', 'customer', 'daterange', 'totalAmount'));

        return $pdf->download('all_receipts.pdf');
    }

    // Fetch all requirements for a customer
    public function getRequirements($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            if (auth('admin')->check() || (auth('customer')->check() && auth('customer')->id() == $customer->id)) {
                $requirements = CustomerRequirement::where('customer_id', $customer->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                return response()->json(['requirements' => $requirements], 200);
            }

            return response()->json(['error' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            \Log::error('Error fetching requirements for customer ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load requirements'], 500);
        }
    }

    // Store a new requirement
    public function storeRequirement(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'body'      => 'required|string',
            'note_type' => 'required|in:requirement,suggestion,post_caption,greeting,faq',
            'priority'  => 'required|in:high,medium,low',
        ]);

        $requirement = CustomerRequirement::create([
            'customer_id' => $customer->id,
            'note_type'   => $request->note_type,
            'priority'    => $request->priority,
            'body'        => $request->body,
        ]);

        return response()->json(['success' => true, 'requirement' => $requirement]);
    }

    // Update an existing requirement
    public function updateRequirement(Request $request, $requirementId)
    {
        $request->validate([
            'body'      => 'required|string',
            'note_type' => 'required|in:requirement,suggestion,post_caption,greeting,faq',
            'priority'  => 'required|in:high,medium,low',
        ]);

        $requirement = CustomerRequirement::findOrFail($requirementId);

        if (auth('admin')->check() || (auth('customer')->check() && $requirement->customer_id === auth('customer')->id())) {
            $requirement->update([
                'body'      => $request->body,
                'note_type' => $request->note_type,
                'priority'  => $request->priority,
            ]);

            return response()->json(['message' => 'Note updated successfully', 'data' => $requirement]);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Delete a requirement
    public function deleteRequirement($requirementId)
    {
        $requirement = CustomerRequirement::findOrFail($requirementId);

        if (auth('admin')->check() || (auth('customer')->check() && $requirement->customer_id === auth('customer')->id())) {
            $requirement->delete();
            return response()->json(['message' => 'Note deleted successfully']);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Show a single requirement in a new tab
    public function showRequirement($requirementId)
    {
        $requirement = CustomerRequirement::findOrFail($requirementId);

        if (auth('admin')->check() || (auth('customer')->check() && $requirement->customer_id === auth('customer')->id())) {
            return response()->json(['data' => $requirement]);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function showRequirementDetail($id)
    {
        $requirement = CustomerRequirement::findOrFail($id);
        return view('customer.requirement-detail', compact('requirement'));
    }

    public function toggleRequiresBill(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if (!auth('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['requires_bill' => 'required|boolean']);

        $customer->requires_bill = $request->boolean('requires_bill');
        $customer->save();

        return response()->json([
            'success'       => true,
            'requires_bill' => $customer->requires_bill,
            'message'       => 'Updated successfully'
        ]);
    }

    // --- lightweight list for dropdown / datalist ---
    public function minimal(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $customers = \DB::table('customers')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('display_name', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('phone_2', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->limit(50)
            ->get(['id', 'display_name', 'name', 'phone', 'phone_2']);

        $list = $customers->map(function ($c) {
            $label = trim(($c->display_name ?: $c->name) ?: 'Unnamed');
            $phone = $c->phone ?: $c->phone_2;
            return [
                'id'    => $c->id,
                'label' => $label,
                'name'  => $c->name,
                'phone' => $phone,
            ];
        });

        return response()->json($list);
    }

    // --- lookup-by-phone / WhatsApp ---
    public function lookupByPhone(Request $request)
    {
        $raw    = (string) $request->get('q', '');
        $needle = preg_replace('/\D+/', '', $raw); // keep digits only

        // normalize: drop leading 0s and country code 977 if present
        $needle = ltrim($needle, '0');
        if (str_starts_with($needle, '977')) {
            $needle = substr($needle, 3);
        }

        if ($needle === '') {
            return response()->json(['found' => false, 'message' => 'Empty query']);
        }

        $c = \DB::table('customers')
            ->select('id', 'display_name', 'name', 'phone', 'phone_2')
            ->where(function ($w) use ($needle) {
                $w->orWhereRaw("REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+', '') LIKE ?", ["%{$needle}%"])
                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(phone_2, ' ', ''), '-', ''), '+', '') LIKE ?", ["%{$needle}%"]);
            })
            ->orderByDesc('id')
            ->first();

        if (!$c) {
            return response()->json(['found' => false, 'message' => 'No convert as customer']);
        }

        return response()->json([
            'found' => true,
            'id'    => $c->id,
            'label' => ($c->display_name ?: $c->name) ?: 'Unnamed',
            'name'  => $c->name,
            'phone' => $c->phone ?: $c->phone_2,
        ]);
    }

    public function quickSearch(Request $request)
    {
        $term = trim($request->get('term', ''));
        if ($term === '') {
            return response()->json(['data' => []]);
        }

        $q = Customer::query()
            ->select(['id', 'name', 'display_name', 'phone'])
            ->when(is_numeric($term), function ($qq) use ($term) {
                $qq->where('phone', 'like', "%{$term}%");
            }, function ($qq) use ($term) {
                $qq->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', "%{$term}%")
                        ->orWhere('display_name', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        $data = $q->map(function ($c) {
            $label = $c->display_name ?: $c->name ?: $c->phone;
            return [
                'id'           => $c->id,
                'label'        => $label,
                'name'         => $c->name,
                'display_name' => $c->display_name,
                'phone'        => $c->phone,
            ];
        });

        return response()->json(['data' => $data]);
    }
    
        public function claimBonus(Request $request, Customer $customer)
{
    // amount validate
    $request->validate([
        'amount_usd' => ['required', 'numeric', 'min:0.01'],
    ]);

    // API BonusController बाट summary reuse गर्ने
    /** @var \App\Http\Controllers\Api\BonusController $bonusApiCtrl */
    $bonusApiCtrl = app(ApiBonusController::class);

    $summaryResponse = $bonusApiCtrl->summary($customer);

    if ($summaryResponse instanceof JsonResponse) {
        $payload = $summaryResponse->getData(true); // associative array
    } else {
        $payload = $summaryResponse;
    }

    if (!is_array($payload) || !isset($payload['data']) || !is_array($payload['data'])) {
        return response()->json([
            'success' => false,
            'message' => 'Bonus season summary उपलब्ध भएन।',
        ], 422);
    }

    // --- summary बाट डेटा निकाल्ने ---
    $data      = $payload['data'];
    $status    = $data['status']          ?? null;
    $canClaim  = (bool)($data['can_claim'] ?? false);
    $claimable = (float)($data['claimable_usd'] ?? 0);
    $seasonId  = $data['season_id']       ?? null;

    if ($status !== 'claim_window_open' || !$canClaim || $claimable <= 0 || !$seasonId) {
        return response()->json([
            'success' => false,
            'message' => 'अहिले bonus claim गर्ने समय छैन वा claimable छैन।',
        ], 422);
    }

    // seasonId पक्का भइसकेपछि मात्रै DB बाट season फेला पार्ने
    $season = BonusSeason::find($seasonId);
    if (!$season) {
        return response()->json([
            'success' => false,
            'message' => 'Bonus season फेला परेन।',
        ], 422);
    }

    // user ले claim गर्न खोजेको amount
    $amount = (float)$request->input('amount_usd');

    if ($amount <= 0 || $amount > $claimable) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid claim amount. Maximum claimable: $' . number_format($claimable, 2),
        ], 422);
    }

    // full कि partial ?
    $mode = (abs($amount - $claimable) < 0.0001) ? 'full' : 'partial';

    // season_code कहिल्यै null नहोस् भनेर fallback
    // bonus_seasons तालिकामा season_code column छैन भने
    // safe fallback: "S" + season id
    $seasonCode = 'S' . $season->id;

    // claim create गर्ने
    $claim = BonusClaim::create([
        'customer_id'     => $customer->id,
        'bonus_season_id' => $season->id,
        'season_code'     => $seasonCode,          // अब null हुँदैन
        'amount_usd'      => $amount,
        'mode'            => $mode,                // column DB मा भएको भए
        'source'          => 'admin_panel',        // column DB मा भएको भए
        'claimed_by'      => auth('admin')->id(),  // admin ले claim गर्यो
        'status'          => 'pending',
        'claimed_at'      => now(),
    ]);

    // अब पुन: summary लिएर UI update गर्न पठाउने
    $newSummaryResponse = $bonusApiCtrl->summary($customer);

    if ($newSummaryResponse instanceof JsonResponse) {
        $newPayload = $newSummaryResponse->getData(true);
    } else {
        $newPayload = $newSummaryResponse;
    }

    $newData = is_array($newPayload) ? ($newPayload['data'] ?? null) : null;

    return response()->json([
        'success' => true,
        'message' => 'Bonus सफलतापूर्वक claim भयो।',
        'data'    => $newData,
        'claim'   => [
            'id'         => $claim->id,
            'amount_usd' => $claim->amount_usd,
            'mode'       => $claim->mode,
        ],
    ]);
}

}
