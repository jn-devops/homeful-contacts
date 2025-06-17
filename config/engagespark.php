<?php
return [
    'api_key' => env('ENGAGESPARK_API_KEY', ''),
    'organization_id' => env('ENGAGESPARK_ORGANIZATION_ID', ''),
    'sender_id' => env('ENGAGESPARK_SENDER_ID', ''),
    'sms_endpoint' => env('ENGAGESPARK_SMS_ENDPOINT', 'https://api.engagespark.com/v1/sms/contact'),

];
