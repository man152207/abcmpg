<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthCode;
use App\Models\AuthCodeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use OTPHP\TOTP;
use DeviceDetector\DeviceDetector;

class TwoFAController extends Controller
{
    public function index()
    {
        $authCodes = AuthCode::all();
        return view('admin.2fa', compact('authCodes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'auth_token_code' => 'required|string|max:255',
            'recovery_code' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cleanedToken = $this->cleanBase32Token($request->auth_token_code);

        AuthCode::create([
            'account_name' => $request->account_name,
            'auth_token_code' => $cleanedToken,
            'recovery_code' => $request->recovery_code,
            'result' => null,
        ]);

        return redirect()->route('admin.2fa.index')->with('success', 'Auth Code added successfully.');
    }

    public function generateCode(Request $request, $id)
    {
        $authCode = AuthCode::findOrFail($id);

        try {
            $totp = TOTP::create($authCode->auth_token_code);
            $code = $totp->now();

            $authCode->update(['result' => $code]);

            // Log the generation
            AuthCodeLog::create([
                'auth_code_id' => $authCode->id,
                'admin_id' => Auth::guard('admin')->id(),
                'device' => $this->parseUserAgent($request->header('User-Agent')),
                'location' => $this->getLocation($request->ip()),
                'generated_at' => now(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'code' => $code,
                    'message' => 'TOTP code generated successfully.'
                ]);
            }

            return redirect()->route('admin.2fa.index')->with('success', 'TOTP code generated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid auth token code: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->route('admin.2fa.index')->with('error', 'Invalid auth token code: ' . $e->getMessage());
        }
    }

    public function resetCode(Request $request, $id)
    {
        $authCode = AuthCode::findOrFail($id);
        $authCode->update(['result' => null]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Code reset successfully.'
            ]);
        }

        return redirect()->route('admin.2fa.index')->with('success', 'Code reset successfully.');
    }

    public function update(Request $request, $id)
    {
        $authCode = AuthCode::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'account_name' => 'required|string|max:255',
            'auth_token_code' => 'required|string|max:255',
            'recovery_code' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cleanedToken = $this->cleanBase32Token($request->auth_token_code);

        $authCode->update([
            'account_name' => $request->account_name,
            'auth_token_code' => $cleanedToken,
            'recovery_code' => $request->recovery_code,
            'result' => null,
        ]);

        return redirect()->route('admin.2fa.index')->with('success', 'Auth Code updated successfully.');
    }

    public function destroy($id)
    {
        $authCode = AuthCode::findOrFail($id);
        $authCode->delete();

        return redirect()->route('admin.2fa.index')->with('success', 'Auth Code deleted successfully.');
    }

    public function showLogs($id)
    {
        $authCode = AuthCode::findOrFail($id);
        $logs = AuthCodeLog::where('auth_code_id', $id)->with('admin')->paginate(10);
        return view('admin.2fa_logs', compact('authCode', 'logs'));
    }

    private function getLocation($ip)
    {
        try {
            $client = new \ipinfo\ipinfo\IPinfo(config('services.ipinfo.token'));
            $details = $client->getDetails($ip);
            return $details->city . ', ' . $details->country;
        } catch (\Exception $e) {
            return $ip;
        }
    }

    private function cleanBase32Token($token)
    {
        // Remove spaces and convert to uppercase
        $cleaned = str_replace(' ', '', strtoupper($token));
        // Ensure only valid Base32 characters (A-Z, 2-7)
        $cleaned = preg_replace('/[^A-Z2-7]/', '', $cleaned);
        return $cleaned;
    }

    private function parseUserAgent($userAgent)
    {
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        $clientInfo = $dd->getClient();
        $osInfo = $dd->getOs();
        $deviceInfo = $dd->getDeviceName();
        $brand = $dd->getBrandName();
        $model = $dd->getModel();

        $browser = $clientInfo['name'] ?? 'Unknown';
        $browserVersion = $clientInfo['version'] ?? '';
        $os = $osInfo['name'] ?? 'Unknown';
        $osVersion = $osInfo['version'] ?? '';
        $deviceType = $deviceInfo ?? 'Unknown';
        $deviceModel = ($brand && $model) ? "$brand $model" : 'Unknown';

        if ($deviceType === 'desktop') {
            $deviceModel = 'Desktop';
        }

        return "$browser $browserVersion on $deviceModel ($os $osVersion)";
    }
}