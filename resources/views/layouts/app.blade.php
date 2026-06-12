<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Coldstore Mobile'))</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="coldstore-body">
        <main class="nativephp-safe-area coldstore-shell">
            @yield('content')
        </main>

        <nav class="mobile-nav" aria-label="Hauptnavigation">
            <a class="mobile-nav__item {{ request()->routeIs('coldstore.dashboard') ? 'mobile-nav__item--active' : '' }}" href="{{ route('coldstore.dashboard', absolute: false) }}">
                <span>Overview</span>
            </a>
            <a class="mobile-nav__item {{ request()->routeIs('coldstore.scanner') ? 'mobile-nav__item--active' : '' }}" href="{{ route('coldstore.scanner', absolute: false) }}">
                <span>Scanner</span>
            </a>
            <a class="mobile-nav__item {{ request()->routeIs('coldstore.settings') ? 'mobile-nav__item--active' : '' }}" href="{{ route('coldstore.settings', absolute: false) }}">
                <span>Status</span>
            </a>
        </nav>
    </body>
</html>
