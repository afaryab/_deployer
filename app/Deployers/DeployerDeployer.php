<?php

namespace App\Deployers;

use App\Deployers\Deployer;
use App\Deployers\DeployerInterface;
use Illuminate\Support\Facades\Http;

class DeployerDeployer extends Deployer implements DeployerInterface
{

    public function create()
    {

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
