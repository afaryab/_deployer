<?php

namespace App\Connectors;

use Illuminate\Support\Facades\Http;

class HttpConnector
{

    public function postRequest($endPoint, $payload)
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'integration-key' => env('PROCESSTON_INTEGRATION_KEY')
        ])
        ->post($endPoint, $payload);

    }
}
