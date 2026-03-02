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
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Application Not Approved</h1>
                <p class="text-lg text-gray-600">We're sorry, but your organization application was not approved at this time.</p>
            </div>

            <!-- Rejection Reason Card -->
            @if($organization && $organization->rejection_reason)
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-6">
                <div class="flex">
                    <svg class="h-6 w-6 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Reason for Rejection</h3>
                        <p class="text-sm text-red-700">{{ $organization->rejection_reason }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Next Steps Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">What you can do next</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">1</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Review the Rejection Reason</h3>
                            <p class="text-sm text-gray-600 mt-1">Carefully read the feedback provided above to understand why your application was not approved.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">2</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Update Your Information</h3>
                            <p class="text-sm text-gray-600 mt-1">Address the concerns mentioned in the rejection reason by updating your organization profile.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">3</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Resubmit Your Application</h3>
                            <p class="text-sm text-gray-600 mt-1">Once you've made the necessary changes, contact support to request a re-review.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('organization.profile.edit') }}" class="flex-1 btn-primary text-center">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </a>
                    <a href="mailto:support@dayaa.com" class="flex-1 btn-secondary text-center">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>

            <!-- Organization Details Card -->
            @if($organization)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Your Organization Details</h2>
                    <span class="badge-error">Rejected</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500">Organization Name</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Contact Person</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->contact_person }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Phone</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->phone }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Rejected On</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- FAQ Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Can I reapply?</h3>
                        <p class="text-sm text-gray-600">Yes, you can update your profile information and contact support to request a re-review of your application.</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">How long does re-review take?</h3>
                        <p class="text-sm text-gray-600">Re-reviews typically take 1-3 business days once you've submitted your updated information.</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">What documents should I provide?</h3>
                        <p class="text-sm text-gray-600">Provide official documents that verify your organization's legal status, such as registration certificates, tax exemption letters, or charity commission documents.</p>
                    </div>
                </div>
            </div>

            <!-- Support Section -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Need help with your application?
                    <a href="mailto:support@dayaa.com" class="text-primary-600 hover:text-primary-700 font-medium">Contact our support team</a>
                </p>
            </div>
        </div>
    </div>
</x-organization-sidebar-layout>
