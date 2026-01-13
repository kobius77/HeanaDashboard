// in app/Filament/Pages/Dashboard.php
use App\Filament\Widgets\EggStatsOverview;
use App\Filament\Widgets\DailyProductionChart;
use App\Filament\Widgets\MonthlyComparisonChart;
use App\Filament\Widgets\EggProductionHeatmap;

// ... inside the Dashboard class

public function getWidgets(): array
{
    return [
        EggStatsOverview::class,
        DailyProductionChart::class,
        MonthlyComparisonChart::class,
        EggProductionHeatmap::class,
    ];
}
