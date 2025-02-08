<?php

namespace App\Deployers;

use App\Deployers\Deployer;
use App\Deployers\DeployerInterface;
use Illuminate\Support\Facades\Http;

class ProxyDomainDeployer extends Deployer implements DeployerInterface
{

    public function create()
    {

        $this->deploymentAction->update([
            'status' => 'in-progress'
        ]);

        $application = $this->deploymentAction->domain->application;
        $domain = $this->deploymentAction->domain;
        $tenant = $domain->tenant;

        $output = $this->parkProxyDomain(
            $domain->domain,
            $tenant->slug,
            $application->folder_path,
            $application->public_path
        );

        $this->deploymentAction->update([
            'status' => 'completed',
            'message' => 'Application is ready',
            'output' => $output
        ]);

        return true;

    }

    public function update()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }

    public static function links(){
        return [

        ];
    }
}
