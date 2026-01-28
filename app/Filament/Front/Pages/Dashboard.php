<?php

namespace App\Filament\Front\Pages;

use App\Models\WidgetSetting;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.front.pages.dashboard';

    public function getTitle(): string
    {
        return '';
    }

    public function getWidgets(): array
    {
        return WidgetSetting::where('is_published', true)
            ->pluck('widget_class')
            ->all();
    }
}
