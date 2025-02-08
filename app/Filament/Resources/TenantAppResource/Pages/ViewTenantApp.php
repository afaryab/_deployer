<?php

namespace App\Filament\Resources\TenantAppResource\Pages;

use App\Filament\Resources\TenantAppResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTenantApp extends ViewRecord
{
    protected static string $resource = TenantAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
