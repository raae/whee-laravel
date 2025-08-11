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
        <div class="mb-8">
            <h1 class="mb-3 text-3xl font-bold text-gray-900">
                Sykkel og abonnement
            </h1>
        </div>

        @if(count($userBikes) > 0) @foreach($userBikes as $bike)
        <!-- Bike Subscription Card -->
        <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 mb-8">
            <div class="space-y-4">
                <!-- Bike Name -->
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $bike["fields"]["name"] ?? "Ukjent sykkel" }}
                </h2>

                <!-- Total Price -->
                <div class="text-lg text-gray-700">
                    <span class="font-semibold">Totalpris:</span>
                    {{
                        number_format(
                            $bike["fields"]["price"] ?? 0,
                            0,
                            ",",
                            " "
                        )
                    }}
                    kr per mnd
                </div>

                <!-- Start Date -->
                <div class="text-gray-700">
                    Du har hatt Wheel-sykkel siden
                    @if(isset($bike['fields']['startDate']))
                    {{ \Carbon\Carbon::parse($bike['fields']['startDate'])->locale('no')->isoFormat('DD. MMMM YYYY') }}
                    @else
                    {{ auth()->user()->airtable_created_at->locale('no')->isoFormat('DD. MMMM YYYY') }}
                    @endif
                </div>

                <!-- Notice Period -->
                <div class="text-gray-700">
                    {{ $bike["fields"]["noticePeriod"] ?? "3" }} mnd
                    oppsigelsestid
                </div>

                <!-- Change Link -->
                <div class="pt-2">
                    <a
                        href="#"
                        class="text-orange-600 hover:text-orange-700 font-medium underline"
                    >
                        Endre
                    </a>
                </div>
            </div>
        </div>
        @endforeach @else
        <!-- No Bikes Message -->
        <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 mb-8">
            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    Ingen aktive sykler
                </h2>
                <p class="text-gray-600">
                    Du har for øyeblikket ingen aktive sykkelabonnementer.
                </p>
            </div>
        </div>
        @endif

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
    </div>
</x-layout>
