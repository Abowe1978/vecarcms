<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.available_locales')[app()->getLocale()]['rtl'] ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Scripts (Admin uses separate JS without Alpine) -->
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/css/admin-bar.css', 'resources/js/admin.js'])
    
    <!-- Page Builder Scripts (only loaded when needed) -->
    @stack('pagebuilder-scripts')

    <!-- Livewire Styles -->
    @livewireStyles
    <script>
            window.Laravel = @json(['csrfToken' => csrf_token(), 'userId' => auth()->check() ? auth()->id() : null]);
        </script>
    <style>
        [x-cloak] { display: none !important; }

        /* RTL Support */
        [dir="rtl"] .ml-2 {
            margin-left: 0;
            margin-right: 0.5rem;
        }
        [dir="rtl"] .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }
        [dir="rtl"] .space-x-2 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        /* Layout */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 46px;
            bottom: 0;
            z-index: 50;
            transition: width 0.3s ease-in-out;
        }

        .sidebar.expanded {
            width: 16rem;
        }

        .sidebar.collapsed {
            width: 4rem;
        }

        .main-content {
            flex: 1;
            transition: margin 0.3s ease-in-out;
        }

        .main-content.sidebar-expanded {
            margin-left: 16rem;
        }

        .main-content.sidebar-collapsed {
            margin-left: 4rem;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0 !important;
            }
            .sidebar {
                width: 16rem;
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
        }

        [dir="rtl"] .sidebar {
            right: 0;
            left: auto;
        }

        [dir="rtl"] .main-content.sidebar-expanded {
            margin-left: 0;
            margin-right: 16rem;
        }

        [dir="rtl"] .main-content.sidebar-collapsed {
            margin-left: 0;
            margin-right: 4rem;
        }

        @media (max-width: 768px) {
            [dir="rtl"] .main-content {
                margin-right: 0 !important;
            }
            [dir="rtl"] .sidebar {
                transform: translateX(100%);
            }
            [dir="rtl"] .sidebar.mobile-open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    
    {{-- WordPress-like Admin Bar (in backend too) --}}
    @include('admin.partials.admin-bar')
    
    <div id="admin-app">
        <div class="admin-layout bg-gray-100"
             style="padding-top: 46px;"
             x-data="{
                sidebarOpen: true,
                isMobile: window.innerWidth < 768
             }"
             @resize.window="isMobile = window.innerWidth < 768">
            <!-- Include Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <div class="main-content flex flex-col min-h-screen"
                 :class="{'sidebar-expanded': sidebarOpen && !isMobile, 'sidebar-collapsed': !sidebarOpen && !isMobile}">
                <!-- Header -->
                <header class="bg-white shadow-sm sticky z-40" style="top: 46px;">
                    <div class="flex justify-between items-center px-4 py-3">
                        <!-- Sidebar toggle button -->
                        <button @click="sidebarOpen = !sidebarOpen"
                                class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-bars"></i>
                        </button>

                        <!-- Search -->
                        <div class="flex-1 max-w-xl px-4">
                            <div class="relative flex items-center">
                                <i class="fas fa-search absolute {{ config('app.available_locales')[app()->getLocale()]['rtl'] ? 'right-3' : 'left-3' }} text-gray-400"></i>
                                <input type="text"
                                    placeholder="{{ __('admin.header.search') }}"
                                    class="w-full {{ config('app.available_locales')[app()->getLocale()]['rtl'] ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500">
                            </div>
                        </div>

                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-grow p-4">
                    @if(session('success'))
                    <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                    @endif

                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="py-4 px-6 border-t border-gray-200 bg-white">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            VeCarCMS - Developed by VeCar Digital Programming
                        </div>
                        <div class="text-xs text-gray-500">
                            Version 1.0.0
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    @livewireScripts

    <!-- Initialize Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('layout', {
                sidebarOpen: true,
                isMobile: window.innerWidth < 768
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
