<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PublicDashboard;
use App\Http\Controllers\HeatmapController;

Route::get('/', PublicDashboard::class);
Route::get('/heatmap', [HeatmapController::class, 'index'])->name('heatmap');