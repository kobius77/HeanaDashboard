<?php

namespace App\Filament\Widgets;

use App\Models\DailyLog;
use App\Models\FlockRecord;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EggStatsOverview extends BaseWidget
{
    /**
     * Used for Tailwind discovery:
     * grid-cols-2 md:grid-cols-4
     */
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

        $stats = [];

        if ($todayCount === null) {
            $yesterdayCount = DailyLog::whereDate('log_date', Carbon::yesterday())->value('egg_count') ?? 0;
            $stats[] = Stat::make(__('Yesterday\'s Eggs'), $yesterdayCount)
                ->description(__('Still ovulating today..'));
        } else {
            $yesterdayCount = DailyLog::whereDate('log_date', Carbon::yesterday())->value('egg_count') ?? 0;
            $comparison = $todayCount - $yesterdayCount;
            $comparisonColor = $comparison >= 0 ? 'success' : 'danger';

            $stats[] = Stat::make(__('Today\'s Eggs'), $todayCount)
                ->description(sprintf('%+d %s', $comparison, __('vs yesterday')))
                ->descriptionIcon('heroicon-m-arrow-trending-'.($comparison >= 0 ? 'up' : 'down'))
                ->color($comparisonColor);
        }

        $stats[] = Stat::make(__('7-Day Average'), number_format($sevenDayAverage, 2))
            ->description(__('Eggs per day over the last week'));
        $stats[] = Stat::make(__('Flock Efficiency'), number_format($efficiency, 1).'%')
            ->description(__('Based on :count laying hens', ['count' => $flockSize]));
            
        // Flock Composition Stat with Animation
        $flockStats = [
            [
                'label' => __('Laying Hens'),
                'value' => $latestFlockRecord?->ovulating_hens ?? 0,
                'color' => 'text-gray-950 dark:text-white',
            ],
            [
                'label' => __('Henopaused'),
                'value' => $latestFlockRecord?->henopaused_hens ?? 0,
                'color' => 'text-gray-950 dark:text-white',
            ],
            [
                'label' => __('Roosters'),
                'value' => $latestFlockRecord?->cock ?? 0,
                'color' => 'text-gray-950 dark:text-white',
            ],
            [
                'label' => __('Chicks'),
                'value' => $latestFlockRecord?->chicklets ?? 0,
                'color' => 'text-gray-950 dark:text-white',
            ],
        ];

        // Filter out zero values unless it's laying hens (primary) or all are zero
        $flockStats = array_values(array_filter($flockStats, fn($stat) => $stat['value'] > 0 || $stat['label'] === __('Laying Hens')));

        $stats[] = Stat::make('Flock Composition', '')
            ->view('filament.widgets.flock-stat', ['stats' => $flockStats]);

        return $stats;
    }
}
