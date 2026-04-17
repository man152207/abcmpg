<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdAccount;
use App\Models\StorredAdAccount;
use Illuminate\Support\Facades\DB;

class AdAccountManagementController extends Controller
{
    public function index()
{
    // Fetch ad accounts with calculated fields
    $adAccountsWithUSD = AdAccount::select('ad_accounts.*')
        ->selectRaw('(
            SELECT SUM(USD)
            FROM ads
            WHERE REGEXP_REPLACE(Ad_Account, \'^.*/\', \'\') = ad_accounts.account_name
            AND DATE(created_at) >= ad_accounts.active_since
        ) AS usd_value')
        ->selectRaw('GREATEST(initial_remaining_days - DATEDIFF(CURDATE(), DATE(active_since)), 0) AS remaining_days')
        ->selectRaw('(
            CASE
                WHEN (
                    SELECT SUM(USD)
                    FROM ads
                    WHERE REGEXP_REPLACE(Ad_Account, \'^.*/\', \'\') = ad_accounts.account_name
                    AND DATE(created_at) >= ad_accounts.active_since
                ) IS NOT NULL
                THEN running_ads_balance + (
                    SELECT SUM(USD)
                    FROM ads
                    WHERE REGEXP_REPLACE(Ad_Account, \'^.*/\', \'\') = ad_accounts.account_name
                    AND DATE(created_at) >= ad_accounts.active_since
                )
                ELSE running_ads_balance
            END
        ) AS running_ads_balance_updated')
        ->selectRaw('(
            CASE
                WHEN (
                    SELECT SUM(USD)
                    FROM ads
                    WHERE REGEXP_REPLACE(Ad_Account, \'^.*/\', \'\') = ad_accounts.account_name
                    AND DATE(created_at) >= ad_accounts.active_since
                ) IS NOT NULL
                THEN account_threshold - (running_ads_balance + (
                    SELECT SUM(USD)
                    FROM ads
                    WHERE REGEXP_REPLACE(Ad_Account, \'^.*/\', \'\') = ad_accounts.account_name
                    AND DATE(created_at) >= ad_accounts.active_since
                ))
                ELSE account_threshold - running_ads_balance
            END
        ) AS targeted_budget_updated')
        ->orderBy('created_at', 'DESC')
        ->paginate(15);

    // Fetch unique Ad_Account values for the dropdown
    $adAccountOptions = DB::table('ads')
        ->selectRaw('REGEXP_REPLACE(Ad_Account, \'^.*/\', \'\') AS Ad_Account_Display, MAX(updated_at) as latest_update')
        ->groupBy('Ad_Account_Display')
        ->orderBy('latest_update', 'DESC')
        ->get();

    // Fetch grouped ad accounts and sort them alphabetically
    $groupedAdAccounts = StorredAdAccount::all()
        ->groupBy('group_name')
        ->sortKeys() // Sort group names alphabetically
        ->map(function ($accounts) {
            return $accounts->sortBy('ad_account_name'); // Sort ad accounts alphabetically within each group
        });

    // Fetch all unique group names
    $allGroups = StorredAdAccount::distinct()->pluck('group_name')->sort()->toArray();

    return view('admin.adAccount.adManagement', compact('adAccountsWithUSD', 'adAccountOptions', 'groupedAdAccounts', 'allGroups'));
}


    public function storeAdAccount(Request $request)
    {
        $validatedData = $request->validate([
            'account_name' => 'required|string|max:255',
            'active_since' => 'nullable|date',
            'threshold_reached_date' => 'nullable|date',
            'account_threshold' => 'nullable|numeric',
            'running_ads_balance' => 'nullable|numeric',
            'targeted_budget' => 'nullable|numeric',
            'new_applied_budget' => 'nullable|numeric',
            'initial_remaining_days' => 'required|integer|min:0',
        ]);

        $validatedData['active_since'] = $validatedData['active_since'] ?? now();
        $validatedData['threshold_reached_date'] = $validatedData['threshold_reached_date'] ?? now();
        $validatedData['new_applied_history'] = 'Initial Data Applied';

        AdAccount::create($validatedData);

        return redirect()->back()->with('success', 'Ad Account added successfully!');
    }

    public function deleteAdAccount($id)
    {
        AdAccount::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Ad Account deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.'], 400);
        }

        try {
            AdAccount::whereIn('id', $ids)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateAdAccount(Request $request, $id)
    {
        $adAccount = AdAccount::findOrFail($id);

        $validatedData = $request->validate([
            'active_since' => 'nullable|date',
            'threshold_reached_date' => 'nullable|date',
            'account_threshold' => 'nullable|numeric',
            'running_ads_balance' => 'nullable|numeric',
            'targeted_budget' => 'nullable|numeric',
        ]);

        // Recalculate Remaining Days
        if (!empty($validatedData['active_since']) && !empty($validatedData['threshold_reached_date'])) {
            $activeSince = new \DateTime($validatedData['active_since']);
            $thresholdDate = new \DateTime($validatedData['threshold_reached_date']);
            $remainingDays = max(0, $thresholdDate->diff(new \DateTime())->days);
            $validatedData['remaining_days'] = $remainingDays;
        }

        // Recalculate Targeted Budget
        if (!empty($validatedData['account_threshold']) && !empty($validatedData['running_ads_balance'])) {
            $threshold = $validatedData['account_threshold'];
            $balance = $validatedData['running_ads_balance'];
            $validatedData['targeted_budget'] = $threshold - $balance;
        }

        $adAccount->update($validatedData);

        return response()->json(['success' => true]);
    }

    public function storeGroup(Request $request)
{
    $request->validate([
        'group_name' => 'required|string|max:255|unique:storred_ad_accounts,group_name',
    ]);

    // Create an empty group with the given name
    StorredAdAccount::create(['group_name' => $request->group_name, 'ad_account_name' => '']);

    return redirect()->back()->with('success', 'Group created successfully!');
}

public function storeGroupedAdAccount(Request $request)
{
    $request->validate([
        'group_name' => 'required|string|max:255',
        'ad_account_name' => 'required|string|max:255',
    ]);

    // Check if the ad account name already exists
    $existingAdAccount = StorredAdAccount::where('ad_account_name', $request->ad_account_name)->first();

    if ($existingAdAccount) {
        // Update the group_name if the ad account already exists
        $existingAdAccount->update([
            'group_name' => $request->group_name,
        ]);

        return redirect()->back()->with('success', 'Ad Account already exists. Group updated successfully!');
    }

    // If the ad account doesn't exist, create a new record
    StorredAdAccount::create($request->all());

    return redirect()->back()->with('success', 'Ad Account added successfully to group!');
}

    public function deleteGroupedAdAccount($id)
{
    try {
        // Find the grouped ad account by ID
        $groupedAdAccount = StorredAdAccount::findOrFail($id);

        // Delete the grouped ad account
        $groupedAdAccount->delete();

        return redirect()->back()->with('success', 'Grouped Ad Account deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to delete the grouped Ad Account: ' . $e->getMessage());
    }
}
    public function store(Request $request)
{
    $request->validate([
        'group_name' => 'required|string',
        'ad_account_name' => 'required|string',
    ]);

    // Store logic for group and ad account
    // ...

    return redirect()
        ->back()
        ->with('selectedGroup', $request->group_name)
        ->with('adAccountName', $request->ad_account_name);
}

}
