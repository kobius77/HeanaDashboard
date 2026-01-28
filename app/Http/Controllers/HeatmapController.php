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
        $data = DailyLog::query()
            ->select(['log_date', 'egg_count'])
            ->orderBy('log_date')
            ->get()
            ->map(fn ($log) => [
                'date' => $log->log_date,
                'value' => (int) $log->egg_count,
            ])
            ->toArray();

        return view('heatmap', [
            'heatmapData' => $data,
        ]);
    }
}