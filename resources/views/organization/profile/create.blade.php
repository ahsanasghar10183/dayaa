<x-organization-sidebar-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('admin.organization.profile_create_page.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ __('admin.organization.profile_create_page.subtitle') }}</p>
        </div>

        <!-- Welcome Message -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        {{ __('admin.organization.profile_create_page.welcome_message') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Create Form -->
        <form method="POST" action="{{ route('organization.profile.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('admin.organization.profile_edit_page.basic_information') }}</h3>

                <div class="space-y-6">
                    <!-- Organization Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.organization_name_required') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required minlength="2" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_create_page.name_placeholder') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.description') }}</label>
                        <textarea name="description" id="description" rows="4" maxlength="2000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_create_page.description_placeholder') }}">{{ old('description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.description_char_hint') }}</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.organization_logo') }}</label>
                        <input type="file" name="logo" id="logo" accept=".jpg,.jpeg,.png,.svg,.webp,image/jpeg,image/png,image/svg+xml,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_create_page.logo_hint_optional') }}</p>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" required minlength="2" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('contact_person') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_create_page.contact_person_placeholder') }}">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.phone_required') }}</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required inputmode="tel" minlength="7" maxlength="25" pattern="[\+]?[0-9\s\-\(\)\.\/]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-red-500 @enderror" placeholder="+49 30 12345678">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.phone_hint') }}</p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div class="md:col-span-2">
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.website') }}</label>
                        <input type="text" name="website" id="website" value="{{ old('website') }}" inputmode="url" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('website') border-red-500 @enderror" placeholder="example.com or https://example.com">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.website_hint') }}</p>
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.profile_edit_page.address_required') }}</label>
                        <textarea name="address" id="address" rows="3" required minlength="10" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('address') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_edit_page.address_placeholder') }}">{{ old('address') }}</textarea>
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
                        <input type="text" name="charity_number" id="charity_number" value="{{ old('charity_number') }}" maxlength="50" pattern="[A-Za-z0-9\-/\s]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('charity_number') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_edit_page.charity_number_placeholder') }}">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.charity_number_hint') }}</p>
                        @error('charity_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tax ID -->
                    <div>
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.organization.tax_id') }}</label>
                        <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}" maxlength="30" pattern="[A-Za-z0-9\-/\s]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('tax_id') border-red-500 @enderror" placeholder="{{ __('admin.organization.profile_edit_page.tax_id_placeholder') }}">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_edit_page.tax_id_hint') }}</p>
                        @error('tax_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Verification Documents Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ __('admin.organization.profile_create_page.verification_documents_optional') }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ __('admin.organization.profile_create_page.verification_documents_hint') }}</p>

                <input type="file" name="verification_documents[]" id="verification_documents" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                <p class="mt-1 text-xs text-gray-500">{{ __('admin.organization.profile_create_page.verification_documents_file_hint') }}</p>
                @error('verification_documents')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('verification_documents.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Important Notice -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>{{ __('admin.shop.note_label') }}</strong> {{ __('admin.organization.profile_create_page.review_notice_text') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <button type="submit" class="btn-primary px-8">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('admin.organization.profile_create_page.submit_for_review') }}
                </button>
            </div>
        </form>
    </div>
</x-organization-sidebar-layout>
