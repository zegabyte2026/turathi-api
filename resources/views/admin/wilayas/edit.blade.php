@extends('admin.layout')

@section('title', __('admin.wilayas_edit_prefix') . ($wilaya->name['fr'] ?? ''))

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #0D211D;">
    <div class="max-w-3xl mx-auto space-y-8">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.wilayas.show', $wilaya) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-4 py-2 transition-colors" style="color: #F3E9CF; background-color: #3E8E7E;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ __('admin.common_back') }}
            </a>
            <h1 class="text-2xl font-bold" style="color: #F3E9CF;">{{ __('admin.wilayas_edit_prefix') }} {{ $wilaya->name['fr'] ?? __('admin.wilayas_title') }}</h1>
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

        <form action="{{ route('admin.wilayas.update', $wilaya) }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-6 space-y-6" style="background-color: #3E8E7E;">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.field_name') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="name_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_french') }}</label>
                        <input type="text" name="name[fr]" id="name_fr" value="{{ old('name.fr', $wilaya->name['fr'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" required>
                        @error('name.fr')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="name_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_arabic') }}</label>
                        <input type="text" name="name[ar]" id="name_ar" value="{{ old('name.ar', $wilaya->name['ar'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" dir="rtl" required>
                        @error('name.ar')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="name_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_english') }}</label>
                        <input type="text" name="name[en]" id="name_en" value="{{ old('name.en', $wilaya->name['en'] ?? '') }}" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;" required>
                        @error('name.en')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.field_description') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="description_fr" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_french') }}</label>
                        <textarea name="description[fr]" id="description_fr" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;">{{ old('description.fr', $wilaya->description['fr'] ?? '') }}</textarea>
                        @error('description.fr')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description_ar" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_arabic') }}</label>
                        <textarea name="description[ar]" id="description_ar" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;" dir="rtl">{{ old('description.ar', $wilaya->description['ar'] ?? '') }}</textarea>
                        @error('description.ar')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description_en" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.lang_english') }}</label>
                        <textarea name="description[en]" id="description_en" rows="4" class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2 resize-y" style="background-color: #0D211D; color: #F3E9CF;">{{ old('description.en', $wilaya->description['en'] ?? '') }}</textarea>
                        @error('description.en')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="cover_image" class="block text-sm font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.field_cover_image') }}</label>
                @if($wilaya->cover_image)
                    <div class="mb-3 rounded-md overflow-hidden inline-block" style="background-color: #0D211D;">
                        <img src="{{ asset('storage/' . $wilaya->cover_image) }}" alt="" class="w-32 h-20 object-cover">
                    </div>
                @endif
                <input type="file" name="cover_image" id="cover_image" class="w-full rounded-lg px-3 py-2 text-sm border-0 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:cursor-pointer" style="background-color: #0D211D; color: #F3E9CF;">
                @error('cover_image')
                    <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #0D211D; background-color: #F3E9CF;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ __('admin.common_save') }}
                </button>
                <a href="{{ route('admin.wilayas.show', $wilaya) }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #F3E9CF; background-color: #0D211D; border: 1px solid #3E8E7E;">
                    {{ __('admin.common_cancel') }}
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
