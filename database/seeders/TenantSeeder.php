<?php

namespace Database\Seeders;

use App\Connectors\ProcesstonIdentity\ProcesstonIdentityConnector;
use App\Deployers\DomainDeployer;
use App\Deployers\IdentityForceDeployer;
use App\Deployers\ProxyDomainDeployer;
use App\Deployers\VenteForceDeployer;
use App\Models\Application;
use App\Models\Country;
use App\Models\Currency;
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

        $currency = Currency::where('code', 'PKR')->first();
        $country = Country::where('code', 'PK')->first();

        $tenant = Tenant::createQuietly([
            'name' => 'Processton',
            'slug' => 'processton',
            'logo' => '',
            'admin_email' => 'ahmadkokab@processton.com',
            'admin_name' => 'Ahmad Faryab Kokab',
            'identity_force_team_id' => 1,
            'identity_force_app_id' => 1,
            'currency_id' => $currency->id,
            'country_id' => $country->id
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

        $response = ProcesstonIdentityConnector::initiateConnection()->addTeam($tenant->name, $tenant->admin_name, $tenant->admin_email);

        if ($response) {

            $tenant->__set('identity_force_team_id', $response['team']['id']);
            $tenant->__set('identity_force_app_id', $response['app']['id']);
            $tenant->save();
        }

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
            'processton-ventiforce.' . env('PRIMARY_DOMAIN') . '/oauth/callback',
            $tenantApp->tenant->identity_force_app_id
        );

        if ($response) {
            $tenantApp->__set('identity_force_app_secret', $response['client_secret']);
            $tenantApp->__set('identity_force_app_key', $response['client_id']);
            $tenantApp->__set('identity_force_app_url', $response['client_url']);
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
