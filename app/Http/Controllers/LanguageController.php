<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (! in_array($locale, ['en', 'de', 'auto'])) {
            abort(400);
        }

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        } else {
            session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
