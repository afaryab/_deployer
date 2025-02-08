<?php

namespace App\Filament\Resources;

use AhmedAbdelaal\FilamentJsonPreview\JsonPreview;
use App\Filament\Resources\DeploymentActivityResource\Pages;
use App\Models\DeploymentActivity;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DeploymentActivityResource extends Resource
{
    protected static ?string $model = DeploymentActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Deployments';

    protected static ?int $navigationSort = 0;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('tenant_app.name'),
                Tables\Columns\TextColumn::make('tenant.name'),
                Tables\Columns\TextColumn::make('application.name'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make([
                        JsonPreview::make('output')
                    ]),
                    Infolists\Components\Section::make([
                        Infolists\Components\IconEntry::make('status')
                            ->icon(fn(string $state): string => match ($state) {
                                'pending' => 'heroicon-o-clock',
                                'failed' => 'heroicon-o-exclamation-circle',
                                'completed' => 'heroicon-o-check-circle',
                                'in-progress' => 'heroicon-o-x-circle',
                            }),
                        Infolists\Components\TextEntry::make('message'),
                        Infolists\Components\TextEntry::make('status'),
                        Infolists\Components\TextEntry::make('application.name'),
                        Infolists\Components\TextEntry::make('tenant.name'),
                    ])->grow(false),
                ])->from('md')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeploymentActivities::route('/'),
            'create' => Pages\CreateDeploymentActivity::route('/create'),
            'view' => Pages\ViewDeploymentActivity::route('/{record}'),
            'edit' => Pages\EditDeploymentActivity::route('/{record}/edit'),
        ];
    }
}
