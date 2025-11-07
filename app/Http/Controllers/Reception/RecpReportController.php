<?php
namespace App\Http\Controllers\Reception;
use App\Http\Controllers\Controller;
use App\Models\RecpEnrollment;
use App\Models\RecpPayment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DuesExport;

class RecpReportController extends Controller
{
    public function dues(Request $req)
    {
        $dues = RecpEnrollment::with('student', 'batch.course')
            ->whereRaw('(fee_agreed - discount - (SELECT COALESCE(SUM(amount),0) FROM recp_payments WHERE enrollment_id = recp_enrollments.id)) > 0')
            ->orderBy('enroll_date', 'desc')->paginate(20);
        return view('reception/reports/dues_list', compact('dues'));
    }

    public function paymentsSummary(Request $req)
    {
        $start = $req->start ?? now()->startOfMonth();
        $end = $req->end ?? now()->endOfMonth();
        $summary = RecpPayment::select('method', DB::raw('SUM(amount) as total'))
            ->whereBetween('paid_at', [$start, $end])
            ->groupBy('method')->get();
        return view('reception/reports/payments_summary', compact('summary', 'start', 'end'));
    }

    public function exportDues()
    {
        return Excel::download(new DuesExport, 'dues_report.xlsx');
    }
}