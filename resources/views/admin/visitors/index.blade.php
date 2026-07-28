@extends('admin.layout')

@section('title', __('admin.sidebar_visitors'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-[#0D211D]">{{ __('admin.sidebar_visitors') }} <span class="text-sm font-normal text-gray-400">({{ $visitors->total() }})</span></h1>
        </div>

        @if(session('success'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium bg-[#3E8E7E] text-white">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium bg-[#B85C38] text-white">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.common_name') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.visitors_location') }}</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.visitors_scans') }}</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.visitors_last_seen') }}</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.common_status') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.common_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($visitors as $v)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-400">{{ $v->id }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.visitors.show', $v) }}" class="text-sm font-semibold text-[#0D211D] hover:underline">{{ $v->name ?? __('admin.visitors_anonymous') }}</a>
                            </td>
                            <td class="px-4 py-3 text-xs font-mono text-gray-600">
                                @if($v->ip_address)
                                    <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $v->ip_address }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                @if($v->city || $v->country)
                                    <span class="inline-flex items-center gap-1">
                                        @if($v->country)
                                            <span class="text-sm" title="{{ $v->country }}">{{ strtoupper($v->country) }}</span>
                                        @endif
                                        @if($v->city)
                                            <span>{{ $v->city }}</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#C89B3C]/10 text-[#C89B3C]">{{ $v->total_scans }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-xs text-gray-600">
                                {{ $v->last_seen_at?->diffForHumans() ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.visitors.block', $v) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full cursor-pointer transition-colors {{ $v->is_blocked ? 'bg-[#B85C38]/10 text-[#B85C38] hover:bg-[#B85C38]/20' : 'bg-[#3E8E7E]/10 text-[#3E8E7E] hover:bg-[#3E8E7E]/20' }}">
                                        {{ $v->is_blocked ? __('admin.visitors_blocked') : __('admin.visitors_active') }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.visitors.show', $v) }}" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="{{ __('admin.common_view') }}">
                                        <svg class="w-4 h-4 text-[#3E8E7E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @if($admin->isSuperAdmin())
                                    <form action="{{ route('admin.visitors.delete', $v) }}" method="POST" onsubmit="return confirm('{{ __('admin.common_confirm_delete') }}');">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="{{ __('admin.common_delete') }}">
                                            <svg class="w-4 h-4 text-[#B85C38]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-400">{{ __('admin.common_no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $visitors->links() }}</div>
    </div>
</div>
@endsection
