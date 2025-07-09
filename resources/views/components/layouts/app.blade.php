<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <x-layouts.app.sidebar :title="$title ?? null">
            <flux:main>
                {{ $slot }}
            </flux:main>
        </x-layouts.app.sidebar>
        @fluxScripts
        @livewire('wire-elements-modal')
    </body>
</html>
