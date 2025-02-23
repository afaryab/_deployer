<?php

namespace Database\Seeders;

use App\Deployers\ApplicationDeployer;
use App\Deployers\DomainDeployer;
use App\Deployers\VenteForceDeployer;
use App\Models\Application;
use App\Models\DeploymentActivity;
use Illuminate\Database\Seeder;

class VentiForceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application = Application::createQuietly([
            'name' => 'Venti Force',
            'slug' => 'venti_force',
            'port' => 82,
            'domain' => 'venti-force.' . env('PRIMARY_DOMAIN'),
            'download_path' => '',
            'folder_path' => '/var/www/vente',
            'public_path' => 'public',
            'description' => 'A centeralised CRM system',
            'icon' => 'fingerprint',
            'logo' => null,
            'provider' => VenteForceDeployer::class,
            'meta' => [
                'MAX_CUSTOMERS' => 999
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
