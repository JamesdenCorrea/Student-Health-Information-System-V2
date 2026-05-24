<x-layouts.app title="Create Profile">
    <section class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">Profile CRUD</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Create student profile</h1>
        </div>

        <form method="POST" action="{{ route('profiles.store') }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            @include('profiles._form', ['submitLabel' => 'Create profile'])
        </form>
    </section>
</x-layouts.app>
