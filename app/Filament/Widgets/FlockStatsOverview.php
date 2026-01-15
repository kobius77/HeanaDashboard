<?php

namespace App\Filament\Widgets;

use App\Models\FlockRecord;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FlockStatsOverview extends BaseWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $latestFlockRecord = FlockRecord::latest('record_date')->first();

        $ovulatingHens = $latestFlockRecord?->ovulating_hens ?? 0;

        return [
            Stat::make('Laying Hens', $ovulatingHens)
                ->description('Currently active')
                ->color('success'),
        ];
    }
}
