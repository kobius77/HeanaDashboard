<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DailyProductionChart;
use App\Filament\Widgets\EggProductionHeatmap;
use App\Filament\Widgets\EggStatsOverview;
use App\Filament\Widgets\FlockStatsOverview;
use App\Filament\Widgets\MonthlyComparisonChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {

        return [

            FlockStatsOverview::class,

            EggStatsOverview::class,

            DailyProductionChart::class,

            MonthlyComparisonChart::class,

            EggProductionHeatmap::class,

        ];

    }
}
