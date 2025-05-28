<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class WherebyService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.whereby.dev/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . config('services.whereby.key'),
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    public function createMeeting(int $durationMinutes = 30): array
    {
        $now = Carbon::now()->addMinutes(1); // Commencer dans 1 min pour éviter erreur

        $payload = [
            'startDate' => $now->toIso8601String(),
            'endDate'   => $now->copy()->addMinutes($durationMinutes)->toIso8601String(),
            'fields'    => ['hostRoomUrl'],
        ];

        try {
            $response = $this->client->post('meetings', ['json' => $payload]);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $error = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response from API';
            Log::error('[Whereby API ERROR]', [
                'message' => $e->getMessage(),
                'response' => $error
            ]);
            throw new Exception("Whereby API error: " . $error);
        }
    }
}
