<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;
use Google_Client;
use Google_Service_Gmail;
use Illuminate\Support\Facades\Log;


use Google_Client;
use Google_Service_Gmail;

class GoogleAuthController extends Controller
{
    public function getGmailReceivedEmails($token)
{
    try {
        // Initialize Google API client
        $client = new Google_Client();
        $client->setScopes([Google_Service_Gmail::GMAIL_READONLY]);
        $client->setAccessToken($token);

        // Create Gmail service
        $service = new Google_Service_Gmail($client);

        // Get the list of messages
        $messages = $service->users_messages->listUsersMessages('me');

        $gmailEmails = [];

        foreach ($messages->getMessages() as $message) {
            $email = $service->users_messages->get('me', $message->getId());
            $gmailEmails[] = $email;
        }

        // Temporary data dumping
        dd($gmailEmails);

        // Ensure that $gmailEmails is never null, return an empty array if no emails
        return $gmailEmails ?: [];
    } 
    catch (\Exception $e) {
        // Handle exception
        \Log::error('Error fetching Gmail emails: ' . $e->getMessage());
        // Return an empty array in case of an exception
        return [];
    }
}
public function handleGoogleCallback()
{
    $user = Socialite::driver('google')->user();
    
    // Get Gmail emails
    $gmailEmails = $this->getGmailReceivedEmails($user->token);

    // Log the output for debugging
    Log::info('Gmail Emails:', $gmailEmails);

    // Ensure $gmailEmails is always an array
    $gmailEmails = is_array($gmailEmails) ? $gmailEmails : [];

    // Pass the Gmail emails to the view
    return view('admin.ads_list', ['gmailEmails' => $gmailEmails]);
}

}

