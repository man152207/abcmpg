<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function add_form()
    {
        try {
            $cards = Card::all();
            return view('client.add', compact('cards'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show()
    {
        try {
            $clients = Client::orderBy('created_at', 'desc')->paginate(10);
            $cards = Card::all();

            // Calculate total USD and NRP
            $totalUSD = $clients->sum('USD');
            $totalNRP = $clients->sum('NRP');

            return view('client.list', compact('clients', 'cards', 'totalUSD', 'totalNRP'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'account' => ['required'],
                'USD' => ['required', 'numeric'],
                'Rate' => ['required', 'numeric'],
                'NRP' => ['required', 'numeric'],
            ]);

            $client = Client::create($request->all());
            $payload['created_by'] = auth('admin')->id(); // ✅ ADD THIS
            $card = Card::where('card_number', $request->account)->first();
            $card->update([
                'USD' => $card->USD + $request->USD,
            ]);

            $admin = Auth('admin')->user();
            DB::table('card_credit_info')->insert([
                'card_id' => $card->id,
                'card_number' => $card->card_number,
                'USD' => $request->USD,
                'by' => "$admin->name ($admin->id)",
                'created_at' => now(),

            ]);

            return redirect('/admin/dashboard/client_list')->with('success', 'Client added successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return redirect('/admin/dashboard/client_list')->with('success', 'Client deleted successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
{
    try {
        $client = Client::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'account' => ['required'],
            'USD' => ['required', 'numeric'],
            'Rate' => ['required', 'numeric'],
            'NRP' => ['required', 'numeric'],
        ]);
        $client->update($request->all());

        return response()->json(['success' => true]);
    } catch (\Throwable $th) {
        return response()->json(['success' => false, 'error' => $th->getMessage()]);
    }
}


    public function update_form($id)
    {
        try {
            $client = Client::findOrFail($id);
            $cards = Card::all();
            return view('client.update', compact('client', 'cards'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Client::query();

            // Apply search filter if provided
            if ($request->has('search') && $request->search != '') {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Apply date filters if provided
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59']);
            }

            // Calculate the total sums for all matching records
            $totalUSD = $query->sum('USD');
            $totalNRP = $query->sum('NRP');

            // Get the paginated results
            $clients = $query->orderBy('created_at', 'desc')->paginate(10);

            // Retrieve all cards
            $cards = Card::all();

            // Pass the totals to the view along with the clients and cards
            return view('client.list', compact('clients', 'cards', 'totalUSD', 'totalNRP'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function summary()
    {
        try {
            $monthlySummaries = Client::select(
                DB::raw('SUM("USD") as totalUSD'),
                DB::raw('SUM("NRP") as totalNRP'),
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as monthYear")
            )
                ->groupBy('monthYear')
                ->orderBy('monthYear', 'desc')
                ->get();

            $monthlyExp = Other_Exp::select(
                DB::raw('SUM(amount) as totalAmt'),
                DB::raw("TO_CHAR(date, 'YYYY-MM') as monthYear")
            )
                ->groupBy('monthYear')
                ->orderBy('monthYear', 'desc')
                ->get();

            return view('client.summary', compact('monthlySummaries', 'monthlyExp'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function thisWeek()
{
    try {
        // Define the start and end of the week
        $startOfWeek = Carbon::now()->startOfWeek()->startOfDay();
        $endOfWeek = Carbon::now()->endOfWeek()->endOfDay();

        // Log the date range for debugging
        \Log::info('Start of Week: ' . $startOfWeek);
        \Log::info('End of Week: ' . $endOfWeek);

        // Fetch clients created within this week
        $clients = Client::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Fetch all cards
        $cards = Card::all();

        // Calculate total USD and NRP for this week
        $totalUSD = $clients->sum('USD');
        $totalNRP = $clients->sum('NRP');

        // Pass the data to the view
        return view('client.list', compact('clients', 'cards', 'totalUSD', 'totalNRP'));
    } catch (\Throwable $th) {
        // Handle any errors
        return redirect()->back()->with('error', $th->getMessage());
    }
}
    public function thisMonth()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $clients = Client::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $cards = Card::all();

            // Calculate total USD and NRP for this month
            $totalUSD = $clients->sum('USD');
            $totalNRP = $clients->sum('NRP');

            return view('client.list', compact('clients', 'cards', 'totalUSD', 'totalNRP'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function thisDay()
    {
        try {
            $startOfDay = Carbon::now()->startOfDay();
            $endOfDay = Carbon::now()->endOfDay();

            $clients = Client::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $cards = Card::all();

            // Calculate total USD and NRP for today
            $totalUSD = $clients->sum('USD');
            $totalNRP = $clients->sum('NRP');

            return view('client.list', compact('clients', 'cards', 'totalUSD', 'totalNRP'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function yesterday()
    {
        try {
            $startOfYesterday = Carbon::yesterday()->startOfDay();
            $endOfYesterday = Carbon::yesterday()->endOfDay();

            $clients = Client::whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $cards = Card::all();

            // Calculate total USD and NRP for yesterday
            $totalUSD = $clients->sum('USD');
            $totalNRP = $clients->sum('NRP');

            return view('client.list', compact('clients', 'cards', 'totalUSD', 'totalNRP'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function showDetailsByName($name)
{
    $clients = Client::where('name', $name)->get();

    if ($clients->isEmpty()) {
        return redirect()->back()->with('error', 'No client found with that name.');
    }

    // Calculate the sums
    $totalUSD = $clients->sum('USD');
    $totalNRP = $clients->sum('NRP');
    $totalRate = $clients->avg('Rate'); // Example: Calculating average rate, modify as needed

    return view('client.clientdetails', compact('clients', 'totalUSD', 'totalNRP', 'totalRate'));
}

}
