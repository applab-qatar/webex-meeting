<?php
return [
    'api-version'=>env('WEBEX_API_VERSION','V1'),

    'client-id'=>env('WEBEX_CLIENT_ID',''),
    'client-secret'=>env('WEBEX_CLIENT_SECRET',''),
    'authorized-code'=>env('WEBEX_AUTH_CODE',''),
    'redirect-uri'=>env('WEBEX_REDIRECT_URI',''),
    'integration-id'=>env('WEBEX_INTEGRATION_ID',''),
    'model' => [
        'Meeting' => "App\Models\Event",
        'Invitee' => "App\Models\EventRegistration"
    ],
];
