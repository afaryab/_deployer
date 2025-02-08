<?php

namespace App\Deployers;

use App\Deployers\Deployer;
use App\Deployers\DeployerInterface;
use Illuminate\Support\Facades\Http;

class IdentityForceDeployer extends Deployer implements DeployerInterface
{

    public function create()
    {

        $this->deploymentAction->update([
            'status' => 'in-progress'
        ]);

        $payload = [
            'name' => $this->deploymentAction->tenant_app->name,
            'slug' => $this->deploymentAction->tenant_app->slug,
            'domains' => $this->deploymentAction->tenant_app->domains->pluck('domain')->toArray(),
            'admin_email' => $this->deploymentAction->tenant->admin_email,
            'admin_name' => $this->deploymentAction->tenant->admin_name,
        ];

        $url = 'http://localhost:'.$this->deploymentAction->application->port. '/register-tenant';


        $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'integration-key' => env('PROCESSTON_INTEGRATION_KEY')
            ])
            ->post($url, $payload);

        if($response->created()){

            $this->deploymentAction->update([
                'status' => 'completed',
                'message' => 'Tenant created successfully',
                'output' => [
                    'tenant' => $response->json(),
                    ...$payload
                ]
            ]);

            return true;

        }else{
            $this->deploymentAction->update([
                'status' => 'failed',
                'message' => 'Failed to create tenant',
                'output' => [
                    'tenant' => $response->json()
                ]
            ]);
        }

        return false;

    }

    public function update()
    {
        dd('Updating IdentityForce');
    }

    public function delete()
    {
        dd('Deleting IdentityForce');
    }

    public static function links(){
        return [
            [
                'name' => 'Dashboard',
                'icon' => 'dashboard',
                'slug' => 'dashboard',
                'path' => 'admin/dashboard'
            ],
            [
                'name' => 'Users',
                'icon' => 'groups',
                'slug' => 'users',
                'path' => 'admin/users'
            ],
            [
                'name' => 'Teams',
                'icon' => 'diversity_2',
                'slug' => 'teams',
                'path' => 'admin/teams'
            ],
            [
                'name' => 'Connected Apps',
                'icon' => 'hub',
                'slug' => 'connected_apps',
                'path' => 'admin/connected-apps'
            ],
            [
                'name' => 'Configurations',
                'icon' => 'settings',
                'slug' => 'configurations',
                'path' => 'admin/configurations'
            ]
        ];
    }
}
