<?php

namespace App\Http\Controllers;

use App\Services\SparrowSmsService;

class WebSMSController extends Controller
{
    protected $sparrowSmsService;

    public function __construct(SparrowSmsService $sparrowSmsService)
    {
        $this->sparrowSmsService = $sparrowSmsService;
    }

    public function sendSms()
    {
        $phoneNumber = '9856000601'; // Replace with the recipient's phone number
        $message = 'Your SMS message content'; // Replace with your message content

        $response = $this->sparrowSmsService->sendSms($phoneNumber, $message);

        if (isset($response['status']) && $response['status'] === 'success') {
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            return response()->json(['message' => 'Failed to send SMS'], 500);
        }
    }
}
