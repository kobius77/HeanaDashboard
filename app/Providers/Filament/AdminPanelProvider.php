<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Models\DailyLog;
use Carbon\Carbon;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $today = DailyLog::whereDate('log_date', Carbon::today())->first();
        $temp = $today?->weather_temp_c;
        $sunHours = $today?->sun_hours;

        $weatherHtml = '';
        if ($temp !== null) {
            $weatherHtml .= '<span class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-300">';
            $weatherHtml .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 15C12.1046 15 13 15.8954 13 17C13 18.1046 12.1046 19 11 19C9.89543 19 9 18.1046 9 17C9 15.8954 9.89543 15 11 15ZM11 15V8M15 6H18M15 8H18M15 10H18M15 12H18M11 3C12.1046 3 13 3.89543 13 5L13.0011 13.5358C14.1961 14.2275 15 15.5199 15 17C15 19.2091 13.2091 21 11 21C8.79086 21 7 19.2091 7 17C7 15.5195 7.80434 14.2268 8.99988 13.5352L9 5C9 3.89543 9.89543 3 11 3Z" stroke="currentColor" stroke-linejoin="round"/></svg>';
            $weatherHtml .= $temp.'°C';
            $weatherHtml .= '</span>';
        }
        if ($sunHours !== null) {
            $weatherHtml .= '<span class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-300">';
            $weatherHtml .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>';
            $weatherHtml .= $sunHours.' h';
            $weatherHtml .= '</span>';
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('CoopControlCenter')
            ->favicon(asset('favicon.svg'))
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label(fn () => __('German'))
                    ->url(fn () => route('language.switch', 'de'))
                    ->icon('heroicon-o-language'),
                MenuItem::make()
                    ->label(fn () => __('English'))
                    ->url(fn () => route('language.switch', 'en'))
                    ->icon('heroicon-o-language'),
                MenuItem::make()
                    ->label(fn () => __('Auto'))
                    ->url(fn () => route('language.switch', 'auto'))
                    ->icon('heroicon-o-computer-desktop'),
            ])
            ->renderHook(
                'panels::global-search.after',
                fn () => new \Illuminate\Support\HtmlString(
                    '<div class="flex items-center gap-3">'.$weatherHtml.'</div>'
                )
            )
            ->renderHook(
                'panels::head.end',
                fn () => new \Illuminate\Support\HtmlString('
                    <style>
                        :root, body, .fi-body {
                            --radius-xs: 0rem !important;
                            --radius-sm: 0rem !important;
                            --radius: 0rem !important;
                            --radius-md: 0rem !important;
                            --radius-lg: 0rem !important;
                            --radius-xl: 0rem !important;
                            --radius-2xl: 0rem !important;
                            --radius-3xl: 0rem !important;
                            --radius-4xl: 0rem !important;
                        }
                        /* Enforce sharp corners on all elements */
                        * { border-radius: 0 !important; }
                        
                        /* Exceptions for elements that MUST be round (like radio buttons or status dots) */
                        input[type="radio"], .rounded-full {
                            border-radius: 9999px !important;
                        }
                    </style>
                ')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                \App\Http\Middleware\SetUserLocale::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
