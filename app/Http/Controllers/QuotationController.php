<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use PDF;

class QuotationController extends Controller
{
    public function create()
    {
        $quotations = Quotation::all();
        return view('item.quotation_generate', compact('quotations'));
    }

    public function store(Request $request)
    {
        // Handle multi-select location
        $request->merge([
            'target_location' => implode(', ', (array) $request->get('target_location')),
        ]);

        $quotation = Quotation::create($request->all());

        // Generate a PDF after storing the quotation
        $pdf = PDF::loadView('item.quotation_pdf', compact('quotation'));

        return redirect()->route('quotation.generate')->with('success', 'Quotation created successfully');
    }

    public function edit($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotations = Quotation::all();
        return view('item.quotation_generate', compact('quotation', 'quotations'));
    }

    public function update(Request $request, $id)
    {
        // Handle multi-select location
        $request->merge([
            'target_location' => implode(', ', (array) $request->get('target_location')),
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update($request->all());
        return redirect()->route('quotation.generate')->with('success', 'Quotation updated successfully');
    }

    public function download($id)
    {
        $quotation = Quotation::findOrFail($id);
        $pdf = PDF::loadView('item.quotation_pdf', compact('quotation'));
        return $pdf->download('quotation.pdf');
    }

    public function view($id)
    {
        $quotation = Quotation::findOrFail($id);
        return view('item.quotation_pdf', compact('quotation'));
    }

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();
        return redirect()->route('quotation.generate')->with('success', 'Quotation deleted successfully');
    }

    public function calculateEstimatedResults(Request $request)
{
    // Sample logic for calculating estimated results based on inputs
    $budget = $request->input('budget');
    $duration = $request->input('duration');
    $targetLocation = $request->input('target_location');
    $ageRange = $request->input('age_range');
    $gender = $request->input('gender');

    // Example calculations (you'll want to refine these based on real data and logic)
    $impressions = round(($budget / 160) * 10000 * ($duration / 7)); // Rough estimate based on budget and duration
    $clicks = round($impressions * 0.05); // Assuming 5% click-through rate (CTR)

    return response()->json([
        'impressions' => $impressions,
        'clicks' => $clicks,
    ]);
}
}
