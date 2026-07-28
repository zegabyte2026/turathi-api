@extends('admin.layout')

@section('title', $endroit->title['fr'] ?? __('admin.endroits_title'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] p-6">
    <div class="max-w-7xl mx-auto">

        <a href="{{ route('admin.endroits.index') }}" class="inline-flex items-center gap-2 text-sm text-[#3E8E7E] hover:underline font-semibold mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('admin.endroits_back') }}
        </a>

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-[#0D211D]">{{ $endroit->title['fr'] ?? __('admin.endroits_title') }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.endroits.edit', $endroit) }}"
                   class="bg-[#B85C38] text-white px-5 py-2.5 rounded-lg hover:bg-[#9E4E2E] transition font-semibold shadow-md text-sm">
                    {{ __('admin.common_edit') }}
                </a>
                <form action="{{ route('admin.endroits.destroy', $endroit) }}" method="POST" class="inline"
                      onsubmit="return confirm('{{ __('admin.common_confirm_delete_endroit') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 transition font-semibold shadow-md text-sm">
                        {{ __('admin.common_delete') }}
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.endroits_info') }}</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.endroits_title_french') }}</label>
                            <p class="text-[#0D211D] text-sm">{{ $endroit->title['fr'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.endroits_title_arabic') }}</label>
                            <p class="text-[#0D211D] text-sm font-arabic" dir="rtl">{{ $endroit->title['ar'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.endroits_title_english') }}</label>
                            <p class="text-[#0D211D] text-sm">{{ $endroit->title['en'] ?? '—' }}</p>
                        </div>

                        <hr class="border-gray-200">

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.endroits_desc_french') }}</label>
                            <p class="text-[#0D211D] text-sm whitespace-pre-line">{{ $endroit->description['fr'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.endroits_desc_arabic') }}</label>
                            <p class="text-[#0D211D] text-sm whitespace-pre-line font-arabic" dir="rtl">{{ $endroit->description['ar'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.endroits_desc_english') }}</label>
                            <p class="text-[#0D211D] text-sm whitespace-pre-line">{{ $endroit->description['en'] ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.endroits_location') }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.endroits_site') }}</label>
                            <p class="text-[#0D211D] text-sm">{{ $endroit->site->name['fr'] ?? $endroit->site->name ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.common_status') }}</label>
                            @if($endroit->is_published)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ __('admin.common_published') }}</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('admin.common_draft') }}</span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.field_latitude') }}</label>
                            <p class="text-[#0D211D] text-sm">{{ $endroit->latitude ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('admin.field_longitude') }}</label>
                            <p class="text-[#0D211D] text-sm">{{ $endroit->longitude ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.endroits_images') }}</h2>
                    @if(!empty($endroit->images) && is_array($endroit->images) && count($endroit->images))
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($endroit->images as $image)
                                @if(is_string($image))
                                <div class="rounded-lg overflow-hidden border border-gray-200 relative group">
                                    <img src="{{ Storage::url($image) }}" alt="{{ __('admin.endroits_image_alt') }}" class="w-full h-28 object-cover">
                                    <a href="{{ Storage::url($image) }}" download
                                       class="absolute bottom-1 right-1 bg-[#0D211D]/80 text-white px-2 py-0.5 rounded text-xs font-semibold opacity-0 group-hover:opacity-100 transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        {{ __('admin.endroits_download') }}
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm">{{ __('admin.endroits_no_images') }}</p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">{{ __('admin.endroits_audio_section') }}</h2>
                    @php $audios = is_array($endroit->audio_paths) ? $endroit->audio_paths : []; @endphp
                    @if(count($audios))
                        <div class="space-y-3">
                            @foreach(['fr' => __('admin.lang_french'), 'ar' => __('admin.lang_arabic'), 'en' => __('admin.lang_english')] as $key => $label)
                                @if(!empty($audios[$key]))
                                <div class="flex items-center gap-3 p-2 rounded-lg" style="background-color: #F3E9CF;">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded uppercase" style="background-color: #3E8E7E; color: white;">{{ $key }}</span>
                                    <span class="text-sm text-[#0D211D] font-medium">{{ $label }}</span>
                                    <audio controls src="{{ asset('storage/' . $audios[$key]) }}" class="h-8 flex-1"></audio>
                                    <a href="{{ asset('storage/' . $audios[$key]) }}" download class="text-xs font-semibold underline" style="color: #B85C38;">{{ __('admin.endroits_download') }}</a>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm">{{ __('admin.endroits_no_audio') }}</p>
                    @endif
                </div>

            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-[#0D211D]">Objets ({{ $endroit->objets->count() }})</h2>
                <a href="{{ route('admin.objets.create') }}?endroit_id={{ $endroit->id }}"
                   class="bg-[#3E8E7E] text-white px-4 py-2 rounded-lg hover:bg-[#2E7E6E] transition font-semibold text-sm">
                    + Ajouter un objet
                </a>
            </div>

            @if($endroit->objets->count())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Titre</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Matériau</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Période</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">QR</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Photos</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Audio</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($endroit->objets as $objet)
                                @php
                                    $hasPhotos = !empty($objet->images) && is_array($objet->images) && count($objet->images) > 0;
                                    $hasAudio = !empty($objet->audio_paths) && is_array($objet->audio_paths) && count($objet->audio_paths) > 0;
                                    $isIncomplete = !$hasPhotos && !$hasAudio;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-[#0D211D]">{{ $objet->id }}</td>
                                    <td class="px-4 py-2 text-sm font-semibold text-[#0D211D]">
                                        <a href="{{ route('admin.objets.show', $objet) }}" class="hover:underline">{{ $objet->title['fr'] ?? '—' }}</a>
                                        @if($isIncomplete)
                                            <span class="ml-2 px-1.5 py-0.5 text-[10px] font-bold rounded bg-yellow-200 text-yellow-800">INCOMPLET</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-[#0D211D]">{{ $objet->materiau ?? '—' }}</td>
                                    <td class="px-4 py-2 text-sm text-[#0D211D]">{{ $objet->periode ?? '—' }}</td>
                                    <td class="px-4 py-2 text-xs text-[#0D211D] font-mono">{{ $objet->qr_code_id ?? '—' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $hasPhotos ? count($objet->images) . ' img' : '—' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $hasAudio ? count($objet->audio_paths) . ' lang' : '—' }}</td>
                                    <td class="px-4 py-2 text-right space-x-2">
                                        <a href="{{ route('admin.objets.edit', $objet) }}" class="text-[#B85C38] hover:underline text-sm font-semibold">Éditer</a>
                                        <form action="{{ route('admin.objets.destroy', $objet) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet objet ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm font-semibold">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-400 text-sm text-center py-4">Aucun objet pour cet endroit.</p>
            @endif
        </div>

    </div>
</div>
@endsection