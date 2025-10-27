<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            html, body {
                height: 100%;
            }
            body {
                background-color: #f6f9fa;
                background-image: url('{{ asset("images/app-logo-light.png") }}');
                background-repeat: no-repeat;
                background-blend-mode: lighten;
                background-position: center;
                background-size: cover;
            }
        </style>
    </head>
    <body class="">
        <div class="h-full flex flex-col items-center justify-center gap-4">
            <h1 class="text-5xl">Error {{$status}}</h1>
            <p>{{ $message }}</p>
            <div>
                <flux:button class="bg-blue-100" :href="back()">Go Back</flux:button>
                <flux:button :href="route('home')">Home</flux:button>
            </div>
        </div>
    </body>
</html>