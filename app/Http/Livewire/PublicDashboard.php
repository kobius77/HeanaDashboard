<?php

namespace App\Http\Livewire;

use App\Models\WidgetSetting;
use Livewire\Component;

class PublicDashboard extends Component
{
    public function render()
    {
        $widgets = WidgetSetting::where('is_published', true)->get();

        return view('livewire.public-dashboard', [
            'widgets' => $widgets,
        ]);
    }
}
