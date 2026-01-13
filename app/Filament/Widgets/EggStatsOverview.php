<?php

namespace App\Filament\Widgets;

use App\Models\DailyLog;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EggStatsOverview extends BaseWidget
{
    protected int|string|array $columns = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
    ];

    protected function getStats(): array
    {
        $flockSize = 10; // As per your description

        // Today vs Yesterday
        $todayCount = DailyLog::whereDate('log_date', Carbon::today())->value('egg_count') ?? 0;
        $yesterdayCount = DailyLog::whereDate('log_date', Carbon::yesterday())->value('egg_count') ?? 0;
        $comparison = $todayCount - $yesterdayCount;
        $comparisonDirection = $comparison >= 0 ? 'increase' : 'decrease';
        $comparisonColor = $comparison >= 0 ? 'success' : 'danger';

        // 7-Day Average
        $sevenDayAverage = DailyLog::where('log_date', '>=', Carbon::today()->subDays(7))
            ->avg('egg_count');

        // Efficiency
        $efficiency = $flockSize > 0 ? ($sevenDayAverage / $flockSize) * 100 : 0;

        return [
            Stat::make('Today\'s Eggs', $todayCount)
                ->description($comparison.' vs yesterday')
                ->descriptionIcon('heroicon-m-arrow-trending-'.($comparison >= 0 ? 'up' : 'down'))
                ->color($comparisonColor),
            Stat::make('7-Day Average', number_format($sevenDayAverage, 2))
                ->description('Average eggs per day over the last week'),
            Stat::make('Flock Efficiency', number_format($efficiency, 1).'%')
                ->description('Based on a 7-day average for '.$flockSize.' hens'),
        ];
    }
}
