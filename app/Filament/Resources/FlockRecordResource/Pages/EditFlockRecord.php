<?php

namespace App\Filament\Resources\FlockRecordResource\Pages;

use App\Filament\Resources\FlockRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFlockRecord extends EditRecord
{
    protected static string $resource = FlockRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
