<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherIncome;
use App\Models\Customer;
use Carbon\Carbon;

class OtherIncomeController extends Controller
{
    public function index()
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // Sum totals for the current month
        $totalOtherIncome = OtherIncome::where('income_type', 'Other Income')
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        $totalOpeningBalance = OtherIncome::where('income_type', 'Opening Balance')
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        // Retrieve incomes for the current month
        $other_incomes = OtherIncome::whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->get();

        // Retrieve all customers for the datalist
        $customers = Customer::all();

        // Retrieve distinct previous months for the selection dropdown
        $previousMonths = OtherIncome::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->distinct()
            ->where('date', '<', $currentMonthStart)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('other_income.index', compact('totalOtherIncome', 'totalOpeningBalance', 'other_incomes', 'customers', 'previousMonths'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'contact_number' => 'required',
            'customer_name' => 'required',
            'amount' => 'required|numeric',
            'income_type' => 'required|in:Opening Balance,Other Income',
        ]);

        OtherIncome::create($request->all());

        return redirect()->route('other_income.index')->with('success', 'Income added successfully');
    }

    public function update(Request $request, OtherIncome $otherIncome)
    {
        $request->validate([
            'date' => 'required|date',
            'contact_number' => 'required|string',
            'customer_name' => 'required|string',
            'amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'income_type' => 'required|string',
        ]);

        try {
            $otherIncome->update($request->all());
            return redirect()->route('other_income.index')->with('success', 'Income updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating income: ' . $e->getMessage());
            return redirect()->route('other_income.index')->with('error', 'Failed to update income.');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $income = OtherIncome::findOrFail($id);
            $income->delete();

            // If the request expects JSON (e.g., an AJAX call), return a JSON response
            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }

            // Otherwise, return a redirect (for non-AJAX web requests)
            return redirect()->route('other_income.index')->with('success', 'Income deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting income: ' . $e->getMessage());

            // If the request expects JSON, return a JSON error response
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error deleting income'], 500);
            }

            // Otherwise, return a redirect with an error message
            return redirect()->route('other_income.index')->with('error', 'Failed to delete income.');
        }
    }

    public function loadMonthData($year_month)
    {
        list($year, $month) = explode('-', $year_month);

        $other_incomes = OtherIncome::whereYear('date', '=', $year)
            ->whereMonth('date', '=', $month)
            ->get();

        return response()->json([
            'other_incomes' => $other_incomes
        ]);
    }
}
