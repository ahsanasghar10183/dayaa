<x-guest-layout>
    <x-slot name="title">Confirm Password</x-slot>

    <x-slot name="leftTitle">
        Secure Area Access
    </x-slot>

    <x-slot name="leftDescription">
        Please confirm your password to continue. This helps keep your account secure.
    </x-slot>

    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Confirm Password</h2>
        <p class="mt-2 text-sm text-gray-600">
            Please verify your password to continue
        </p>
    </div>

    <!-- Confirm Form -->
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autofocus
                autocomplete="current-password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror"
                placeholder="Enter your password"
            />
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
        >
            Confirm
        </button>

        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <div class="text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700">
                    Forgot your password?
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
