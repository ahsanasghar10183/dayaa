<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dayaa') }} - {{ auth()->user()->organization->name ?? 'Organization' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

        <!-- Alpine.js Collapse Plugin -->
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }

            /* Smooth transitions for sidebar */
            .sidebar-transition {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* Custom scrollbar for sidebar */
            .sidebar-scroll::-webkit-scrollbar {
                width: 5px;
            }
            .sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            .sidebar-scroll::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.1);
                border-radius: 3px;
            }
            .sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background: rgba(0, 0, 0, 0.15);
            }

            /* Gradient text */
            .text-gradient-dayaa {
                background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside
                x-cloak
                :class="sidebarOpen ? 'w-72' : 'w-20'"
                class="sidebar-transition bg-white flex-shrink-0 overflow-hidden shadow-xl border-r border-gray-100"
            >
                <div class="flex flex-col h-full">
                    <!-- Logo / Brand -->
                    <div class="flex items-center justify-between h-20 px-6 border-b border-gray-100">
                        <a href="{{ route('organization.dashboard') }}" class="flex items-center space-x-3 min-w-0">
                            <div class="w-11 h-11 bg-gradient-dayaa rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg hover:shadow-xl transition-shadow">
                                <span class="text-white font-bold text-xl">{{ substr(auth()->user()->organization->name ?? 'O', 0, 1) }}</span>
                            </div>
                            <div x-show="sidebarOpen" class="flex flex-col">
                                <span class="text-xl font-bold text-gradient-dayaa">Dayaa</span>
                                <span class="text-xs text-gray-500 font-medium truncate">{{ Str::limit(auth()->user()->organization->name ?? 'Organization', 20) }}</span>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto sidebar-scroll">
                        <!-- Dashboard -->
                        <a href="{{ route('organization.dashboard') }}"
                           class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('organization.dashboard') ? 'bg-gradient-dayaa text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-semibold text-sm">Dashboard</span>
                        </a>

                        <!-- Profile -->
                        <a href="{{ route('organization.profile.show') }}"
                           class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('organization.profile.*') ? 'bg-gradient-dayaa text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-semibold text-sm">Profile</span>
                        </a>

                        <!-- Campaigns -->
                        <a href="{{ route('organization.campaigns.index') }}"
                           class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('organization.campaigns.*') ? 'bg-gradient-dayaa text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-semibold text-sm">Campaigns</span>
                        </a>

                        <!-- Devices -->
                        <a href="{{ route('organization.devices.index') }}"
                           class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('organization.devices.*') ? 'bg-gradient-dayaa text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-semibold text-sm">Devices</span>
                        </a>

                        <!-- Donations -->
                        <a href="{{ route('organization.donations.index') }}"
                           class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('organization.donations.*') ? 'bg-gradient-dayaa text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-semibold text-sm">Donations</span>
                        </a>

                        <!-- Reports -->
                        <a href="{{ route('organization.reports.index') }}"
                           class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('organization.reports.*') ? 'bg-gradient-dayaa text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-semibold text-sm">Reports</span>
                        </a>

                        <!-- Billing -->
                        <a href="{{ route('organization.billing.index') }}"
                           class="flex items-center px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('organization.billing.*') ? 'bg-gradient-dayaa text-white shadow-lg shadow-primary-500/30' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 font-semibold text-sm">Billing</span>
                        </a>
                    </nav>

                    <!-- User Profile & Settings -->
                    <div class="border-t border-gray-100 p-4">
                        <div x-data="{ userMenuOpen: false }" class="relative">
                            <button @click="userMenuOpen = !userMenuOpen"
                                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition-all group">
                                <div class="w-10 h-10 bg-gradient-dayaa rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg transition-shadow">
                                    <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div x-show="sidebarOpen" class="flex-1 text-left min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <svg x-show="sidebarOpen" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="userMenuOpen"
                                 @click.away="userMenuOpen = false"
                                 x-cloak
                                 class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-2xl py-2 border border-gray-100">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    User Settings
                                </a>
                                <a href="{{ route('organization.profile.show') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Organization Profile
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Bar -->
                <header class="bg-white border-b border-gray-100 shadow-sm z-10">
                    <div class="flex items-center justify-between h-16 px-6">
                        <!-- Sidebar Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen"
                                class="p-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-all hover:shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Page Title -->
                        @if(isset($header))
                        <div class="flex-1 ml-6">
                            {{ $header }}
                        </div>
                        @endif

                        <!-- Right Side Actions -->
                        <div class="flex items-center space-x-3">
                            <!-- Notifications -->
                            <button class="p-2.5 rounded-xl text-gray-600 hover:bg-gray-100 transition-all relative hover:shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mx-6 mt-6">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm animate-fadeIn">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="ml-3 text-sm text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mx-6 mt-6">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm animate-fadeIn">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="ml-3 text-sm text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50">
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
