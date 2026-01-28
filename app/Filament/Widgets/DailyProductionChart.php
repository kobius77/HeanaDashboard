<?php

namespace App\Filament\Widgets;

use App\Models\DailyLog;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class DailyProductionChart extends ChartWidget
{
    public ?string $filter = '30';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    public function getHeading(): ?string
    {
        return __('Daily Egg Production');
    }

    protected function getFilters(): ?array
    {
        return [
            '30' => __('Last 30 days'),
            '60' => __('Last 60 days'),
            '90' => __('Last 90 days'),
        ];
    }

    protected function getData(): array
    {
        $days = (int) $this->filter;

        $data = DailyLog::where('log_date', '>=', now()->subDays($days))
            ->orderBy('log_date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Eggs Laid'),
                    'data' => $data->map(fn ($log) => $log->egg_count),
                    'backgroundColor' => '#FFAE42',
                    'borderColor' => '#FFAE42', // Set borderColor to match backgroundColor for a solid fill
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('Average'),
                    'data' => array_fill(0, $data->count(), round($data->avg('egg_count'), 2)),
                    'borderColor' => '#FF0000', // Red color for average line
                    'backgroundColor' => '#FF0000',
                    'type' => 'line',
                    'borderWidth' => 1.5, // Thinner line
                    'fill' => false,
                    'pointRadius' => 0,
                    'borderDash' => [10, 5], // Longer dashes
                ],
            ],
            'labels' => $data->map(fn ($log) => Carbon::parse($log->log_date)->format('M d')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
            {
                scales: {
                    y: {
                        ticks: {
                            stepSize: 1,
                        },
                        min: 0,
                    },
                },
            }
        JS);
    }
}
