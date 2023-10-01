<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        @vite('resources/css/app.css')

        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    <body class="bg-slate-800">
        @auth()
            <livewire:profile/>
        @endauth
        {{ $slot }}
    </body>
</html>
