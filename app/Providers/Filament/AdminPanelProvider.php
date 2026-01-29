<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
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
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
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
