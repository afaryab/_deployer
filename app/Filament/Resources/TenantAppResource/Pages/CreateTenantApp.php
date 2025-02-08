<?php

namespace App\Filament\Resources\TenantAppResource\Pages;

use App\Filament\Resources\TenantAppResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Tenant;
use App\Models\Application;

class CreateTenantApp extends CreateRecord
{
    protected static string $resource = TenantAppResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $tenant = Tenant::find($data['tenant_id']);
        $application = Application::find($data['application_id']);


        if(!$data['name']){
            $data['name'] = $tenant->name . ' - ' . $application->name;
        }

        if(!$data['slug']){
            $data['slug'] = $tenant->slug . '-' . $application->slug;
        }else if(!str_starts_with($data['slug'], $tenant->slug)){
            $data['slug'] = $tenant->slug . '-' . $data['slug'];
        }

        if(!$data['icon']){
            $data['icon'] = $application->icon;
        }

        return $data;
    }
}
