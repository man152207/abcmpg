<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardDebitController extends Controller
{
    public function debit_form()
    {
        try {
            $cards = Card::all();

            return view('card.debit.add', compact('cards'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }


    public function search_list(Request $request)
    {
        try {
            $query = DB::table('card_debit_info');

            // Filter by card number
            if ($request->has('search')) {
                $query->where('card_number', 'like', '%' . $request->search . '%');
            }

            // Filter by date range
            if ($request->start_date != null && $request->end_date != null) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59']);
            }

            // Fetch the filtered credits
            $credits = $query->paginate(15);

            return view('card.credit.list', compact('credits'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show()
    {
        try {
            $debits = DB::table('card_debit_info')->orderBy('id', 'desc')->paginate(15);

            return view('card.debit.list', compact('debits'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }


    public function debit(Request $request)
    {

        try {
            $card = Card::where('card_number', $request->card_number)->first();

            // dd($card);
            $request->validate([
                'USD' => 'required | numeric ',
            ]);
            if ($card->USD >= $request->USD) {
                $card->update([
                    'name' => $card->name,
                    'card_number' => $card->card_number,
                    'USD' => $card->USD - $request->USD,
                ]);
                $admin = Auth('admin')->user();
                DB::table('card_debit_info')->insert([
                    'card_id' => $card->id,
                    'card_number' => $card->card_number,
                    'USD' => $request->USD,
                    'by' => "$admin->name ($admin->id)",
                    'created_at' => now(),
                ]);
                // $credits = DB::select('select * from card_credit_info');

                // return redirect('/admin/dashboard/debit/list');
                return redirect()->route('all_in_one');
            } else {
                // return redirect('/admin/dashboard')->with('status', 'insufficent balance!');
                return redirect()->route('all_in_one')->with('status', 'insufficent balance!');
            }
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function summary()
    {
        try {
            $monthlySummaries = DB::table('card_debit_info')
                ->select(
                    DB::table('card_debit_info')->raw('SUM(USD) as totalUSD'),
                    DB::table('card_debit_info')->raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->paginate(12);

            return view('card.debit.summary', compact('monthlySummaries'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $monthlySummaries = DB::table('card_debit_info')
                ->where('card_number', $request->search)
                ->select(
                    DB::table('card_debit_info')->raw('SUM(USD) as totalUSD'),
                    DB::table('card_debit_info')->raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->paginate(12);

            return view('card.debit.summary', compact('monthlySummaries'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
