<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerApiController extends Controller
{
    /**
     * Get paginated list of customers with activity status
     */
    public function index(Request $request)
    {
        $query = Customer::select(
            'id',
            'name',
            'display_name',
            'usd_rate',
            'email',
            'phone',
            'phone_2',
            'address',
            'created_at',
            'updated_at',
            'profile_picture',
            'requires_bill',
            'billing_status',
            'facebook_url',
            'created_by'
        )
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        });

        $customers = $query->paginate($request->per_page ?? 50);

        $customers->getCollection()->transform(function ($customer) {
            return $this->appendActivityStatus($customer);
        });

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Get single customer with activity status
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $customerData = $this->appendActivityStatus($customer);

        return response()->json([
            'success' => true,
            'data' => $customerData,
        ]);
    }

    /**
     * Append analytics fields to customer data
     * FIXED: Use 'NRP' column instead of 'total_npr'
     */
    private function appendActivityStatus(Customer $customer): array
    {
        $now = Carbon::now();
        
        // === Activity timestamps ===
        $lastAd = $customer->ads()->latest('created_at')->first();
        $lastActivityAt = $lastAd ? Carbon::parse($lastAd->created_at) : null;

        // === Package status (customer_package singular) ===
        $hasActivePackage = false;
        $packageEndingSoon = false;
        
        // Wrap in try-catch in case customer_package table/columns don't exist
        try {
            $hasActivePackage = $customer->packages()
                ->where(function ($q) use ($now) {
                    $q->where('customer_package.status', 'active')
                      ->orWhereDate('customer_package.end_date', '>=', $now->toDateString());
                })
                ->exists();

            $packageEndingSoon = $customer->packages()
                ->where('customer_package.status', 'active')
                ->whereDate('customer_package.end_date', '>=', $now->toDateString())
                ->whereDate('customer_package.end_date', '<=', $now->copy()->addDays(7)->toDateString())
                ->exists();
        } catch (\Exception $e) {
            // Package table may not exist or have different structure
            $hasActivePackage = false;
            $packageEndingSoon = false;
        }

        // === Active rule: 30 days OR active package ===
        $activeByActivity = $lastActivityAt ? $lastActivityAt->gte($now->copy()->subDays(30)) : false;
        $isActive = $activeByActivity || $hasActivePackage;

        // === Order counts ===
        $ordersLast30 = $customer->ads()
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->count();
        $ordersLast60 = $customer->ads()
            ->where('created_at', '>=', $now->copy()->subDays(60))
            ->count();

        // === Spend calculations (FIXED: use 'NRP' column) ===
        $spendLast30 = $customer->ads()
            ->where('created_at', '>=', $now->copy()->subDays(30))
            ->sum('NRP') ?? 0;

        $spendPrev30 = $customer->ads()
            ->whereBetween('created_at', [
                $now->copy()->subDays(60),
                $now->copy()->subDays(30)
            ])
            ->sum('NRP') ?? 0;

        $spendLast60 = $customer->ads()
            ->where('created_at', '>=', $now->copy()->subDays(60))
            ->sum('NRP') ?? 0;

        $spendPrev60 = $customer->ads()
            ->whereBetween('created_at', [
                $now->copy()->subDays(120),
                $now->copy()->subDays(60)
            ])
            ->sum('NRP') ?? 0;

        $lifetimeSpend = $customer->ads()->sum('NRP') ?? 0;

        // === Due amount & invoice (if receipts relationship exists) ===
        $dueAmount = 0;
        $lastInvoiceAt = null;
        
        try {
            if (method_exists($customer, 'receipts')) {
                $dueAmount = $customer->receipts()
                    ->where('status', 'unpaid')
                    ->sum('amount') ?? 0;

                $lastReceipt = $customer->receipts()->latest('created_at')->first();
                $lastInvoiceAt = $lastReceipt 
                    ? Carbon::parse($lastReceipt->created_at)->toIso8601String() 
                    : null;
            }
        } catch (\Exception $e) {
            // Receipts table may not exist
            $dueAmount = 0;
            $lastInvoiceAt = null;
        }

        // === Build response ===
        $data = $customer->toArray();

        $data['last_activity_at'] = $lastActivityAt ? $lastActivityAt->toIso8601String() : null;
        $data['has_active_package'] = $hasActivePackage;
        $data['package_ending_soon'] = $packageEndingSoon;
        $data['is_active'] = $isActive;
        $data['orders_last_30'] = $ordersLast30;
        $data['orders_last_60'] = $ordersLast60;
        $data['spend_last_30_npr'] = (float) $spendLast30;
        $data['spend_prev_30_npr'] = (float) $spendPrev30;
        $data['spend_last_60_npr'] = (float) $spendLast60;
        $data['spend_prev_60_npr'] = (float) $spendPrev60;
        $data['lifetime_spend_npr'] = (float) $lifetimeSpend;
        $data['due_amount_npr'] = (float) $dueAmount;
        $data['last_invoice_at'] = $lastInvoiceAt;

        return $data;
    }
}
