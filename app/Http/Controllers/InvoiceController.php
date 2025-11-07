<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Invoice_Items;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Ad; 

class InvoiceController extends Controller
{
    public function showForm()
    {
        try {
            $items = Item::all();
            $customers = Customer::all();
            return view('invoice.add', compact('items', 'customers'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function list()
    {
        try {
            $invoices = Invoice::paginate(5);

            return view('invoice.list', compact('invoices'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function saveInvoice(Request $request)
    {
        // try {
        // Validate the form data
        // $request->validate([
        //     'prices' => 'required|array',
        //     'items.*' => 'required|string',
        //     'quantities.*' => 'required|numeric|min:1',
        //     'prices.*' => 'required|numeric|min:0',
        // ]);
        // dd($request);
        // Create a new Invoice instance
        $invoice = new Invoice();

        $invoice_number = rand(1000000000, 9999999999);
        // Check if invoice_number already exists
        while (Invoice::where('invoice_number', $invoice_number)->exists()) {
            $invoice_number = rand(1000000000, 9999999999);
        }
        $invoice->invoice_number =  $invoice_number;
        $admin = Auth('admin')->user();
        $invoice->salesperson = $admin->name;
        $invoice->customer = $request->customer;
        $invoice->date = $request->date;
        $invoice->description = $request->description;
        $invoice->created_by = auth('admin')->id();
        $invoice->save(); // Assuming you have fields like customer_id, date, etc., you can set them here

        // Attach items to the invoice
        foreach ($request->items as $key => $item) {
            $item_ = Item::find($item);
            Invoice_Items::create([
                'invoice_id' => $invoice->id,
                'item_id' => $item,
                'name' => $item_->name,
                'rate' => $request->rate[$key],
                'quantity' => $request->quantities[$key],
                'tax' => $request->tax[$key],
                'amount' => $request->amount[$key],
                'created_by' => auth('admin')->id(),

            ]);
        }
        return redirect('/admin/dashboard/invoice/list')->with('success', 'Invoice saved successfully');
        // } catch (\Throwable $th) {
        //     $th->getMessage();
        // }
    }


    public function update_form($id)
    {
        try {
            $invoice = Invoice::findorFail($id);
            $customers = Customer::all();
            $invoice_items = Invoice_Items::where('invoice_id', $id)->get();
            $items = Item::all();
            return view('invoice.update', compact('invoice', 'customers', 'invoice_items', 'items'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            // dd($request);
            $invoice = Invoice::findorFail($id);
            // dd($invoice);
            $invoice_items = Invoice_Items::where('invoice_id', $id)->get();
            // dd($invoice_items);
            foreach ($invoice_items as $item) {
                $item->delete();
            }
            $invoice->delete();
            // dd('hello');
            return redirect('/admin/dashboard/invoice/list')->with('status', 'successfully deleted');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // dd($request);
            $invoice = Invoice::findorFail($id);
            $invoice->update([
                'customer' => $request->customer,
                'invoice_number' => $invoice->invoice_number,
                'salesperson' => $invoice->salesperson,
                'description' => $request->description,
                'date' => $request->date,
            ]);
            $old_invoice_items = Invoice_Items::where('invoice_id', $invoice->id)->get();
            // dd($old_invoice_items);
            foreach ($old_invoice_items as $old_item) {
                $old_item->delete();
            }
            foreach ($request->items as $key => $item) {
                $item_ = Item::find($item);
                Invoice_Items::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $item,
                    'name' => $item_->name,
                    'rate' => $request->rate[$key],
                    'quantity' => $request->quantities[$key],
                    'tax' => $request->tax[$key],
                    'amount' => $request->amount[$key],
                ]);
            }
            return redirect('admin/dashboard/invoice/list')->with('status', 'successfully updated');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
