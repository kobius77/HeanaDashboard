<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Heana Dashboard - Egg Production</title>
        @filamentStyles
        @vite('resources/css/app.css')
    </head>
    <body class="h-full font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-white">
        <main class="p-4 mx-auto max-w-7xl sm:p-6 lg:p-8">
            {{ $slot }}
        </main>

        @livewire('notifications')
        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
