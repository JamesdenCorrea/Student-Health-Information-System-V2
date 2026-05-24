<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="min-h-screen">
        <header class="sticky top-0 z-30 border-b border-teal-950/10 bg-white/90 backdrop-blur">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('landing') }}" class="flex items-center gap-3">
                    <span class="grid size-10 place-items-center rounded-lg bg-gradient-to-br from-teal-950 via-teal-800 to-emerald-500 text-sm font-bold text-white shadow-sm">SH</span>
                    <span class="text-sm font-semibold tracking-wide text-slate-950">Student Health</span>
                </a>

                <div class="hidden items-center gap-1 md:flex">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-teal-950 text-white' : 'text-slate-700 hover:bg-teal-50 hover:text-teal-950' }}">Dashboard</a>
                        <a href="{{ route('profiles.index') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('profiles.*') ? 'bg-teal-950 text-white' : 'text-slate-700 hover:bg-teal-50 hover:text-teal-950' }}">Profiles</a>
                        @if (auth()->user()->isClinicStaff())
                            <a href="{{ route('health-records.index') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('health-records.*') ? 'bg-teal-950 text-white' : 'text-slate-700 hover:bg-teal-50 hover:text-teal-950' }}">Health Records</a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.users.index') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-teal-950 text-white' : 'text-slate-700 hover:bg-teal-50 hover:text-teal-950' }}">Users</a>
                            <a href="{{ route('admin.parent-assignments.index') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.parent-assignments.*') ? 'bg-teal-950 text-white' : 'text-slate-700 hover:bg-teal-50 hover:text-teal-950' }}">Parents</a>
                        @endif
                        <a href="{{ route('analytics') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('analytics') ? 'bg-teal-950 text-white' : 'text-slate-700 hover:bg-teal-50 hover:text-teal-950' }}">Analytics</a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-teal-50 hover:text-teal-950">Login</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-teal-950 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-900">Register</a>
                    @endauth
                </div>

                @auth
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-3">
                        @csrf
                        <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                        <button class="rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:border-teal-700 hover:text-teal-950">Logout</button>
                    </form>
                @endauth
            </nav>
        </header>

        @if (session('status'))
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
