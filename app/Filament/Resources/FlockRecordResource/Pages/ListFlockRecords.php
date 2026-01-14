<?php

namespace App\Filament\Resources\FlockRecordResource\Pages;

use App\Filament\Resources\FlockRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFlockRecords extends ListRecords
{
    protected static string $resource = FlockRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
