<x-filament-panels::page>
    <x-filament::section>
        <x-slot:heading>
            Liefertermine
        </x-slot:heading>

        <x-slot:description>
            Aktueller Intervall: {{ $interval }} Tage
        </x-slot:description>

        @if($deliveries->isEmpty())
            <p class="text-gray-500">Keine anstehenden Lieferungen gefunden.</p>
        @else
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Datum</th>
                        <th class="py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Lieferant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="py-3 text-sm text-gray-700 dark:text-gray-300">{{ $delivery['display_date'] }}</td>
                            <td class="py-3 text-sm text-gray-700 dark:text-gray-300">{{ $delivery['title'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-filament::section>
</x-filament-panels::page>
