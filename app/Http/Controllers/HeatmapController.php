<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use App\Services\GoogleCalendarService;
use Illuminate\View\View;

class HeatmapController extends Controller
{
    /**
     * Display the standalone heatmap page.
     */
    public function index(): View
    {
        $query = DailyLog::query()
            ->select(['log_date', 'egg_count', 'notes', 'weather_temp_c', 'sun_hours'])
            ->orderBy('log_date');

        $firstLog = (clone $query)->first();
        $lastLog = (clone $query)->latest('log_date')->first();

        $deliveryService = new GoogleCalendarService;
        $deliveries = collect($deliveryService->getUpcomingFeedDeliveries(5, 100));
        $deliveryDates = $deliveries->pluck('date')->toArray();

        $data = $query->get()
            ->map(fn ($log) => [
                'date' => $log->log_date,
                'value' => (int) $log->egg_count,
                'notes' => $log->notes,
                'temp' => $log->weather_temp_c !== null ? (float) $log->weather_temp_c : null,
                'sun' => $log->sun_hours !== null ? (float) $log->sun_hours : null,
            ])
            ->toArray();

        $yearlyStats = collect($data)
            ->groupBy(fn ($d) => \Carbon\Carbon::parse($d['date'])->year)
            ->map(fn ($year) => [
                'eggs' => $year->sum('value'),
                'sun' => $year->filter(fn ($d) => $d['sun'] !== null)->count() > 0
                    ? round($year->filter(fn ($d) => $d['sun'] !== null)->avg('sun'), 1)
                    : 0.0,
            ])
            ->toArray();

        $startDate = \Carbon\Carbon::parse('2025-10-01')->startOfDay();

        // Show from Q4 2025 to current quarter (past + current, no future)
        $endDate = now()->endOfQuarter();

        $calendarRows = [];

        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $quarterEnd = $current->copy()->endOfQuarter();

            $calendarRows[] = [
                'start' => $current->format('Y-m-d'),
                'range' => 3,
                'label' => $current->year.' ('.$current->format('M').' - '.$quarterEnd->format('M').')',
                'year' => $current->year,
                'quarter' => ceil($current->month / 3),
            ];

            $current->addMonths(3)->startOfQuarter();
        }

        return view('heatmap', [
            'heatmapData' => $data,
            'calendarRows' => $calendarRows,
            'yearlyStats' => $yearlyStats,
            'deliveryDates' => $deliveryDates,
        ]);
    }
}
