<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;

class InvoicelistsController extends Controller
{
    public function index()
{
    // Get the currently logged-in customer
    $customer = Auth::guard('customer')->user();

    // Fetch ads based on phone number (not customer_id)
    $ads = Ad::where('customer', $customer->phone)->orderBy('created_at', 'desc')->get();

    // USD Rate fallback
    $usdRate = $customer->usd_rate ?? 130;

    return view('auth.invoicelists', compact('ads', 'customer', 'usdRate'));
}
}
