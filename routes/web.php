<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PublicDashboard;

Route::get('/', PublicDashboard::class);