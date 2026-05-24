<x-layouts.app title="Student Health Information System">
    <section class="overflow-hidden bg-gradient-to-br from-teal-950 via-teal-900 to-emerald-700 text-white">
        <div class="mx-auto grid min-h-[calc(100vh-113px)] max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
            <div>
                <div class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-sm text-teal-50">School clinic records, organized</div>
                <h1 class="mt-6 max-w-3xl text-4xl font-semibold leading-tight sm:text-6xl">Student Health Information System</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-teal-50/85">A focused workspace for student profiles, health background, clinic visits, and care continuity across school health teams.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="rounded-md bg-white px-5 py-3 text-sm font-semibold text-teal-950 shadow-sm hover:bg-teal-50">Create account</a>
                    <a href="{{ route('login') }}" class="rounded-md border border-white/30 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10">Login</a>
                </div>
            </div>

            <div class="rounded-xl border border-white/15 bg-white/10 p-5 shadow-2xl backdrop-blur">
                <div class="rounded-lg bg-white p-5 text-slate-950">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Today</p>
                            <h2 class="text-xl font-semibold">Clinic Overview</h2>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">Live</span>
                    </div>
                    <div class="mt-5 grid grid-cols-3 gap-3">
                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-2xl font-semibold">128</p>
                            <p class="mt-1 text-xs text-slate-500">Profiles</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-2xl font-semibold">14</p>
                            <p class="mt-1 text-xs text-slate-500">Visits</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-4">
                            <p class="text-2xl font-semibold">3</p>
                            <p class="mt-1 text-xs text-slate-500">Follow ups</p>
                        </div>
                    </div>
                    <div class="mt-5 space-y-3">
                        @foreach (['Headache assessment', 'Medication record updated', 'Guardian contacted'] as $item)
                            <div class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3">
                                <span class="text-sm font-medium">{{ $item }}</span>
                                <span class="size-2 rounded-full bg-teal-600"></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
