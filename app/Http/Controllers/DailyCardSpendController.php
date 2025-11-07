<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyCardSpend;
use App\Models\Card;
use Illuminate\Support\Facades\DB;
use PDF;

class DailyCardSpendController extends Controller
{
    public function index()
    {
        $records = DailyCardSpend::orderBy('date', 'desc')->get();
        $cards = Card::where('status', 1)->orderBy('USD', 'desc')->get();

        // Calculate total spend for each card
        $cardTotals = $records->groupBy('card_name')->map(function ($records) {
            return $records->sum('amount_usd');
        });

        $groupedAdAccounts = DB::table('storred_ad_accounts')
            ->select('group_name', 'ad_account_name')
            ->get()
            ->groupBy('group_name');

        return view('admin.DCSRS.index', compact('cards', 'groupedAdAccounts', 'records', 'cardTotals'));
    }

    public function viewCard(Request $request, $cardName)
    {
        $query = DailyCardSpend::where('card_name', $cardName);

        // Apply date filters if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $records = $query->orderBy('date', 'desc')->get();

        return view('admin.DCSRS.single', compact('cardName', 'records'));
    }

    public function downloadCardRecords(Request $request, $cardName)
    {
        $query = DailyCardSpend::where('card_name', $cardName);

        // Apply date filters if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $records = $query->orderBy('date', 'desc')->get();

        // Generate PDF using the Blade view
        $pdf = PDF::loadView('downloadable.card-records-pdf', compact('cardName', 'records'));

        return $pdf->download("{$cardName}_records.pdf");
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_name' => 'required|string',
            'ad_account' => 'required|string',
            'date' => 'required|date',
            'amount_usd' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        DailyCardSpend::create([
            'card_name' => $request->input('card_name'),
            'ad_account' => $request->input('ad_account'),
            'date' => $request->input('date'),
            'amount_usd' => $request->input('amount_usd'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('daily-card-spends.index')->with('success', 'Record added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount_usd' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $record = DailyCardSpend::findOrFail($id);
        $record->update([
            'amount_usd' => $request->input('amount_usd'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['message' => 'Record updated successfully.']);
    }

    public function destroy($id)
    {
        $record = DailyCardSpend::findOrFail($id);
        $record->delete();

        return response()->json(['message' => 'Record deleted successfully.']);
    }
    
}
