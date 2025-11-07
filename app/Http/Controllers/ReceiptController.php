<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Invoice_Items;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{

    public function show($id)
{
    try {
        $ad = Ad::findOrFail($id);
        $customer = Customer::where('phone', $ad->customer)->first();
        return view('downloadable.receipt', compact('ad', 'customer'));
    } catch (\Throwable $th) {
        return $th->getMessage();
    }
}


public function create_pdf($id)
{
    try {
        $ad = Ad::findOrFail($id);
        $customer = Customer::where('phone', $ad->customer)->first();

        $data = ['ad' => $ad, 'customer' => $customer];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('downloadable.receipt', $data);
        return $pdf->download('receipt.pdf');
    } catch (\Throwable $th) {
        return $th->getMessage();
    }
}

    public function show_invoice($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoiceItems = Invoice_Items::where('invoice_id', $id)->get();
            $customer = Customer::where('phone', $invoice->customer)->first();
            return view('downloadable.invoice', compact('invoice'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function create_pdf_invoice($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice_items = Invoice_Items::where('invoice_id', $id)->get();
            $customer = Customer::where('phone', $invoice->customer)->first();
            // Pass data to the view

            $data = ['invoice' => $invoice];
            // dd("hello");
            // Create a new instance of the PDF class
            $pdf = app('dompdf.wrapper');
            // dd("hello");
            // Load the view and pass data to it
            $pdf->loadView('downloadable.invoice', $data);
            // dd("hello");
            // Download the PDF file
            return $pdf->download('pdfFile.pdf');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function generateReceipt($invoiceId)
{
    $invoice = Invoice::findOrFail($invoiceId);
    $customer = Customer::find($invoice->customer_id);

    return view('downloadable.receipt', compact('invoice', 'customer'));
}

}
