<x-organization-sidebar-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('admin.organization.edit_profile') }}</h1>
                <p class="mt-2 text-gray-600">{{ __('admin.organization.profile_edit_page.subtitle') }}</p>
            </div>
            <a href="{{ route('organization.profile.show') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('admin.organization.profile_edit_page.back_to_profile') }}
            </a>
        </div>

        <!-- Edit Form -->
        <form method="POST" action="{{ route('organization.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('admin.organization.profile_edit_page.basic_information') }}</h3>

                <div class="space-y-6">
                    <!-- Organization Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.organization_name_required') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $organization->name) }}" required minlength="2" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.description') }}</label>
                        <textarea name="description" id="description" rows="4" maxlength="2000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_edit_page.description_placeholder') }}">{{ old('description', $organization->description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.description_char_hint') }}</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.organization_logo') }}</label>
                        <div class="flex items-center space-x-4">
                            @if($organization->logo)
                            <img src="{{ Storage::url($organization->logo) }}" alt="Current logo" class="w-20 h-20 rounded-lg object-cover border border-gray-300">
                            @else
                            <div class="w-20 h-20 bg-gradient-primary rounded-lg flex items-center justify-center text-white font-bold text-2xl">
                                {{ substr($organization->name, 0, 1) }}
                            </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="logo" id="logo" accept=".jpg,.jpeg,.png,.svg,.webp,image/jpeg,image/png,image/svg+xml,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.logo_hint') }}</p>
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @if($organization->logo)
                        <label class="mt-2 flex items-center">
                            <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-600">{{ __('admin.organization.profile_edit_page.remove_current_logo') }}</span>
                        </label>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('admin.super_admin.contact_information') }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.contact_person_required') }}</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $organization->contact_person) }}" required minlength="2" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('contact_person') border-red-500 @enderror">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.phone_required') }}</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $organization->phone) }}" required inputmode="tel" minlength="7" maxlength="25" pattern="[\+]?[0-9\s\-\(\)\.\/]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-red-500 @enderror" placeholder="+49 30 12345678">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.phone_hint') }}</p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div class="md:col-span-2">
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.website') }}</label>
                        <input type="text" name="website" id="website" value="{{ old('website', $organization->website) }}" inputmode="url" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('website') border-red-500 @enderror" placeholder="example.com or https://example.com">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.website_hint') }}</p>
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.address_required') }}</label>
                        <textarea name="address" id="address" rows="3" required minlength="10" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('address') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_edit_page.address_placeholder') }}">{{ old('address', $organization->address) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.address_hint') }}</p>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Legal Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('admin.super_admin.legal_information') }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Charity Number -->
                    <div>
                        <label for="charity_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.charity_number') }}</label>
                        <input type="text" name="charity_number" id="charity_number" value="{{ old('charity_number', $organization->charity_number) }}" maxlength="50" pattern="[A-Za-z0-9\-/\s]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('charity_number') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_edit_page.charity_number_placeholder') }}">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.charity_number_hint') }}</p>
                        @error('charity_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tax ID -->
                    <div>
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.tax_id') }}</label>
                        <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $organization->tax_id) }}" maxlength="30" pattern="[A-Za-z0-9\-/\s]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('tax_id') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_edit_page.tax_id_placeholder') }}">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.tax_id_hint') }}</p>
                        @error('tax_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Banking Information Card (for active organizations) -->
            @if($organization->status == 'active')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('admin.organization.profile_edit_page.banking_information') }}</h3>

                <div>
                    <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.bank_account_iban') }}</label>
                    <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $organization->bank_account) }}" maxlength="42" pattern="[A-Za-z]{2}[0-9]{2}[A-Za-z0-9\s]{11,38}" autocomplete="off" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase @error('bank_account') border-red-500 @enderror" placeholder="DE89 3704 0044 0532 0130 00">
                    <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.bank_account_iban_hint') }}</p>
                    @error('bank_account')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('organization.profile.show') }}" class="btn-secondary">{{ __('admin.common.cancel') }}</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('admin.organization.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</x-organization-sidebar-layout>
