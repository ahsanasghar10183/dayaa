{{-- Cookie Consent Banner --}}
<div id="cookie-consent-banner" class="cookie-consent-banner" style="display: none;">
    <div class="cookie-consent-container">
        <div class="cookie-consent-content">
            <div class="cookie-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <circle cx="12" cy="12" r="1" fill="currentColor"></circle>
                    <circle cx="8" cy="8" r="1" fill="currentColor"></circle>
                    <circle cx="16" cy="8" r="1" fill="currentColor"></circle>
                    <circle cx="8" cy="16" r="1" fill="currentColor"></circle>
                    <circle cx="16" cy="16" r="1" fill="currentColor"></circle>
                </svg>
            </div>
            <div class="cookie-text">
                <h4>{{ __('marketing.cookies.title') }}</h4>
                <p>
                    {{ __('marketing.cookies.message') }}
                    <a href="{{ route('marketing.privacy') }}" class="cookie-policy-link">
                        {{ __('marketing.cookies.learn_more') }}
                    </a>
                </p>
            </div>
        </div>
        <div class="cookie-actions">
            <button type="button" class="cookie-btn cookie-btn-accept" onclick="acceptCookies()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                {{ __('marketing.cookies.accept') }}
            </button>
            <button type="button" class="cookie-btn cookie-btn-decline" onclick="declineCookies()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                {{ __('marketing.cookies.decline') }}
            </button>
            <button type="button" class="cookie-btn cookie-btn-settings" onclick="toggleCookieSettings()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M12 1v6m0 6v6m8.66-13L17 10M7 14l-3.66 6M20.66 7L17 14M7 10L3.34 7m17.32 10L17 14M7 10l-3.66-6"></path>
                </svg>
                {{ __('marketing.cookies.settings') }}
            </button>
        </div>
    </div>

    {{-- Cookie Settings Panel (Hidden by default) --}}
    <div id="cookie-settings-panel" class="cookie-settings-panel" style="display: none;">
        <div class="cookie-settings-header">
            <h5>{{ __('marketing.cookies.settings_title') }}</h5>
            <button type="button" class="cookie-close-btn" onclick="toggleCookieSettings()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="cookie-settings-body">
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label class="cookie-switch">
                        <input type="checkbox" id="cookie-essential" checked disabled>
                        <span class="cookie-slider"></span>
                    </label>
                    <div class="cookie-category-info">
                        <h6>{{ __('marketing.cookies.essential') }}</h6>
                        <p>{{ __('marketing.cookies.essential_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label class="cookie-switch">
                        <input type="checkbox" id="cookie-analytics" checked>
                        <span class="cookie-slider"></span>
                    </label>
                    <div class="cookie-category-info">
                        <h6>{{ __('marketing.cookies.analytics') }}</h6>
                        <p>{{ __('marketing.cookies.analytics_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label class="cookie-switch">
                        <input type="checkbox" id="cookie-marketing" checked>
                        <span class="cookie-slider"></span>
                    </label>
                    <div class="cookie-category-info">
                        <h6>{{ __('marketing.cookies.marketing') }}</h6>
                        <p>{{ __('marketing.cookies.marketing_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="cookie-settings-footer">
            <button type="button" class="cookie-btn cookie-btn-accept" onclick="saveCustomCookiePreferences()">
                {{ __('marketing.cookies.save_preferences') }}
            </button>
        </div>
    </div>
</div>

<style>
/* Cookie Consent Banner Styles */
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    z-index: 999999;
    animation: slideUp 0.4s ease-out;
    border-top: 3px solid #0F69F3;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(100%);
        opacity: 0;
    }
}

.cookie-consent-banner.hiding {
    animation: slideDown 0.3s ease-out forwards;
}

.cookie-consent-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.cookie-consent-content {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    flex: 1;
}

.cookie-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #0F69F3 0%, #1707B2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.cookie-text h4 {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.cookie-text p {
    font-size: 14px;
    line-height: 1.6;
    color: #6b7280;
    margin: 0;
}

.cookie-policy-link {
    color: #0F69F3;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.cookie-policy-link:hover {
    color: #1707B2;
    text-decoration: underline;
}

.cookie-actions {
    display: flex;
    gap: 12px;
    flex-shrink: 0;
    align-items: center;
}

.cookie-btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.cookie-btn svg {
    flex-shrink: 0;
}

.cookie-btn-accept {
    background: linear-gradient(135deg, #0F69F3 0%, #1707B2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(15, 105, 243, 0.3);
}

.cookie-btn-accept:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 105, 243, 0.4);
}

.cookie-btn-decline {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}

.cookie-btn-decline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
}

.cookie-btn-settings {
    background: white;
    color: #0F69F3;
    border: 2px solid #0F69F3;
}

.cookie-btn-settings:hover {
    background: #0F69F3;
    color: white;
}

/* Cookie Settings Panel */
.cookie-settings-panel {
    border-top: 1px solid #e5e7eb;
    padding: 24px 32px;
    background: #f9fafb;
}

.cookie-settings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.cookie-settings-header h5 {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.cookie-close-btn {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 4px;
    transition: color 0.3s ease;
}

.cookie-close-btn:hover {
    color: #1f2937;
}

.cookie-settings-body {
    max-width: 800px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.cookie-category {
    background: white;
    border-radius: 8px;
    padding: 16px;
    border: 1px solid #e5e7eb;
}

.cookie-category-header {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.cookie-category-info h6 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.cookie-category-info p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

/* Toggle Switch */
.cookie-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
    flex-shrink: 0;
}

.cookie-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.cookie-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 24px;
}

.cookie-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

.cookie-switch input:checked + .cookie-slider {
    background: linear-gradient(135deg, #0F69F3 0%, #1707B2 100%);
}

.cookie-switch input:checked + .cookie-slider:before {
    transform: translateX(24px);
}

.cookie-switch input:disabled + .cookie-slider {
    background-color: #94a3b8;
    cursor: not-allowed;
}

.cookie-settings-footer {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
}

/* Responsive Design */
@media (max-width: 991px) {
    .cookie-consent-container {
        flex-direction: column;
        align-items: stretch;
        padding: 20px;
    }

    .cookie-actions {
        flex-direction: column;
        width: 100%;
    }

    .cookie-btn {
        width: 100%;
        justify-content: center;
    }

    .cookie-settings-panel {
        padding: 20px;
    }
}

@media (max-width: 576px) {
    .cookie-consent-container {
        padding: 16px;
    }

    .cookie-icon {
        width: 40px;
        height: 40px;
    }

    .cookie-icon svg {
        width: 24px;
        height: 24px;
    }

    .cookie-text h4 {
        font-size: 16px;
    }

    .cookie-text p {
        font-size: 13px;
    }

    .cookie-btn {
        padding: 10px 20px;
        font-size: 13px;
    }

    .cookie-settings-panel {
        padding: 16px;
    }

    .cookie-category {
        padding: 12px;
    }
}
</style>

<script>
// Cookie Consent Management
(function() {
    'use strict';

    const COOKIE_NAME = 'dayaa_cookie_consent';
    const COOKIE_EXPIRY_DAYS = 365;

    // Check if user has already made a choice
    function hasUserConsented() {
        return getCookie(COOKIE_NAME) !== null;
    }

    // Show banner if user hasn't made a choice
    function checkAndShowBanner() {
        if (!hasUserConsented()) {
            setTimeout(() => {
                document.getElementById('cookie-consent-banner').style.display = 'block';
            }, 1000); // Show after 1 second
        }
    }

    // Set cookie
    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
        document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/;SameSite=Lax';
    }

    // Get cookie
    function getCookie(name) {
        const nameEQ = name + '=';
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Hide banner with animation
    function hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        banner.classList.add('hiding');
        setTimeout(() => {
            banner.style.display = 'none';
            banner.classList.remove('hiding');
        }, 300);
    }

    // Accept all cookies
    window.acceptCookies = function() {
        const preferences = {
            essential: true,
            analytics: true,
            marketing: true,
            timestamp: new Date().toISOString()
        };

        setCookie(COOKIE_NAME, JSON.stringify(preferences), COOKIE_EXPIRY_DAYS);
        hideBanner();

        // Enable analytics and marketing scripts
        enableAnalytics();
        enableMarketing();

        console.log('Cookies accepted:', preferences);
    };

    // Decline non-essential cookies
    window.declineCookies = function() {
        const preferences = {
            essential: true,
            analytics: false,
            marketing: false,
            timestamp: new Date().toISOString()
        };

        setCookie(COOKIE_NAME, JSON.stringify(preferences), COOKIE_EXPIRY_DAYS);
        hideBanner();

        console.log('Non-essential cookies declined:', preferences);
    };

    // Toggle settings panel
    window.toggleCookieSettings = function() {
        const panel = document.getElementById('cookie-settings-panel');
        if (panel.style.display === 'none' || panel.style.display === '') {
            panel.style.display = 'block';
        } else {
            panel.style.display = 'none';
        }
    };

    // Save custom preferences
    window.saveCustomCookiePreferences = function() {
        const preferences = {
            essential: true, // Always true
            analytics: document.getElementById('cookie-analytics').checked,
            marketing: document.getElementById('cookie-marketing').checked,
            timestamp: new Date().toISOString()
        };

        setCookie(COOKIE_NAME, JSON.stringify(preferences), COOKIE_EXPIRY_DAYS);
        hideBanner();

        // Enable/disable based on preferences
        if (preferences.analytics) {
            enableAnalytics();
        }
        if (preferences.marketing) {
            enableMarketing();
        }

        console.log('Custom cookie preferences saved:', preferences);
    };

    // Enable analytics (Google Analytics, etc.)
    function enableAnalytics() {
        // Add your analytics code here
        // Example: Load Google Analytics
        // gtag('js', new Date());
        // gtag('config', 'GA_MEASUREMENT_ID');
        console.log('Analytics enabled');
    }

    // Enable marketing (Facebook Pixel, etc.)
    function enableMarketing() {
        // Add your marketing code here
        // Example: Load Facebook Pixel
        console.log('Marketing enabled');
    }

    // Check existing consent and load scripts
    function loadExistingPreferences() {
        const consent = getCookie(COOKIE_NAME);
        if (consent) {
            try {
                const preferences = JSON.parse(consent);
                if (preferences.analytics) {
                    enableAnalytics();
                }
                if (preferences.marketing) {
                    enableMarketing();
                }
            } catch (e) {
                console.error('Error parsing cookie consent:', e);
            }
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        checkAndShowBanner();
        loadExistingPreferences();
    });
})();
</script>
