@extends('admin.layout')

@section('title', __('admin.dashboard_title'))

@section('content')
<div class="space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-sand font-kufi">{{ __('admin.dashboard_title') }}</h1>
            <p class="text-sand/50 text-sm mt-1">{{ __('admin.dashboard_welcome') }} <span class="text-teal font-medium">{{ $admin->name }}</span></p>
        </div>
        <div class="flex items-center gap-2 text-xs text-sand/30">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            <span>{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-ink900 border border-ink800 rounded-xl p-5 hover:border-teal/30 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-teal/10 flex items-center justify-center group-hover:bg-teal/15 transition-colors">
                    <svg class="w-6 h-6 text-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-sand">{{ $sites }}</p>
                    <p class="text-xs text-sand/40 mt-0.5">{{ __('admin.dashboard_total_sites') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-ink900 border border-ink800 rounded-xl p-5 hover:border-clay/30 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-clay/10 flex items-center justify-center group-hover:bg-clay/15 transition-colors">
                    <svg class="w-6 h-6 text-clay" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-sand">{{ $endroits }}</p>
                    <p class="text-xs text-sand/40 mt-0.5">{{ __('admin.dashboard_total_endroits') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-ink900 border border-ink800 rounded-xl p-5 hover:border-gold/30 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-gold/10 flex items-center justify-center group-hover:bg-gold/15 transition-colors">
                    <svg class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-sand">{{ $users }}</p>
                    <p class="text-xs text-sand/40 mt-0.5">{{ __('admin.dashboard_total_users') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-ink900 border border-ink800 rounded-xl p-5 hover:border-teal/30 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-teal/10 flex items-center justify-center group-hover:bg-teal/15 transition-colors">
                    <svg class="w-6 h-6 text-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-sand">{{ $published }}</p>
                    <p class="text-xs text-sand/40 mt-0.5">{{ __('admin.dashboard_published_endroits') }}</p>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div class="p-6 rounded-xl" style="background-color: #3E8E7E;">
            <h3 class="text-sm font-semibold uppercase tracking-wider" style="color: #0D211D;">{{ __('admin.visitors_title') }}</h3>
            <p class="text-2xl font-bold mt-2" style="color: #F3E9CF;">{{ $stats['visitors_count'] }}</p>
        </div>
        <div class="p-6 rounded-xl" style="background-color: #3E8E7E;">
            <h3 class="text-sm font-semibold uppercase tracking-wider" style="color: #0D211D;">{{ __('admin.visitors_scans') }}</h3>
            <p class="text-2xl font-bold mt-2" style="color: #F3E9CF;">{{ $stats['total_scans'] }}</p>
        </div>
        <div class="p-6 rounded-xl" style="background-color: #3E8E7E;">
            <h3 class="text-sm font-semibold uppercase tracking-wider" style="color: #0D211D;">{{ __('admin.dashboard_blocked') }}</h3>
            <p class="text-2xl font-bold mt-2" style="color: #F3E9CF;">{{ $stats['blocked_visitors'] }}</p>
        </div>
        <div class="p-6 rounded-xl" style="background-color: #3E8E7E;">
            <h3 class="text-sm font-semibold uppercase tracking-wider" style="color: #0D211D;">{{ __('admin.dashboard_active') }}</h3>
            <p class="text-2xl font-bold mt-2" style="color: #F3E9CF;">{{ $stats['active_visitors_today'] }}</p>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-sand/70 mb-4 font-kufi">{{ __('admin.dashboard_quick_access') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <a href="{{ route('admin.sites.index') }}" class="flex items-center gap-4 bg-ink900 border border-ink800 rounded-xl p-5 hover:border-teal/40 hover:bg-teal/5 transition-all group">
                <div class="shrink-0 w-10 h-10 rounded-lg bg-teal/10 flex items-center justify-center group-hover:bg-teal/20 transition-colors">
                    <svg class="w-5 h-5 text-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-sand group-hover:text-teal transition-colors">{{ __('admin.dashboard_manage_sites') }}</p>
                    <p class="text-xs text-sand/30 mt-0.5">{{ __('admin.dashboard_sites_subtitle') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.endroits.index') }}" class="flex items-center gap-4 bg-ink900 border border-ink800 rounded-xl p-5 hover:border-clay/40 hover:bg-clay/5 transition-all group">
                <div class="shrink-0 w-10 h-10 rounded-lg bg-clay/10 flex items-center justify-center group-hover:bg-clay/20 transition-colors">
                    <svg class="w-5 h-5 text-clay" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-sand group-hover:text-clay transition-colors">{{ __('admin.dashboard_manage_endroits') }}</p>
                    <p class="text-xs text-sand/30 mt-0.5">{{ __('admin.dashboard_endroits_subtitle') }}</p>
                </div>
            </a>

            <a href="{{ route('admin.qr.index') }}" class="flex items-center gap-4 bg-ink900 border border-ink800 rounded-xl p-5 hover:border-gold/40 hover:bg-gold/5 transition-all group">
                <div class="shrink-0 w-10 h-10 rounded-lg bg-gold/10 flex items-center justify-center group-hover:bg-gold/20 transition-colors">
                    <svg class="w-5 h-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-sand group-hover:text-gold transition-colors">{{ __('admin.dashboard_qr_codes') }}</p>
                    <p class="text-xs text-sand/30 mt-0.5">{{ __('admin.dashboard_qr_subtitle') }}</p>
                </div>
            </a>

            @if($admin->isSuperAdmin())
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 bg-ink900 border border-ink800 rounded-xl p-5 hover:border-sand/40 hover:bg-sand/5 transition-all group">
                <div class="shrink-0 w-10 h-10 rounded-lg bg-sand/10 flex items-center justify-center group-hover:bg-sand/15 transition-colors">
                    <svg class="w-5 h-5 text-sand/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-sand group-hover:text-sand transition-colors">{{ __('admin.dashboard_users') }}</p>
                    <p class="text-xs text-sand/30 mt-0.5">{{ __('admin.dashboard_users_subtitle') }}</p>
                </div>
            </a>
            @endif

        </div>
    </div>

</div>
@endsection
