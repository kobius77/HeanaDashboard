<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyLogResource\Pages;
use App\Models\DailyLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DailyLogResource extends Resource
{
    protected static ?string $model = DailyLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('Daily Log');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Daily Logs');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('log_date')
                    ->label(__('Log Date'))
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('egg_count')
                    ->label(__('Egg Count'))
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('weather_temp_c')
                    ->numeric()
                    ->label(__('Temperature (°C)'))
                    ->nullable(),
                Forms\Components\TextInput::make('sun_hours')
                    ->numeric()
                    ->label(__('Sun Hours'))
                    ->nullable(),
                Forms\Components\Select::make('reported_by')
                    ->label(__('Reported By'))
                    ->relationship('reportedBy', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('notes')
                    ->label(__('Notes'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_date')
                    ->label(__('Log Date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg_count')
                    ->label(__('Egg Count'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weather_temp_c')
                    ->numeric()
                    ->label(__('Temp (°C)'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sun_hours')
                    ->numeric()
                    ->label(__('Sun (h)'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('reportedBy.name')
                    ->label(__('Reported By'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('Notes'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false)
            ->defaultSort('log_date', 'desc')
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListDailyLogs::route('/'),
            'create' => Pages\CreateDailyLog::route('/create'),
            'edit' => Pages\EditDailyLog::route('/{record}/edit'),
        ];
    }
}
