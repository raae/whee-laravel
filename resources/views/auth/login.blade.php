<x-layout title="Login">
    <x-slot:header>
        <nav class="flex items-center justify-end gap-4">
            <a
                href="{{ url('/') }}"
                class="inline-block px-4 py-2 text-gray-700 hover:text-orange-600 text-sm font-medium transition-colors"
            >
                Home
            </a>
        </nav>
    </x-slot:header>
    <div
        class="bg-white border-2 border-orange-100 p-12 pt-16 rounded-lg space-y-6"
    >
        <h1 class="text-4xl font-bold text-gray-900">Logg inn</h1>
        <p class="text-gray-600 text-lg font-thin">
            Se relevant informasjon knyttet til deg og din sykkel, eller for å
            bestille time for hjelp og service på vårt verksted.
        </p>

        <form action="/auth/send-otp" method="POST" class="space-y-4">
            @csrf

            <!-- Error Messages -->
            @if($errors->any())
            <div
                class="p-4 bg-red-50 border-2 border-red-200 rounded-lg text-red-800 font-medium"
            >
                @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <fieldset class="space-y-2">
                <label for="phone" class="block font-normal text-gray-900"
                    >Hva er telefonnummeret ditt?</label
                >
                <input
                    type="tel"
                    name="phone"
                    id="phone"
                    required
                    placeholder="93678901"
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-orange-600 font-medium text-lg"
                />
            </fieldset>

            <button
                type="submit"
                class="w-full px-6 py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-lg font-bold transition-colors"
            >
                Send Verification Code
            </button>
        </form>
    </div>
</x-layout>
