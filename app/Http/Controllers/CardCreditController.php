<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CardCreditController extends Controller
{

    public function credit_form()
    {
        try {
            $cards = Card::all();

            return view('card.credit.add', compact('cards'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search_list(Request $request)
    {
        try {
            $query = DB::table('card_credit_info');

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
            $credits = DB::table('card_credit_info')->orderBy('id', 'desc')->paginate(15);

            return view('card.credit.list', compact('credits'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }


    public function credit(Request $request)
    {

        try {
            $card = Card::where('card_number', $request->card_number)->first();

            // dd($card);
            $request->validate([
                'USD' => 'required | numeric ',
            ]);
            $card->update([
                'name' => $card->name,
                'card_number' => $card->card_number,
                'USD' => $card->USD + $request->USD,
            ]);
            $admin = Auth('admin')->user();
            DB::table('card_credit_info')->insert([
                'card_id' => $card->id,
                'card_number' => $card->card_number,
                'USD' => $request->USD,
                'by' => "$admin->name ($admin->id)",
                'created_at' => now()
            ]);
            // $credits = DB::select('select * from card_credit_info');
            return redirect()->route('all_in_one');
            // return redirect('/admin/dashboard/credit/list');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function summary()
    {
        try {
            $monthlySummaries = DB::table('card_credit_info')
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->paginate(12);

            return view('card.credit.summary', compact('monthlySummaries'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function search(Request $request)
    {
        try {
            $monthlySummaries = DB::table('card_credit_info')
                ->where('card_number', $request->search)
                ->select(
                    DB::raw('SUM(USD) as totalUSD'),
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as monthYear")
                )
                ->groupBy('monthYear')
                ->paginate(12);

            return view('card.credit.summary', compact('monthlySummaries'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
