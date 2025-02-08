<?php

namespace App\Observers;

use App\Connectors\ProcesstonIdentity\ProcesstonIdentityConnector;
use App\Deployers\IdentityForceDeployer;
use App\Deployers\VenteForceDeployer;
use App\Models\Application;
use App\Models\Tenant;
use App\Models\TenantApp;

class TenantObserver
{
    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {

        $response = ProcesstonIdentityConnector::initiateConnection()->addTeam($tenant->name, $tenant->admin_name, $tenant->admin_email);

        if($response){

            $tenant->__set('identity_force_team_id', $response['data']['team']['id']);
            $tenant->__set('identity_force_app_id', $response['data']['app']['id']);
            $tenant->save();

            $ventiForceApplication = Application::where('provider', VenteForceDeployer::class)->first();

            TenantApp::create([
                'tenant_id' => $tenant->id,
                'application_id' => $ventiForceApplication->id,
                'name' => $tenant->name . ' ' . $ventiForceApplication->name,
                'slug' => $tenant->slug . '_' . $ventiForceApplication->slug,
                'icon' => $ventiForceApplication->icon,
                'meta' => [
                    'MAX_CUSTOMERS' => 0
                ]
            ]);

        }

    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleted(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "restored" event.
     */
    public function restored(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "force deleted" event.
     */
    public function forceDeleted(Tenant $tenant): void
    {
        //
    }
}
