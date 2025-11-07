<?php

namespace App\Http\Controllers;

use App\Models\AdAccount;
use Illuminate\Http\Request;

class TABCController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'adAccountName' => 'required|string|max:255',
        'currentThreshold' => 'required|numeric',
        'currentBalance' => 'required|numeric',
        'targetedBudget' => 'required|numeric',
    ]);

    $adAccount = AdAccount::create([
        'account_name' => $validated['adAccountName'],
        'current_threshold' => $validated['currentThreshold'],
        'current_balance' => $validated['currentBalance'],
        'targeted_budget' => $validated['targetedBudget'],
    ]);

    return response()->json([
        'success' => true,
        'adAccount' => [
            'account_name' => $adAccount->account_name,
            'current_threshold' => $adAccount->current_threshold,
            'current_balance' => $adAccount->current_balance,
            'targeted_budget' => $adAccount->targeted_budget,
        ],
    ]);
}

    public function getAll()
    {
        $adAccounts = AdAccount::all();
        return response()->json(['success' => true, 'data' => $adAccounts]);
    }

    public function update(Request $request, $id)
    {
        $adAccount = AdAccount::findOrFail($id);
        $adAccount->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        AdAccount::destroy($id);
        return response()->json(['success' => true]);
    }
}
