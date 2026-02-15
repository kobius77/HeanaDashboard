<?php

namespace App\Filament\Widgets;

use App\Models\FinancialTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $transactions = FinancialTransaction::all();

        $topups = $transactions->where('amount', '>', 0);
        $totalExpenses = abs($transactions->where('amount', '<', 0)->sum('amount'));
        $balance = $topups->sum('amount') - $totalExpenses;

        $topupsAJ = $topups->where('name', 'A&J')->sum('amount');
        $topupsMM = $topups->where('name', 'M&M')->sum('amount');

        return [
            Stat::make('Aktueller Kontostand', number_format($balance, 2).' €')
                ->color($balance >= 0 ? 'success' : 'danger'),

            Stat::make('Gesamtausgaben', number_format($totalExpenses, 2).' €')
                ->color('danger'),

            Stat::make('Einlagen A&J', number_format($topupsAJ, 2).' €')
                ->color('success'),

            Stat::make('Einlagen M&M', number_format($topupsMM, 2).' €')
                ->color('success'),
        ];
    }
}
