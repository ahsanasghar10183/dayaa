<x-organization-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Organization Profile</h1>
                <p class="mt-2 text-gray-600">View and manage your organization information</p>
            </div>
            <a href="{{ route('organization.profile.edit') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Profile
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Organization Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-primary">
                        <div class="flex items-center">
                            @if($organization->logo)
                            <img src="{{ Storage::url($organization->logo) }}" alt="{{ $organization->name }}" class="w-20 h-20 rounded-xl object-cover border-4 border-white shadow-lg">
                            @else
                            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-xl flex items-center justify-center text-white font-bold text-3xl border-4 border-white shadow-lg">
                                {{ substr($organization->name, 0, 1) }}
                            </div>
                            @endif
                            <div class="ml-5 text-white">
                                <h2 class="text-2xl font-bold">{{ $organization->name }}</h2>
                                <p class="text-sm opacity-90 mt-1">{{ $organization->contact_person }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- About Section -->
                        @if($organization->description)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">About</h3>
                            <p class="text-gray-600">{{ $organization->description }}</p>
                        </div>
                        @endif

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500">Contact Person</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->contact_person }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Email</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Phone</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->phone }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Website</label>
                                    @if($organization->website)
                                    <a href="{{ $organization->website }}" target="_blank" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                                        {{ $organization->website }}
                                    </a>
                                    @else
                                    <p class="text-sm font-medium text-gray-400">Not provided</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Address</h3>
                            <p class="text-sm text-gray-900">{{ $organization->address }}</p>
                        </div>

                        <!-- Legal Information -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Legal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500">Charity Number</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->charity_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Tax ID</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->tax_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information (if active) -->
                        @if($organization->status == 'active')
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Bank Account:</strong> {{ $organization->bank_account ?? 'Not configured' }}
                                    </p>
                                    @if(!$organization->bank_account)
                                    <p class="text-xs text-blue-600 mt-1">Please add your bank account details to receive donations</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Status</h3>

                    <div class="mb-4">
                        @if($organization->status == 'active')
                            <span class="badge-success text-lg">Active</span>
                        @elseif($organization->status == 'pending')
                            <span class="badge-warning text-lg">Pending Approval</span>
                        @elseif($organization->status == 'suspended')
                            <span class="badge-error text-lg">Suspended</span>
                        @else
                            <span class="badge-gray text-lg">{{ ucfirst($organization->status) }}</span>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500 space-y-1">
                        <p>Registered: {{ $organization->created_at->format('M d, Y') }}</p>
                        @if($organization->approved_at)
                        <p>Approved: {{ $organization->approved_at->format('M d, Y') }}</p>
                        @endif
                    </div>

                    @if($organization->status == 'pending')
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-yellow-800">Your organization is pending approval. You'll be notified once it's reviewed.</p>
                    </div>
                    @elseif($organization->status == 'rejected')
                    <div class="mt-4 p-3 bg-red-50 rounded-lg">
                        <p class="text-xs text-red-800 font-medium mb-1">Rejection Reason:</p>
                        <p class="text-xs text-red-700">{{ $organization->rejection_reason }}</p>
                    </div>
                    @elseif($organization->status == 'suspended')
                    <div class="mt-4 p-3 bg-red-50 rounded-lg">
                        <p class="text-xs text-red-800 font-medium mb-1">Suspension Reason:</p>
                        <p class="text-xs text-red-700">{{ $organization->suspension_reason }}</p>
                    </div>
                    @endif
                </div>

                <!-- Verification Documents -->
                @if($organization->verification_documents)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Verification Documents</h3>
                    <div class="space-y-2">
                        @foreach($organization->verification_documents as $document)
                        <a href="{{ Storage::url($document) }}" target="_blank" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Document {{ $loop->iteration }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Statistics -->
                <div class="bg-gradient-primary rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Quick Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm opacity-90">Campaigns</span>
                            <span class="text-xl font-bold">{{ $stats['total_campaigns'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm opacity-90">Devices</span>
                            <span class="text-xl font-bold">{{ $stats['total_devices'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm opacity-90">Donations</span>
                            <span class="text-xl font-bold">{{ $stats['total_donations'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-organization-layout>
