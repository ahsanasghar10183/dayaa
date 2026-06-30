<x-organization-sidebar-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                <p class="mt-2 text-gray-600">Update your organization information</p>
            </div>
            <a href="{{ route('organization.profile.show') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Profile
            </a>
        </div>

        <!-- Edit Form -->
        <form method="POST" action="{{ route('organization.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>

                <div class="space-y-6">
                    <!-- Organization Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Organization Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $organization->name) }}" required minlength="2" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" maxlength="2000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('description') border-red-500 @enderror" placeholder="Tell us about your organization...">{{ old('description', $organization->description) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Up to 2000 characters (optional)</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Organization Logo</label>
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
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG, SVG or WEBP. Max 5MB, max 4000×4000 px</p>
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @if($organization->logo)
                        <label class="mt-2 flex items-center">
                            <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-600">Remove current logo</span>
                        </label>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Contact Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person *</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $organization->contact_person) }}" required minlength="2" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('contact_person') border-red-500 @enderror">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $organization->phone) }}" required inputmode="tel" minlength="7" maxlength="25" pattern="[\+]?[0-9\s\-\(\)\.\/]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-red-500 @enderror" placeholder="+49 30 12345678">
                        <p class="mt-1 text-xs text-gray-500">Digits, +, spaces, ( ), - and . are allowed</p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div class="md:col-span-2">
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="text" name="website" id="website" value="{{ old('website', $organization->website) }}" inputmode="url" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('website') border-red-500 @enderror" placeholder="example.com or https://example.com">
                        <p class="mt-1 text-xs text-gray-500">If you omit https://, we'll add it automatically</p>
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="address" id="address" rows="3" required minlength="10" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('address') border-red-500 @enderror" placeholder="Street, City, Postal Code, Country">{{ old('address', $organization->address) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Include street, city, postal code and country</p>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Legal Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Legal Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Charity Number -->
                    <div>
                        <label for="charity_number" class="block text-sm font-medium text-gray-700 mb-2">Charity Number</label>
                        <input type="text" name="charity_number" id="charity_number" value="{{ old('charity_number', $organization->charity_number) }}" maxlength="50" pattern="[A-Za-z0-9\-/\s]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('charity_number') border-red-500 @enderror" placeholder="e.g., CHY1234">
                        <p class="mt-1 text-xs text-gray-500">Letters, digits, hyphens or slashes (optional)</p>
                        @error('charity_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tax ID -->
                    <div>
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax ID</label>
                        <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $organization->tax_id) }}" maxlength="30" pattern="[A-Za-z0-9\-/\s]+" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('tax_id') border-red-500 @enderror" placeholder="e.g., DE123456789">
                        <p class="mt-1 text-xs text-gray-500">German VAT: DE + 9 digits (optional)</p>
                        @error('tax_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Banking Information Card (for active organizations) -->
            @if($organization->status == 'active')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Banking Information</h3>

                <div>
                    <label for="bank_account" class="block text-sm font-medium text-gray-700 mb-2">Bank Account (IBAN)</label>
                    <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $organization->bank_account) }}" maxlength="42" pattern="[A-Za-z]{2}[0-9]{2}[A-Za-z0-9\s]{11,38}" autocomplete="off" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase @error('bank_account') border-red-500 @enderror" placeholder="DE89 3704 0044 0532 0130 00">
                    <p class="mt-1 text-xs text-gray-500">IBAN format (e.g., German IBAN: DE + 20 digits). Spaces will be removed automatically. Required to receive donations.</p>
                    @error('bank_account')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('organization.profile.show') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-organization-sidebar-layout>
