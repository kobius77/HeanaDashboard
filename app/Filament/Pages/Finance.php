<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FinanceOverview;
use App\Models\FinancialTransaction;
use Filament\Pages\Page;

class Finance extends Page
{
    protected static ?string $title = 'Konto';

    protected static ?string $slug = 'finanzen';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.resources.financial-transaction-resource.pages.finance';

    protected function getHeaderWidgets(): array
    {
        return [
            FinanceOverview::class,
        ];
    }

    public $transactions = [];

    public function mount(): void
    {
        $this->transactions = FinancialTransaction::orderBy('transaction_date', 'desc')->get();
    }
}
