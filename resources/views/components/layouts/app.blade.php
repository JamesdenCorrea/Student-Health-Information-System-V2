<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[linear-gradient(180deg,#f8fafc_0%,#eefdf8_100%)] font-sans text-slate-900 antialiased">
    <div class="min-h-screen">
        <header class="sticky top-0 z-30 border-b border-teal-950/10 bg-white/95 shadow-sm shadow-slate-200/50 backdrop-blur">
            <nav class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3">
                        <span class="grid size-10 place-items-center rounded-lg bg-gradient-to-br from-teal-950 via-teal-800 to-emerald-500 text-sm font-bold text-white shadow-sm">SH</span>
                        <span>
                            <span class="block text-sm font-semibold tracking-wide text-slate-950">Student Health</span>
                            <span class="hidden text-xs text-slate-500 sm:block">Information System</span>
                        </span>
                    </a>

                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-3">
                            @csrf
                            <div class="hidden text-right sm:block">
                                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs capitalize text-slate-500">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                            </div>
                            <span class="grid size-9 place-items-center rounded-full bg-teal-50 text-xs font-bold text-teal-900 ring-1 ring-teal-900/10">
                                {{ str(auth()->user()->name)->substr(0, 1) }}
                            </span>
                            <button class="rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:border-teal-700 hover:bg-teal-50 hover:text-teal-950">Logout</button>
                        </form>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-950">Login</a>
                            <a href="{{ route('register') }}" class="rounded-md bg-teal-950 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-900">Register</a>
                        </div>
                    @endauth
                </div>

                @auth
                    @php
                        $navItems = [
                            ['label' => 'Dashboard', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                            ['label' => 'Profiles', 'route' => route('profiles.index'), 'active' => request()->routeIs('profiles.*')],
                            ['label' => 'Analytics', 'route' => route('analytics'), 'active' => request()->routeIs('analytics')],
                        ];

                        if (auth()->user()->isClinicStaff()) {
                            $navItems[] = ['label' => 'Health Records', 'route' => route('health-records.index'), 'active' => request()->routeIs('health-records.*')];
                        }

                        if (auth()->user()->isAdmin()) {
                            $navItems[] = ['label' => 'Users', 'route' => route('admin.users.index'), 'active' => request()->routeIs('admin.users.*')];
                            $navItems[] = ['label' => 'Parent Assignment', 'route' => route('admin.parent-assignments.index'), 'active' => request()->routeIs('admin.parent-assignments.*')];
                        }
                    @endphp
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach ($navItems as $item)
                            <a href="{{ $item['route'] }}" class="sh-nav-item {{ $item['active'] ? 'sh-nav-item-active' : '' }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                @endauth
            </nav>
        </header>

        @if (session('status'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 shadow-sm">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main class="pb-10">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
