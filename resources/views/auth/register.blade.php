<x-guest-layout>
    <x-slot name="title">{{ __('auth.register.title') }}</x-slot>

    <x-slot name="leftTitle">
        {{ __('auth.register.title') }}
    </x-slot>

    <x-slot name="leftDescription">
        {{ __('auth.register.subtitle') }}
    </x-slot>

    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">{{ __('auth.register.title') }}</h2>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('auth.register.already_have_account') }}
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                {{ __('auth.register.sign_in') }}
            </a>
        </p>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Organization Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.organization_name') }}
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="organization"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                placeholder="{{ __('auth.register.organization_name_placeholder') }}"
            />
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.email') }}
            </label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                placeholder="{{ __('auth.register.email_placeholder') }}"
            />
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.password') }}
            </label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="new-password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror"
                placeholder="{{ __('auth.register.password_placeholder') }}"
            />
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('auth.register.confirm_password') }}
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="{{ __('auth.register.confirm_password_placeholder') }}"
            />
        </div>

        <!-- Terms & Conditions -->
        <div class="flex items-start">
            <input
                id="terms"
                name="terms"
                type="checkbox"
                required
                class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
            />
            <label for="terms" class="ml-2 text-sm text-gray-700">
                {{ __('auth.register.terms') }}
                <a href="#" class="text-blue-600 hover:text-blue-700">{{ __('auth.register.terms_and_conditions') }}</a>
                {{ __('auth.register.and') }}
                <a href="#" class="text-blue-600 hover:text-blue-700">{{ __('auth.register.privacy_policy') }}</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
        >
            {{ __('auth.register.submit') }}
        </button>
    </form>
</x-guest-layout>
