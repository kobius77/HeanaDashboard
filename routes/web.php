<?php

use App\Http\Controllers\HeatmapController;
use App\Http\Livewire\PublicDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [HeatmapController::class, 'index'])->name('heatmap');
Route::get('/dashboard', PublicDashboard::class)->name('public.dashboard');
Route::get('/lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

Route::post('/webhook/ingest', [App\Http\Controllers\WebhookController::class, 'ingest'])->name('webhook.ingest');
