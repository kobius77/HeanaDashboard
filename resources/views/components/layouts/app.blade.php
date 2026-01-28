<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'CoopControlCenter') }}</title>
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        @vite('resources/css/app.css')
        @livewireStyles
    </head>
    <body>
        {{ $slot }}
        @livewireScripts
        @filamentScripts
    </body>
</html>
