<?php

namespace App\Http\Resources;

use App\Models\TenantApp;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TenantAppsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function (TenantApp $tenantApp) {
            $provider = $tenantApp->application->provider;

            return [
                "name" => $tenantApp->name,
                "slug" => $tenantApp->slug,
                "icon" => $tenantApp->logo ? $tenantApp->logo : "question_mark",
                "provider_key" => $tenantApp->identity_force_app_key,
                "provider_url" => $tenantApp->identity_force_app_url,
                "domains" => new TenantAppsDomainCollection($tenantApp->domains),
                "links" => $provider::links()
            ];

        })->toArray();
    }
}
