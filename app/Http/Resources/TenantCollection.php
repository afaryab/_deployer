<?php

namespace App\Http\Resources;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TenantCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function (Tenant $tenant) {
            $default = [];
            if($tenant->slug === "processton"){
                $default[] = [
                    "name" => "Deployer",
                    "slug" => "deployer",
                    "icon" => "deployed_code",
                    "domains" => [
                        [
                            "domain" => "deployer.docker.processton.com"
                        ]
                    ],
                    "links" => [
                        [
                            "name" => "Dashboard",
                            "icon" => "dashboard",
                            "slug" => "dashboard",
                            "path" => "setup"
                        ],
                        [
                            "name" => "Deployments",
                            "icon" => "deployed_code_history",
                            "slug" => "deployment-activities",
                            "path" => "setup/deployment-activities"
                        ],
                        [
                            "name" => "Sites",
                            "icon" => "flag",
                            "slug" => "sites",
                            "path" => "setup/tenant-apps"
                        ],
                        [
                            "name" => "Tenants",
                            "icon" => "tenancy",
                            "slug" => "tenants",
                            "path" => "setup/tenants"
                        ],
                        [
                            "name" => "Domains",
                            "icon" => "router",
                            "slug" => "domains",
                            "path" => "setup/domains"
                        ],
                        [
                            "name" => "Apps",
                            "icon" => "view_apps",
                            "slug" => "apps",
                            "path" => "setup/applications"
                        ]
                    ]
                ];
            }

            $parsedApps = new TenantAppsCollection($tenant->apps);

            return [
                "name" => $tenant->name,
                "slug" => $tenant->slug,
                "logo" => $tenant->logo,
                "applications" => $parsedApps,
                "defaultapps" => $default
            ];
        })->toArray();
    }
}
