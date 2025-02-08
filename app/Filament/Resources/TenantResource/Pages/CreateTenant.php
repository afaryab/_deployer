<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Tenant;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['slug'] = Str::slug($data['name']);

        if(Tenant::where('slug', $data['slug'])->exists()){
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        }

        return $data;
    }
}
