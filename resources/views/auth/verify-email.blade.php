<x-guest-layout>
    <x-slot name="title">Verify Email</x-slot>

    <x-slot name="leftTitle">
        Verify Your Email
    </x-slot>

    <x-slot name="leftDescription">
        Click the link in the email we sent you to activate your account and start accepting donations.
    </x-slot>

    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Check Your Email</h2>
        <p class="mt-2 text-sm text-gray-600">
            We've sent a verification link to your inbox
        </p>
    </div>

    <!-- Info Message -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm text-blue-800">
            Thanks for signing up! Please click on the link we emailed you to verify your address. If you didn't receive the email, we will gladly send you another.
        </p>
    </div>

    <!-- Success Status -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">
                A new verification link has been sent to your email address.
            </p>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="space-y-4">
        <!-- Resend Verification Email -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200"
            >
                Resend Verification Email
            </button>
        </form>

        <!-- Log Out -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full bg-white border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition-colors duration-200"
            >
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
