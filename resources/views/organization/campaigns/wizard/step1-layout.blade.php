<x-organization-sidebar-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wizard Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Campaign</h1>
                    <p class="mt-2 text-gray-600">Design a beautiful donation campaign in minutes</p>
                </div>
                <a href="{{ route('organization.campaigns.index') }}" class="btn-secondary">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
            </div>

            <!-- Progress Steps -->
            <div class="flex items-center justify-center space-x-4 mb-8">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-primary text-white font-semibold shadow-primary">1</div>
                    <span class="ml-2 text-sm font-medium text-gray-900">Select Layout</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Design</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Donations</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">4</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Thank You</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-500 font-semibold">5</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Finish</span>
                </div>
            </div>
        </div>

        <!-- Layout Selection -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Choose Your Campaign Layout</h2>
                <p class="text-gray-600">Select a layout that best fits your campaign style</p>
            </div>

            <form method="POST" action="{{ route('organization.campaigns.wizard.step1.post') }}" id="layoutForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                    <!-- Layout 1: Solid Color -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="layout_type" value="solid_color" class="hidden layout-radio" required>
                        <div class="layout-card border-3 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-primary-500 hover:shadow-xl hover:-translate-y-1">
                            <div class="aspect-[4/3] bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl mb-4 p-6 flex flex-col items-center justify-center relative overflow-hidden">
                                <!-- Preview -->
                                <div class="absolute inset-0 bg-blue-500 opacity-10"></div>
                                <div class="relative z-10 text-center space-y-4 w-full">
                                    <div class="h-3 bg-blue-600 rounded w-3/4 mx-auto opacity-80"></div>
                                    <div class="h-2 bg-blue-400 rounded w-1/2 mx-auto opacity-60"></div>
                                    <div class="grid grid-cols-2 gap-2 mt-6 px-4">
                                        <div class="h-10 bg-blue-600 rounded-lg shadow-lg"></div>
                                        <div class="h-10 bg-blue-600 rounded-lg shadow-lg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                    </svg>
                                    Solid Color
                                </h3>
                                <p class="text-sm text-gray-600">Simple background with donation buttons</p>
                                <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                    Perfect for minimal design
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Layout 2: Dual Color -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="layout_type" value="dual_color" class="hidden layout-radio" required>
                        <div class="layout-card border-3 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-primary-500 hover:shadow-xl hover:-translate-y-1">
                            <div class="aspect-[4/3] bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl mb-4 p-6 flex flex-col relative overflow-hidden">
                                <!-- Preview -->
                                <div class="absolute top-0 left-0 right-0 h-1/3 bg-purple-600"></div>
                                <div class="absolute bottom-0 left-0 right-0 h-2/3 bg-purple-100"></div>
                                <div class="relative z-10 space-y-4">
                                    <div class="h-3 bg-white rounded w-3/4 opacity-90 shadow-sm"></div>
                                    <div class="h-2 bg-white rounded w-1/2 opacity-80 shadow-sm"></div>
                                    <div class="grid grid-cols-2 gap-2 mt-8 px-4">
                                        <div class="h-10 bg-purple-600 rounded-lg shadow-lg"></div>
                                        <div class="h-10 bg-purple-600 rounded-lg shadow-lg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                    </svg>
                                    Dual Color
                                </h3>
                                <p class="text-sm text-gray-600">Header color with body section</p>
                                <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-purple-50 text-purple-700 text-xs font-medium">
                                    Modern & professional
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Layout 3: Banner Image -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="layout_type" value="banner_image" class="hidden layout-radio" required>
                        <div class="layout-card border-3 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-primary-500 hover:shadow-xl hover:-translate-y-1">
                            <div class="aspect-[4/3] bg-gradient-to-br from-green-50 to-green-100 rounded-xl mb-4 overflow-hidden relative">
                                <!-- Preview -->
                                <div class="h-1/3 bg-gradient-to-r from-green-400 to-emerald-500 relative">
                                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDQwIDAgTCAwIDAgMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLW9wYWNpdHk9IjAuMSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-30"></div>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div class="h-3 bg-green-600 rounded w-3/4 opacity-80"></div>
                                    <div class="h-2 bg-green-400 rounded w-1/2 opacity-60"></div>
                                    <div class="grid grid-cols-2 gap-2 mt-6">
                                        <div class="h-10 bg-green-600 rounded-lg shadow-lg"></div>
                                        <div class="h-10 bg-green-600 rounded-lg shadow-lg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Banner Image
                                </h3>
                                <p class="text-sm text-gray-600">Image header with colored body</p>
                                <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                                    Eye-catching & engaging
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Layout 4: Full Background -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="layout_type" value="full_background" class="hidden layout-radio" required>
                        <div class="layout-card border-3 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-primary-500 hover:shadow-xl hover:-translate-y-1">
                            <div class="aspect-[4/3] bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl mb-4 p-6 flex flex-col items-center justify-center relative overflow-hidden">
                                <!-- Preview -->
                                <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-red-500 opacity-80"></div>
                                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDQwIDAgTCAwIDAgMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLW9wYWNpdHk9IjAuMSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-20"></div>
                                <div class="relative z-10 text-center space-y-4 w-full">
                                    <div class="h-3 bg-white rounded w-3/4 mx-auto shadow-lg"></div>
                                    <div class="h-2 bg-white rounded w-1/2 mx-auto opacity-90 shadow-md"></div>
                                    <div class="grid grid-cols-2 gap-2 mt-6 px-4">
                                        <div class="h-10 bg-white rounded-lg shadow-xl"></div>
                                        <div class="h-10 bg-white rounded-lg shadow-xl"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Full Background
                                </h3>
                                <p class="text-sm text-gray-600">Complete background image</p>
                                <div class="mt-4 inline-flex items-center px-4 py-2 rounded-full bg-orange-50 text-orange-700 text-xs font-medium">
                                    Immersive & dramatic
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Next Button -->
                <div class="flex items-center justify-center mt-12">
                    <button type="submit" id="nextBtn" disabled class="btn-primary px-12 py-4 text-lg opacity-50 cursor-not-allowed transition-all duration-300">
                        Continue to Design
                        <svg class="w-6 h-6 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Layout selection handler
        const layoutRadios = document.querySelectorAll('.layout-radio');
        const layoutCards = document.querySelectorAll('.layout-card');
        const nextBtn = document.getElementById('nextBtn');

        layoutRadios.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                // Reset all cards
                layoutCards.forEach(card => {
                    card.classList.remove('border-primary-500', 'shadow-primary-lg', 'bg-primary-50', 'ring-4', 'ring-primary-100');
                    card.classList.add('border-gray-200');
                });

                // Highlight selected card
                if (this.checked) {
                    const card = layoutCards[index];
                    card.classList.remove('border-gray-200');
                    card.classList.add('border-primary-500', 'shadow-primary-lg', 'bg-primary-50', 'ring-4', 'ring-primary-100');

                    // Enable next button
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    nextBtn.classList.add('hover:shadow-primary-lg', 'hover:-translate-y-0.5');
                }
            });
        });
    </script>
</x-organization-sidebar-layout>
