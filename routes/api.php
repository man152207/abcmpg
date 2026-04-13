<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;  // <-- ADD THIS LINE!
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\CustomerDashboardApiController;
use App\Http\Controllers\Api\EmailMarketingController;
use App\Http\Controllers\Api\BonusController;


Route::middleware('api.key')->group(function () {
    // Basic customer endpoints
    Route::get('/customers', [CustomerApiController::class, 'index']);
    Route::get('/customers/{id}', [CustomerApiController::class, 'show']);
    Route::get('/customers/{id}/campaign-links', [CustomerDashboardApiController::class, 'campaignLinks']);

    // Customer dashboard endpoints
    Route::get('/customers/{id}/dashboard', [CustomerDashboardApiController::class, 'dashboard']);
    Route::get('/customers/{id}/notes', [CustomerDashboardApiController::class, 'notes']);
    Route::get('/customers/{id}/receipts', [CustomerDashboardApiController::class, 'receipts']);
    Route::get('/customers/{id}/monthly-data', [CustomerDashboardApiController::class, 'monthlyData']);

    // Email marketing
    Route::get('/email-marketing/active-customers', [EmailMarketingController::class, 'index']);
    Route::get('/ads', [EmailMarketingController::class, 'getAllAds']);

    // Bonus routes (protected only by API key)
    Route::get('/customers/{customer}/bonus-summary', [BonusController::class, 'summary']);
    Route::post('/customers/{customer}/bonus-claim', [BonusController::class, 'claim']);
    Route::get('/customers/{customer}/bonus-claims', [BonusController::class, 'claims']);
    
    // Expense tracking endpoints (FIXED - removed extra middleware)
    Route::get('/clients', function () {
        $clients = DB::table('clients')->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $clients]);
    });

    Route::get('/other-expenses', function () {
        $expenses = DB::table('other__exps')->orderBy('date', 'desc')->get();
        return response()->json(['success' => true, 'data' => $expenses]);
    });
});
