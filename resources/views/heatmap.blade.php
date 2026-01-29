<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Egg Production Heatmap') }}</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    <!-- Tailwind & App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Cal-Heatmap v4 -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://unpkg.com/cal-heatmap/dist/cal-heatmap.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/cal-heatmap/dist/cal-heatmap.css">
    
    <!-- Dependencies -->
    <script src="https://unpkg.com/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/dayjs@1.11.10/dayjs.min.js"></script>
    <script src="https://unpkg.com/dayjs@1.11.10/locale/de.js"></script>
    <script src="https://unpkg.com/dayjs@1.11.10/plugin/localizedFormat.js"></script>
    
    <!-- Tooltip Plugin -->
    <script src="https://unpkg.com/cal-heatmap/dist/plugins/Tooltip.min.js"></script>
    <script src="https://unpkg.com/cal-heatmap/dist/plugins/LegendLite.min.js"></script>

    <style>
        .ch-subdomain-bg {
            fill: #ffffff;
        }
        /* Ensure responsiveness on small screens */
        svg.ch-plugin-legend-width, .ch-container svg {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="bg-white font-sans antialiased text-gray-900">
    <div class="p-4 flex flex-col items-center">
        <div class="w-full max-w-[500px]"> <!-- limit width for better layout -->
            <main class="py-4 flex flex-col items-center gap-4">
                
                @php $lastYear = null; @endphp
                @foreach($calendarRows as $index => $row)
                    @if($lastYear !== $row['year'])
                        <div class="w-full text-center border-b border-gray-100 pb-1 mt-2 first:mt-0">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                {{ $row['year'] }}&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;🥚 {{ $yearlyStats[$row['year']]['eggs'] ?? 0 }}&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;☀️ {{ number_format($yearlyStats[$row['year']]['sun'] ?? 0, 1) }}
                            </span>
                        </div>
                        @php $lastYear = $row['year']; @endphp
                    @endif

                    <div class="flex flex-col items-center w-full">
                        <div id="cal-heatmap-{{ $index }}" class="flex justify-center w-full"></div>
                    </div>
                @endforeach
                
                <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-400">
                    <span>🥚</span>
                    <div id="legend" class="flex gap-1"></div>
                    <span>🥚🥚🥚</span>
                </div>

                <footer class="mt-12 flex justify-center gap-4 text-xs font-normal text-gray-400 border-t border-gray-100 pt-8 w-full">
                    <a href="{{ route('public.dashboard') }}" class="hover:text-gray-600 transition-colors">{{ __('Public Dashboard') }}</a>
                    <span class="text-gray-200">|</span>
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="hover:text-gray-600 transition-colors">{{ __('Administration') }}</a>
                </footer>
            </main>
        </div>
    </div>

    <script>
        // Initialize Dayjs localizedFormat and Locale
        dayjs.locale('{{ app()->getLocale() }}');
        dayjs.extend(dayjs_plugin_localizedFormat);

        const heatmapData = @json($heatmapData);
        const calendarRows = @json($calendarRows);
        
        // Pre-process data into a Map for O(1) lookup in tooltip
        const dataMap = new Map(heatmapData.map(d => [dayjs(d.date).format('YYYY-MM-DD'), d]));

        // Calculate monthly sums and sun averages
        const monthlySums = new Map();
        const monthlySun = new Map();
        heatmapData.forEach(d => {
            const key = dayjs(d.date).format('YYYY-MM');
            
            // Eggs
            monthlySums.set(key, (monthlySums.get(key) || 0) + d.value);

            // Sun hours (count and sum for average)
            if (d.sun !== null && d.sun !== undefined) {
                const currentSun = monthlySun.get(key) || { sum: 0, count: 0 };
                monthlySun.set(key, { 
                    sum: currentSun.sum + d.sun, 
                    count: currentSun.count + 1 
                });
            }
        });

        // Tooltip Configuration (Shared)
        const tooltipConfig = {
            text: function(date, value, dayjsDate) {
                const eggsLabel = '{{ __("Eggs") }}';
                const noDataLabel = '{{ __("No data") }}';
                const onLabel = '{{ __("on") }}';
                const tempLabel = '{{ __("Temp") }}';
                const sunLabel = '{{ __("Sun") }}';

                let text = (value !== null ? value + ' ' + eggsLabel : noDataLabel) + ' ' + onLabel + ' ' + dayjsDate.format('LL');
                
                const dataPoint = dataMap.get(dayjsDate.format('YYYY-MM-DD'));
                if (dataPoint) {
                    if (dataPoint.temp !== null && dataPoint.temp !== undefined) {
                        text += '<br><span style="font-size: 0.9em; color: #ef4444;">' + tempLabel + ': ' + dataPoint.temp + '°C</span>';
                    }
                    if (dataPoint.sun !== null && dataPoint.sun !== undefined) {
                        text += '<br><span style="font-size: 0.9em; color: #f59e0b;">' + sunLabel + ': ' + dataPoint.sun + 'h</span>';
                    }
                    if (dataPoint.notes) {
                        text += '<br><span style="font-size: 0.9em; font-style: italic; color: #666;">' + dataPoint.notes + '</span>';
                    }
                }
                
                return text;
            },
        };

        calendarRows.forEach((row, index) => {
            const cal = new CalHeatmap();
            
            // Define plugins for this instance
            const plugins = [
                [ window.Tooltip, tooltipConfig ]
            ];

            // Only attach legend to the first instance so it renders once in the shared container
            if (index === 0) {
                plugins.push([
                    window.LegendLite,
                    {
                        itemSelector: '#legend',
                        radius: 2,
                        width: 12,
                        height: 12,
                        gutter: 3
                    }
                ]);
            }

            cal.paint({
                itemSelector: '#cal-heatmap-' + index,
                data: {
                    source: heatmapData,
                    x: 'date',
                    y: 'value',
                },
                date: {
                    start: new Date(row.start),
                    locale: { weekStart: 1 }, // Start on Monday
                },
                range: row.range,
                domain: {
                    type: 'month',
                    gutter: 10,
                                        label: { 
                                            text: (t) => {
                                                const d = dayjs(t);
                                                const monthKey = d.format('YYYY-MM');
                                                const eggSum = monthlySums.get(monthKey) || 0;
                                                
                                                const sunData = monthlySun.get(monthKey);
                                                const sunAvg = sunData ? (sunData.sum / sunData.count).toFixed(1) : '0.0';

                                                const spaces = '\u00A0\u00A0\u00A0';
                                                return `${d.format('MMM')}${spaces}|${spaces}🥚 ${eggSum}${spaces}|${spaces}☀️ ${sunAvg}`;
                                            },
                                            textAlign: 'start', 
                                            position: 'top' 
                                        },
                },
                subDomain: { 
                    type: 'day', 
                    radius: 2, 
                    width: 20, 
                    height: 20, 
                    gutter: 4 
                },
                scale: {
                    color: {
                        base: '#ffffff', // White for "No data" squares on white background
                        type: 'threshold',
                        range: [
                            '#f1f5f9', // 0 (Slate 100)
                            '#ffedd5', // 1 (Orange-100)
                            '#fed7aa', // 2 (Orange-200)
                            '#fdba74', // 3 (Orange-300)
                            '#fb923c', // 4 (Orange-400)
                            '#f97316', // 5 (Orange-500)
                            '#ea580c', // 6 (Orange-600)
                            '#c2410c', // 7 (Orange-700)
                            '#9a3412', // 8 (Orange-800)
                            '#7c2d12', // 9 (Orange-900)
                            '#431407'  // 10+ (Orange-950)
                        ],
                        domain: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    },
                },
            }, plugins);
        });
    </script>
</body>
</html>
