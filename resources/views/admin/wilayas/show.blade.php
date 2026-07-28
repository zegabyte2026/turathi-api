@extends('admin.layout')

@section('title', $wilaya->name['fr'] ?? __('admin.wilayas_title'))

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #0D211D;">
    <div class="max-w-7xl mx-auto space-y-8">

        <a href="{{ route('admin.wilayas.index') }}" class="inline-flex items-center gap-2 text-sm font-medium" style="color: #3E8E7E;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('admin.wilayas_back') }}
        </a>

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold" style="color: #F3E9CF;">{{ $wilaya->name['fr'] ?? __('admin.wilayas_title') }}</h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.wilayas.edit', $wilaya) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-5 py-2.5 transition-colors" style="color: #0D211D; background-color: #F3E9CF;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('admin.common_edit') }}
                </a>
                <form action="{{ route('admin.wilayas.destroy', $wilaya) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.common_confirm_delete_wilaya') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-5 py-2.5 transition-colors" style="color: #F3E9CF; background-color: #B85C38;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        {{ __('admin.common_delete') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="rounded-xl p-6" style="background-color: #3E8E7E;">
                    <h2 class="text-lg font-bold mb-4" style="color: #F3E9CF;">{{ __('admin.wilayas_info') }}</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: #0D211D;">{{ __('admin.wilayas_name_french') }}</label>
                            <p class="text-sm" style="color: #F3E9CF;">{{ $wilaya->name['fr'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: #0D211D;">{{ __('admin.wilayas_name_arabic') }}</label>
                            <p class="text-sm font-arabic" dir="rtl" style="color: #F3E9CF;">{{ $wilaya->name['ar'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: #0D211D;">{{ __('admin.wilayas_name_english') }}</label>
                            <p class="text-sm" style="color: #F3E9CF;">{{ $wilaya->name['en'] ?? '—' }}</p>
                        </div>

                        <hr style="border-color: #0D211D;">

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: #0D211D;">{{ __('admin.wilayas_desc_french') }}</label>
                            <p class="text-sm whitespace-pre-line" style="color: #F3E9CF;">{{ $wilaya->description['fr'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: #0D211D;">{{ __('admin.wilayas_desc_arabic') }}</label>
                            <p class="text-sm whitespace-pre-line font-arabic" dir="rtl" style="color: #F3E9CF;">{{ $wilaya->description['ar'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider mb-1" style="color: #0D211D;">{{ __('admin.wilayas_desc_english') }}</label>
                            <p class="text-sm whitespace-pre-line" style="color: #F3E9CF;">{{ $wilaya->description['en'] ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl p-6" style="background-color: #3E8E7E;">
                    <h2 class="text-lg font-bold mb-4" style="color: #F3E9CF;">{{ __('admin.wilayas_sites_linked') }} <span class="text-xs font-normal" style="color: #0D211D;">({{ $wilaya->sites_count }})</span></h2>
                    @if($wilaya->sites->count())
                        <div class="space-y-2">
                            @foreach($wilaya->sites as $site)
                                <div class="flex items-center justify-between rounded-lg px-4 py-3" style="background-color: #0D211D;">
                                    <span class="text-sm font-medium" style="color: #F3E9CF;">{{ $site->name['fr'] ?? '—' }}</span>
                                    <a href="{{ route('admin.sites.show', $site) }}" class="text-xs font-medium" style="color: #3E8E7E;">{{ __('admin.common_view') }}</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm" style="color: #0D211D;">{{ __('admin.wilayas_no_sites_linked') }}</p>
                    @endif
                </div>
            </div>

            <div class="space-y-6">

                <div class="rounded-xl p-6" style="background-color: #3E8E7E;">
                    <h2 class="text-lg font-bold mb-4" style="color: #F3E9CF;">{{ __('admin.wilayas_cover_image') }}</h2>
                    @if($wilaya->cover_image)
                        <div class="rounded-lg overflow-hidden" style="background-color: #0D211D;">
                            <img src="{{ asset('storage/' . $wilaya->cover_image) }}" alt="{{ __('admin.wilayas_cover_image') }}" class="w-full h-48 object-cover">
                        </div>
                    @else
                        <p class="text-sm" style="color: #0D211D;">{{ __('admin.wilayas_no_cover_image') }}</p>
                    @endif
                </div>

                <div class="rounded-xl p-6" style="background-color: #3E8E7E;">
                    <h2 class="text-lg font-bold mb-4" style="color: #F3E9CF;">{{ __('admin.wilayas_stats') }}</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between rounded-lg px-4 py-3" style="background-color: #0D211D;">
                            <span class="text-sm" style="color: #F3E9CF;">{{ __('admin.wilayas_sites_count_label') }}</span>
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold" style="background-color: #B85C38; color: #F3E9CF;">{{ $wilaya->sites_count }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
