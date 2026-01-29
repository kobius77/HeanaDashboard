<?php

namespace Database\Seeders;

use App\Models\WidgetSetting;
use Illuminate\Database\Seeder;

class WidgetSettingsSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        $widgets = [
            'App\Filament\Widgets\EggStatsOverview',
            'App\Filament\Widgets\DailyProductionChart',
            'App\Filament\Widgets\MonthlyComparisonChart',
        ];

        foreach ($widgets as $widgetClass) {
            WidgetSetting::firstOrCreate(
                ['widget_class' => $widgetClass],
                ['is_published' => true]
            );
        }
    }
}
