<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Ad;

class InvoiceBillsController extends Controller
{
    /**
     * List per-AD rows for customers that require bill.
     * Shows ALL ads (any payment), and marks due only for Pending/Paused/Baki.
     */
    public function index(Request $request)
    {
        $search   = trim($request->get('search', ''));
        $dueStats = ['Pending', 'Paused', 'Baki'];

        // Each ad becomes a row, joined with its customer (by phone)
        // NOTE: We DO NOT filter by ads.Payment anymore -> show all past rows.
        $ads = Ad::query()
            ->join('customers', 'customers.phone', '=', 'ads.customer')
            ->where('customers.requires_bill', true)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('customers.name', 'like', "%{$search}%")
                       ->orWhere('customers.display_name', 'like', "%{$search}%")
                       ->orWhere('customers.phone', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('ads.created_at')
            ->select([
                'ads.id             as ad_id',
                'ads.created_at     as ad_created_at',
                'ads.NRP            as ad_npr',
                'ads.Payment        as ad_payment',
                'ads.customer       as ad_customer_phone',
                'ads.billing_status as ad_billing_status',

                'customers.id              as customer_id',
                'customers.display_name    as customer_display_name',
                'customers.name            as customer_name',
                'customers.email           as customer_email',
                'customers.phone           as customer_phone',
                'customers.profile_picture as customer_profile_picture',
            ])
            ->paginate(20)
            ->withQueryString();

        $billingStatuses = ['Bill Not Sent', 'Bill Issued', 'Bill Sent'];

        return view('invoice.bills', [
            'ads'                 => $ads,
            'billingStatuses'     => $billingStatuses,
            'duePaymentStatuses'  => $dueStats, // pass to blade for due check
            'search'              => $search,
        ]);
    }

    /**
     * Update per-AD billing status (row-level update).
     */
    public function updateBillingStatus(Request $request, Ad $ad)
    {
        $allowed = ['Bill Not Sent', 'Bill Issued', 'Bill Sent'];

        $request->validate([
            'billing_status' => 'required|in:' . implode(',', $allowed),
        ]);

        $ad->billing_status = $request->billing_status;
        $ad->save();

        return back()->with('status', 'Billing status updated for this ad.');
    }
}
