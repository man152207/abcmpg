<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\BotPrompt;
use Illuminate\Support\Facades\Cache;

class FacebookWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1) FB VERIFY (GET)
        if ($request->isMethod('get')) {
            $verifyToken = env('FB_VERIFY_TOKEN');

            $mode      = $request->query('hub_mode') ?? $request->query('hub.mode');
            $token     = $request->query('hub_verify_token') ?? $request->query('hub.verify_token');
            $challenge = $request->query('hub_challenge') ?? $request->query('hub.challenge');

            if ($mode === 'subscribe' && $token === $verifyToken) {
                return response($challenge, 200);
            }

            return response('Verification failed', 403);
        }

        // 2) FB MESSAGE (POST)
        $payload = $request->all();
        Log::info('FB POST payload', $payload);

        if (($payload['object'] ?? '') === 'page') {
            foreach ($payload['entry'] as $entry) {
                foreach ($entry['messaging'] as $event) {

                    // delivery / read / echo skip
                    if (isset($event['delivery']) || isset($event['read'])) {
                        continue;
                    }
                    if (isset($event['message']['is_echo']) && $event['message']['is_echo'] === true) {
                        continue;
                    }

                    $senderId = $event['sender']['id'] ?? null;
                    $text     = $event['message']['text'] ?? null;

                    if ($senderId && $text) {

                        // (optional) local conversation context
                        $conversationContext = $this->getConversationContext($senderId, $text);

                        // 1st priority: Assistant API (dashboard ma banako)
                        $reply = $this->getAssistantResponse($senderId, $text);

                        // fallback: old completion way
                        if (!$reply) {
                            $reply = $this->getAIResponseWithContext($text, $conversationContext);
                        }

                        // update local context (so old code still works)
                        $this->updateConversationContext($senderId, $text, $reply);

                        // send to FB
                        $this->sendMessage($senderId, $reply);
                    }
                }
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Get conversation context for user (your existing one)
     */
    private function getConversationContext(string $senderId, string $currentMessage): array
    {
        $context = Cache::get("fb_conversation_{$senderId}", []);

        $context['recent_messages'][] = [
            'type' => 'user',
            'text' => $currentMessage,
            'time' => now()
        ];

        if (isset($context['recent_messages']) && count($context['recent_messages']) > 5) {
            $context['recent_messages'] = array_slice($context['recent_messages'], -5);
        }

        return $context;
    }

    /**
     * Update conversation context after response
     */
    private function updateConversationContext(string $senderId, string $userMessage, string $botResponse): void
    {
        $context = Cache::get("fb_conversation_{$senderId}", []);

        $context['recent_messages'][] = [
            'type' => 'bot',
            'text' => $botResponse,
            'time' => now()
        ];

        if (preg_match('/my name is (.*)/i', $userMessage, $matches)) {
            $context['user_name'] = trim($matches[1]);
        }

        Cache::put("fb_conversation_{$senderId}", $context, 3600);
    }

    /**
     * NEW: Use OpenAI Assistants (dashboard ma banako)
     */
    private function getAssistantResponse(string $senderId, string $userText): ?string
{
    $apiKey      = env('OPENAI_API_KEY');
    $assistantId = env('OPENAI_ASSISTANT_ID');
    $baseUrl     = env('OPENAI_BASE_URL', 'https://api.openai.com/v1');
    $projectId   = env('OPENAI_PROJECT_ID'); // optional

    if (!$apiKey || !$assistantId) {
        return null;
    }

    // common headers (this is the missing part 👇)
    $headers = [
        'Authorization' => 'Bearer '.$apiKey,
        'Content-Type'  => 'application/json',
        'OpenAI-Beta'   => 'assistants=v2',
    ];
    if ($projectId) {
        $headers['OpenAI-Project'] = $projectId;
    }

    try {
        // 1) get/create thread
        $threadId = Cache::get("fb_thread_{$senderId}");

        if (!$threadId) {
            $res = Http::withHeaders($headers)
                ->post($baseUrl . '/threads', []);

            if (!$res->successful()) {
                Log::error('Assistant: thread create failed', [
                    'status' => $res->status(),
                    'body'   => $res->body(),
                ]);
                return null;
            }

            $threadId = $res->json('id');
            Cache::put("fb_thread_{$senderId}", $threadId, 3600);
        }

        // 2) add user message
        $msgRes = Http::withHeaders($headers)
            ->post($baseUrl . "/threads/{$threadId}/messages", [
                'role'    => 'user',
                'content' => $userText,
            ]);

        if (!$msgRes->successful()) {
            Log::error('Assistant: add message failed', [
                'status' => $msgRes->status(),
                'body'   => $msgRes->body(),
            ]);
            return null;
        }

        // 3) start run
        $runRes = Http::withHeaders($headers)
            ->post($baseUrl . "/threads/{$threadId}/runs", [
                'assistant_id' => $assistantId,
            ]);

        if (!$runRes->successful()) {
            Log::error('Assistant: run create failed', [
                'status' => $runRes->status(),
                'body'   => $runRes->body(),
            ]);
            return null;
        }

        $runId = $runRes->json('id');

        // 4) poll
        $maxTries = 10;
        for ($i = 0; $i < $maxTries; $i++) {
            sleep(1);

            $check = Http::withHeaders($headers)
                ->get($baseUrl . "/threads/{$threadId}/runs/{$runId}");

            if (!$check->successful()) {
                Log::error('Assistant: run status failed', [
                    'status' => $check->status(),
                    'body'   => $check->body(),
                ]);
                return null;
            }

            $status = $check->json('status');

            if ($status === 'completed') {
                // 5) get messages
                $messagesRes = Http::withHeaders($headers)
                    ->get($baseUrl . "/threads/{$threadId}/messages", [
                        'limit' => 10,
                    ]);

                if ($messagesRes->successful()) {
                    $messages = $messagesRes->json('data');

                    // newest first
                    foreach ($messages as $m) {
                        if (($m['role'] ?? '') === 'assistant') {
                            foreach (($m['content'] ?? []) as $p) {
                                if (($p['type'] ?? '') === 'text') {
                                    return trim($p['text']['value']);
                                }
                            }
                        }
                    }
                }

                break;
            }

            if (in_array($status, ['failed', 'cancelled', 'expired'])) {
                Log::error('Assistant: run ended with '.$status);
                return null;
            }
        }

    } catch (\Throwable $e) {
        Log::error('Assistant exception', [
            'message' => $e->getMessage(),
        ]);
        return null;
    }

    return null;
}


    /**
     * OLD: chat.completions fallback
     */
    private function getAIResponseWithContext(string $userText, array $context): string
    {
        $apiKey = env('OPENAI_API_KEY');

        // DB prompt (fallback)
        $dbPrompt = BotPrompt::where('key', 'facebook_messenger')
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();

        if (!$apiKey) {
            return "Namaste hajur! Hami Facebook/Instagram ads, boosting ra graphics design gardachau. WhatsApp: 9856000601";
        }

        // context build
        $contextInfo = "";
        if (isset($context['user_name'])) {
            $contextInfo .= "User's name: {$context['user_name']}\n";
        }
        if (isset($context['recent_messages'])) {
            $contextInfo .= "Recent conversation:\n";
            foreach ($context['recent_messages'] as $msg) {
                $role = $msg['type'] === 'user' ? 'User' : 'Assistant';
                $contextInfo .= "{$role}: {$msg['text']}\n";
            }
        }

        // final system prompt
        $enhancedPrompt = ($dbPrompt->prompt_text ?? '') . "\n\nCURRENT CONVERSATION CONTEXT:\n" . $contextInfo;

        try {
            $headers = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ];

            if (env('OPENAI_PROJECT_ID')) {
                $headers['OpenAI-Project'] = env('OPENAI_PROJECT_ID');
            }

            $res = Http::withHeaders($headers)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    // tyo bela gpt-5 use gar:
                    'model' => 'gpt-5',
                    'messages' => [
                        ['role' => 'system', 'content' => $enhancedPrompt],
                        ['role' => 'user',   'content' => $userText],
                    ],
                    'max_tokens'  => 200,
                    'temperature' => 0.7,
                ]);

            if ($res->successful()) {
                $content = $res->json('choices.0.message.content');
                if ($content) {
                    return trim($content);
                }
            } else {
                Log::error('OpenAI API error', [
                    'status' => $res->status(),
                    'body'   => $res->body(),
                ]);
            }

        } catch (\Throwable $e) {
            Log::error('OpenAI exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return "Namaste hajur! MPG Solution bata. Kripaya hamro WhatsApp ma samparka garnuhos: 9856000601";
    }

    /**
     * Send to FB
     */
    private function sendMessage(string $recipientId, string $message): void
    {
        $pageToken = env('FB_PAGE_ACCESS_TOKEN');

        try {
            Http::post('https://graph.facebook.com/v18.0/me/messages', [
                'recipient'    => ['id' => $recipientId],
                'message'      => ['text' => $message],
                'access_token' => $pageToken,
            ]);

        } catch (\Throwable $e) {
            Log::error('FB send message failed: '.$e->getMessage());
        }
    }
}
