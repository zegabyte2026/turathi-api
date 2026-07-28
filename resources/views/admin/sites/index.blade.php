@extends('admin.layout')

@section('title', __('admin.sidebar_sites'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-[#0D211D]">{{ __('admin.sidebar_sites') }}</h1>
            @if($admin->isSuperAdmin())
                <a href="{{ route('admin.sites.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold rounded-lg px-5 py-2.5 bg-[#3E8E7E] text-white hover:bg-[#2d7a6a] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    {{ __('admin.common_add') }}
                </a>
            @endif
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.field_name_fr') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.sites_wilaya') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.endroits_title') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.common_status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.common_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sites as $site)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $site->id }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.sites.show', $site) }}" class="text-sm font-semibold text-[#0D211D] hover:underline">{{ $site->name['fr'] ?? '—' }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $site->wilaya->name['fr'] ?? '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#3E8E7E]/10 text-[#3E8E7E]">{{ $site->endroits_count ?? $site->endroits->count() }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($site->is_published)
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#3E8E7E]/10 text-[#3E8E7E]">{{ __('admin.common_published') }}</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#B85C38]/10 text-[#B85C38]">{{ __('admin.common_draft') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.sites.show', $site) }}" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="{{ __('admin.common_view') }}">
                                        <svg class="w-4 h-4 text-[#3E8E7E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.sites.edit', $site) }}" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="{{ __('admin.common_edit') }}">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.qr.export', $site) }}" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="{{ __('admin.qr_print') }}" target="_blank">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    @if($admin->isSuperAdmin())
                                    <form action="{{ route('admin.sites.destroy', $site) }}" method="POST" onsubmit="return confirm('{{ __('admin.common_confirm_delete') }}');">
                                        @csrf @method('DELETE')
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
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">{{ __('admin.common_no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $sites->links() }}</div>
    </div>
</div>
@endsection
