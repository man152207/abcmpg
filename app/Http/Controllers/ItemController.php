<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_Items;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function add_form()
    {
        try {
            return view('item.add');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show()
    {
        try {
            $items = Item::orderBy('id', 'desc')->paginate(5);
            return view('item.list', compact('items'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'unit' => ['required', 'string'],
                'selling_price' => ['required'],
                'description' => ['required', 'string'],
            ]);

            $item = Item::create($request->all());

            return redirect('/admin/dashboard/item_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
    public function delete($id)
    {
        try {
            $item = Item::findorFail($id);
            $invoice_items = Invoice_Items::where('Item_id', $item->id)->get();

            // dd($invoice_items);
            foreach ($invoice_items as $inv_item) {
                // dd($inv_item);
                $invoice = Invoice::where('id', $inv_item->invoice_id)->first();
                // dd($invoice);
                $inv_item->delete();
                $invoice_itms = Invoice_Items::where('invoice_id', $invoice->id)->first();
                // dd($invoice_itms == null);
                if ($invoice_itms == null) {
                    $invoice->delete();
                }
            }
            // $invoices = Invoice::all();
            // foreach($invoices as $invoice){

            // };
            $item->delete();

            return redirect('/admin/dashboard/item_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = Item::findOrFail($id);
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'unit' => ['required', 'string'],
                'selling_price' => ['required'],
                'description' => ['required', 'string'],
            ]);
            $item->update($request->all());

            return redirect('/admin/dashboard/item_list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function update_form($id)
    {
        try {
            $item = Item::findOrFail($id);
            return view('item.update', compact('item'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $items = Item::where('name', 'like', '%' . $request->search . '%')->paginate(5);

            return view('item.list', compact('items'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
