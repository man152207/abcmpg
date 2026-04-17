<?php

namespace App\Http\Controllers;

use App\Models\Other_Exp;
use Illuminate\Http\Request;

class OtherExpController extends Controller
{
    // Method to handle field update via AJAX
    public function updateField(Request $request)
{
    $exp = Other_Exp::find($request->id);

    if ($exp) {
        $oldValue = $exp->{$request->field}; // Store the old value

        $exp->{$request->field} = $request->value;
        if ($exp->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'oldValue' => $oldValue, 'message' => 'Failed to save changes.']);
        }
    }

    return response()->json(['success' => false, 'message' => 'Expense not found.']);
}

    // Method to display add form
    public function add_form()
    {
        return view('client.other_exp.add');
    }

    // Method to display expenses list
    public function show()
{
    // Fetch monthly summary with pagination
    $monthlySummary = Other_Exp::selectRaw("TO_CHAR(date, 'YYYY-MM') as month, SUM(amount) as total_amount")
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->paginate(5); // Show 5 rows per page

    // Fetch all expenses with pagination
    $exps = Other_Exp::orderBy('id', 'desc')->paginate(10);

    // Return to the view with data
    return view('client.other_exp.list', compact('exps', 'monthlySummary'));
}


    // Method to store new expense
    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required'],
            'title' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
        ]);

        Other_Exp::create($request->all());

        return redirect()->route('exp.show');
    }

    // Method to display update form
    public function update_form($id)
    {
        $exp = Other_Exp::findOrFail($id);
        return view('client.other_exp.update', compact('exp'));
    }

    // Method to handle updating of an expense
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => ['required'],
            'title' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
        ]);

        $exp = Other_Exp::findOrFail($id);
        $exp->update($request->all());

        return redirect()->route('exp.show');
    }

    // Method to delete an expense
    public function delete($id)
    {
        $exp = Other_Exp::findOrFail($id);
        $exp->delete();

        return redirect()->route('exp.show');
    }

    // Method to search for expenses
    public function search(Request $request)
    {
        $exps = Other_Exp::where('title', 'like', '%' . $request->search . '%')->paginate(10);
        return view('client.other_exp.list', compact('exps'));
    }
}
