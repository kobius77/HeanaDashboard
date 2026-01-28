<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlockRecordResource\Pages;
use App\Models\FlockRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FlockRecordResource extends Resource
{
    protected static ?string $model = FlockRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\DatePicker::make('record_date')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(2),

                    ]),
                Forms\Components\TextInput::make('ovulating_hens')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('henopaused_hens')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('cock')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Cocks'),
                Forms\Components\TextInput::make('chicklets')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('record_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ovulating_hens')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('henopaused_hens')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cock')
                    ->numeric()
                    ->sortable()
                    ->label('Cocks'),
                Tables\Columns\TextColumn::make('chicklets')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->defaultSort('record_date', 'desc')
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
            'index' => Pages\ListFlockRecords::route('/'),
            'create' => Pages\CreateFlockRecord::route('/create'),
            'edit' => Pages\EditFlockRecord::route('/{record}/edit'),
        ];
    }
}
