<?php

namespace App\Connectors\ProcesstonIdentity;

use App\Connectors\IdentityForce\IdentityForceConnector;

class ProcesstonIdentityConnector
{

    public static function initiateConnection()
    {
        return IdentityForceConnector::initiateConnection(
            'login.'. env('PRIMARY_DOMAIN')
        );
    }


}
