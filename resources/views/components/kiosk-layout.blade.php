@props(['device' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dayaa') }} - Kiosk</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Kiosk Mode Styles */
        body {
            overflow: hidden;
            cursor: default;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Disable right-click */
        body {
            -webkit-touch-callout: none;
        }

        /* Full screen mode */
        .kiosk-container {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            position: fixed;
            top: 0;
            left: 0;
        }

        /* Touch optimized buttons */
        .touch-btn {
            min-height: 60px;
            min-width: 60px;
            font-size: 1.125rem;
            -webkit-tap-highlight-color: transparent;
        }

        /* Prevent text selection */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="kiosk-container">
        {{ $slot }}
    </div>

    @stack('scripts')

    <script>
        // Disable right-click context menu
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Disable certain keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Disable F5, Ctrl+R
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                e.preventDefault();
            }
            // Disable Ctrl+W (close tab)
            if (e.ctrlKey && e.key === 'w') {
                e.preventDefault();
            }
        });

        // Keep screen awake (using Wake Lock API if available)
        if ('wakeLock' in navigator) {
            let wakeLock = null;
            const requestWakeLock = async () => {
                try {
                    wakeLock = await navigator.wakeLock.request('screen');
                } catch (err) {
                    console.error('Wake Lock error:', err);
                }
            };
            requestWakeLock();

            // Re-request wake lock on visibility change
            document.addEventListener('visibilitychange', async () => {
                if (wakeLock !== null && document.visibilityState === 'visible') {
                    await requestWakeLock();
                }
            });
        }
    </script>
</body>
</html>
