<?php

namespace Database\Seeders;

use App\Deployers\ApplicationDeployer;
use App\Deployers\DomainDeployer;
use App\Deployers\IdentityForceDeployer;
use App\Models\Application;
use App\Models\DeploymentActivity;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application = Application::createQuietly([
            'name' => 'Identity Force',
            'slug' => 'identity-force',
            'description' => 'This is authentication provider available for tenants',
            'port' => 81,
            'domain' => 'identity-force.' . env('PRIMARY_DOMAIN'),
            'download_path' => '',
            'folder_path' => '/var/www/identity',
            'public_path' => 'public',
            'icon' => 'fingerprint',
            'logo' => null,
            'provider' => IdentityForceDeployer::class,
            'meta' => [
                "MAX_USERS" => 99,
                "MAX_TEAMS" => 33,
                "ADMIN_TEAM_NAME" => 'Processton',
                "ADMIN_IDENTIFIED_BY" => 'Team'
            ]
        ]);

        $deploymentAction = DeploymentActivity::createQuietly([
            'application_id' => $application->id,
            'provider' => ApplicationDeployer::class,
            'method' => 'create',
            'status' => 'pending',
        ]);

        $deploymentAction->provider::deploy($deploymentAction);

        $deploymentAction = DeploymentActivity::createQuietly([
            'application_id' => $application->id,
            'provider' => DomainDeployer::class,
            'method' => 'create',
            'status' => 'pending',
        ]);

        $deploymentAction->provider::deploy($deploymentAction);
    }
}
