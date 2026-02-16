<x-filament-panels::page>
    <style>
        .finance-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1.5rem !important;
        }
        .finance-widgets {
            grid-column: span 2 !important;
        }
        .finance-widgets-inner {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1rem !important;
        }
        .finance-table {
            grid-column: span 2 !important;
        }
        @media (min-width: 1280px) {
            .finance-grid {
                grid-template-columns: repeat(4, 1fr) !important;
            }
            .finance-widgets {
                grid-column: span 2 !important;
            }
            .finance-table {
                grid-column: span 2 !important;
            }
        }
    </style>
    <div class="fi-content">
        <div class="grid grid-cols-2 gap-6 finance-grid">
            <!-- Widgets -->
            <div class="col-span-2 finance-widgets">
                <div class="grid grid-cols-2 gap-4 finance-widgets-inner">
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktueller Kontostand</div>
                    <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                        {{ number_format($balance, 2) }} €
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Gesamtausgaben</div>
                    <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                        {{ number_format($totalExpenses, 2) }} €
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo A&J</div>
                    <div class="text-3xl font-semibold tracking-tight" style="color: {{ $balanceAJ >= 0 ? '#16a34a' : '#dc2626' }};">
                        {{ $balanceAJ > 0 ? '+' : '' }}{{ number_format($balanceAJ, 2) }} €
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo M&M</div>
                    <div class="text-3xl font-semibold tracking-tight" style="color: {{ $balanceMM >= 0 ? '#16a34a' : '#dc2626' }};">
                        {{ $balanceMM > 0 ? '+' : '' }}{{ number_format($balanceMM, 2) }} €
                    </div>
                </div>
                </div>
            </div>

            <!-- Table -->
            <div class="col-span-2 finance-table bg-white dark:bg-gray-800 rounded-lg border shadow-sm">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold">Transaktionen</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Datum</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Name</th>
                                <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 dark:text-gray-300">Betrag</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono" style="font-family: 'Courier New', Courier, monospace;">
                            @forelse($transactions as $transaction)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d.m.Y') }}</td>
                                    <td class="px-4 py-2">{{ $transaction->name }}</td>
                                    <td class="px-4 py-2 text-right {{ $transaction->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($transaction->amount, 2) }} €
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">Noch keine Transaktionen</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
