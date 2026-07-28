@extends('admin.layout')

@section('title', $objet->title['fr'] ?? 'Objet')

@section('content')
<div class="min-h-screen bg-[#F3E9CF] p-6">
    <div class="max-w-7xl mx-auto">

        <a href="{{ route('admin.objets.index') }}" class="inline-flex items-center gap-2 text-sm text-[#3E8E7E] hover:underline font-semibold mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour aux objets
        </a>

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-[#0D211D]">{{ $objet->title['fr'] ?? 'Objet' }}</h1>
                <p class="text-sm text-gray-500 mt-1">Endroit : <a href="{{ route('admin.endroits.show', $objet->endroit) }}" class="text-[#3E8E7E] hover:underline">{{ $objet->endroit->title['fr'] ?? '—' }}</a></p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.objets.edit', $objet) }}"
                   class="bg-[#B85C38] text-white px-5 py-2.5 rounded-lg hover:bg-[#9E4E2E] transition font-semibold shadow-md text-sm">
                    Éditer
                </a>
                <form action="{{ route('admin.objets.destroy', $objet) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet objet ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 transition font-semibold shadow-md text-sm">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">Informations</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Titre FR</label>
                                <p class="text-[#0D211D] text-sm">{{ $objet->title['fr'] ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Titre AR</label>
                                <p class="text-[#0D211D] text-sm font-arabic" dir="rtl">{{ $objet->title['ar'] ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Titre EN</label>
                                <p class="text-[#0D211D] text-sm">{{ $objet->title['en'] ?? '—' }}</p>
                            </div>
                        </div>
                        <hr class="border-gray-200">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Description FR</label>
                                <p class="text-[#0D211D] text-sm whitespace-pre-line">{{ $objet->description['fr'] ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Description AR</label>
                                <p class="text-[#0D211D] text-sm whitespace-pre-line font-arabic" dir="rtl">{{ $objet->description['ar'] ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Description EN</label>
                                <p class="text-[#0D211D] text-sm whitespace-pre-line">{{ $objet->description['en'] ?? '—' }}</p>
                            </div>
                        </div>
                        <hr class="border-gray-200">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Matériau</label>
                                <p class="text-[#0D211D] text-sm">{{ $objet->materiau ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Période</label>
                                <p class="text-[#0D211D] text-sm">{{ $objet->periode ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Dimensions</label>
                                <p class="text-[#0D211D] text-sm">{{ $objet->dimensions ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">Statut</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Publication</label>
                            @if($objet->is_published)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Publié</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">QR Code</label>
                            <p class="text-[#0D211D] text-sm font-mono">{{ $objet->qr_code_id ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">Photos</h2>
                    @if(!empty($objet->images) && is_array($objet->images) && count($objet->images))
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($objet->images as $image)
                                @if(is_string($image))
                                <div class="rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ Storage::url($image) }}" alt="Photo objet" class="w-full h-28 object-cover">
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm">Aucune photo.</p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-[#0D211D] mb-4">Audio</h2>
                    @php $audios = is_array($objet->audio_paths) ? $objet->audio_paths : []; @endphp
                    @if(count($audios))
                        <div class="space-y-3">
                            @foreach(['fr' => 'Français', 'ar' => 'Arabe', 'en' => 'English'] as $key => $label)
                                @if(!empty($audios[$key]))
                                <div class="flex items-center gap-3 p-2 rounded-lg" style="background-color: #F3E9CF;">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded uppercase" style="background-color: #3E8E7E; color: white;">{{ $key }}</span>
                                    <span class="text-sm text-[#0D211D] font-medium">{{ $label }}</span>
                                    <audio controls src="{{ asset('storage/' . $audios[$key]) }}" class="h-8 flex-1"></audio>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 text-sm">Aucun audio.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
