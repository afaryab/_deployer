<?php

namespace App\Filament\Resources\DeploymentActivityResource\Pages;

use App\Filament\Resources\DeploymentActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeploymentActivity extends EditRecord
{
    protected static string $resource = DeploymentActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
