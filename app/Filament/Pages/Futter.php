<?php

namespace App\Filament\Pages;

use App\Services\GoogleCalendarService;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Futter extends Page
{
    protected static ?string $title = 'Futter';

    protected static ?string $slug = 'futter';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.futter';

    public Collection $deliveries;

    public int $interval;

    public function mount(): void
    {
        $this->loadDeliveries();
    }

    protected function loadDeliveries(): void
    {
        $service = new GoogleCalendarService;
        $this->deliveries = new Collection($service->getUpcomingFeedDeliveries());
        $this->interval = $service->calculateInterval();
    }

    protected function getActions(): array
    {
        return [
            Action::make('reload')
                ->label('Kalender neu laden')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    GoogleCalendarService::clearCache();
                    $this->loadDeliveries();
                }),
        ];
    }
}
