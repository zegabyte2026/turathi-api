<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $currentLocale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('admin.dashboard_title')) - {{ __('admin.brand') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Reem+Kufi:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink950: '#0D211D',
                        ink900: '#132E28',
                        ink800: '#1A3D35',
                        ink700: '#234F45',
                        sand: '#F3E9CF',
                        sandLight: '#FAF6EE',
                        sandDark: '#E8D9B8',
                        teal: '#3E8E7E',
                        tealLight: '#4FA997',
                        tealDark: '#347769',
                        clay: '#B85C38',
                        clayLight: '#C9744F',
                        clayDark: '#9E4E2E',
                    },
                    fontFamily: {
                        arabic: ['Reem Kufi', 'sans-serif'],
                        sans: ['IBM Plex Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; }
        .font-arabic { font-family: 'Reem Kufi', sans-serif; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #234F45; border-radius: 4px; }

        /* RTL overrides — mobile: sidebar off-screen right, main has no margin */
        [dir="rtl"] .sidebar {
            left: auto !important;
            right: 0 !important;
            transform: translateX(100%) !important;
        }
        [dir="rtl"] .sidebar.is-open {
            transform: translateX(0) !important;
        }
        /* Desktop: sidebar is static + in flex flow, no extra margin needed */
        @media (min-width: 1024px) {
            [dir="rtl"] .sidebar {
                transform: translateX(0) !important;
            }
        }
    </style>
</head>
<body class="bg-sand min-h-screen" x-data="{ sidebarOpen: false, isRtl: {{ $currentLocale === 'ar' ? 'true' : 'false' }} }">

    <div class="flex min-h-screen">

        <!-- Mobile overlay -->
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        ></div>

        <!-- Sidebar -->
        <aside
            x-bind:class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen && !isRtl,
                'translate-x-full': !sidebarOpen && isRtl,
            }"
            x-bind:style="isRtl ? 'right:0;left:auto' : 'left:0;right:auto'"
            class="sidebar fixed inset-y-0 z-50 w-64 bg-ink950 flex flex-col transition-transform duration-300 ease-in-out lg:!translate-x-0 lg:static lg:z-auto"
        >
            <div class="flex items-center gap-3 px-6 py-5 border-b border-ink700/50">
                <div class="w-9 h-9 rounded-lg bg-teal flex items-center justify-center">
                    <span class="text-white font-arabic font-bold text-sm">T</span>
                </div>
                <div>
                    <h1 class="text-white font-arabic font-bold text-lg leading-tight">{{ __('admin.brand') }}</h1>
                    <p class="text-tealLight text-xs">{{ __('admin.brand_subtitle') }}</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                    </svg>
                    {{ __('admin.sidebar_dashboard') }}
                </a>

                @if($admin->isSuperAdmin())
                <a
                    href="{{ route('admin.wilayas.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.wilayas.*') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('admin.sidebar_wilayas') }}
                </a>
                @endif

                <a
                    href="{{ route('admin.sites.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.sites.*') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('admin.sidebar_sites') }}
                </a>

                <a
                    href="{{ route('admin.endroits.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.endroits.*') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('admin.sidebar_endroits') }}
                </a>

                <a
                    href="{{ route('admin.objets.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.objets.*') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    {{ __('admin.sidebar_objets') }}
                </a>

                <a
                    href="{{ route('admin.qr.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.qrcodes.*') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    {{ __('admin.sidebar_qr_codes') }}
                </a>

                <a
                    href="{{ route('admin.visitors.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.visitors.*') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('admin.sidebar_visitors') }}
                </a>

                @if($admin->isSuperAdmin())
                <div class="pt-3 mt-3 border-t border-ink700/50">
                    <p class="px-3 mb-1 text-xs font-semibold text-ink700 uppercase tracking-wider">{{ __('admin.sidebar_administration') }}</p>
                </div>

                <a
                    href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-teal/20 text-tealLight' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    {{ __('admin.sidebar_users') }}
                </a>
                @endif
            </nav>

            <!-- Language switcher -->
            <div class="px-4 py-3 border-t border-ink700/50">
                <div class="flex items-center justify-center gap-1">
                    @foreach(['fr' => 'FR', 'ar' => 'العربية', 'en' => 'EN'] as $code => $label)
                        <a
                            href="{{ route('admin.lang', $code) }}"
                            class="px-2.5 py-1 text-xs font-semibold rounded transition-colors {{ $currentLocale === $code ? 'bg-teal text-white' : 'text-sandDark hover:bg-ink800 hover:text-white' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="px-4 py-4 border-t border-ink700/50">
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 hover:bg-ink800 rounded-lg px-2 py-1.5 transition-colors -mx-2">
                    <div class="w-8 h-8 rounded-full bg-teal/30 flex items-center justify-center">
                        <span class="text-tealLight text-xs font-semibold">{{ substr($admin->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-medium truncate">{{ $admin->name }}</p>
                        <p class="text-ink700 text-xs truncate">{{ $admin->email }}</p>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main content -->
        <div class="main-wrap flex-1 flex flex-col min-w-0">

            <!-- Top bar -->
            <header class="sticky top-0 z-30 bg-sandLight/80 backdrop-blur-md border-b border-sandDark/50">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                    <div class="flex items-center gap-4">
                        <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 rounded-lg text-ink800 hover:bg-sandDark/50 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h2 class="text-lg font-semibold text-ink950">@yield('title', __('admin.dashboard_title'))</h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="hidden sm:block text-sm text-ink700">{{ $admin->name }}</span>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            @method('POST')
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-clay hover:text-clayDark hover:bg-clay/10 rounded-lg transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="hidden sm:inline">{{ __('admin.logout') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
