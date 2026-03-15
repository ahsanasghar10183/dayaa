<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DAYAA') }} - {{ $title ?? 'Authentication' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Custom gradient for DAYAA brand */
            .bg-gradient-dayaa {
                background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%);
            }

            /* Animated gradient background */
            .animated-gradient {
                background: linear-gradient(135deg, #0F69F3 0%, #170AB5 50%, #0F69F3 100%);
                background-size: 200% 200%;
                animation: gradientShift 8s ease infinite;
            }

            @keyframes gradientShift {
                0%, 100% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
            }

            /* Glassmorphism effect */
            .glass {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            /* Floating animation */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }

            .float {
                animation: float 6s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - Gradient Panel (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 animated-gradient relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute top-20 right-20 w-72 h-72 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">
                    <!-- Logo & Branding -->
                    <div>
                        <div class="flex items-center space-x-3 mb-8">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-md border border-white border-opacity-30 shadow-lg">
                                <span class="text-2xl font-black text-white">D</span>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight">DAYAA</h1>
                                <p class="text-sm text-white text-opacity-90">Empowering Generosity</p>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="float">
                        <h2 class="text-4xl font-bold leading-tight mb-6">
                            {{ $leftTitle ?? 'Transform Your Fundraising' }}
                        </h2>
                        <p class="text-lg text-white text-opacity-90 leading-relaxed max-w-md">
                            {{ $leftDescription ?? 'The modern donation management platform trusted by organizations worldwide. Accept contactless donations, manage campaigns, and track every contribution effortlessly.' }}
                        </p>

                        <!-- Features -->
                        <div class="mt-12 space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 glass rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-white text-opacity-90">Contactless tablet donations</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 glass rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-white text-opacity-90">Real-time analytics & reports</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 glass rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-white text-opacity-90">Secure payment processing</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-sm text-white text-opacity-75">
                        <p>© {{ date('Y') }} DAYAA. All rights reserved.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form Panel -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
                <div class="w-full max-w-md">
                    <!-- Language Switcher (Top Right) -->
                    <div class="flex justify-end mb-4">
                        <x-language-switcher type="flags" />
                    </div>

                    <!-- Mobile Logo (visible only on mobile) -->
                    <div class="lg:hidden flex items-center justify-center mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-dayaa rounded-xl flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-black text-white">D</span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">DAYAA</h1>
                                <p class="text-sm text-gray-600">Empowering Generosity</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Container -->
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        {{ $slot }}
                    </div>

                    <!-- Additional Links -->
                    <div class="mt-6 text-center">
                        {{ $footer ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
