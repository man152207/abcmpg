<?php

namespace App\Http\Controllers\Admin\Smmx;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SmmxDeliverable;
use App\Models\SmmxMonthlyReport;
use App\Models\SmmxOnboarding;
use App\Models\SmmxWorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmmxCustomerPanelController extends Controller
{
    public function index()
    {
        $customers = Customer::query()
            ->whereIn('id', function ($query) {
                $query->select('customer_id')->from('smmx_onboardings');
            })
            ->with([
                'packages' => function ($q) {
                    $q->latest();
                }
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($customer) {
                $latestOnboarding = SmmxOnboarding::where('customer_id', $customer->id)->latest()->first();
                $latestDeliverable = SmmxDeliverable::where('customer_id', $customer->id)
                    ->orderByDesc('report_year')
                    ->orderByDesc('report_month')
                    ->latest()
                    ->first();

                $pendingLogs = SmmxWorkLog::where('customer_id', $customer->id)
                    ->whereIn('status', ['pending', 'in_progress', 'waiting_approval'])
                    ->count();

                $lastLog = SmmxWorkLog::where('customer_id', $customer->id)->latest()->first();

                return (object) [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->whatsapp ?? $customer->phone ?? '',
                    'brand_name' => $latestOnboarding->brand_name ?? $latestOnboarding->business_name ?? '-',
                    'business_name' => $latestOnboarding->business_name ?? '-',
                    'goal' => $latestOnboarding->primary_goal ?? '-',
                    'package_name' => optional(optional($customer->packages)->first())->name ?? '-',
                    'completion_rate' => $latestDeliverable?->completion_rate ?? 0,
                    'approval_status' => $latestDeliverable->approval_status ?? '-',
                    'report_status' => $latestDeliverable?->report_sent ? 'Sent' : 'Pending',
                    'assigned_staff' => is_array($latestDeliverable->assigned_staff ?? null)
                        ? implode(', ', $latestDeliverable->assigned_staff)
                        : '-',
                    'pending_logs' => $pendingLogs,
                    'last_activity' => optional($lastLog?->created_at)->format('Y-m-d h:i A') ?? '-',
                ];
            });

        return view('admin.smmx.customers.index', compact('customers'));
    }

    public function show($customerId)
    {
        $customer = Customer::findOrFail($customerId);

        $onboarding = SmmxOnboarding::where('customer_id', $customerId)->latest()->first();
        $deliverable = SmmxDeliverable::where('customer_id', $customerId)
            ->orderByDesc('report_year')
            ->orderByDesc('report_month')
            ->latest()
            ->first();

        $report = SmmxMonthlyReport::where('customer_id', $customerId)
            ->orderByDesc('report_year')
            ->orderByDesc('report_month')
            ->latest()
            ->first();

        $workLogs = SmmxWorkLog::where('customer_id', $customerId)
            ->orderByDesc('work_date')
            ->latest()
            ->paginate(20);

        $package = null;
        if (method_exists($customer, 'packages')) {
            $package = $customer->packages()->latest()->first();
        }

        $stats = [
            'pending_tasks' => SmmxWorkLog::where('customer_id', $customerId)
                ->whereIn('status', ['pending', 'in_progress', 'waiting_approval'])
                ->count(),

            'completed_tasks' => SmmxWorkLog::where('customer_id', $customerId)
                ->where('status', 'done')
                ->count(),

            'this_month_logs' => SmmxWorkLog::where('customer_id', $customerId)
                ->where('report_month', now()->month)
                ->where('report_year', now()->year)
                ->count(),

            'completion_rate' => $deliverable?->completion_rate ?? 0,
        ];

        return view('admin.smmx.customers.show', compact(
            'customer',
            'onboarding',
            'deliverable',
            'report',
            'workLogs',
            'package',
            'stats'
        ));
    }

    public function storeWorkLog(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);

        $data = $request->validate([
            'work_date' => 'required|date',
            'report_month' => 'required|integer|min:1|max:12',
            'report_year' => 'required|integer|min:2000|max:2100',
            'work_type' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
            'status' => 'required|string|max:100',
            'assigned_to' => 'nullable|string|max:255',
            'asset_link' => 'nullable|string|max:255',
            'external_link' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
        ]);

        $latestOnboarding = SmmxOnboarding::where('customer_id', $customerId)->latest()->first();
        $latestDeliverable = SmmxDeliverable::where('customer_id', $customerId)
            ->orderByDesc('report_year')
            ->orderByDesc('report_month')
            ->latest()
            ->first();

        $data['customer_id'] = $customer->id;
        $data['onboarding_id'] = $latestOnboarding?->id;
        $data['deliverable_id'] = $latestDeliverable?->id;
        $data['created_by'] = auth('admin')->id();

        SmmxWorkLog::create($data);

        return redirect()
            ->route('admin.smmx.customers.show', $customerId)
            ->with('success', 'Work log added successfully.');
    }

    public function deleteWorkLog($customerId, $id)
    {
        $log = SmmxWorkLog::where('customer_id', $customerId)->findOrFail($id);
        $log->delete();

        return redirect()
            ->route('admin.smmx.customers.show', $customerId)
            ->with('success', 'Work log deleted successfully.');
    }
}