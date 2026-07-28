@extends('admin.layout')

@section('title', __('admin.sites_edit_title'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.sites.show', $site) }}" class="text-[#3E8E7E] hover:underline font-semibold">&larr; {{ __('admin.common_back') }}</a>
        </div>

        <h1 class="text-3xl font-bold text-[#0D211D] mb-8">{{ __('admin.sites_edit_title') }}</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.sites.update', $site) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-8">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="wilaya_id" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.sites_wilaya') }} *</label>
                <select name="wilaya_id" id="wilaya_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    <option value="">{{ __('admin.sites_select_wilaya') }}</option>
                    @foreach($wilayas as $wilaya)
                        <option value="{{ $wilaya->id }}" {{ old('wilaya_id', $site->wilaya_id) == $wilaya->id ? 'selected' : '' }}>
                            {{ $wilaya->name['fr'] ?? '—' }}
                        </option>
                    @endforeach
                </select>
                @error('wilaya_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="name_fr" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_name_fr') }} *</label>
                    <input type="text" name="name[fr]" id="name_fr" value="{{ old('name.fr', $site->name['fr'] ?? '') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    @error('name.fr')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="name_ar" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_name_ar') }}</label>
                    <input type="text" name="name[ar]" id="name_ar" value="{{ old('name.ar', $site->name['ar'] ?? '') }}" dir="rtl"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    @error('name.ar')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="name_en" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_name_en') }}</label>
                    <input type="text" name="name[en]" id="name_en" value="{{ old('name.en', $site->name['en'] ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    @error('name.en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="description_fr" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_description_fr') }}</label>
                    <textarea name="description[fr]" id="description_fr" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">{{ old('description.fr', $site->description['fr'] ?? '') }}</textarea>
                    @error('description.fr')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="description_ar" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_description_ar') }}</label>
                    <textarea name="description[ar]" id="description_ar" rows="4" dir="rtl"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">{{ old('description.ar', $site->description['ar'] ?? '') }}</textarea>
                    @error('description.ar')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="description_en" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_description_en') }}</label>
                    <textarea name="description[en]" id="description_en" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">{{ old('description.en', $site->description['en'] ?? '') }}</textarea>
                    @error('description.en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="cover_image" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_cover_image') }}</label>
                @if($site->cover_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $site->cover_image) }}" alt="{{ __('admin.sites_current_image') }}" class="h-32 rounded-lg object-cover">
                        <p class="text-sm text-gray-500 mt-1">{{ __('admin.sites_current_image') }}</p>
                    </div>
                @endif
                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                @error('cover_image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-3 gap-6 mb-8">
                <div>
                    <label for="latitude" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_latitude') }}</label>
                    <input type="number" name="latitude" id="latitude" step="any" value="{{ old('latitude', $site->latitude) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    @error('latitude')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_longitude') }}</label>
                    <input type="number" name="longitude" id="longitude" step="any" value="{{ old('longitude', $site->longitude) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    @error('longitude')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="altitude" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.field_altitude') }}</label>
                    <input type="number" name="altitude" id="altitude" step="any" value="{{ old('altitude', $site->altitude) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    @error('altitude')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="images" class="block text-sm font-semibold text-[#0D211D] mb-2">{{ __('admin.endroits_images') }} — Site</label>
                @if(is_array($site->images) && count($site->images))
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($site->images as $image)
                            @if(is_string($image))
                            <div class="rounded-md overflow-hidden relative group">
                                <img src="{{ asset('storage/' . $image) }}" alt="" class="w-20 h-20 object-cover rounded">
                                <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="hidden peer/rm-{{ $loop->index }}">
                                <label for="rm-img-{{ $loop->index }}" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs cursor-pointer opacity-80 hover:opacity-100">✕</label>
                                <label for="rm-img-{{ $loop->index }}" class="absolute inset-0 cursor-pointer hidden peer-checked/rm-{{ $loop->index }}:block" style="background-color: rgba(220,38,38,0.7);"></label>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    @foreach($site->images as $image)
                        @if(is_string($image))
                        <input type="hidden" name="existing_images[]" value="{{ $image }}">
                        @endif
                    @endforeach
                @endif
                <input type="file" name="images[]" id="images" multiple accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Images de présentation du site</p>
                @error('images')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <h3 class="text-sm font-semibold text-[#0D211D] mb-3">Audio de présentation (3 langues)</h3>
                @php $siteAudios = is_array($site->audio_paths) ? $site->audio_paths : []; @endphp
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-[#0D211D] mb-1">Français</label>
                        @if(!empty($siteAudios['fr']))
                            <div class="mb-2 flex items-center gap-2">
                                <audio controls src="{{ asset('storage/' . $siteAudios['fr']) }}" class="h-8 max-w-full"></audio>
                                <a href="{{ asset('storage/' . $siteAudios['fr']) }}" download class="text-xs underline text-[#3E8E7E]">Télécharger</a>
                            </div>
                            <input type="hidden" name="existing_audio_fr" value="{{ $siteAudios['fr'] }}">
                        @endif
                        <input type="file" name="audio_fr" id="audio_fr" accept="audio/*"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#0D211D] mb-1">العربية</label>
                        @if(!empty($siteAudios['ar']))
                            <div class="mb-2 flex items-center gap-2">
                                <audio controls src="{{ asset('storage/' . $siteAudios['ar']) }}" class="h-8 max-w-full"></audio>
                                <a href="{{ asset('storage/' . $siteAudios['ar']) }}" download class="text-xs underline text-[#3E8E7E]">Télécharger</a>
                            </div>
                            <input type="hidden" name="existing_audio_ar" value="{{ $siteAudios['ar'] }}">
                        @endif
                        <input type="file" name="audio_ar" id="audio_ar" accept="audio/*"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#0D211D] mb-1">English</label>
                        @if(!empty($siteAudios['en']))
                            <div class="mb-2 flex items-center gap-2">
                                <audio controls src="{{ asset('storage/' . $siteAudios['en']) }}" class="h-8 max-w-full"></audio>
                                <a href="{{ asset('storage/' . $siteAudios['en']) }}" download class="text-xs underline text-[#3E8E7E]">Télécharger</a>
                            </div>
                            <input type="hidden" name="existing_audio_en" value="{{ $siteAudios['en'] }}">
                        @endif
                        <input type="file" name="audio_en" id="audio_en" accept="audio/*"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#3E8E7E] focus:border-transparent">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Audio de présentation générale du site (3 langues)</p>
            </div>

            <div class="mb-8 flex items-center gap-3">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $site->is_published) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-[#3E8E7E] focus:ring-[#3E8E7E]">
                <label for="is_published" class="text-sm font-semibold text-[#0D211D]">{{ __('admin.common_published') }}</label>
                <span class="text-xs text-gray-500">— {{ __('admin.sites_visible_mobile') }}</span>
            </div>

            <button type="submit"
                    class="w-full bg-[#3E8E7E] text-white py-3 rounded-lg hover:bg-[#2d7a6a] transition font-semibold shadow-md">
                {{ __('admin.common_update') }}
            </button>
        </form>
    </div>
</div>
@endsection
