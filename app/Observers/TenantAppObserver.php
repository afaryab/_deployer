<?php

namespace App\Observers;

use App\Connectors\ProcesstonIdentity\ProcesstonIdentityConnector;
use App\Deployers\IdentityForceDeployer;
use App\Models\TenantApp;
use App\Models\Domain;
use App\Models\DeploymentActivity;

class TenantAppObserver
{

    /**
     * Handle the TenantApp "created" event.
     */
    public function created(TenantApp $tenantApp): void
    {
        $domainName = str_replace("_", "-", $tenantApp->slug) . env('PRIMARY_DOMAIN');

        if($tenantApp->application->provider != IdentityForceDeployer::class){


            $response = ProcesstonIdentityConnector::initiateConnection()->addClient(
                $domainName .'/callback/identity-force',
                $tenantApp->tenant->identity_force_app_id
            );

            if ($response) {
                $tenantApp->__set('identity_force_app_secret', $response['data']['client_secret']);
                $tenantApp->__set('identity_force_app_key', $response['data']['client_id']);
                $tenantApp->__set('identity_force_app_url', $response['data']['client_url']);
                $tenantApp->save();
            }
        }


        DeploymentActivity::create([
            'tenant_id' => $tenantApp->tenant_id,
            'application_id' => $tenantApp->application_id,
            'tenant_app_id' => $tenantApp->id,
            'provider' => $tenantApp->application->provider,
            'method' => 'create',
            'status' => 'pending',
        ]);

        Domain::create([
            'tenant_id' => $tenantApp->tenant_id,
            'application_id' => $tenantApp->application_id,
            'tenant_app_id' => $tenantApp->id,
            'domain' => $domainName,
        ]);
    }

    /**
     * Handle the TenantApp "updated" event.
     */
    public function updated(TenantApp $tenantApp): void
    {
        // DeploymentActivity::create([
        //     'tenant_id' => $tenantApp->tenant_id,
        //     'application_id' => $tenantApp->application_id,
        //     'tenant_app_id' => $tenantApp->id,
        //     'method' => 'sync',
        //     'status' => 'pending',
        // ]);
    }

    /**
     * Handle the TenantApp "deleted" event.
     */
    public function deleted(TenantApp $tenantApp): void
    {
        // DeploymentActivity::create([
        //     'tenant_id' => $tenantApp->tenant_id,
        //     'application_id' => $tenantApp->application_id,
        //     'tenant_app_id' => $tenantApp->id,
        //     'method' => 'turn-off',
        //     'status' => 'pending',
        // ]);
    }

    /**
     * Handle the TenantApp "restored" event.
     */
    public function restored(TenantApp $tenantApp): void
    {
        // DeploymentActivity::create([
        //     'tenant_id' => $tenantApp->tenant_id,
        //     'application_id' => $tenantApp->application_id,
        //     'tenant_app_id' => $tenantApp->id,
        //     'method' => 'turn-on',
        //     'status' => 'pending',
        // ]);
    }

    /**
     * Handle the TenantApp "force deleted" event.
     */
    public function forceDeleted(TenantApp $tenantApp): void
    {
        // DeploymentActivity::create([
        //     'tenant_id' => $tenantApp->tenant_id,
        //     'application_id' => $tenantApp->application_id,
        //     'tenant_app_id' => $tenantApp->id,
        //     'method' => 'delete',
        //     'status' => 'pending',
        // ]);
    }
}
