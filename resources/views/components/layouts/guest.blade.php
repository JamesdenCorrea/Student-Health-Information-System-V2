<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-teal-950 via-teal-900 to-emerald-700 font-sans text-white antialiased">
    <main class="grid min-h-screen grid-cols-1 lg:grid-cols-[1fr_520px]">
        <section class="flex items-center px-6 py-10 sm:px-10 lg:px-16">
            <div class="max-w-2xl">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-3">
                    <span class="grid size-11 place-items-center rounded-lg bg-white text-sm font-bold text-teal-950">SH</span>
                    <span class="font-semibold tracking-wide">Student Health Information System</span>
                </a>
                <h1 class="mt-12 max-w-xl text-4xl font-semibold leading-tight sm:text-5xl">A cleaner clinic workflow for student care records.</h1>
                <p class="mt-5 max-w-lg text-base leading-7 text-teal-50/85">Manage student profiles, health background, and clinic visits from one organized school health workspace.</p>
                <div class="mt-10 grid max-w-xl grid-cols-3 gap-3 text-sm text-teal-50/85">
                    <div class="border-l border-white/25 pl-4">Student profiles</div>
                    <div class="border-l border-white/25 pl-4">Health records</div>
                    <div class="border-l border-white/25 pl-4">Clinic visits</div>
                </div>
            </div>
        </section>

        <section class="flex items-center bg-white px-6 py-10 text-slate-950 shadow-2xl sm:px-10">
            <div class="w-full">
                {{ $slot }}
            </div>
        </section>
    </main>
</body>
</html>
