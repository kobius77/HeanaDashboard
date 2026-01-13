<?php

namespace App\Filament\Widgets;

use App\Models\DailyLog;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DailyProductionChart extends ChartWidget
{
    protected static ?string $heading = 'Daily Egg Production (Last 30 Days)';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = DailyLog::where('log_date', '>=', now()->subDays(30))
            ->orderBy('log_date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Eggs Laid',
                    'data' => $data->map(fn ($log) => $log->egg_count),
                ],
            ],
            'labels' => $data->map(fn ($log) => Carbon::parse($log->log_date)->format('M d')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
