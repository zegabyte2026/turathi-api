@extends('admin.layout')

@section('title', __('admin.visitor_profile'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.visitors.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold rounded-lg px-4 py-2 bg-[#3E8E7E] text-white hover:bg-[#2d7a6a] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ __('admin.common_back') }}
            </a>
            <h1 class="text-2xl font-bold text-[#0D211D]">{{ $visitor->name ?? __('admin.visitors_anonymous') }}</h1>
            @if($visitor->is_blocked)
                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-[#B85C38] text-white">{{ __('admin.visitors_blocked_badge') }}</span>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium bg-[#3E8E7E] text-white">{{ session('success') }}</div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-md p-5 text-center">
                <p class="text-3xl font-bold text-[#C89B3C]">{{ $visitor->total_scans }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.visitors_scans') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-5 text-center">
                @php
                    $uniqueSites = $visitor->scanLogs()->whereNotNull('site_id')->distinct('site_id')->count();
                @endphp
                <p class="text-3xl font-bold text-[#3E8E7E]">{{ $uniqueSites }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.visitors_unique_sites') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-5 text-center">
                @php
                    $uniqueEndroits = $visitor->scanLogs()->whereNotNull('endroit_id')->distinct('endroit_id')->count();
                @endphp
                <p class="text-3xl font-bold text-[#B85C38]">{{ $uniqueEndroits }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.visitors_unique_endroits') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-5 text-center">
                <p class="text-3xl font-bold text-[#0D211D]">{{ $visitor->created_at->diffForHumans() }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('admin.visitors_joined') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Profile Card --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.visitor_profile') }}</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.visitors_name') }}</label>
                            <p class="text-sm font-semibold text-[#0D211D]">{{ $visitor->name ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email</label>
                            <p class="text-sm text-[#0D211D]">{{ $visitor->email ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.visitors_phone') }}</label>
                            <p class="text-sm text-[#0D211D]">{{ $visitor->phone ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Device ID</label>
                            <p class="text-xs font-mono text-gray-600 break-all">{{ $visitor->device_id ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- IP & Location --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.visitors_ip_location') }}</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">IP Address</label>
                            @if($visitor->ip_address)
                                <p class="text-sm font-mono bg-gray-100 px-3 py-1.5 rounded-lg text-[#0D211D]">{{ $visitor->ip_address }}</p>
                            @else
                                <p class="text-sm text-gray-400">—</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.visitors_location') }}</label>
                            <p class="text-sm text-[#0D211D]">{{ $visitor->formatted_location }}</p>
                        </div>
                        @if($visitor->latitude && $visitor->longitude)
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.visitors_coordinates') }}</label>
                            <p class="text-xs font-mono text-gray-600">{{ $visitor->latitude }}, {{ $visitor->longitude }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.visitors_last_seen') }}</label>
                            <p class="text-sm text-[#0D211D]">{{ $visitor->last_seen_at ? $visitor->last_seen_at->format('d/m/Y H:i:s') : '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.common_actions') }}</h2>
                    <div class="flex flex-col gap-3">
                        <form action="{{ route('admin.visitors.block', $visitor) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-sm font-semibold rounded-lg px-4 py-2.5 transition-colors {{ $visitor->is_blocked ? 'bg-[#3E8E7E] text-white hover:bg-[#2d7a6a]' : 'bg-[#B85C38] text-white hover:bg-[#a04e2f]' }}">
                                {{ $visitor->is_blocked ? __('admin.visitors_unblock') : __('admin.visitors_block') }}
                            </button>
                        </form>
                        @if($admin->isSuperAdmin())
                        <form action="{{ route('admin.visitors.delete', $visitor) }}" method="POST" onsubmit="return confirm('{{ __('admin.common_confirm_delete') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-sm font-semibold rounded-lg px-4 py-2.5 bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">
                                {{ __('admin.common_delete') }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Scan History --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.visitors_scan_history') }}</h2>
                    @if($scanLogs->count())
                        <div class="space-y-2">
                            @foreach($scanLogs as $log)
                                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                            @if($log->qr_code_id && str_starts_with($log->qr_code_id, 'OBJ')) bg-[#C89B3C]/10 text-[#C89B3C]
                                            @elseif($log->qr_code_id && str_starts_with($log->qr_code_id, 'END')) bg-[#B85C38]/10 text-[#B85C38]
                                            @else bg-[#3E8E7E]/10 text-[#3E8E7E] @endif">
                                            @if($log->qr_code_id && str_starts_with($log->qr_code_id, 'OBJ')) OBJ
                                            @elseif($log->qr_code_id && str_starts_with($log->qr_code_id, 'END')) END
                                            @else SITE
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-[#0D211D]">{{ $log->site->name['fr'] ?? '—' }}{{ $log->endroit ? ' → ' . ($log->endroit->title['fr'] ?? '—') : '' }}</p>
                                            <p class="text-xs text-gray-400 font-mono">{{ $log->qr_code_id }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                        @if($log->ip_address)
                                            <br><span class="text-[10px] font-mono text-gray-400">{{ $log->ip_address }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">{{ $scanLogs->links() }}</div>
                    @else
                        <p class="text-sm text-center text-gray-400 py-6">{{ __('admin.visitors_no_scans') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
