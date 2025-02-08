<?php

namespace App\Filament\Resources\DeploymentActivityResource\Pages;

use App\Filament\Resources\DeploymentActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDeploymentActivity extends ViewRecord
{
    protected static string $resource = DeploymentActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
