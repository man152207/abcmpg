<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function byCustomer(Request $request)
    {
        return response()->json([]);
    }

    public function attach(Request $request)
    {
        return response()->json(['message' => 'Not implemented']);
    }
}
