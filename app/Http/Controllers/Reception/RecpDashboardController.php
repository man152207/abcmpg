<?php
namespace App\Http\Controllers\Reception;
use App\Http\Controllers\Controller;
use App\Models\RecpStudent;
use App\Models\RecpEnrollment;
use App\Models\RecpPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RecpDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $studentsToday = RecpStudent::whereDate('created_at', $today)->count();
        $enrollsToday = RecpEnrollment::whereDate('created_at', $today)->count();
        $paymentsByMethod = RecpPayment::select('method', DB::raw('SUM(amount) as total'))
            ->whereDate('paid_at', $today)->groupBy('method')->pluck('total', 'method');
        $paidToday = RecpPayment::whereDate('paid_at', $today)->sum('amount');

        // New: Dues list (top 5 overdue)
        $dues = RecpEnrollment::with('student')->where('status', 'enrolled')
            ->whereRaw('(fee_agreed - discount - (SELECT COALESCE(SUM(amount),0) FROM recp_payments WHERE enrollment_id = recp_enrollments.id)) > 0')
            ->orderBy('enroll_date')->take(5)->get();

        // New: Chart data (last 7 days payments)
        $chartData = RecpPayment::select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(amount) as total'))
            ->where('paid_at', '>=', Carbon::today()->subDays(7))
            ->groupBy('date')->pluck('total', 'date')->toArray();

        return view('reception/recp_dashboard', compact('studentsToday', 'enrollsToday', 'paymentsByMethod', 'paidToday', 'dues', 'chartData'));
    }
}