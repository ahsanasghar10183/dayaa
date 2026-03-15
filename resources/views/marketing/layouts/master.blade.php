<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--<< Header Area >>-->
    <head>
        <!-- ========== Meta Tags ========== -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Dayaa">
        <meta name="description" content="@yield('meta_description', 'Dayaa - Digital Donation Management Platform')">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- ======== Page title ============ -->
        <title>@yield('title', 'Dayaa - Digital Donation Management Platform')</title>

        <!--<< Favcion >>-->
        <link rel="shortcut icon" href="{{ asset('marketing/assets/img/favicon.svg') }}">

        <!--<< Bootstrap min.css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/bootstrap.min.css') }}">
        <!--<< All Min Css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/all.min.css') }}">
        <!--<< Animate.css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/animate.css') }}">
        <!--<< Magnific Popup.css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/magnific-popup.css') }}">
        <!--<< MeanMenu.css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/meanmenu.css') }}">
        <!--<< Swiper Bundle.css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/swiper-bundle.min.css') }}">
        <!--<< Nice Select.css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/nice-select.css') }}">
        <!--<< Main.css >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/main.css') }}">
        <!--<< Dayaa Branding Overrides >>-->
        <link rel="stylesheet" href="{{ asset('marketing/assets/css/dayaa-branding.css') }}">

        @stack('styles')
    </head>
    <body>

        <!-- Preloader Start -->
        <div id="preloader" class="preloader">
            <div class="animation-preloader">
                <div class="spinner">
                </div>
                <div class="txt-loading">
                    <span data-text-preloader="D" class="letters-loading">D</span>
                    <span data-text-preloader="A" class="letters-loading">A</span>
                    <span data-text-preloader="Y" class="letters-loading">Y</span>
                    <span data-text-preloader="A" class="letters-loading">A</span>
                    <span data-text-preloader="A" class="letters-loading">A</span>
                </div>
                <p class="text-center">Loading</p>
            </div>
            <div class="loader">
                <div class="row">
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- pp Back To Top Start -->
        <button id="pp-back-top" class="pp-back-to-top show">
           <i class="fa-solid fa-arrow-up"></i>
        </button>

        <!-- pp MouseCursor Start -->
        <div class="mouseCursor cursor-outer"></div>
        <div class="mouseCursor cursor-inner"></div>

        <!-- Offcanvas Area Start -->
         <div class="fix-area">
            <div class="offcanvas__info">
                <div class="offcanvas__wrapper">
                    <div class="offcanvas__content">
                        <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                            <div class="offcanvas__logo">
                                <a href="{{ route('marketing.home') }}">
                                    <img src="{{ asset('marketing/assets/img/logo/dayaa-logo.png') }}" alt="Dayaa Logo" style="max-height: 40px;">
                                </a>
                            </div>
                            <div class="offcanvas__close">
                                <button>
                                <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text d-none d-xl-block">
                            Transform your fundraising with Dayaa's innovative digital donation platform. Streamline donations, engage donors, and maximize your impact.
                        </p>
                        <div class="mobile-menu fix mb-3"></div>
                        <div class="offcanvas__contact">
                            <h4>{{ __('marketing.footer.contact_info') }}</h4>
                            <ul>
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon">
                                        <i class="fal fa-map-marker-alt"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a target="_blank" href="#">Berlin, Germany</a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon mr-15">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a href="mailto:info@dayaatech.de">info@dayaatech.de</a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon mr-15">
                                        <i class="fal fa-clock"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a target="_blank" href="#">Mon-Fri, 09:00 - 17:00</a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon mr-15">
                                        <i class="far fa-phone"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a href="tel:+491234567890">+49 123 456 7890</a>
                                    </div>
                                </li>
                            </ul>
                            <div class="header-button mt-4">
                                <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn">
                                    <span class="pp-text-btn">
                                        <span class="pp-text-2">GET STARTED</span>
                                    </span>
                                </a>
                            </div>
                            <div class="social-icon d-flex align-items-center">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-youtube"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas__overlay"></div>

        <!-- Header Section Start -->
         <header id="header-sticky" class="header-1">
            <div class="container-fluid">
                <div class="mega-menu-wrapper">
                    <div class="header-main style-1">
                        <div class="logo">
                            <a href="{{ route('marketing.home') }}" class="header-logo">
                                <img src="{{ asset('marketing/assets/img/logo/dayaa-logo.png') }}" alt="Dayaa Logo" style="max-height: 50px;">
                            </a>
                            <a href="{{ route('marketing.home') }}" class="header-logo-2">
                                <img src="{{ asset('marketing/assets/img/logo/dayaa-logo.png') }}" alt="Dayaa Logo" style="max-height: 50px;">
                            </a>
                        </div>
                        <div class="mean__menu-wrapper">
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li class="{{ request()->routeIs('marketing.home') ? 'active' : '' }}">
                                            <a href="{{ route('marketing.home') }}">{{ __('marketing.nav.home') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('marketing.about') ? 'active' : '' }}">
                                            <a href="{{ route('marketing.about') }}">{{ __('marketing.nav.about') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('marketing.features') ? 'active' : '' }}">
                                            <a href="{{ route('marketing.features') }}">{{ __('marketing.nav.features') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('marketing.shop.*') ? 'active' : '' }} has-dropdown">
                                            <a href="{{ route('marketing.shop.index') }}">{{ __('marketing.nav.shop') }}</a>
                                            <ul class="submenu">
                                                <li><a href="{{ route('marketing.shop.index') }}">{{ __('marketing.shop.all_products') }}</a></li>
                                                <li><a href="{{ route('marketing.cart.index') }}">{{ __('marketing.cart.title') }}</a></li>
                                            </ul>
                                        </li>
                                        <li class="{{ request()->routeIs('marketing.pricing') ? 'active' : '' }}">
                                            <a href="{{ route('marketing.pricing') }}">{{ __('marketing.nav.pricing') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('marketing.faq') ? 'active' : '' }}">
                                            <a href="{{ route('marketing.faq') }}">{{ __('marketing.nav.faq') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('marketing.contact') ? 'active' : '' }}">
                                            <a href="{{ route('marketing.contact') }}">{{ __('marketing.nav.contact') }}</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="header-right d-flex justify-content-end align-items-center gap-3">
                            <!-- Language Switcher -->
                            <div class="language-switcher">
                                <x-language-switcher type="simple" />
                            </div>

                            <!-- Cart Icon with Badge -->
                            <div class="cart-icon-wrapper position-relative" id="cartIconWrapper" style="display: none;">
                                <a href="{{ route('marketing.cart.index') }}" class="cart-icon-link">
                                    <i class="fa-solid fa-shopping-cart" style="font-size: 20px; color: #0F69F3;"></i>
                                    <span class="cart-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartBadge">
                                        0
                                    </span>
                                </a>
                            </div>

                            <a href="{{ route('marketing.get-started') }}" class="pp-theme-btn">
                                {{ __('marketing.nav.get_started') }} <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                            <div class="header__hamburger d-xl-none my-auto">
                                <div class="sidebar__toggle">
                                    <div class="header-bar style-1">
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        @yield('content')

         <!-- Pp Footer Section Start -->
        <footer class="pp-footer-section section-bg-2">
            <div class="top-shape">
                <img src="{{ asset('marketing/assets/img/home-1/bg-shape.png') }}" alt="img">
            </div>
            <div class="container">
                <div class="pp-footer-widget-wrapper">
                    <div class="row justify-content-between">
                        <div class="col-xl-5 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                            <div class="pp-footer-widget-items">
                                <div class="pp-widget-head">
                                    <a href="{{ route('marketing.home') }}" class="pp-footer-logo">
                                        <img src="{{ asset('marketing/assets/img/logo/dayaa-logo-transparent.png') }}" alt="Dayaa Logo" style="max-height: 250px;">
                                    </a>
                                </div>
                                <div class="pp-footer-content">
                                    <p>
                                        {{ __('marketing.footer.tagline') }}
                                    </p>
                                    <div class="social-icon d-flex align-items-center">
                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#"><i class="fab fa-twitter"></i></a>
                                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 ps-lg-5 wow fadeInUp" data-wow-delay=".4s">
                            <div class="pp-footer-widget-items">
                                <div class="pp-widget-head">
                                    <h3>{{ __('marketing.footer.quick_links') }}</h3>
                                </div>
                                <ul class="pp-list-area">
                                    <li>
                                        <a href="{{ route('marketing.home') }}">{{ __('marketing.footer.home') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.about') }}">{{ __('marketing.footer.about') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.features') }}">{{ __('marketing.footer.features') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.pricing') }}">{{ __('marketing.footer.pricing') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.shop.index') }}">{{ __('marketing.footer.shop') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 ps-lg-5 wow fadeInUp" data-wow-delay=".6s">
                            <div class="pp-footer-widget-items">
                                <div class="pp-widget-head">
                                    <h3>{{ __('marketing.footer.resources') }}</h3>
                                </div>
                                <ul class="pp-list-area">
                                    <li>
                                        <a href="{{ route('marketing.faq') }}">{{ __('marketing.footer.faq') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.contact') }}">{{ __('marketing.footer.support') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.contact') }}">{{ __('marketing.footer.documentation') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.contact') }}">{{ __('marketing.footer.contact_us') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('marketing.get-started') }}">{{ __('marketing.footer.get_started') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ps-lg-2 wow fadeInUp" data-wow-delay=".8s">
                            <div class="pp-footer-widget-items">
                                <div class="pp-widget-head">
                                    <h3>{{ __('marketing.footer.ready_to_start') }}</h3>
                                </div>
                                <div class="pp-contact-info">
                                    <div class="pp-icon">
                                        <i class="fa-regular fa-envelope"></i>
                                    </div>
                                    <div class="pp-content">
                                        <h6>
                                            <a href="mailto:info@dayaatech.de">info@dayaatech.de</a> <br>
                                            <a href="mailto:support@dayaatech.de">support@dayaatech.de</a>
                                        </h6>
                                    </div>
                                </div>
                                <div class="pp-contact-info mb-0">
                                    <div class="pp-icon">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div class="pp-content">
                                        <h6>
                                            <a href="tel:+491234567890">+49 123 456 7890</a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom3">
                <div class="container">
                    <div class="pp-footer-bottom-wrapper">
                        <p class="wow fadeInUp" data-wow-delay=".3s">Copyright &copy; {{ date('Y') }} <b>Dayaa</b>. {{ __('marketing.footer.rights') }}</p>
                        <ul class="pp-footer-list wow fadeInUp" data-wow-delay=".5s">
                            <li>
                                <a href="{{ route('marketing.contact') }}">{{ __('marketing.footer.terms_conditions') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('marketing.contact') }}">{{ __('marketing.footer.privacy') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('marketing.contact') }}">{{ __('marketing.footer.contact_us') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

        <!--<< All JS Plugins >>-->
        <script src="{{ asset('marketing/assets/js/jquery-3.7.1.min.js') }}"></script>
        <!--<< Viewport Js >>-->
        <script src="{{ asset('marketing/assets/js/viewport.jquery.js') }}"></script>
        <!--<< Bootstrap Js >>-->
        <script src="{{ asset('marketing/assets/js/bootstrap.bundle.min.js') }}"></script>
        <!--<< nice-selec Js >>-->
        <script src="{{ asset('marketing/assets/js/jquery.nice-select.min.js') }}"></script>
        <!--<< Waypoints Js >>-->
        <script src="{{ asset('marketing/assets/js/jquery.waypoints.js') }}"></script>
        <!--<< Counterup Js >>-->
        <script src="{{ asset('marketing/assets/js/jquery.counterup.min.js') }}"></script>
        <!--<< Swiper Slider Js >>-->
        <script src="{{ asset('marketing/assets/js/swiper-bundle.min.js') }}"></script>
        <!--<< MeanMenu Js >>-->
        <script src="{{ asset('marketing/assets/js/jquery.meanmenu.min.js') }}"></script>
        <!--<< Magnific Popup Js >>-->
        <script src="{{ asset('marketing/assets/js/jquery.magnific-popup.min.js') }}"></script>
        <!--<< Wow Animation Js >>-->
        <script src="{{ asset('marketing/assets/js/wow.min.js') }}"></script>
        <!--<< Main.js >>-->
        <script src="{{ asset('marketing/assets/js/main.js') }}"></script>

        <!-- Cart Count Update Script -->
        <script>
        function updateCartCount() {
            fetch('{{ route("marketing.cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const cartWrapper = document.getElementById('cartIconWrapper');
                    const cartBadge = document.getElementById('cartBadge');

                    if (data.count > 0) {
                        cartWrapper.style.display = 'block';
                        cartBadge.textContent = data.count;
                    } else {
                        cartWrapper.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching cart count:', error));
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
        </script>

        @stack('scripts')
    </body>
</html>
