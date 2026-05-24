<x-layouts.app title="User Management">
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
            <div>
                <div class="mb-6">
                    <p class="text-sm font-medium text-teal-800">Admin</p>
                    <h1 class="mt-1 text-3xl font-semibold text-slate-950">User management</h1>
                </div>

                <form class="mb-4">
                    <input name="search" value="{{ request('search') }}" placeholder="Search users" class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
                </form>

                <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3">User</th>
                                <th class="px-5 py-3">Role</th>
                                <th class="px-5 py-3">Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-slate-950">{{ $user->name }}</p>
                                        <p class="mt-1 text-slate-500">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600">{{ str_replace('_', ' ', $user->role) }}</td>
                                    <td class="px-5 py-4">
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="grid gap-2 md:grid-cols-3">
                                            @csrf
                                            @method('PUT')
                                            <input name="name" value="{{ $user->name }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                                            <input name="email" value="{{ $user->email }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                                            <select name="role" class="rounded-md border border-slate-300 px-3 py-2 text-sm">
                                                @foreach (['admin', 'clinic_staff', 'parent'] as $role)
                                                    <option value="{{ $role }}" @selected($user->role === $role)>{{ str_replace('_', ' ', ucfirst($role)) }}</option>
                                                @endforeach
                                            </select>
                                            <button class="rounded-md bg-teal-950 px-3 py-2 text-sm font-semibold text-white md:col-span-3">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="border-t border-slate-100 px-5 py-4">{{ $users->links() }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <h2 class="font-semibold text-slate-950">Create user</h2>
                <div class="mt-5 space-y-4">
                    <input name="name" value="{{ old('name') }}" placeholder="Name" class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                    <input name="email" value="{{ old('email') }}" placeholder="Email" type="email" class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                    <select name="role" class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                        <option value="parent">Parent</option>
                        <option value="clinic_staff">Clinic staff</option>
                        <option value="admin">Admin</option>
                    </select>
                    <input name="password" placeholder="Password" type="password" class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                    <input name="password_confirmation" placeholder="Confirm password" type="password" class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                </div>
                <button class="mt-5 w-full rounded-md bg-teal-950 px-4 py-2.5 text-sm font-semibold text-white">Create user</button>
            </form>
        </div>
    </section>
</x-layouts.app>
