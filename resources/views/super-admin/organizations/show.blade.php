<x-super-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('super-admin.organizations.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Organizations
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Organization Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-primary">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center text-white font-bold text-2xl">
                                {{ substr($organization->name, 0, 1) }}
                            </div>
                            <div class="ml-4 text-white">
                                <h1 class="text-2xl font-bold">{{ $organization->name }}</h1>
                                <p class="text-sm opacity-90">{{ $organization->contact_person }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Description -->
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
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->website ?? 'N/A' }}</p>
                                </div>
                            </div>
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
                                <div class="md:col-span-2">
                                    <label class="text-xs text-gray-500">Address</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Reason -->
                        @if($organization->status == 'rejected' && $organization->rejection_reason)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <h3 class="text-sm font-semibold text-red-800 mb-2">Rejection Reason</h3>
                            <p class="text-sm text-red-700">{{ $organization->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="text-sm text-gray-600 mb-1">Total Campaigns</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_campaigns'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['active_campaigns'] }} active</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="text-sm text-gray-600 mb-1">Total Devices</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_devices'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['online_devices'] }} online</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="text-sm text-gray-600 mb-1">Total Donations</div>
                        <div class="text-2xl font-bold text-green-600">€{{ number_format($stats['total_amount'], 2) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['total_donations'] }} donations</div>
                    </div>
                </div>

                <!-- Recent Donations -->
                @if($organization->donations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Donations</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campaign</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($organization->donations as $donation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $donation->campaign->name }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600 text-right">€{{ number_format($donation->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $donation->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
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
                            <span class="badge-gray text-lg">Rejected</span>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500 space-y-1">
                        <p>Registered: {{ $organization->created_at->format('M d, Y') }}</p>
                        @if($organization->approved_at)
                        <p>Approved: {{ $organization->approved_at->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($organization->status == 'pending')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Actions</h3>

                    <!-- Approve Button -->
                    <form method="POST" action="{{ route('super-admin.organizations.approve', $organization) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full btn-success" onclick="return confirm('Are you sure you want to approve this organization?')">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approve Organization
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full btn-danger">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Reject Organization
                    </button>
                </div>
                @elseif($organization->status == 'active')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Actions</h3>

                    <!-- Suspend Button -->
                    <button onclick="document.getElementById('suspendModal').classList.remove('hidden')" class="w-full btn-danger">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        Suspend Organization
                    </button>
                </div>
                @elseif($organization->status == 'suspended')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Actions</h3>

                    <!-- Reactivate Button -->
                    <form method="POST" action="{{ route('super-admin.organizations.reactivate', $organization) }}">
                        @csrf
                        <button type="submit" class="w-full btn-success" onclick="return confirm('Are you sure you want to reactivate this organization?')">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Reactivate Organization
                        </button>
                    </form>
                </div>
                @endif

                <!-- Subscription Info -->
                @if($organization->subscription)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Subscription</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Plan</span>
                            <span class="badge-{{ $organization->subscription->plan == 'premium' ? 'info' : 'gray' }}">
                                {{ ucfirst($organization->subscription->plan) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Price</span>
                            <span class="text-sm font-semibold">€{{ number_format($organization->subscription->price, 2) }}/mo</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="badge-{{ $organization->subscription->status == 'active' ? 'success' : 'gray' }}">
                                {{ ucfirst($organization->subscription->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Organization</h3>
            <form method="POST" action="{{ route('super-admin.organizations.reject', $organization) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                    <textarea name="rejection_reason" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 btn-secondary">Cancel</button>
                    <button type="submit" class="flex-1 btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div id="suspendModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Suspend Organization</h3>
            <form method="POST" action="{{ route('super-admin.organizations.suspend', $organization) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Suspension Reason *</label>
                    <textarea name="suspension_reason" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Please provide a reason for suspension..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('suspendModal').classList.add('hidden')" class="flex-1 btn-secondary">Cancel</button>
                    <button type="submit" class="flex-1 btn-danger">Suspend</button>
                </div>
            </form>
        </div>
    </div>
</x-super-admin-layout>
