<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    public function handle(Request $request, Closure $next)
    {
        // Request बाट पठाइएको key
        $apiKey = $request->header('X-API-Key'); // Postman/Lovable बाट यही नाम पठाउने

        // .env बाट expected key
        $expectedKey = env('API_SECRET_KEY');

        if (!$expectedKey || $apiKey !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
