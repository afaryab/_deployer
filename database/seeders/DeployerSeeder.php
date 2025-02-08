<?php

namespace Database\Seeders;

use App\Deployers\ApplicationDeployer;
use App\Deployers\DeployerDeployer;
use App\Deployers\DomainDeployer;
use App\Models\Application;
use App\Models\DeploymentActivity;
use Illuminate\Database\Seeder;

class DeployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application = Application::createQuietly([
            'name' => 'Deployer',
            'slug' => 'deployer',
            'port' => 99,
            'domain' => 'deployer.docker.processton.com',
            'download_path' => '',
            'folder_path' => '/var/www/_deployer',
            'public_path' => 'public',
            'description' => 'Deployment management',
            'icon' => 'deployed_code',
            'logo' => null,
            'provider' => DeployerDeployer::class,
            'meta' => [

            ]
        ]);

        $deploymentAction = DeploymentActivity::createQuietly([
            'application_id' => $application->id,
            'provider' => DomainDeployer::class,
            'method' => 'create',
            'status' => 'pending',
        ]);

        $deploymentAction->provider::deploy($deploymentAction);


    }
}
