<?php

namespace App\Filament\Widgets;

use App\Models\DailyLog;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class EggProductionHeatmap extends Widget
{
    protected static string $view = 'filament.widgets.egg-production-heatmap';
    protected int | string | array $columnSpan = 'full';

    public function getChartData(): array
    {
        $data = DailyLog::where('log_date', '>=', now()->subYear())
            ->orderBy('log_date')
            ->get(['log_date', 'egg_count']);

        // Format for Cal-Heatmap: { date: "YYYY-MM-DD", value: 123 }
        return $data->map(function ($log) {
            return [
                'date' => Carbon::parse($log->log_date)->format('Y-m-d'),
                'value' => $log->egg_count,
            ];
        })->toArray();
    }
}
