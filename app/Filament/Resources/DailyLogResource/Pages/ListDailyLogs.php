<?php

namespace App\Filament\Resources\DailyLogResource\Pages;

use App\Filament\Resources\DailyLogResource;
use App\Models\DailyLog;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ListDailyLogs extends ListRecords
{
    protected static string $resource = DailyLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        $months = DailyLog::query()
            ->selectYearMonth()
            ->distinct()
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        foreach ($months as $data) {
            $date = Carbon::create($data->year, $data->month, 1);
            $label = $date->format('M Y');
            $key = $date->format('Y-m');

            $tabs[$key] = Tab::make($label)
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereYear('log_date', $data->year)
                    ->whereMonth('log_date', $data->month)
                );
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return DailyLog::query()
            ->orderByDesc('log_date')
            ->first()
            ?->log_date
            ?->format('Y-m');
    }
}
