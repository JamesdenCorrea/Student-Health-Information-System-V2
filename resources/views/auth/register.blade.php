<x-layouts.guest title="Register">
    <div class="mx-auto max-w-md">
        <div class="mb-8 grid size-12 place-items-center rounded-lg bg-teal-50 text-sm font-bold text-teal-950 ring-1 ring-teal-900/10">SH</div>
        <h1 class="text-3xl font-semibold text-slate-950">Register</h1>
        <p class="mt-2 text-sm text-slate-500">Create an account for review. Admin can adjust roles after registration.</p>

        <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
                @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
            </div>

            <button class="w-full rounded-md bg-gradient-to-r from-teal-950 via-teal-800 to-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:from-teal-900 hover:to-emerald-500">Create account</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Already registered?
            <a href="{{ route('login') }}" class="font-semibold text-teal-800 hover:text-teal-950">Login</a>
        </p>
    </div>
</x-layouts.guest>
