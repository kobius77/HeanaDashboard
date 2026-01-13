@php
    $chartData = $this->getChartData();
@endphp

<x-filament-widgets::widget>
    <x-filament::card>
        <h2 class="text-lg font-semibold">Production Heatmap (Last Year)</h2>

        <div id="cal-heatmap" class="mt-4"></div>
    </x-filament::card>

    {{-- Cal-Heatmap scripts --}}
    <script type="text/javascript" src="https://unpkg.com/cal-heatmap/dist/cal-heatmap.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/cal-heatmap/dist/cal-heatmap.css">
    <script type="text/javascript" src="https://unpkg.com/@cal-heatmap/tooltip/dist/tooltip.min.js"></script>
    <script type="text/javascript" src="//unpkg.com/d3@7/dist/d3.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cal = new CalHeatmap();
            const data = @json($chartData);

            cal.paint({
                data: {
                    source: data,
                    x: 'date',
                    y: 'value'
                },
                date: {
                    start: new Date(new Date().setFullYear(new Date().getFullYear() - 1))
                },
                range: 12,
                scale: {
                    color: {
                        type: 'threshold',
                        range: ['#a8ddb5', '#7bccc4', '#4eb3d3', '#2b8cbe', '#08589e'],
                        domain: [2, 4, 6, 8, 10],
                    },
                },
                domain: { type: 'month' },
                subDomain: { type: 'day', radius: 2 },
            }, [
                [
                    Tooltip,
                    {
                        text: function (date, value, dayjsDate) {
                            return (value ? value + ' eggs' : 'No data') + ' on ' + dayjsDate.format('LL');
                        }
                    }
                ]
            ]);
        });
    </script>
</x-filament-widgets::widget>
