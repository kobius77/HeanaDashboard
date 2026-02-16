<?php

namespace App\Filament\Pages;

use App\Models\FinancialTransaction;
use Filament\Pages\Page;

class Finance extends Page
{
    protected static ?string $title = 'Konto';

    protected static ?string $slug = 'finances';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.resources.financial-transaction-resource.pages.finance';

    public float $balance = 0;

    public float $totalExpenses = 0;

    public float $topupsAJ = 0;

    public float $topupsMM = 0;

    public float $balanceAJ = 0;

    public float $balanceMM = 0;

    public $transactions = [];

    public function mount(): void
    {
        $this->calculateMetrics();
        $this->transactions = FinancialTransaction::orderBy('transaction_date', 'desc')->get();
    }

    protected function calculateMetrics(): void
    {
        $transactions = FinancialTransaction::all();

        $topups = $transactions->where('amount', '>', 0);
        $this->totalExpenses = abs($transactions->where('amount', '<', 0)->sum('amount'));
        $this->balance = $topups->sum('amount') - $this->totalExpenses;

        $this->topupsAJ = $topups->where('name', 'A&J')->sum('amount');
        $this->topupsMM = $topups->where('name', 'M&M')->sum('amount');

        $sharePerParty = $this->totalExpenses / 2;
        $this->balanceAJ = $this->topupsAJ - $sharePerParty;
        $this->balanceMM = $this->topupsMM - $sharePerParty;
    }
}
