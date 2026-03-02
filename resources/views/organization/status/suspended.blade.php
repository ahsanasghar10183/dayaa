<x-organization-sidebar-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-12">
            <!-- Suspended Icon -->
            <div class="flex justify-center mb-8">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>

            <!-- Status Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Account Suspended</h1>
                <p class="text-lg text-gray-600">Your organization account has been temporarily suspended.</p>
            </div>

            <!-- Suspension Reason Card -->
            @if($organization && $organization->suspension_reason)
            <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-lg mb-6">
                <div class="flex">
                    <svg class="h-6 w-6 text-orange-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-orange-800 mb-2">Reason for Suspension</h3>
                        <p class="text-sm text-orange-700">{{ $organization->suspension_reason }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Important Notice -->
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-6">
                <div class="flex">
                    <svg class="h-6 w-6 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Account Restrictions</h3>
                        <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                            <li>You cannot create or edit campaigns</li>
                            <li>You cannot receive new donations</li>
                            <li>Devices are deactivated</li>
                            <li>Public profile is hidden</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Resolution Steps Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">How to resolve this</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">1</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Review the Suspension Reason</h3>
                            <p class="text-sm text-gray-600 mt-1">Carefully read the reason for suspension provided above.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">2</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Contact Support</h3>
                            <p class="text-sm text-gray-600 mt-1">Reach out to our support team to discuss the suspension and resolution steps.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">3</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Address the Issues</h3>
                            <p class="text-sm text-gray-600 mt-1">Take the necessary actions to address the concerns that led to the suspension.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">4</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Request Reactivation</h3>
                            <p class="text-sm text-gray-600 mt-1">Once issues are resolved, request account reactivation from support.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="mailto:support@dayaa.com?subject=Account Suspension - {{ $organization->name ?? 'Organization' }}" class="w-full btn-primary text-center block">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Support Team
                    </a>
                </div>
            </div>

            <!-- Organization Details Card -->
            @if($organization)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Organization Details</h2>
                    <span class="badge-error">Suspended</span>
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
                        <label class="text-xs text-gray-500">Suspended On</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Data Access Card -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                <div class="flex">
                    <svg class="h-6 w-6 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-blue-800 mb-2">Your Data is Safe</h3>
                        <p class="text-sm text-blue-700">While your account is suspended, all your data including past donations, campaigns, and reports remain accessible in read-only mode. No data will be lost.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">How long will my account be suspended?</h3>
                        <p class="text-sm text-gray-600">The suspension duration depends on the reason and how quickly the issues are resolved. Contact support for specific details.</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Can I access my donation history?</h3>
                        <p class="text-sm text-gray-600">Yes, you can view your historical data including donations and reports, but you cannot make changes or receive new donations.</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">What happens to active campaigns?</h3>
                        <p class="text-sm text-gray-600">Active campaigns are paused during suspension. They will not accept new donations until your account is reactivated.</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">How do I appeal the suspension?</h3>
                        <p class="text-sm text-gray-600">Contact our support team to discuss your suspension and provide any additional information that may help resolve the issue.</p>
                    </div>
                </div>
            </div>

            <!-- Support Section -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    <strong>Need immediate assistance?</strong>
                    <a href="mailto:support@dayaa.com" class="text-primary-600 hover:text-primary-700 font-medium">Email our support team</a>
                </p>
            </div>
        </div>
    </div>
</x-organization-sidebar-layout>
