<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#080808">
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg?v=3') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.svg?v=3') }}">
        <meta name="description" content="{{ __('ui.meta.description') }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:description" content="{{ __('ui.meta.description') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
        @routes
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
        <noscript><style>#page-loader { display: none !important; }</style></noscript>
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>
