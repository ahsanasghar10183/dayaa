<x-super-admin-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4 min-w-0">
            <a href="{{ route('super-admin.organizations.index') }}" class="text-gray-600 hover:text-gray-900 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $organization->name }}</h1>
                <p class="text-sm text-gray-600 mt-1">{{ __('admin.super_admin.organization_details') }}</p>
            </div>
        </div>
    </x-slot>

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
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">{{ __('admin.super_admin.about') }}</h3>
                            <p class="text-gray-600 break-words">{{ $organization->description }}</p>
                        </div>
                        @endif

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.super_admin.contact_information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500">{{ __('admin.organization.contact_person') }}</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->contact_person }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">{{ __('admin.organization.email') }}</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">{{ __('admin.organization.phone') }}</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->phone }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">{{ __('admin.organization.website') }}</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->website ?? __('admin.billing.not_available') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Legal Information -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">{{ __('admin.super_admin.legal_information') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500">{{ __('admin.organization.charity_number') }}</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->charity_number ?? __('admin.billing.not_available') }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">{{ __('admin.organization.tax_id') }}</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->tax_id ?? __('admin.billing.not_available') }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-xs text-gray-500">{{ __('admin.organization.address') }}</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $organization->address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Reason -->
                        @if($organization->status == 'rejected' && $organization->rejection_reason)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <h3 class="text-sm font-semibold text-red-800 mb-2">{{ __('admin.super_admin.rejection_reason') }}</h3>
                            <p class="text-sm text-red-700">{{ $organization->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="text-sm text-gray-600 mb-1">{{ __('admin.super_admin.total_campaigns_card') }}</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_campaigns'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['active_campaigns'] }} {{ __('admin.dashboard.active') }}</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="text-sm text-gray-600 mb-1">{{ __('admin.super_admin.total_devices_card') }}</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_devices'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['online_devices'] }} {{ __('admin.dashboard.online') }}</div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="text-sm text-gray-600 mb-1">{{ __('admin.super_admin.total_donations_card') }}</div>
                        <div class="text-2xl font-bold text-green-600">€{{ number_format($stats['total_amount'], 2) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['total_donations'] }} {{ __('admin.dashboard.donations') }}</div>
                    </div>
                </div>

                <!-- Recent Donations -->
                @if($organization->donations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.super_admin.recent_donations') }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.campaign') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.amount') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.dashboard.date') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($organization->donations as $donation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $donation->campaign->name }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600 text-right">€{{ number_format($donation->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $donation->created_at->translatedFormat('M d, Y H:i') }}</td>
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
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">{{ __('admin.dashboard.status') }}</h3>

                    <div class="mb-4">
                        @if($organization->status == 'active')
                            <span class="badge-success text-lg">{{ __('admin.common.active') }}</span>
                        @elseif($organization->status == 'pending')
                            <span class="badge-warning text-lg">{{ __('admin.super_admin.pending_approval') }}</span>
                        @elseif($organization->status == 'suspended')
                            <span class="badge-error text-lg">{{ __('admin.super_admin.suspended') }}</span>
                        @else
                            <span class="badge-gray text-lg">{{ __('admin.super_admin.rejected') }}</span>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500 space-y-1">
                        <p>{{ __('admin.super_admin.registered') }}: {{ $organization->created_at->translatedFormat('M d, Y') }}</p>
                        @if($organization->approved_at)
                        <p>{{ __('admin.super_admin.approved') }}: {{ $organization->approved_at->translatedFormat('M d, Y') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($organization->status == 'pending')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">{{ __('admin.super_admin.actions') }}</h3>

                    <!-- Approve Button -->
                    <form method="POST" action="{{ route('super-admin.organizations.approve', $organization) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full btn-success" onclick="return confirm('{{ __('admin.super_admin.confirm_approve_organization') }}')">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('admin.super_admin.approve_organization') }}
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full btn-danger">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ __('admin.super_admin.reject_organization') }}
                    </button>
                </div>
                @elseif($organization->status == 'active')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">{{ __('admin.super_admin.actions') }}</h3>

                    <!-- Suspend Button -->
                    <button onclick="document.getElementById('suspendModal').classList.remove('hidden')" class="w-full btn-danger">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        {{ __('admin.super_admin.suspend_organization') }}
                    </button>
                </div>
                @elseif($organization->status == 'suspended')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">{{ __('admin.super_admin.actions') }}</h3>

                    <!-- Reactivate Button -->
                    <form method="POST" action="{{ route('super-admin.organizations.reactivate', $organization) }}">
                        @csrf
                        <button type="submit" class="w-full btn-success" onclick="return confirm('{{ __('admin.super_admin.confirm_reactivate_organization') }}')">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('admin.super_admin.reactivate_organization') }}
                        </button>
                    </form>
                </div>
                @endif

                <!-- Subscription Info -->
                @if($organization->subscription)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">{{ __('admin.super_admin.subscription') }}</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('admin.dashboard.plan') }}</span>
                            <span class="badge-{{ $organization->subscription->plan == 'premium' ? 'info' : 'gray' }}">
                                {{ ucfirst($organization->subscription->plan) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('admin.dashboard.price') }}</span>
                            <span class="text-sm font-semibold">€{{ number_format($organization->subscription->price, 2) }}/mo</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">{{ __('admin.dashboard.status') }}</span>
                            <span class="badge-{{ $organization->subscription->status == 'active' ? 'success' : 'gray' }}">
                                {{ ucfirst($organization->subscription->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.super_admin.reject_organization') }}</h3>
            <form method="POST" action="{{ route('super-admin.organizations.reject', $organization) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.super_admin.rejection_reason') }} *</label>
                    <textarea name="rejection_reason" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="{{ __('admin.super_admin.rejection_reason_placeholder') }}"></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 btn-secondary">{{ __('admin.common.cancel') }}</button>
                    <button type="submit" class="flex-1 btn-danger">{{ __('admin.super_admin.reject') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div id="suspendModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.super_admin.suspend_organization') }}</h3>
            <form method="POST" action="{{ route('super-admin.organizations.suspend', $organization) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.super_admin.suspension_reason') }} *</label>
                    <textarea name="suspension_reason" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="{{ __('admin.super_admin.suspension_reason_placeholder') }}"></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('suspendModal').classList.add('hidden')" class="flex-1 btn-secondary">{{ __('admin.common.cancel') }}</button>
                    <button type="submit" class="flex-1 btn-danger">{{ __('admin.super_admin.suspend') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-super-admin-sidebar-layout>
