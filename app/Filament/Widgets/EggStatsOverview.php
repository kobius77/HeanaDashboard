<?php

namespace App\Filament\Widgets;

use App\Models\DailyLog;
use App\Models\FlockRecord;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class EggStatsOverview extends BaseWidget
{
    protected static ?int $sort = -1;

    protected int|string|array $columns = [
        'default' => 2,
        'md' => 4,
    ];

    protected function getStats(): array
    {
        $latestFlockRecord = FlockRecord::latest('record_date')->first();
        $flockSize = $latestFlockRecord?->ovulating_hens ?? 1;

        $todayCount = DailyLog::whereDate('log_date', Carbon::today())->value('egg_count');
        $sevenDayAverage = DailyLog::where('log_date', '>=', Carbon::today()->subDays(7))
            ->avg('egg_count');

        $efficiency = $flockSize > 0 ? ($sevenDayAverage / $flockSize) * 100 : 0;

        $stats = [
            Stat::make('Laying Hens', $flockSize)
                ->description('Currently active')
                ->color('success'),
        ];

        if ($todayCount === null) {
            $stats[] = Stat::make(
                'Today\'s Eggs',
                new HtmlString('<div style="font-size: 1.5rem; font-weight: 500;"><span class="animated-eggs"><span>🥚</span><span>🥚</span><span>🥚</span></span></div>')
            );
        } else {
            $yesterdayCount = DailyLog::whereDate('log_date', Carbon::yesterday())->value('egg_count') ?? 0;
            $comparison = $todayCount - $yesterdayCount;
            $comparisonColor = $comparison >= 0 ? 'success' : 'danger';

            $stats[] = Stat::make('Today\'s Eggs', $todayCount)
                ->description(sprintf('%+d vs yesterday', $comparison))
                ->descriptionIcon('heroicon-m-arrow-trending-'.($comparison >= 0 ? 'up' : 'down'))
                ->color($comparisonColor);
        }

        $stats[] = Stat::make('7-Day Average', number_format($sevenDayAverage, 2))
            ->description('Average eggs per day over the last week');
        $stats[] = Stat::make('Flock Efficiency', number_format($efficiency, 1).'%')
            ->description('Based on '.$flockSize.' laying hens');

        return $stats;
    }
}
