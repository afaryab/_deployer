<?php

namespace App\Filament\Resources\DeploymentActivityResource\Pages;

use App\Filament\Resources\DeploymentActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeploymentActivities extends ListRecords
{
    protected static string $resource = DeploymentActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
