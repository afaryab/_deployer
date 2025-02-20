<?php

namespace App\Deployers;

use App\Deployers\Deployer;
use App\Deployers\DeployerInterface;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class VenteForceDeployer extends Deployer implements DeployerInterface
{

    public function create()
    {

        $this->deploymentAction->update([
            'status' => 'in-progress'
        ]);

        $payload = [
            'name' => $this->deploymentAction->tenant_app->name,
            'id' => $this->makeSafeTableName($this->deploymentAction->tenant_app->slug),
            'domain' => $this->deploymentAction->tenant_app->domains->pluck('domain')->first(),
            'domains' => $this->deploymentAction->tenant_app->domains->pluck('domain')->toArray(),
            'admin_email' => $this->deploymentAction->tenant->admin_email,
            'admin_name' => $this->deploymentAction->tenant->admin_name,
            'tenancy_db_name' => $this->makeSafeTableName($this->deploymentAction->tenant_app->slug),
            'tenancy_db_username' => $this->makeSafeTableName($this->deploymentAction->tenant_app->slug),
            'tenancy_db_password' => $this->makeSafeTableName($this->deploymentAction->tenant_app->slug),
            'currency' => $this->deploymentAction->tenant->currency->code,
            'country' => $this->deploymentAction->tenant->country->code,
            ...$this->deploymentAction->tenant_app->meta
        ];

        $url = 'http://localhost:' . $this->deploymentAction->application->port . '/tenant/register';

        $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Integration-Key' => env('PROCESSTON_INTEGRATION_KEY')
            ])
            ->timeout(0)
            ->post($url, $payload);

        if($response->created()){

            $responseJson = $response->json();

            $process = new Process(['php', 'artisan', "tenants:migrate", "--tenants=".$responseJson['id']], $this->deploymentAction->application->folder_path);
            $process->setTimeout(600);
            $process->run();

            $process = new Process(['php', 'artisan', "tenants:seed", "--tenants=".$responseJson['id']], $this->deploymentAction->application->folder_path);
            $process->setTimeout(600);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

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
                'name' => 'dashboard',
                'path' => 'admin/dashboard',
                'slug' => 'dashboard',
                'icon' => 'dashboard',
            ],
            [
                'name' => 'customers',
                'path' => 'admin/customers',
                'slug' => 'customers',
                'icon' => 'face',
            ],
            [
                'name' => 'items',
                'path' => 'admin/items',
                'slug' => 'items',
                'icon' => 'shelves',
            ],
            [
                'name' => 'estimates',
                'path' => 'admin/estimates',
                'slug' => 'estimates',
                'icon' => 'request_quote',
            ],
            [
                'name' => 'invoices',
                'path' => 'admin/invoices',
                'slug' => 'invoices',
                'icon' => 'receipt_long',
            ],
            [
                'name' => 'recurring-invoices',
                'path' => 'admin/recurring-invoices',
                'slug' => 'admin-invoices',
                'icon' => 'cycle',
            ],
            [
                'name' => 'payments',
                'path' => 'admin/payments',
                'slug' => 'payments',
                'icon' => 'payments',
            ],
            [
                'name' => 'expenses',
                'path' => 'admin/expenses',
                'slug' => 'expenses',
                'icon' => 'attach_money',
            ],
            [
                'name' => 'users',
                'path' => 'admin/users',
                'slug' => 'users',
                'icon' => 'group',
            ],
            [
                'name' => 'reports',
                'path' => 'admin/reports',
                'slug' => 'reports',
                'icon' => 'lab_profile',
            ],
            [
                'name' => 'settings',
                'path' => 'admin/settings',
                'slug' => 'settings',
                'icon' => 'settings',
            ]
        ];
    }
}
