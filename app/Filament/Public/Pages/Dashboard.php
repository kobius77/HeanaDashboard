<?php

namespace App\Filament\Public\Pages;

use App\Filament\Widgets\DailyProductionChart;
use App\Filament\Widgets\EggProductionHeatmap;
use App\Filament\Widgets\EggStatsOverview;
use App\Filament\Widgets\MonthlyComparisonChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\MaxWidth;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $routePath = '/';

    protected static string $view = 'filament.public.pages.dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            EggStatsOverview::class,
            DailyProductionChart::class,
            MonthlyComparisonChart::class,
            EggProductionHeatmap::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::FiveExtraLarge;
    }
}
