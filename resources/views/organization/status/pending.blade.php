<x-organization-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-12">
            <!-- Pending Icon -->
            <div class="flex justify-center mb-8">
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Status Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Application Under Review</h1>
                <p class="text-lg text-gray-600 mb-2">Thank you for registering with Dayaa!</p>
                <p class="text-gray-600">Your organization profile is currently being reviewed by our team.</p>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">What happens next?</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">1</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Review Process</h3>
                            <p class="text-sm text-gray-600 mt-1">Our team will review your organization details and verification documents (if provided).</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">2</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Notification</h3>
                            <p class="text-sm text-gray-600 mt-1">You'll receive an email notification once the review is complete.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                            <span class="text-primary-700 font-semibold text-sm">3</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Get Started</h3>
                            <p class="text-sm text-gray-600 mt-1">Once approved, you can start creating campaigns and receiving donations.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organization Details Card -->
            @if($organization)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Your Organization Details</h2>
                    <span class="badge-warning">Pending Review</span>
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
                        <label class="text-xs text-gray-500">Submitted On</label>
                        <p class="text-sm font-medium text-gray-900">{{ $organization->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('organization.profile.edit') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Edit Profile →
                    </a>
                </div>
            </div>
            @endif

            <!-- Timeline Card -->
            <div class="bg-gradient-primary rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Review Timeline</h3>
                <div class="flex items-center justify-between">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-xs opacity-90">Submitted</p>
                    </div>
                    <div class="flex-1 h-1 bg-white bg-opacity-20 mx-2"></div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-2 animate-pulse">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-xs opacity-90">In Review</p>
                    </div>
                    <div class="flex-1 h-1 bg-white bg-opacity-20 mx-2"></div>
                    <div class="text-center opacity-50">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-xs">Approved</p>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-sm opacity-90">Typical review time: 1-3 business days</p>
                </div>
            </div>

            <!-- Support Section -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Have questions about your application?
                    <a href="mailto:support@dayaa.com" class="text-primary-600 hover:text-primary-700 font-medium">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</x-organization-layout>
