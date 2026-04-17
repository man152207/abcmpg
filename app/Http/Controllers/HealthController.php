<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        $defaultConnection = config('database.default');
        $driver = config("database.connections.{$defaultConnection}.driver", $defaultConnection);

        try {
            DB::connection()->getPdo();

            return response()->json([
                'status'  => 'ok',
                'db_ok'   => true,
                'driver'  => DB::getDriverName(),
                'db_name' => DB::getDatabaseName(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Health check DB connection failed', [
                'driver' => $driver,
                'error'  => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'db_ok'  => false,
                'driver' => $driver,
                'error'  => 'database_unreachable',
            ], 503);
        }
    }
}
