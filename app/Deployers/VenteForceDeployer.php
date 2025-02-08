<?php

namespace App\Deployers;

use App\Deployers\Deployer;
use App\Deployers\DeployerInterface;
use Illuminate\Support\Facades\Http;

class VenteForceDeployer extends Deployer implements DeployerInterface
{

    public function create()
    {

        $this->deploymentAction->update([
            'status' => 'in-progress'
        ]);

        $payload = [
            'name' => $this->deploymentAction->tenant_app->name,
            'slug' => $this->deploymentAction->tenant_app->slug,
            'domains' => $this->deploymentAction->tenant_app->domains->pluck('domain'),
            'admin_email' => $this->deploymentAction->tenant->admin_email,
            'admin_name' => $this->deploymentAction->tenant->admin_name,
            'identity_force_app_key' => $this->deploymentAction->tenant_app->identity_force_app_key,
            'identity_force_app_secret' => $this->deploymentAction->tenant_app->identity_force_app_secret,
            'identity_force_app_url' => $this->deploymentAction->tenant_app->identity_force_app_url
        ];

        $url = 'http://localhost:' . $this->deploymentAction->application->port . '/register-tenant';

        $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'integration-key' => env('PROCESSTON_INTEGRATION_KEY')
            ])
            ->timeout(0)
            ->post($url, $payload);


        if($response->created()){


            $this->deploymentAction->update([
                'status' => 'completed',
                'message' => 'Tenant created successfully',
                'output' => [
                    'tenant' => $response->json(),
                    ... $payload
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

    public static function links()
    {
        return [
            [
                'name' => 'customers',
                'icon' => '',
                'slug' => 'customers',
                'path' => 'customers/list'
            ]
        ];
    }
}
