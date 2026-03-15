@props(['type' => 'dropdown']) {{-- type can be 'dropdown' or 'flags' or 'simple' --}}

@php
    $currentLocale = app()->getLocale();
    $languages = [
        'en' => [
            'name' => 'English',
            'flag' => '🇬🇧',
            'short' => 'EN'
        ],
        'de' => [
            'name' => 'Deutsch',
            'flag' => '🇩🇪',
            'short' => 'DE'
        ],
    ];

    // Determine which language switch route to use based on current route name prefix
    // If we're on a marketing route (route name starts with 'marketing.'), use marketing language route
    $currentRouteName = request()->route() ? request()->route()->getName() : '';
    $languageRoute = str_starts_with($currentRouteName, 'marketing.')
        ? 'marketing.language.switch'
        : 'language.switch';
@endphp

@if($type === 'dropdown')
    {{-- Dropdown Style Language Switcher --}}
    <div class="relative inline-block text-left" x-data="{ open: false }">
        <button @click="open = !open" type="button"
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
            <span class="text-base">{{ $languages[$currentLocale]['flag'] }}</span>
            <span class="hidden sm:inline">{{ $languages[$currentLocale]['name'] }}</span>
            <span class="sm:hidden">{{ $languages[$currentLocale]['short'] }}</span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 z-50 mt-2 w-40 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
             style="display: none;">
            <div class="py-1">
                @foreach($languages as $code => $language)
                    <a href="{{ route($languageRoute, $code) }}"
                       class="group flex items-center gap-3 px-4 py-2 text-sm {{ $currentLocale === $code ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} transition-colors duration-150">
                        <span class="text-base">{{ $language['flag'] }}</span>
                        <span>{{ $language['name'] }}</span>
                        @if($currentLocale === $code)
                            <svg class="ml-auto w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@elseif($type === 'flags')
    {{-- Flag Style Language Switcher (Horizontal) --}}
    <div class="flex items-center gap-2">
        @foreach($languages as $code => $language)
            <a href="{{ route($languageRoute, $code) }}"
               title="{{ $language['name'] }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ $currentLocale === $code ? 'bg-blue-50 text-blue-700 ring-2 ring-blue-500' : 'text-gray-600 hover:bg-gray-100' }}">
                <span class="text-base">{{ $language['flag'] }}</span>
                <span>{{ $language['short'] }}</span>
            </a>
        @endforeach
    </div>

@elseif($type === 'simple')
    {{-- Simple Text Links --}}
    <div class="flex items-center gap-3">
        @foreach($languages as $code => $language)
            <a href="{{ route($languageRoute, $code) }}"
               class="text-sm font-medium transition-colors duration-200 {{ $currentLocale === $code ? 'text-blue-600 font-bold underline' : 'text-gray-600 hover:text-gray-900' }}">
                {{ $language['short'] }}
            </a>
        @endforeach
    </div>

@endif
