<x-layout>
    <x-slot name="header">
        @if (Route::has('login'))
        <nav class="flex items-center justify-end gap-4">
            @auth
            <a
                href="{{ url('/dashboard') }}"
                class="inline-block px-4 py-2 text-gray-700 hover:text-orange-600 text-sm font-medium transition-colors"
            >
                Dashboard
            </a>
            @else
            <a
                href="{{ route('login') }}"
                class="inline-block px-4 py-2 text-gray-700 hover:text-orange-600 text-sm font-medium transition-colors"
            >
                Log in
            </a>
            @endauth
        </nav>
        @endif
    </x-slot>

    <div class="flex items-center justify-center min-h-[60vh]">
        <h1 class="text-6xl font-bold text-gray-900">Whee.no</h1>
    </div>
</x-layout>
