<x-organization-sidebar-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">SumUp Integration</h1>
            <p class="mt-2 text-gray-600">Connect your SumUp account to accept card donations through the SumUp Solo card reader.</p>
        </div>

        @if(session('completed'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6">
                <p class="text-sm text-green-700">{{ session('completed') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Connection Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Connection status</h3>
                    @if($organization->isSumUpConnected())
                        <div class="mt-3 flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Connected
                            </span>
                            <span class="text-sm text-gray-600">
                                Merchant <strong>{{ $organization->sumup_merchant_code }}</strong>
                                @if($organization->sumup_merchant_name) &mdash; {{ $organization->sumup_merchant_name }} @endif
                            </span>
                        </div>
                        @if($organization->sumup_connected_at)
                            <p class="mt-2 text-xs text-gray-500">Last verified: {{ $organization->sumup_connected_at->diffForHumans() }}</p>
                        @endif
                    @elseif($organization->sumup_connection_status === 'invalid')
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Invalid key</span>
                            <p class="mt-2 text-sm text-gray-600">Your saved API key no longer works. Please re-enter it below.</p>
                        </div>
                    @else
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Not connected</span>
                            <p class="mt-2 text-sm text-gray-600">You haven't connected a SumUp account yet.</p>
                        </div>
                    @endif
                </div>

                @if($organization->isSumUpConnected())
                    <div class="flex items-center gap-2">
                        <a href="{{ route('organization.sumup.readers.index') }}" class="px-4 py-2 text-sm font-semibold text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100">Manage readers</a>
                        <form method="POST" action="{{ route('organization.sumup.test') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Test connection</button>
                        </form>
                        <form method="POST" action="{{ route('organization.sumup.destroy') }}" onsubmit="return confirm('Disconnect SumUp? You will not be able to accept card donations until you reconnect.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100">Disconnect</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- How to get your API key -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">How to get your SumUp API key</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-blue-900">
                <li>Sign in to <a href="https://me.sumup.com" target="_blank" class="underline font-semibold">me.sumup.com</a> with your SumUp account.</li>
                <li>Click your account name (top-right) &rarr; <strong>Settings</strong>.</li>
                <li>Scroll down to <strong>For developers</strong> and click <strong>Toolkit</strong>.</li>
                <li>In the developer portal, go to <strong>Account &rarr; API Keys</strong> and click <strong>Create</strong>.</li>
                <li>Give the key a name (e.g. "Dayaa Integration"), then <strong>copy the secret key (starts with <code>sup_sk_</code>)</strong> &mdash; it is only shown once.</li>
                <li>Paste it into the form below and save.</li>
            </ol>
            <p class="mt-3 text-xs text-blue-800">Your API key is stored encrypted and only used to process donations on your behalf.</p>
        </div>

        <!-- Connect / Update form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $organization->isSumUpConnected() ? 'Replace API key' : 'Connect SumUp account' }}
            </h3>

            <form method="POST" action="{{ route('organization.sumup.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="sumup_api_key" class="block text-sm font-medium text-gray-700 mb-2">SumUp API Key *</label>
                    <input type="password"
                           name="sumup_api_key"
                           id="sumup_api_key"
                           required
                           autocomplete="off"
                           minlength="20"
                           maxlength="255"
                           pattern="sup_sk_[A-Za-z0-9]+"
                           value="{{ old('sumup_api_key') }}"
                           placeholder="sup_sk_..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-mono text-sm @error('sumup_api_key') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Starts with <code>sup_sk_</code>. We will verify the key with SumUp before saving it.</p>
                    @error('sumup_api_key')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ open: {{ old('sumup_merchant_code') ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="text-xs font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="open ? 'rotate-90' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Advanced: manual merchant code
                    </button>
                    <div x-show="open" x-cloak class="mt-3">
                        <label for="sumup_merchant_code" class="block text-sm font-medium text-gray-700 mb-2">Merchant code (manual)</label>
                        <input type="text"
                               name="sumup_merchant_code"
                               id="sumup_merchant_code"
                               autocomplete="off"
                               maxlength="32"
                               pattern="M[A-Za-z0-9]+"
                               value="{{ old('sumup_merchant_code') }}"
                               placeholder="M........"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-mono text-sm @error('sumup_merchant_code') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Only fill this if you see "could not reach SumUp" after pressing Connect. Find it top-right at <a href="https://me.sumup.com" target="_blank" class="underline">me.sumup.com</a> (starts with <code>M</code>).</p>
                        @error('sumup_merchant_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="btn-primary px-8">
                        {{ $organization->isSumUpConnected() ? 'Replace key' : 'Verify & connect' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-organization-sidebar-layout>
