@extends('admin.layout')

@section('title', __('admin.endroits_edit_prefix') . ($endroit->title['fr'] ?? __('admin.endroits_title')))

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #0D211D;">
    <div class="max-w-3xl mx-auto space-y-8">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.endroits.show', $endroit) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-4 py-2 transition-colors" style="color: #F3E9CF; background-color: #3E8E7E;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ __('admin.common_back') }}
            </a>
            <h1 class="text-2xl font-bold" style="color: #F3E9CF;">{{ __('admin.endroits_edit_prefix') }} {{ $endroit->title['fr'] ?? __('admin.endroits_title') }}</h1>
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

        <form action="{{ route('admin.endroits.update', $endroit) }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-6 space-y-6" style="background-color: #3E8E7E;">
            @csrf
            @method('PUT')

            <div>
                <label for="site_id" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.endroits_site') }}</label>
                <select name="site_id" id="site_id" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" required>
                    <option value="">{{ __('admin.endroits_select_site') }}</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}" {{ old('site_id', $endroit->site_id) == $site->id ? 'selected' : '' }}>
                            {{ $site->name['fr'] ?? __('admin.endroits_no_name') }} — {{ $site->wilaya->name['fr'] ?? '' }}
                        </option>
                    @endforeach
                </select>
                @error('site_id')
                    <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.endroits_title_section') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="title_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_french') }}</label>
                        <input type="text" name="title[fr]" id="title_fr" value="{{ old('title.fr', $endroit->title['fr'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" required>
                        @error('title.fr')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_arabic') }}</label>
                        <input type="text" name="title[ar]" id="title_ar" value="{{ old('title.ar', $endroit->title['ar'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" dir="rtl">
                        @error('title.ar')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_english') }}</label>
                        <input type="text" name="title[en]" id="title_en" value="{{ old('title.en', $endroit->title['en'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                        @error('title.en')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.endroits_desc_section') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="description_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_french') }}</label>
                        <textarea name="description[fr]" id="description_fr" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;">{{ old('description.fr', $endroit->description['fr'] ?? '') }}</textarea>
                        @error('description.fr')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_arabic') }}</label>
                        <textarea name="description[ar]" id="description_ar" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;" dir="rtl">{{ old('description.ar', $endroit->description['ar'] ?? '') }}</textarea>
                        @error('description.ar')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_english') }}</label>
                        <textarea name="description[en]" id="description_en" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;">{{ old('description.en', $endroit->description['en'] ?? '') }}</textarea>
                        @error('description.en')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.field_latitude') }}</label>
                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $endroit->latitude ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                    @error('latitude')
                        <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.field_longitude') }}</label>
                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $endroit->longitude ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                    @error('longitude')
                        <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="altitude" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.field_altitude') }}</label>
                    <input type="number" step="any" name="altitude" id="altitude" value="{{ old('altitude', $endroit->altitude ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                    @error('altitude')
                        <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="images" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.endroits_images') }}</label>
                @if(is_array($endroit->images) && count($endroit->images))
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($endroit->images as $image)
                            @if(is_string($image))
                            <div class="rounded-md overflow-hidden relative group" style="background-color: #0D211D;">
                                <img src="{{ asset('storage/' . $image) }}" alt="" class="w-20 h-20 object-cover">
                                <label class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs cursor-pointer opacity-80 hover:opacity-100">✕</label>
                                <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="hidden peer/remove-img-{{ $loop->index }}">
                                <label for="remove-img-{{ $loop->index }}" class="absolute inset-0 cursor-pointer hidden peer-checked/remove-img-{{ $loop->index }}:block" style="background-color: rgba(220,38,38,0.7);"></label>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @foreach($endroit->images as $image)
                        @if(is_string($image))
                        <input type="hidden" name="existing_images[]" value="{{ $image }}">
                        @endif
                    @endforeach
                @endif
                <input type="file" name="images[]" id="images" multiple class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                <p class="mt-1 text-xs" style="color: rgba(243,233,207,0.7);">{{ __('admin.endroits_images_hint') }}</p>
                @error('images')
                    <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.endroits_audio_per_lang') }}</h3>
                @php $prevAudios = is_array($endroit->audio_paths) ? $endroit->audio_paths : []; @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="audio_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_french') }}</label>
                        @if(!empty($prevAudios['fr']))
                            <div class="mb-2 flex items-center gap-2">
                                <audio controls src="{{ asset('storage/' . $prevAudios['fr']) }}" class="h-8 max-w-full"></audio>
                                <a href="{{ asset('storage/' . $prevAudios['fr']) }}" download class="text-xs underline" style="color: #3E8E7E;">{{ __('admin.endroits_download') }}</a>
                            </div>
                            <input type="hidden" name="existing_audio_fr" value="{{ $prevAudios['fr'] }}">
                        @endif
                        <input type="file" name="audio_fr" id="audio_fr" accept="audio/*" class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                    <div>
                        <label for="audio_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_arabic') }}</label>
                        @if(!empty($prevAudios['ar']))
                            <div class="mb-2 flex items-center gap-2">
                                <audio controls src="{{ asset('storage/' . $prevAudios['ar']) }}" class="h-8 max-w-full"></audio>
                                <a href="{{ asset('storage/' . $prevAudios['ar']) }}" download class="text-xs underline" style="color: #3E8E7E;">{{ __('admin.endroits_download') }}</a>
                            </div>
                            <input type="hidden" name="existing_audio_ar" value="{{ $prevAudios['ar'] }}">
                        @endif
                        <input type="file" name="audio_ar" id="audio_ar" accept="audio/*" class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                    <div>
                        <label for="audio_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_english') }}</label>
                        @if(!empty($prevAudios['en']))
                            <div class="mb-2 flex items-center gap-2">
                                <audio controls src="{{ asset('storage/' . $prevAudios['en']) }}" class="h-8 max-w-full"></audio>
                                <a href="{{ asset('storage/' . $prevAudios['en']) }}" download class="text-xs underline" style="color: #3E8E7E;">{{ __('admin.endroits_download') }}</a>
                            </div>
                            <input type="hidden" name="existing_audio_en" value="{{ $prevAudios['en'] }}">
                        @endif
                        <input type="file" name="audio_en" id="audio_en" accept="audio/*" class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                </div>
                <p class="text-xs" style="color: rgba(243,233,207,0.7);">{{ __('admin.endroits_audio_keep_hint') }}</p>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $endroit->is_published) ? 'checked' : '' }} class="w-4 h-4 rounded border-0 focus:ring-2" style="background-color: #0D211D; color: #3E8E7E;">
                <label for="is_published" class="text-sm font-semibold" style="color: #F3E9CF;">{{ __('admin.common_published') }}</label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #0D211D; background-color: #F3E9CF;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ __('admin.common_save') }}
                </button>
                <a href="{{ route('admin.endroits.show', $endroit) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #F3E9CF; background-color: #0D211D; border: 1px solid #3E8E7E;">
                    {{ __('admin.common_cancel') }}
                </a>
            </div>
        </form>

    </div>
</div>
@endsection