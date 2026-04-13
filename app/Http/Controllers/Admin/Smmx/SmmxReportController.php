<?php

namespace App\Http\Controllers\Admin\Smmx;

use App\Http\Controllers\Controller;
use App\Models\SmmxDeliverable;
use App\Models\SmmxMonthlyReport;
use Illuminate\Http\Request;

class SmmxReportController extends Controller
{
    public function index()
    {
        $reports = SmmxMonthlyReport::with(['customer', 'deliverable'])
            ->orderByDesc('report_year')
            ->orderByDesc('report_month')
            ->latest()
            ->paginate(15);

        $stats = [
            'active_clients' => SmmxDeliverable::distinct('customer_id')->count('customer_id'),
            'deliverables' => SmmxDeliverable::count(),
            'reports_sent' => SmmxMonthlyReport::where('report_status', 'sent')->count(),
            'pending_reports' => SmmxMonthlyReport::where('report_status', '!=', 'sent')->count(),
            'total_spend' => SmmxMonthlyReport::sum('total_spend'),
        ];

        return view('admin.smmx.reports.index', compact('reports', 'stats'));
    }

    public function show($id)
    {
        $report = SmmxMonthlyReport::with(['customer', 'deliverable.package'])->findOrFail($id);

        return view('admin.smmx.reports.show', compact('report'));
    }
}