<?php

namespace Database\Seeders;

use App\Connectors\ProcesstonIdentity\ProcesstonIdentityConnector;
use App\Deployers\DomainDeployer;
use App\Deployers\IdentityForceDeployer;
use App\Deployers\ProxyDomainDeployer;
use App\Deployers\VenteForceDeployer;
use App\Models\Application;
use App\Models\DeploymentActivity;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\TenantApp;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::createQuietly([
            'name' => 'Processton',
            'slug' => 'processton',
            'logo' => '',
            'admin_email' => 'ahmadkokab@processton.com',
            'admin_name' => 'Ahmad Faryab Kokab',
            'identity_force_team_id' => 1,
            'identity_force_app_id' => 1
        ]);

        $tenantApps = Application::whereIn('provider',[
            IdentityForceDeployer::class,
            VenteForceDeployer::class,
        ])->get();


        $idpApplication = $tenantApps->filter(function ($app) {
            return $app->provider == IdentityForceDeployer::class;
        })->first();

        $tenantApp = TenantApp::createQuietly([
            'tenant_id' => $tenant->id,
            'application_id' => $idpApplication->id,
            'name' => $tenant->name . ' ' . $idpApplication->name,
            'slug' => $tenant->slug . '_' . $idpApplication->slug,
            'icon' => $idpApplication->icon,
            'meta' => [
                "MAX_USERS" => 0,
                "MAX_TEAMS" => 0,
                "ADMIN_TEAM_NAME" => 'Processton',
                "ADMIN_EMAILS" => 'ahmadkokab@processton.com',
                "ADMIN_IDENTIFIED_BY" => 'Team'
            ]
        ]);

        $deploymentAction1 = DeploymentActivity::create([
            'tenant_id' => $tenantApp->tenant_id,
            'application_id' => $tenantApp->application_id,
            'tenant_app_id' => $tenantApp->id,
            'provider' => $tenantApp->application->provider,
            'method' => 'create',
            'status' => 'pending',
        ]);


        $domain = Domain::createQuietly([
            'tenant_id' => $tenantApp->tenant_id,
            'application_id' => $tenantApp->application_id,
            'tenant_app_id' => $tenantApp->id,
            'domain' => 'login.' . env('PRIMARY_DOMAIN'),
        ]);

        $deploymentAction2 = DeploymentActivity::create([
            'domain_id' => $domain->id,
            'provider' => ProxyDomainDeployer::class,
            'method' => 'create',
            'status' => 'pending',
        ]);

        $deploymentAction1->provider::deploy($deploymentAction1);
        $deploymentAction2->provider::deploy($deploymentAction2);

        $ventiForceApplication = $tenantApps->filter(function ($app) {
            return $app->provider == VenteForceDeployer::class;
        })->first();

        $tenantApp = TenantApp::createQuietly([
            'tenant_id' => $tenant->id,
            'application_id' => $ventiForceApplication->id,
            'name' => $tenant->name . ' ' . $ventiForceApplication->name,
            'slug' => $tenant->slug.'_' . $ventiForceApplication->slug,
            'icon' => $ventiForceApplication->icon,
            'meta' => [
                'MAX_CUSTOMERS' => 0
            ]
        ]);


        $response = ProcesstonIdentityConnector::initiateConnection()->addClient(
            $tenantApp->slug . '.' . env('PRIMARY_DOMAIN') . '/callback/identity-force',
            $tenantApp->tenant->identity_force_app_id
        );

        if ($response) {
            $tenantApp->__set('identity_force_app_secret', $response['data']['client_secret']);
            $tenantApp->__set('identity_force_app_key', $response['data']['client_id']);
            $tenantApp->__set('identity_force_app_url', $response['data']['client_url']);
            $tenantApp->save();
        }

        $deploymentAction1 = DeploymentActivity::create([
            'tenant_id' => $tenantApp->tenant_id,
            'application_id' => $tenantApp->application_id,
            'tenant_app_id' => $tenantApp->id,
            'provider' => $tenantApp->application->provider,
            'method' => 'create',
            'status' => 'pending',
        ]);


        $domain = Domain::createQuietly([
            'tenant_id' => $tenantApp->tenant_id,
            'application_id' => $tenantApp->application_id,
            'tenant_app_id' => $tenantApp->id,
            'domain' => 'processton-ventiforce.' . env('PRIMARY_DOMAIN'),
        ]);



        $deploymentAction2 = DeploymentActivity::create([
            'domain_id' => $domain->id,
            'provider' => ProxyDomainDeployer::class,
            'method' => 'create',
            'status' => 'pending',
        ]);

        $deploymentAction1->provider::deploy($deploymentAction1);
        $deploymentAction2->provider::deploy($deploymentAction2);


    }
}
