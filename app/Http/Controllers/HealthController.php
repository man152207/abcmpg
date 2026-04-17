<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        try {
            DB::connection()->getPdo();
            $driver = DB::getDriverName();
            $dbName = DB::getDatabaseName();

            return response()->json([
                'status'  => 'ok',
                'db_ok'   => true,
                'driver'  => $driver,
                'db_name' => $dbName,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'db_ok'   => false,
                'driver'  => config('database.default'),
                'error'   => $e->getMessage(),
            ], 503);
        }
    }
}
