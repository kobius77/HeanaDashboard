<?php

namespace App\Filament\Resources\FlockRecordResource\Pages;

use App\Filament\Resources\FlockRecordResource;
use App\Models\FlockRecord; // Import FlockRecord model
use Carbon\Carbon; // Import Carbon for date handling
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification; // Import Notification for user feedback

class CreateFlockRecord extends CreateRecord
{
    protected static string $resource = FlockRecordResource::class;

    public function loadPreviousRecord(string $dateString): void
    {
        $selectedDate = Carbon::parse($dateString);

        // Find the last record before the selected date
        $lastRecord = FlockRecord::where('record_date', '<', $selectedDate)
                                ->latest('record_date')
                                ->first();

        if ($lastRecord) {
            $this->data['ovulating_hens'] = $lastRecord->ovulating_hens;
            $this->data['henopaused_hens'] = $lastRecord->henopaused_hens;
            $this->data['cock'] = $lastRecord->cock;
            $this->data['chicklets'] = $lastRecord->chicklets;

            Notification::make()
                ->title('Previous record loaded')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('No previous record found before selected date.')
                ->warning()
                ->send();

            // Clear fields if no previous record found
            $this->data['ovulating_hens'] = 0;
            $this->data['henopaused_hens'] = 0;
            $this->data['cock'] = 0;
            $this->data['chicklets'] = 0;
        }
    }
}
