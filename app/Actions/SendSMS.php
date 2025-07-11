<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class SendSMS
{
    use AsAction;

    public function handle(string $mobile, string $message)
    {
        $apiUrl = 'https://api.engagespark.com/v1/sms/contact';
        $apiKey = config('engagespark.api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'orgId' => config('engagespark.organization_id'),
            'from' => config('engagespark.sender_id'),
            'message' => $message,
            'fullPhoneNumber' => $mobile,
            'to'=>$mobile,
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'error' => $response->json('error') ?? 'Unknown error',
            ];
        }

        return [
            'success' => true,
            'response' => $response->json(),
        ];
    }
}
