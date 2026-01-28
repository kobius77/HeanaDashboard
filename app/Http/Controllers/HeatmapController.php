<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeatmapController extends Controller
{
    /**
     * Display the standalone heatmap page.
     */
    public function index(): View
    {
        $query = DailyLog::query()
            ->select(['log_date', 'egg_count'])
            ->where('egg_count', '>', 0)
            ->orderBy('log_date');

        $firstLog = (clone $query)->first();
        $lastLog = (clone $query)->latest('log_date')->first();

        $data = $query->get()
            ->map(fn ($log) => [
                'date' => $log->log_date,
                'value' => (int) $log->egg_count,
            ])
            ->toArray();

        $startDate = $firstLog ? \Carbon\Carbon::parse($firstLog->log_date)->startOfMonth() : now()->startOfYear();
        $endDate = $lastLog ? \Carbon\Carbon::parse($lastLog->log_date)->endOfMonth() : now();
        
        // Calculate range in months
        $range = $startDate->diffInMonths($endDate) + 1;

        return view('heatmap', [
            'heatmapData' => $data,
            'startDate' => $startDate->toDateString(),
            'range' => $range,
        ]);
    }
}