<?php

namespace App\Filament\Resources\TenantAppResource\Pages;

use App\Filament\Resources\TenantAppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenantApps extends ListRecords
{
    protected static string $resource = TenantAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
