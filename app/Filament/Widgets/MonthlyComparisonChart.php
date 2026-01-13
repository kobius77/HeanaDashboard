<?php

namespace App\Filament\Widgets;

use App\Models\DailyLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlyComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Cumulative Performance: This Month vs. Last Month';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->subMonthNoOverflow()->endOfMonth();

        // Get data for the relevant periods
        $thisMonthData = DailyLog::where('log_date', '>=', $thisMonthStart)
            ->orderBy('log_date')
            ->pluck('egg_count', 'log_date');

        $lastMonthData = DailyLog::whereBetween('log_date', [$lastMonthStart, $lastMonthEnd])
            ->orderBy('log_date')
            ->pluck('egg_count', 'log_date');

        // Generate labels for the days of the month
        $daysInMonth = now()->daysInMonth;
        $labels = range(1, $daysInMonth);

        // Calculate cumulative sums
        $thisMonthCumulative = [];
        $lastMonthCumulative = [];
        $currentThisMonthSum = 0;
        $currentLastMonthSum = 0;

        foreach ($labels as $day) {
            // This month (only add data up to today)
            $thisMonthDate = $thisMonthStart->copy()->day($day);
            if ($thisMonthDate->isPast() || $thisMonthDate->isToday()) {
                $currentThisMonthSum += $thisMonthData->get($thisMonthDate->toDateString(), 0);
                $thisMonthCumulative[] = $currentThisMonthSum;
            }

            // Last month
            if($day <= $lastMonthStart->daysInMonth) {
                $lastMonthDate = $lastMonthStart->copy()->day($day);
                $currentLastMonthSum += $lastMonthData->get($lastMonthDate->toDateString(), 0);
                $lastMonthCumulative[] = $currentLastMonthSum;
            }
        }

        return [
            'datasets' => [
                ['label' => 'This Month', 'data' => $thisMonthCumulative, 'borderColor' => '#3b82f6'],
                ['label' => 'Last Month', 'data' => $lastMonthCumulative, 'borderColor' => '#6b7280'],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
