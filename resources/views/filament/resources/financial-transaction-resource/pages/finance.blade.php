<x-filament-panels::page>
    <div class="fi-content">
        <div style="display: grid; grid-template-columns: 1fr 3fr; gap: 1.5rem;">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Aktueller Kontostand</div>
                    <div class="text-xl font-bold">
                        {{ number_format($balance, 2) }} €
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Gesamtausgaben</div>
                    <div class="text-xl font-bold">
                        {{ number_format($totalExpenses, 2) }} €
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Saldo A&J</div>
                    <div class="text-xl font-bold" style="color: {{ $balanceAJ >= 0 ? '#16a34a' : '#dc2626' }};">
                        {{ $balanceAJ > 0 ? '+' : '' }}{{ number_format($balanceAJ, 2) }} €
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Saldo M&M</div>
                    <div class="text-xl font-bold" style="color: {{ $balanceMM >= 0 ? '#16a34a' : '#dc2626' }};">
                        {{ $balanceMM > 0 ? '+' : '' }}{{ number_format($balanceMM, 2) }} €
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border shadow-sm">
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
                        <tbody>
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
