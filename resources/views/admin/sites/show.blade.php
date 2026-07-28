@extends('admin.layout')

@section('title', $site->name['fr'] ?? __('admin.endroits_site'))

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #0D211D;">
    <div class="max-w-5xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.sites.index') }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-4 py-2 transition-colors" style="color: #F3E9CF; background-color: #3E8E7E;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ __('admin.sites_back') }}
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.sites.edit', $site) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-4 py-2 transition-colors" style="color: #0D211D; background-color: #3E8E7E;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('admin.common_edit') }}
                </a>
                @if($admin->isSuperAdmin())
                <form action="{{ route('admin.sites.destroy', $site) }}" method="POST" onsubmit="return confirm('{{ __('admin.common_confirm_delete_site') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-4 py-2 transition-colors" style="color: #F3E9CF; background-color: #B85C38;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        {{ __('admin.common_delete') }}
                    </button>
                </form>
                @endif
            </div>
        </div>

        <div class="rounded-xl p-6 space-y-6" style="background-color: #3E8E7E; color: #0D211D;">
            <h2 class="text-2xl font-bold" style="color: #F3E9CF;">{{ $site->name['fr'] ?? __('admin.endroits_site') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.sites_name_french') }}</span>
                        <p class="mt-1 text-sm">{{ $site->name['fr'] ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.sites_name_arabic') }}</span>
                        <p class="mt-1 text-sm" dir="rtl">{{ $site->name['ar'] ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.sites_name_english') }}</span>
                        <p class="mt-1 text-sm">{{ $site->name['en'] ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.sites_wilaya') }}</span>
                        <p class="mt-1 text-sm">{{ $site->wilaya->name['fr'] ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.sites_coordinates') }}</span>
                        <p class="mt-1 text-sm">{{ $site->latitude ?? '—' }}, {{ $site->longitude ?? '—' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.common_status') }}</span>
                        <p class="mt-1">
                            @if($site->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background-color: #0D211D; color: #3E8E7E;">{{ __('admin.common_published') }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background-color: #B85C38; color: #F3E9CF;">{{ __('admin.common_draft') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div>
                    @if($site->cover_image)
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.sites_cover_image') }}</span>
                        <div class="mt-2 rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $site->cover_image) }}" alt="{{ $site->name['fr'] ?? __('admin.endroits_site') }}" class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @else
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.sites_cover_image') }}</span>
                        <div class="mt-2 rounded-lg overflow-hidden flex items-center justify-center h-48" style="background-color: #0D211D;">
                            <span class="text-sm" style="color: #B85C38;">{{ __('admin.sites_no_cover_image') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-xl p-6" style="background-color: #3E8E7E; color: #0D211D;">
            <h3 class="text-xl font-bold mb-6" style="color: #F3E9CF;">{{ __('admin.sites_images_section') ?? 'Photos du site' }}</h3>
            @php $siteImages = is_array($site->images) ? $site->images : []; @endphp
            @if(count($siteImages))
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($siteImages as $image)
                        @if(is_string($image))
                        <div class="rounded-lg overflow-hidden border border-[#0D211D]/30 relative group">
                            <img src="{{ asset('storage/' . $image) }}" alt="Photo site" class="w-full h-32 object-cover">
                            <a href="{{ asset('storage/' . $image) }}" download
                               class="absolute bottom-1 right-1 bg-[#0D211D]/80 text-white px-2 py-0.5 rounded text-xs font-semibold opacity-0 group-hover:opacity-100 transition flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                {{ __('admin.endroits_download') }}
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-[#0D211D]/50 text-sm">{{ __('admin.sites_no_images') ?? 'Aucune photo' }}</p>
            @endif
        </div>

        <div class="rounded-xl p-6" style="background-color: #3E8E7E; color: #0D211D;">
            <h3 class="text-xl font-bold mb-6" style="color: #F3E9CF;">{{ __('admin.sites_audio_section') ?? 'Audios du site' }}</h3>
            @php $siteAudios = is_array($site->audio_paths) ? $site->audio_paths : []; @endphp
            @if(count($siteAudios))
                <div class="space-y-3">
                    @foreach(['fr' => __('admin.lang_french'), 'ar' => __('admin.lang_arabic'), 'en' => __('admin.lang_english')] as $key => $label)
                        @if(!empty($siteAudios[$key]))
                        <div class="flex items-center gap-3 p-2 rounded-lg" style="background-color: #0D211D;">
                            <span class="px-2 py-0.5 text-xs font-bold rounded uppercase" style="background-color: #3E8E7E; color: #F3E9CF;">{{ $key }}</span>
                            <span class="text-sm font-medium" style="color: #F3E9CF;">{{ $label }}</span>
                            <audio controls preload="none" src="{{ asset('storage/' . $siteAudios[$key]) }}" class="h-8 flex-1"></audio>
                            <a href="{{ asset('storage/' . $siteAudios[$key]) }}" download class="text-xs font-semibold underline" style="color: #B85C38;">{{ __('admin.endroits_download') }}</a>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-[#0D211D]/50 text-sm">{{ __('admin.sites_no_audio') ?? 'Aucun audio' }}</p>
            @endif
        </div>

        <div class="rounded-xl p-6" style="background-color: #3E8E7E; color: #0D211D;">
            <div class="flex items-center gap-3 mb-6">
                <h3 class="text-xl font-bold" style="color: #F3E9CF;">{{ __('admin.sites_endroits_section') }}</h3>
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold" style="background-color: #0D211D; color: #3E8E7E;">{{ $site->endroits->count() }}</span>
            </div>
            @if($site->endroits->count())
                <div class="space-y-3">
                    @foreach($site->endroits as $endroit)
                        <div class="flex items-center justify-between rounded-lg px-4 py-3" style="background-color: #0D211D;">
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-medium" style="color: #F3E9CF;">{{ $endroit->title['fr'] ?? __('admin.sites_untitled') }}</span>
                                @if($endroit->is_published)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold" style="background-color: #3E8E7E; color: #F3E9CF;">{{ __('admin.common_published') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold" style="background-color: #B85C38; color: #F3E9CF;">{{ __('admin.common_draft') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.endroits.edit', $endroit) }}" class="inline-flex items-center gap-1 text-xs font-medium rounded-md px-3 py-1.5 transition-colors" style="color: #F3E9CF; background-color: #3E8E7E;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    {{ __('admin.common_edit') }}
                                </a>
                                <form action="{{ route('admin.endroits.destroy', $endroit) }}" method="POST" onsubmit="return confirm('{{ __('admin.common_confirm_delete_endroit') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium rounded-md px-3 py-1.5 transition-colors" style="color: #F3E9CF; background-color: #B85C38;">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        {{ __('admin.common_delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 rounded-lg" style="background-color: #0D211D;">
                    <p class="text-sm" style="color: #B85C38;">{{ __('admin.sites_no_endroits') }}</p>
                </div>
            @endif
        </div>

        <div class="rounded-xl p-6" style="background-color: #3E8E7E; color: #0D211D;">
            <div class="flex items-center gap-3 mb-6">
                <h3 class="text-xl font-bold" style="color: #F3E9CF;">{{ __('admin.qr_title') }}</h3>
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold" style="background-color: #0D211D; color: #3E8E7E;">{{ $site->qrCodes->count() }}</span>
            </div>
            @if($site->qrCodes->count())
                <div class="space-y-3">
                    @foreach($site->qrCodes as $qr)
                        <div class="flex items-center justify-between rounded-lg px-4 py-3" style="background-color: #0D211D;">
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-mono font-medium" style="color: #3E8E7E;">{{ substr($qr->qr_code_id, 0, 12) }}...</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold" style="background-color: #3E8E7E; color: #F3E9CF;">{{ $qr->type ?? '—' }}</span>
                                @if($qr->endroit)
                                    <span class="text-xs" style="color: #F3E9CF;">→ {{ $qr->endroit->title['fr'] ?? __('admin.endroits_title') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 rounded-lg" style="background-color: #0D211D;">
                    <p class="text-sm" style="color: #B85C38;">{{ __('admin.sites_no_qr_codes') }}</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
