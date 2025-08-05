<x-layout title="Verify Code">
    <x-slot:header>
        <nav class="flex items-center justify-end gap-4">
            <a
                href="{{ url('/') }}"
                class="inline-block px-4 py-2 text-gray-700 hover:text-orange-600 text-sm font-medium transition-colors"
            >
                whee.no
            </a>
            <a
                href="{{ route('login') }}"
                class="inline-block px-4 py-2 text-gray-700 hover:text-orange-600 text-sm font-medium transition-colors"
            >
                ← Tilbake til logg inn
            </a>
        </nav>
    </x-slot:header>
    <div
        class="bg-white border-2 border-orange-100 p-12 pt-16 rounded-lg space-y-6"
    >
        <h1 class="text-4xl font-bold text-gray-900">Logg inn</h1>

        <form action="/auth/verify-otp" method="POST" class="space-y-4">
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
                <label for="otp" class="block font-normal text-gray-900"
                    >Legg inn koden du fikk sendt til
                    <span class="font-bold text-gray-900">
                        {{ session("phone_for_verification") }}
                    </span>
                </label>

                <input
                    type="text"
                    name="otp"
                    id="otp"
                    required
                    placeholder="123456"
                    maxlength="6"
                    value="{{ old('otp') }}"
                    class="w-full px-4 py-4 border-2 border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-orange-600 text-2xl tracking-widest font-bold"
                />
            </fieldset>

            <button
                type="submit"
                class="w-full px-6 py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-lg font-bold transition-colors"
            >
                Logg inn
            </button>

            <p class="text-sm text-gray-600">
                Fikk du ikke koden?
                <a
                    href="{{ route('login') }}"
                    class="underline hover:text-orange-600"
                >
                    Prøv igjen
                </a>
            </p>
        </form>
    </div>

    @if(!session('phone_for_verification'))
    <script>
        // If no phone number in session, redirect to login
        window.location.href = "/auth/login";
    </script>
    @endif
</x-layout>
