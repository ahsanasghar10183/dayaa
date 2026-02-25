<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dayaa') }} - {{ auth()->user()->organization->name ?? 'Organization' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Logo and Brand -->
                        <div class="flex items-center space-x-8">
                            <a href="{{ route('organization.dashboard') }}" class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xl">D</span>
                                </div>
                                <span class="ml-3 text-2xl font-bold text-gradient-primary">Dayaa</span>
                            </a>

                            <!-- Main Navigation -->
                            <div class="hidden md:flex space-x-1">
                                <a href="{{ route('organization.dashboard') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('organization.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('organization.profile.show') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('organization.profile.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Profile
                                </a>
                                <a href="{{ route('organization.campaigns.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('organization.campaigns.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Campaigns
                                </a>
                                <a href="{{ route('organization.devices.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('organization.devices.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Devices
                                </a>
                                <a href="{{ route('organization.donations.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('organization.donations.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Donations
                                </a>
                                <a href="{{ route('organization.reports.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('organization.reports.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Reports
                                </a>
                                <a href="{{ route('organization.billing.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('organization.billing.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Billing
                                </a>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center space-x-4">
                            <!-- Organization Status Badge -->
                            @if(auth()->user()->organization)
                                @if(auth()->user()->organization->status == 'active')
                                    <span class="badge-success">Active</span>
                                @elseif(auth()->user()->organization->status == 'pending')
                                    <span class="badge-warning">Pending</span>
                                @elseif(auth()->user()->organization->status == 'suspended')
                                    <span class="badge-error">Suspended</span>
                                @else
                                    <span class="badge-gray">{{ ucfirst(auth()->user()->organization->status) }}</span>
                                @endif
                            @endif

                            <!-- Language Switcher -->
                            <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                                <a href="{{ route('language.switch', 'de') }}"
                                   class="px-2 py-1 text-xs font-semibold rounded {{ app()->getLocale() === 'de' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                    DE
                                </a>
                                <a href="{{ route('language.switch', 'en') }}"
                                   class="px-2 py-1 text-xs font-semibold rounded {{ app()->getLocale() === 'en' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                                    EN
                                </a>
                            </div>

                            <!-- Notifications Badge -->
                            <div class="relative">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100 cursor-pointer">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </span>
                            </div>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-100">
                                    <div class="w-8 h-8 bg-gradient-primary rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div class="text-left hidden sm:block">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        @if(auth()->user()->organization)
                                            <p class="text-xs text-gray-500">{{ Str::limit(auth()->user()->organization->name, 20) }}</p>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Account Settings</a>
                                    <a href="{{ route('organization.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Organization Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="pt-20">
                <!-- Page Header -->
                @if(isset($header))
                <header class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                @endif

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded animate-fadeIn">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded animate-fadeIn">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="ml-3 text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('info'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded animate-fadeIn">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="ml-3 text-sm text-blue-800">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Main Content -->
                <div class="py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
