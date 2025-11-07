<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Ad;

class CustomerDashboardController extends Controller
{
    /**
     * Display the customer dashboard with the latest and consistent data.
     */
    public function index(Request $request, $offset = 0)
    {
        // Check if the admin is impersonating a customer
        if (Session::has('impersonate_customer_id')) {
            $customerId = Session::get('impersonate_customer_id');
            $customer = Customer::findOrFail($customerId);
        } else {
            // Get the logged-in customer
            $customer = Auth::guard('customer')->user();

            if (!$customer) {
                return redirect()->route('portal.login')->with('error', 'Please log in to access the dashboard.');
            }
        }

        // Fetch all ads for the customer
        $ads = Ad::where('customer', $customer->phone)
          ->orderBy('created_at', 'desc')
          ->get();

        // Filter ads for today only
        $todayAds = $ads->filter(function ($ad) {
            return $ad->created_at->isToday();
        });

        // Calculate "My Order Amount" (for today)
        $myOrderAmount = $todayAds->sum('NRP');

        // Calculate "Quantity" (for today)
        $quantity = $todayAds->sum(function ($ad) {
            return $ad->Quantity ?? 0; // Default to 0 if Quantity is null
        });

        // Calculate "Unpaid Invoice" and "Paid Invoice"
        $dueAmount = 0;
        $paidInvoice = 0;

        foreach ($todayAds as $ad) {
            if (in_array($ad->Payment, ['Pending', 'Paused'])) {
                $dueAmount += $ad->NRP;
            } elseif ($ad->Payment === 'Baki') {
                $dueAmount += $ad->advance;
                $paidInvoice += $ad->NRP - $ad->advance;
            } elseif (in_array($ad->Payment, ['FPY Received', 'eSewa Received', 'Paid', 'PV Adjusted'])) {
                $paidInvoice += $ad->NRP;
            }
        }

        // Determine the due date status
        $dueStatus = null;
        if ($dueAmount > 0) {
            $now = Carbon::now();
            $cutoffTime = Carbon::today()->setHour(17); // Today at 5PM

            $dueStatus = $now->greaterThan($cutoffTime)
                ? 'Payment date exceeded'
                : 'Due Today till 5PM';
        }

        // Get "Due Date" for ads with "Baki" status
        $dueDate = $todayAds->where('Payment', 'Baki')->max('due_date') ?? 'N/A';

        // Get the USD rate
        $usdRate = $customer->usd_rate ?? 170;

        // Calculate data for the current month
        $totalUSDThisMonth = $ads->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('USD');
        $totalNPRThisMonth = $ads->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('NRP');
        $totalQuantityThisMonth = $ads->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('Quantity');

        // Get historical data
        $totalUSDAllTime = $ads->sum('USD');
        $totalQuantityAllTime = $ads->sum('Quantity');

        // Calculate "Previous Months Data"
        $previousMonthsData = [];
        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::now()->subMonths($offset + $i);
            $month = $date->format('F Y');

            $usd = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('USD');
            $npr = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('NRP');
            $quantity = Ad::where('customer', $customer->phone)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('Quantity');

            $previousMonthsData[$month] = [
                'usd' => $usd,
                'npr' => $npr,
                'quantity' => $quantity,
            ];
        }

        // Financial year summary
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $financialYearData = [
            'usd' => Ad::where('customer', $customer->phone)
                ->whereBetween('created_at', [$startOfYear, $endOfYear])
                ->sum('USD'),
            'npr' => Ad::where('customer', $customer->phone)
                ->whereBetween('created_at', [$startOfYear, $endOfYear])
                ->sum('NRP'),
            'quantity' => Ad::where('customer', $customer->phone)
                ->whereBetween('created_at', [$startOfYear, $endOfYear])
                ->sum('Quantity'),
        ];

        // Pass all data to the view
        return view('auth.dashboard', compact(
            'ads',
            'customer',
            'myOrderAmount',
            'quantity',
            'dueAmount',
            'paidInvoice',
            'dueDate',
            'dueStatus',
            'totalUSDThisMonth',
            'totalNPRThisMonth',
            'totalQuantityThisMonth',
            'totalUSDAllTime',
            'totalQuantityAllTime',
            'previousMonthsData',
            'financialYearData',
            'offset',
            'usdRate'
        ));
    }

    /**
     * Impersonate a customer as an admin.
     */
    public function impersonate($id)
    {
        // Ensure admin is logged in
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized access.');
        }

        // Find the customer to impersonate
        $customer = Customer::findOrFail($id);

        // Set impersonation session
        Session::put('impersonate_customer_id', $customer->id);

        return redirect()->route('portal.dashboard')->with('success', "You are now impersonating {$customer->name}");
    }

    /**
     * Stop impersonating a customer as an admin.
     */
    public function stopImpersonation()
    {
        // Ensure admin is logged in
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Unauthorized access.');
        }

        // Clear impersonation session
        Session::forget('impersonate_customer_id');

        return redirect()->route('admin.dashboard')->with('success', 'Stopped impersonating the customer.');
    }
    public function fetchTableData($offset = 0)
{
    $customer = Auth::guard('customer')->user();

    if (!$customer) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $previousMonthsData = [];
    for ($i = 0; $i < 5; $i++) {
        $date = Carbon::now()->subMonths($offset + $i);
        $month = $date->format('F Y');

        $npr = Ad::where('customer', $customer->phone)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->sum('NRP');
        $quantity = Ad::where('customer', $customer->phone)
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->sum('Quantity');

        $previousMonthsData[$month] = [
            'npr' => $npr,
            'quantity' => $quantity,
        ];
    }

    return response()->json(['data' => $previousMonthsData]);
}
    public function showProfileSettings()
{
    $customer = Auth::guard('customer')->user();

    if (!$customer) {
        return redirect()->route('portal.login')->with('error', 'Please log in to access your profile.');
    }

    $usdRate = $customer->usd_rate ?? 'N/A';

    return view('auth.profile_settings', compact('customer', 'usdRate'));
}

public function updateProfile(Request $request)
{
    $customer = Auth::guard('customer')->user();

    if (!$customer) {
        return redirect()->route('portal.login')->with('error', 'Please log in to update your profile.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'display_name' => 'required|string|max:255',
        'usd_rate' => 'required|numeric',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
        'phone_2' => 'nullable|string|max:15',
        'address' => 'required|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'facebook_url' => 'nullable|url|max:255',
    ]);

    \Log::info('Updating Profile', $request->all());

    $data = $request->only(['name', 'display_name', 'usd_rate', 'email', 'phone', 'phone_2', 'address', 'facebook_url']);

    if ($request->hasFile('profile_picture')) {
        $imageName = time() . '.' . $request->profile_picture->extension();
    $request->file('profile_picture')->move(public_path('uploads/customers'), $imageName);
    $customer->profile_picture = 'customers/' . $imageName;

    }

    if ($customer->update($data)) {
        \Log::info('Customer updated successfully.');
    } else {
        \Log::error('Customer update failed.');
    }

    return redirect()->route('customer.profileSettings')->with('success', 'Profile updated successfully.');
}


}
