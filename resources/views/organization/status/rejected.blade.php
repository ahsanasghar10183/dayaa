<x-organization-sidebar-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-12">
            <!-- Rejected Icon -->
            <div class="flex justify-center mb-8">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>

            <!-- Status Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('admin.organization.rejected_page.title') }}</h1>
                <p class="text-lg text-gray-600">{{ __('admin.organization.rejected_page.subtitle') }}</p>
            </div>

            <!-- Rejection Reason Card -->
            @if($organization && $organization->rejection_reason)
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-6">
                <div class="flex">
                    <svg class="h-6 w-6 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">{{ __('admin.organization.rejected_page.rejection_reason_title') }}</h3>
                        <p class="text-sm text-red-700">{{ $organization->rejection_reason }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Next Steps Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.organization.rejected_page.next_steps_title') }}</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">1</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">{{ __('admin.organization.rejected_page.step1_title') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('admin.organization.rejected_page.step1_text') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">2</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">{{ __('admin.organization.rejected_page.step2_title') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('admin.organization.rejected_page.step2_text') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">3</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">{{ __('admin.organization.rejected_page.step3_title') }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ __('admin.organization.rejected_page.step3_text') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('organization.profile.edit') }}" class="flex-1 btn-primary text-center">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.organization.rejected_page.edit_profile_button') }}
                    </a>
                    <a href="mailto:support@dayaa.com" class="flex-1 btn-secondary text-center">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('admin.organization.rejected_page.contact_support_button') }}
                    </a>
                </div>
            </div>

            <!-- Organization Details Card -->
            @if($organization)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('admin.organization.rejected_page.details_title') }}</h2>
                    <span class="badge-error">{{ __('admin.organization.rejected_page.badge') }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500">{{ __('admin.organization.organization_name') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('admin.organization.contact_person') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->contact_person }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('admin.organization.phone') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->phone }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">{{ __('admin.organization.rejected_page.rejected_on') }}</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- FAQ Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.organization.rejected_page.faq_title') }}</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">{{ __('admin.organization.rejected_page.faq1_q') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('admin.organization.rejected_page.faq1_a') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">{{ __('admin.organization.rejected_page.faq2_q') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('admin.organization.rejected_page.faq2_a') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">{{ __('admin.organization.rejected_page.faq3_q') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('admin.organization.rejected_page.faq3_a') }}</p>
                    </div>
                </div>
            </div>

            <!-- Support Section -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    {{ __('admin.organization.rejected_page.support_question') }}
                    <a href="mailto:support@dayaa.com" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('admin.organization.rejected_page.contact_support_team') }}</a>
                </p>
            </div>
        </div>
    </div>
</x-organization-sidebar-layout>
