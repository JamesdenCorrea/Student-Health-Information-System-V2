<x-layouts.guest title="Login">
    <div class="mx-auto max-w-md">
        <h1 class="text-3xl font-semibold text-slate-950">Login</h1>
        <p class="mt-2 text-sm text-slate-500">Access the student health workspace.</p>

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
                @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-900 focus:ring-teal-800">
                Remember me
            </label>

            <button class="w-full rounded-md bg-gradient-to-r from-teal-950 via-teal-800 to-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:from-teal-900 hover:to-emerald-500">Login</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            No account yet?
            <a href="{{ route('register') }}" class="font-semibold text-teal-800 hover:text-teal-950">Register</a>
        </p>
    </div>
</x-layouts.guest>
