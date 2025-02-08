<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantAppResource\Pages;
use App\Models\TenantApp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Tenant;
use App\Models\Application;

class TenantAppResource extends Resource
{
    protected static ?string $model = TenantApp::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationLabel = 'Sites';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('tenant_id')
                        ->label('Tenant')
                        ->options(Tenant::all()->pluck('name', 'id'))
                        ->searchable(),
                    Forms\Components\Select::make('application_id')
                        ->label('Application')
                        ->options(Application::all()->pluck('name', 'id'))
                        ->searchable()
                ]),
                Forms\Components\Section::make('Overwrite the default settings')
                    ->description('When user is looking for multiple versions on specific tenant app.')
                    ->schema([
                        Forms\Components\TextInput::make('name'),
                        Forms\Components\TextInput::make('slug'),
                        Forms\Components\TextInput::make('icon'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn ($record): string => $record->slug.'.processton-client.com'),
                // Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('tenant.name'),
                Tables\Columns\TextColumn::make('application.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenantApps::route('/'),
            'create' => Pages\CreateTenantApp::route('/create'),
            'view' => Pages\ViewTenantApp::route('/{record}'),
            'edit' => Pages\EditTenantApp::route('/{record}/edit'),
        ];
    }
}
