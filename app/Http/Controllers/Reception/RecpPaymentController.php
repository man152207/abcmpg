<?php
namespace App\Http\Controllers\Reception;
use App\Http\Controllers\Controller;
use App\Models\RecpEnrollment;
use App\Models\RecpPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RecpPaymentController extends Controller
{
    public function list(Request $req)
    {
        $payments = RecpPayment::with('enrollment.student', 'receiver')->latest()->paginate(20);
        return view('reception/payments/payments_list', compact('payments'));
    }

    public function create(RecpEnrollment $enrollment)
    {
        $enrollment->load('student','batch.course','payments.receiver');
        return view('reception/payments/take_payment', compact('enrollment'));
    }

    public function store(Request $req, RecpEnrollment $enrollment)
    {
        $data = $req->validate([
            'amount' => ['required','numeric','min:0.01'],
            'method' => ['required','in:cash,esewa,khalti,bank,card,other'],
            'source_account' => ['nullable','string','max:120'],
            'reference' => ['nullable','string','max:120'],
            'paid_at' => ['nullable','date'],
            'note' => ['nullable','string'],
        ]);
        $data['enrollment_id'] = $enrollment->id;
        $data['received_by_admin_id'] = Auth::guard('admin')->id();
        $data['paid_at'] = $data['paid_at'] ?? now();
        $payment = RecpPayment::create($data);
        return redirect()->route('recp.payment.create', $enrollment)->with('success','Payment recorded.');
    }

    public function edit(RecpPayment $payment)
    {
        $payment->load('enrollment.student', 'receiver');
        return view('reception/payments/payment_edit', compact('payment'));
    }

    public function update(Request $req, RecpPayment $payment)
    {
        $data = $req->validate([
            'amount' => ['required','numeric','min:0.01'],
            'method' => ['required','in:cash,esewa,khalti,bank,card,other'],
            'source_account' => ['nullable','string','max:120'],
            'reference' => ['nullable','string','max:120'],
            'paid_at' => ['nullable','date'],
            'note' => ['nullable','string'],
        ]);
        $payment->update($data);
        return back()->with('success','Payment updated.');
    }

    public function destroy(RecpPayment $payment)
    {
        $payment->delete();
        return redirect()->route('recp.payment.list')->with('success','Payment deleted (soft).');
    }

    public function receipt(RecpPayment $payment)
    {
        $payment->load('enrollment.student', 'enrollment.batch.course', 'receiver');
        $pdf = Pdf::loadView('reception/payments/receipt_pdf', compact('payment'));
        return $pdf->download('payment_receipt_'.$payment->id.'.pdf');
    }
}