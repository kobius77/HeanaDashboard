<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Egg Production Heatmap</title>

    <!-- Tailwind & App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Cal-Heatmap v4 -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://unpkg.com/cal-heatmap/dist/cal-heatmap.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/cal-heatmap/dist/cal-heatmap.css">
    
    <!-- Dependencies -->
    <script src="https://unpkg.com/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/dayjs@1.11.10/dayjs.min.js"></script>
    <script src="https://unpkg.com/dayjs@1.11.10/plugin/localizedFormat.js"></script>
    
    <!-- Tooltip Plugin -->
    <script src="https://unpkg.com/cal-heatmap/dist/plugins/Tooltip.min.js"></script>
    <script src="https://unpkg.com/cal-heatmap/dist/plugins/Legend.min.js"></script>
</head>
<body class="bg-white font-sans antialiased text-gray-900">
    <div class="p-4 flex flex-col items-center">
        <div class="w-full">
            <main class="overflow-x-auto py-4">
                <div id="cal-heatmap" class="mx-auto"></div>
                
                <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-400">
                    <span>Less Eggs</span>
                    <div id="legend" class="flex gap-1"></div>
                    <span>More Eggs</span>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Initialize Dayjs localizedFormat
        dayjs.extend(dayjs_plugin_localizedFormat);

        const heatmapData = @json($heatmapData);

        const cal = new CalHeatmap();
        cal.paint({
            itemSelector: '#cal-heatmap',
            data: {
                source: heatmapData,
                x: 'date',
                y: 'value',
            },
            date: {
                start: new Date("{{ $startDate }}"),
                locale: { weekStart: 1 }, // Start on Monday
            },
            range: {{ $range }},
            domain: {
                type: 'month',
                gutter: 15,
                label: { text: 'MMM', textAlign: 'start', position: 'top' },
            },
            subDomain: { 
                type: 'ghDay', 
                radius: 2, 
                width: 12, 
                height: 12, 
                gutter: 3 
            },
            scale: {
                color: {
                    type: 'threshold',
                    range: ['#e5e7eb', '#bbf7d0', '#86efac', '#22c55e', '#166534'],
                    domain: [1, 10, 20, 30],
                },
            },
        }, [
            [
                window.Tooltip,
                {
                    text: function(date, value, dayjsDate) {
                        return (value ? value + ' Eggs' : 'No data') + ' on ' + dayjsDate.format('LL');
                    },
                },
            ],
            [
                window.Legend,
                {
                    itemSelector: '#legend',
                    type: 'color',
                }
            ]
        ]);
    </script>
</body>
</html>
