@extends('admin.layout')

@section('title', __('admin.sidebar_wilayas'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-[#0D211D]">{{ __('admin.sidebar_wilayas') }}</h1>
            @if($admin->isSuperAdmin())
                <a href="{{ route('admin.wilayas.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold rounded-lg px-5 py-2.5 bg-[#3E8E7E] text-white hover:bg-[#2d7a6a] transition-colors">
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.field_name_ar') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.sidebar_sites') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.common_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($wilayas as $wilaya)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $wilaya->id }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-[#0D211D]">{{ $wilaya->name['fr'] ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-[#0D211D]" dir="rtl">{{ $wilaya->name['ar'] ?? '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#3E8E7E]/10 text-[#3E8E7E]">{{ $wilaya->sites_count }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.wilayas.show', $wilaya) }}" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="{{ __('admin.common_view') }}">
                                        <svg class="w-4 h-4 text-[#3E8E7E]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.wilayas.edit', $wilaya) }}" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors" title="{{ __('admin.common_edit') }}">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    @if($admin->isSuperAdmin())
                                    <form action="{{ route('admin.wilayas.destroy', $wilaya) }}" method="POST" onsubmit="return confirm('{{ __('admin.common_confirm_delete') }}');">
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
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">{{ __('admin.common_no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $wilayas->links() }}</div>
    </div>
</div>
@endsection
