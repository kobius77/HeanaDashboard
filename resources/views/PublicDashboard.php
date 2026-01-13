<?php

namespace App\Http\Livewire;

use App\Filament\Widgets\DailyProductionChart;
use App\Filament\Widgets\EggProductionHeatmap;
use App\Filament\Widgets\EggStatsOverview;
use App\Filament\Widgets\MonthlyComparisonChart;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class PublicDashboard extends Component implements HasForms
{
    use InteractsWithForms;

    public function getHeaderWidgets(): array
    {
        return [
            EggStatsOverview::class,
            DailyProductionChart::class,
            MonthlyComparisonChart::class,
            EggProductionHeatmap::class,
        ];
    }

    public function render()
    {
        return view('livewire.public-dashboard')->layout('layouts.public');
    }
}