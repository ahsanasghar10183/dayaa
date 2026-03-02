<x-organization-sidebar-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wizard Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Final Campaign Details</h1>
                    <p class="mt-2 text-gray-600">Complete your campaign setup</p>
                </div>
                <a href="{{ route('organization.campaigns.index') }}" class="btn-secondary">Cancel</a>
            </div>

            <!-- Progress Steps -->
            <div class="flex items-center justify-center space-x-4 mb-8">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Layout</span>
                </div>
                <div class="w-16 h-1 bg-primary-500"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Design</span>
                </div>
                <div class="w-16 h-1 bg-primary-500"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Donations</span>
                </div>
                <div class="w-16 h-1 bg-primary-500"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-600 text-white font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Thank You</span>
                </div>
                <div class="w-16 h-1 bg-primary-500"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-primary text-white font-semibold shadow-primary">5</div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Finish</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('organization.campaigns.wizard.finish') }}">
            @csrf

            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Campaign Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Campaign Settings
                    </h3>

                    <div class="space-y-6">
                        <!-- Campaign Type -->
                        <div>
                            <label for="campaign_type" class="block text-sm font-medium text-gray-700 mb-2">Campaign Type *</label>
                            <select name="campaign_type" id="campaign_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="one-time">One-Time Donations</option>
                                <option value="recurring">Recurring Donations</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Choose whether donors can make one-time or recurring donations</p>
                        </div>

                        <!-- Reference Code -->
                        <div>
                            <label for="reference_code" class="block text-sm font-medium text-gray-700 mb-2">Reference Code (Optional)</label>
                            <input type="text" name="reference_code" id="reference_code" value="{{ old('reference_code') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g., SPRING2026">
                            <p class="mt-1 text-xs text-gray-500">Internal reference for tracking purposes</p>
                        </div>

                        <!-- Start & End Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date (Optional)</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">When should this campaign start?</p>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <p class="mt-1 text-xs text-gray-500">When should this campaign end?</p>
                            </div>
                        </div>

                        <!-- Campaign Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Initial Status *</label>
                            <select name="status" id="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="inactive">Inactive - Save as draft</option>
                                <option value="active" selected>Active - Launch immediately</option>
                                <option value="scheduled">Scheduled - Launch on start date</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Device Assignment (if devices exist) -->
                @if($devices && $devices->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Assign to Devices
                    </h3>
                    <p class="text-sm text-gray-600 mb-6">Select which devices will display this campaign</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($devices as $device)
                            @php
                                $hasActiveCampaign = $device->campaigns->where('status', 'active')->isNotEmpty();
                                $activeCampaignNames = $device->campaigns->where('status', 'active')->pluck('name')->join(', ');
                            @endphp
                            <label class="flex items-center p-4 border-2 {{ $hasActiveCampaign ? 'border-orange-300 bg-orange-50' : 'border-gray-200' }} rounded-xl {{ $hasActiveCampaign ? 'cursor-not-allowed opacity-75' : 'hover:border-primary-500 cursor-pointer hover:shadow-md' }} transition-all group">
                                <input
                                    type="checkbox"
                                    name="devices[]"
                                    value="{{ $device->id }}"
                                    class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                    {{ $hasActiveCampaign ? 'disabled' : '' }}
                                    id="device-{{ $device->id }}"
                                >
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium {{ $hasActiveCampaign ? 'text-gray-600' : 'text-gray-900 group-hover:text-primary-600' }}">{{ $device->name }}</span>
                                        @if($device->status == 'online')
                                            <span class="badge-success text-xs">Online</span>
                                        @else
                                            <span class="badge-gray text-xs">Offline</span>
                                        @endif
                                    </div>
                                    @if($device->location)
                                    <p class="text-xs text-gray-500 mt-1">{{ $device->location }}</p>
                                    @endif
                                    @if($hasActiveCampaign)
                                    <div class="mt-2 flex items-start">
                                        <svg class="w-4 h-4 text-orange-500 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-xs text-orange-700">
                                            Already assigned to: <span class="font-medium">{{ $activeCampaignNames }}</span>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @if($devices->filter(function($d) { return $d->campaigns->where('status', 'active')->isNotEmpty(); })->isNotEmpty())
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-900">One Device, One Active Campaign</p>
                                <p class="text-xs text-blue-700 mt-1">Devices already assigned to active campaigns cannot be selected. To reassign a device, please deactivate its current campaign first, or set this campaign status to "Inactive".</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Campaign Summary -->
                <div class="bg-gradient-to-br from-primary-50 to-blue-50 rounded-2xl shadow-sm border-2 border-primary-200 p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Campaign Summary
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-xs font-medium text-gray-500 uppercase mb-2">Campaign Name</h4>
                            <p class="text-sm font-semibold text-gray-900">{{ session('campaign_wizard.campaign_name', 'Your Campaign') }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-xs font-medium text-gray-500 uppercase mb-2">Layout Type</h4>
                            <p class="text-sm font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', session('campaign_wizard.layout_type', 'N/A')) }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-xs font-medium text-gray-500 uppercase mb-2">Donation Amounts</h4>
                            <p class="text-sm font-semibold text-gray-900">{{ count(session('campaign_wizard.amounts', [])) }} preset amounts</p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-xs font-medium text-gray-500 uppercase mb-2">Custom Amount</h4>
                            <p class="text-sm font-semibold text-gray-900">{{ session('campaign_wizard.show_custom_amount') ? 'Enabled' : 'Disabled' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('organization.campaigns.wizard.step4') }}" class="btn-secondary">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back
                    </a>
                    <button type="submit" class="btn-success px-12 py-4 text-lg">
                        <svg class="w-6 h-6 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Campaign
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-organization-sidebar-layout>
