<?php

namespace App\Services;

use GuzzleHttp\Client;

class SparrowSmsService
{
    protected $client;
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->token = 'v2_b2q3qGuirL7Kw7DmG2BS2h1eijd.4owt'; // Your Sparrow SMS token
    }

    public function sendSms($to, $message)
    {
        $response = $this->client->post('https://api.sparrowsms.com/v2/sms/', [
            'form_params' => [
                'token' => $this->token,
                'from' => 'AppMPG', // Your sender ID
                'to' => $to,
                'text' => $message,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
