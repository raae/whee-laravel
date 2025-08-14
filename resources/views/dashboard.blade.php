<x-layout
    title="Dashboard"
    main-classes="flex max-w-[335px] w-full flex-col lg:max-w-4xl"
>
    <x-slot:header>
        <nav class="flex items-center justify-between">
            <div class="flex items-center">
                <h2 class="text-lg font-medium">Dashboard</h2>
            </div>
            <div class="flex items-center gap-4">
                <form method="POST" action="/auth/logout" id="logout-form">
                    @csrf
                    <button
                        type="submit"
                        class="inline-block px-4 py-2 text-gray-700 hover:text-orange-600 text-sm font-medium transition-colors"
                    >
                        Logg ut
                    </button>
                </form>
            </div>
        </nav>
    </x-slot:header>

    <div class="p-8 bg-white border-2 border-gray-200 rounded-lg">
        <div class="mb-6">
            <h1 class="mb-3 text-3xl font-bold text-gray-900">
                Velkommen tilbake!
            </h1>
            <p class="text-gray-600 text-lg">
                Du har logget inn med telefonnummer.
            </p>
        </div>

        <!-- User Information Card -->
        <div class="bg-orange-50 border-2 border-gray-200 rounded-lg p-6 mb-8">
            <h3
                class="text-sm font-bold mb-4 text-gray-900 uppercase tracking-wide"
            >
                Kontoinformasjon
            </h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Navn:</span>
                    <span
                        class="text-sm font-bold text-gray-900"
                        >{{ auth()->user()->name ?? 'N/A' }}</span
                    >
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900"
                        >Telefonnummer:</span
                    >
                    <span
                        class="text-sm font-bold text-gray-900"
                        >{{ auth()->user()->phone ?? 'N/A' }}</span
                    >
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900"
                        >Email:</span
                    >
                    <span
                        class="text-sm font-bold text-gray-900"
                        >{{ auth()->user()->email ?? 'N/A' }}</span
                    >
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900"
                        >Telefonnummer bekreftet:</span
                    >
                    <span class="text-sm font-medium">
                        @if(auth()->user()->phone_verified_at)
                        <span class="text-green-600 font-bold"
                            >✓ Bekreftet</span
                        >
                        @else
                        <span class="text-orange-600 font-bold">Venter</span>
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900"
                        >Medlem siden:</span
                    >
                    <span
                        class="text-sm font-bold text-gray-900"
                        >{{ auth()->user()->airtable_created_at->format('M j, Y') }}</span
                    >
                </div>
            </div>
        </div>
        <!-- Service time and place info -->
        <div>
            <h3
                    class="text-sm font-bold mb-4 text-gray-900 uppercase tracking-wide"
                >Neste service
            </h3>
                    
                    
                    
                    <div class="space-y-2">
                <div
                    class="flex items-center gap-3 p-4 rounded-lg bg-orange-50 border-2 border-gray-200"
                >
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $booking['time'] }}
                        </div>
                        <div class="text-sm text-gray-600">
                            på {{ $booking['location'] }}
                        </div>
                    </div>
                </div>

                <button
                    class="p-4 text-left border-2 border-gray-200 rounded-lg hover:border-orange-400 transition-colors"
                >
                    <div class="text-sm font-bold mb-1 text-gray-900">
                    Hvis du vil 
                    </div>
                    <a href="/book" class="text-sm text-gray-600">
                        Endre tid eller Kansellere servicen
                    </a>
                </button>
            </div>
        </div>    

        <!-- Quick Actions -->
        <div class="mb-6">
            <h3
                class="text-sm font-bold mb-4 text-gray-900 uppercase tracking-wide"
            >
                Hurtighandlinger
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button
                    class="p-4 text-left border-2 border-gray-200 rounded-lg hover:border-orange-400 transition-colors"
                >
                    <div class="text-sm font-bold mb-1 text-gray-900">
                        Oppdater profil
                    </div>
                    <div class="text-sm text-gray-600">
                        Endre informasjon om deg
                    </div>
                </button>

                <button
                    class="p-4 text-left border-2 border-gray-200 rounded-lg hover:border-orange-400 transition-colors"
                >
                    <div class="text-sm font-bold mb-1 text-gray-900">
                        Sikkerhet
                    </div>
                    <div class="text-sm text-gray-600">
                        Endre autentiseringsinnstillinger
                    </div>
                </button>
            </div>
        </div>

        <!-- Recent Activity -->
        <div>
            <h3
                class="text-sm font-bold mb-4 text-gray-900 uppercase tracking-wide"
            >
                Nylige aktiviteter
            </h3>
            <div class="space-y-2">
                <div
                    class="flex items-center gap-3 p-4 rounded-lg bg-orange-50 border-2 border-gray-200"
                >
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">
                            Telefonnummer bekreftet
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ auth()->user()->phone_verified_at ? auth()->user()->phone_verified_at->diffForHumans() : 'Just now' }}
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 p-4 rounded-lg bg-orange-50 border-2 border-gray-200"
                >
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">
                            Konto opprettet
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ auth()->user()->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
