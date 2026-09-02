<x-organization-sidebar-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('admin.organization.sumup_connect.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('admin.organization.sumup_connect.subtitle') }}</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.organization.sumup_connect.connection_status') }}</h3>
                    @if($organization->isSumUpConnected())
                        <div class="mt-3 flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('admin.organization.sumup_connect.connected') }}
                            </span>
                            <span class="text-sm text-gray-600">
                                {{ __('admin.organization.sumup_connect.merchant_prefix') }} <strong>{{ $organization->sumup_merchant_code }}</strong>
                                @if($organization->sumup_merchant_name) &mdash; {{ $organization->sumup_merchant_name }} @endif
                            </span>
                        </div>
                        @if($organization->sumup_connected_at)
                            <p class="mt-2 text-xs text-gray-500">{{ __('admin.organization.sumup_connect.last_verified_prefix') }} {{ $organization->sumup_connected_at->diffForHumans() }}</p>
                        @endif
                    @elseif($organization->sumup_connection_status === 'invalid')
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">{{ __('admin.organization.sumup_connect.invalid_key') }}</span>
                            <p class="mt-2 text-sm text-gray-600">{{ __('admin.organization.sumup_connect.invalid_key_text') }}</p>
                        </div>
                    @else
                        <div class="mt-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">{{ __('admin.organization.sumup_connect.not_connected') }}</span>
                            <p class="mt-2 text-sm text-gray-600">{{ __('admin.organization.sumup_connect.not_connected_text') }}</p>
                        </div>
                    @endif
                </div>

                @if($organization->isSumUpConnected())
                    <div class="flex items-center gap-2">
                        <a href="{{ route('organization.sumup.readers.index') }}" class="px-4 py-2 text-sm font-semibold text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100">{{ __('admin.organization.sumup_connect.manage_readers') }}</a>
                        <form method="POST" action="{{ route('organization.sumup.test') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">{{ __('admin.organization.sumup_connect.test_connection') }}</button>
                        </form>
                        <form method="POST" action="{{ route('organization.sumup.destroy') }}" onsubmit="return confirm(@json(__('admin.organization.sumup_connect.disconnect_confirm')));">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100">{{ __('admin.organization.sumup_connect.disconnect') }}</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- How to get your API key -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">{{ __('admin.organization.sumup_connect.how_to_get_key_title') }}</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-blue-900">
                <li>{!! __('admin.organization.sumup_connect.step1') !!}</li>
                <li>{!! __('admin.organization.sumup_connect.step2') !!}</li>
                <li>{!! __('admin.organization.sumup_connect.step3') !!}</li>
                <li>{!! __('admin.organization.sumup_connect.step4') !!}</li>
                <li>{!! __('admin.organization.sumup_connect.step5') !!}</li>
                <li>{{ __('admin.organization.sumup_connect.step6') }}</li>
            </ol>
            <p class="mt-3 text-xs text-blue-800">{{ __('admin.organization.sumup_connect.key_storage_note') }}</p>
        </div>

        <!-- Connect / Update form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $organization->isSumUpConnected() ? __('admin.organization.sumup_connect.replace_api_key') : __('admin.organization.sumup_connect.connect_sumup_account') }}
            </h3>

            <form method="POST" action="{{ route('organization.sumup.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="sumup_api_key" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.sumup_connect.api_key_label') }}</label>
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
                    <p class="mt-1 text-xs text-gray-500">{!! __('admin.organization.sumup_connect.api_key_hint') !!}</p>
                    @error('sumup_api_key')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ open: {{ old('sumup_merchant_code') ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="text-xs font-semibold text-gray-600 hover:text-gray-900 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="open ? 'rotate-90' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        {{ __('admin.organization.sumup_connect.advanced_manual_merchant') }}
                    </button>
                    <div x-show="open" x-cloak class="mt-3">
                        <label for="sumup_merchant_code" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.sumup_connect.merchant_code_manual_label') }}</label>
                        <input type="text"
                               name="sumup_merchant_code"
                               id="sumup_merchant_code"
                               autocomplete="off"
                               maxlength="32"
                               pattern="M[A-Za-z0-9]+"
                               value="{{ old('sumup_merchant_code') }}"
                               placeholder="M........"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent font-mono text-sm @error('sumup_merchant_code') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">{!! __('admin.organization.sumup_connect.merchant_code_hint') !!}</p>
                        @error('sumup_merchant_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="btn-primary px-8">
                        {{ $organization->isSumUpConnected() ? __('admin.organization.sumup_connect.replace_key_button') : __('admin.organization.sumup_connect.verify_connect_button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-organization-sidebar-layout>
