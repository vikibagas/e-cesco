<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#16763d">
    @vite('resources/js/app.js')
    <x-inertia::head />
</head>
<body>
    <x-inertia::app />
    <div id="app-loading-overlay" class="app-loading-overlay" aria-hidden="true">
        <div class="app-loading-card" role="status" aria-live="polite">
            <span class="app-loading-spinner" aria-hidden="true"></span>
            <strong>{{ app()->isLocale('id') ? 'Memuat…' : 'Loading…' }}</strong>
        </div>
    </div>
</body>
</html>
