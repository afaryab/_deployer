<?php

namespace App\Connectors\IdentityForce;

use App\Connectors\HttpConnector;

class IdentityForceConnector extends HttpConnector
{
    private $endPoint;

    public function __construct($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    public static function initiateConnection($endPoint)
    {
        return new self($endPoint);
    }

    public function addTeam($name, $admin_name, $admin_email, $create_connected_app = true)
    {
        $response = $this->postRequest(
            $this->endPoint . '/api/integration/teams/new',
            [
                'name' => $name,
                'admin_name' => $admin_name,
                'admin_email' => $admin_email,
                'create_connected_app' => $create_connected_app
            ]
        );

        if($response->created()){
            return $response->json();
        }else{
            return false;
        }
    }

    public function addClient($call_back , $connected_app_id)
    {

        $response = $this->postRequest(
            $this->endPoint . '/api/integration/teams/client/new',
            [
                'call_back' => $this->ensureUrlHasScheme($call_back),
                'connected_app_id' => $connected_app_id
            ]
        );
        if ($response->created()) {
            return $response->json();
        } else {
            return false;
        }
    }

    private function ensureUrlHasScheme($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            return 'http://' . $url; // Default to HTTP if missing
        }
        return $url;
    }
}
