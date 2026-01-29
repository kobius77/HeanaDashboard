<?php

use App\Http\Controllers\HeatmapController;
use App\Http\Livewire\PublicDashboard;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

$dashboardRoute = 'dashboard';

if (Schema::hasTable('general_settings')) {
    $dashboardRoute = GeneralSetting::where('key', 'public_dashboard_route')->value('value') ?? 'dashboard';
}

// Ensure route starts with / if not root
if ($dashboardRoute !== '/' && !str_starts_with($dashboardRoute, '/')) {
    $dashboardRoute = '/' . $dashboardRoute;
}

if ($dashboardRoute === '/') {
    Route::get('/', PublicDashboard::class)->name('public.dashboard');
    Route::get('/heatmap', [HeatmapController::class, 'index'])->name('heatmap');
} else {
    Route::get('/', [HeatmapController::class, 'index'])->name('heatmap');
    Route::get($dashboardRoute, PublicDashboard::class)->name('public.dashboard');
}

Route::get('/lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

Route::post('/webhook/ingest', [App\Http\Controllers\WebhookController::class, 'ingest'])->name('webhook.ingest');
