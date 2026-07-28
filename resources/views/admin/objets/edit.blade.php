@extends('admin.layout')

@section('title', 'Éditer: ' . ($objet->title['fr'] ?? 'Objet'))

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #0D211D;">
    <div class="max-w-3xl mx-auto space-y-8">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.objets.show', $objet) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-4 py-2 transition-colors" style="color: #F3E9CF; background-color: #3E8E7E;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Retour
            </a>
            <h1 class="text-2xl font-bold" style="color: #F3E9CF;">Éditer: {{ $objet->title['fr'] ?? 'Objet' }}</h1>
        </div>

        @if($errors->any())
            <div class="rounded-lg px-4 py-3 text-sm" style="background-color: #B85C38; color: #F3E9CF;">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.objets.update', $objet) }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-6 space-y-6" style="background-color: #3E8E7E;">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">Titre</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="title_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">Français</label>
                        <input type="text" name="title[fr]" id="title_fr" value="{{ old('title.fr', $objet->title['fr'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" required>
                        @error('title.fr') <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="title_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">Arabe</label>
                        <input type="text" name="title[ar]" id="title_ar" value="{{ old('title.ar', $objet->title['ar'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" dir="rtl">
                    </div>
                    <div>
                        <label for="title_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">English</label>
                        <input type="text" name="title[en]" id="title_en" value="{{ old('title.en', $objet->title['en'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">Description</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="description_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">Français</label>
                        <textarea name="description[fr]" id="description_fr" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;">{{ old('description.fr', $objet->description['fr'] ?? '') }}</textarea>
                        @error('description.fr') <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">Arabe</label>
                        <textarea name="description[ar]" id="description_ar" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;" dir="rtl">{{ old('description.ar', $objet->description['ar'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="description_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">English</label>
                        <textarea name="description[en]" id="description_en" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;">{{ old('description.en', $objet->description['en'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="materiau" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">Matériau</label>
                    <input type="text" name="materiau" id="materiau" value="{{ old('materiau', $objet->materiau ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                </div>
                <div>
                    <label for="periode" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">Période</label>
                    <input type="text" name="periode" id="periode" value="{{ old('periode', $objet->periode ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                </div>
                <div>
                    <label for="dimensions" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">Dimensions</label>
                    <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $objet->dimensions ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                </div>
            </div>

            @if(!empty($objet->images) && count($objet->images))
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #F3E9CF;">Photos actuelles</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($objet->images as $img)
                            @if(is_string($img))
                            <img src="{{ Storage::url($img) }}" class="w-full h-20 object-cover rounded-lg border border-white/20">
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <label for="images" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">Remplacer les photos (optionnel)</label>
                <input type="file" name="images[]" id="images" multiple class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">Audio (remplacer si besoin)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="audio_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">Français</label>
                        <input type="file" name="audio_fr" id="audio_fr" accept="audio/*" class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                    <div>
                        <label for="audio_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">Arabe</label>
                        <input type="file" name="audio_ar" id="audio_ar" accept="audio/*" class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                    <div>
                        <label for="audio_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">English</label>
                        <input type="file" name="audio_en" id="audio_en" accept="audio/*" class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $objet->is_published) ? 'checked' : '' }} class="w-4 h-4 rounded border-0 focus:ring-2" style="background-color: #0D211D; color: #3E8E7E;">
                <label for="is_published" class="text-sm font-semibold" style="color: #F3E9CF;">Publié</label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #0D211D; background-color: #F3E9CF;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Mettre à jour
                </button>
                <a href="{{ route('admin.objets.show', $objet) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #F3E9CF; background-color: #0D211D; border: 1px solid #3E8E7E;">
                    Annuler
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
