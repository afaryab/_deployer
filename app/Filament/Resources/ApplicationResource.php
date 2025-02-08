<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Deployers\IdentityForceDeployer;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Support\Enums\FontWeight;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Settings';

    public static function canCreate(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('provider')->options([
                        IdentityForceDeployer::toString() => 'Identity Force',
                    ])->required(),
                    Forms\Components\FileUpload::make('icon')
                        ->extraInputAttributes(['height' => 500])->required(),
                    Forms\Components\TextInput::make('name')->columnSpanFull()->required(),
                    Forms\Components\TextInput::make('slug')->required()
                    ->hint('Slug must be unique for every application.'),
                    Forms\Components\KeyValue::make('meta')
                        ->columnSpanFull(),
                    // Forms\Components\KeyValue::make('meta')
                    //     ->simple(
                    //         Forms\Components\TextInput::make('key')->required()
                    //     )->columnSpanFull()
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('icon')
                        ->circular()->grow(false),
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold)
                        ->searchable()
                        ->description(fn($record): string => $record->provider)
                        ->sortable(),
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('tenant_apps_count')->label('Deployments')->counts('tenant_apps')
                            ->icon('heroicon-m-flag')->grow(false),
                        Tables\Columns\TextColumn::make('deployment_activities_count')->label('Deployments')->counts('deployment_activities')
                            ->icon('heroicon-m-bolt')->grow(false)

                    ])->visibleFrom('md')
                ]),

            ])
            ->filters([
                //
            ])->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()->schema([
                    Infolists\Components\Split::make([
                        Infolists\Components\ImageEntry::make('icon')
                            ->height(80)
                            ->square()->label('Logo')->grow(false),
                        Infolists\Components\Fieldset::make('Basic Info')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')->label('Name'),
                                Infolists\Components\TextEntry::make('provider')->label('Provider')
                            ]),
                    ])->from('md'),
                    Infolists\Components\Split::make([
                        Infolists\Components\Fieldset::make('')
                            ->schema([
                                Infolists\Components\TextEntry::make('description')->label('Description')->columnSpanFull(),
                                Infolists\Components\TextEntry::make('credits')->label('Credits')->columnSpanFull()->placeholder('Contribution notes to the provider'),
                            ]),
                        Infolists\Components\Fieldset::make('')
                            ->schema([
                                Infolists\Components\KeyValueEntry::make('meta')->label(false)->columnSpanFull()
                            ]),
                    ])->from('md'),
                    Infolists\Components\RepeatableEntry::make('tenant_apps')
                        ->schema([
                            Infolists\Components\TextEntry::make('tenant.name'),
                            Infolists\Components\TextEntry::make('name'),
                            Infolists\Components\TextEntry::make('slug'),
                            Infolists\Components\RepeatableEntry::make('domains')
                            ->schema([
                                Infolists\Components\TextEntry::make('domain')->label(false),
                            ])
                            ->columns(1)->columnSpanFull(),
                        ])
                        ->columns(3)

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
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'view' => Pages\ViewApplication::route('/{record}'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
