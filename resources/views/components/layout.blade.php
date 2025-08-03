<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{ $styles ?? '' }}
    </head>
    <body class="bg-[#fffcf4] text-gray-900 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-medium">
        @unless($hideHeader ?? false)
            <header class="w-full {{ $headerWidth ?? 'lg:max-w-4xl max-w-[335px]' }} text-sm mb-6">
                {{ $header ?? '' }}
            </header>
        @endunless

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="{{ $mainClasses ?? 'flex max-w-[335px] w-full flex-col lg:max-w-2xl' }}">
                {{ $slot }}
            </main>
        </div>

        @unless($hideFooter ?? false)
            {{ $footer ?? '' }}
        @endunless

        {{ $scripts ?? '' }}
    </body>
</html>