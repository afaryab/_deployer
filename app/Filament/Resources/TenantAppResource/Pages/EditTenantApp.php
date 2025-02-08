<?php

namespace App\Filament\Resources\TenantAppResource\Pages;

use App\Filament\Resources\TenantAppResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenantApp extends EditRecord
{
    protected static string $resource = TenantAppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
