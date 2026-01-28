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
</head>
<body class="h-full font-sans antialiased text-gray-900">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <div class="w-full max-w-6xl">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    Egg Production Heatmap
                </h1>
                <p class="mt-4 text-lg text-gray-500">
                    Daily distribution of egg counts across the flock.
                </p>
            </header>

            <main class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
                <div id="cal-heatmap" class="mx-auto"></div>
                
                <div class="mt-8 flex items-center justify-center gap-4 text-sm text-gray-500">
                    <span>Less Eggs</span>
                    <div id="legend" class="flex gap-1"></div>
                    <span>More Eggs</span>
                </div>
            </main>

            <footer class="mt-10 text-center text-sm text-gray-400">
                <a href="{{ url('/') }}" class="hover:text-gray-600 transition-colors underline decoration-gray-300 underline-offset-4">
                    Back to Dashboard
                </a>
            </footer>
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
                start: new Date(new Date().getFullYear(), 0, 1), // Start of current year
                locale: { weekStart: 1 }, // Start on Monday
            },
            range: 12,
            domain: {
                type: 'month',
                gutter: 15,
                label: { text: 'MMM', textAlign: 'start', position: 'top' },
            },
            subDomain: { 
                type: 'ghDay', 
                radius: 2, 
                width: 15, 
                height: 15, 
                gutter: 4 
            },
            scale: {
                color: {
                    type: 'threshold',
                    range: ['#e5e7eb', '#bbf7d0', '#4ade80', '#16a34a', '#166534'],
                    domain: [1, 5, 10, 20], // Adjust these thresholds based on typical egg counts
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
        ]);
    </script>
</body>
</html>
