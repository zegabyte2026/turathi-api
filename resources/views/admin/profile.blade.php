@extends('admin.layout')

@section('title', __('admin.profile_title'))

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #0D211D;">
    <div class="max-w-3xl mx-auto space-y-8">

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-4 py-2 transition-colors" style="color: #F3E9CF; background-color: #3E8E7E;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                {{ __('admin.common_back') }}
            </a>
            <h1 class="text-2xl font-bold" style="color: #F3E9CF;">{{ __('admin.profile_title') }}</h1>
        </div>

        @if(session('success'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium" style="background-color: #3E8E7E; color: #F3E9CF;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-lg px-4 py-3 text-sm" style="background-color: #B85C38; color: #F3E9CF;">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="rounded-xl p-6 space-y-6" style="background-color: #3E8E7E;">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.profile_subtitle') }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.profile_name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                        @error('name')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.profile_email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                        @error('email')
                            <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider" style="color: #F3E9CF;">{{ __('admin.field_password') }}</h3>

                <div>
                    <label for="current_password" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.profile_current_password') }} *</label>
                    <input type="password" name="current_password" id="current_password" required
                           placeholder="{{ __('admin.profile_current_password_placeholder') }}"
                           class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                    @error('current_password')
                        <p class="mt-1 text-xs" style="color: #F3E9CF;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.profile_new_password') }}</label>
                        <input type="password" name="password" id="password"
                               placeholder="{{ __('admin.profile_new_password_placeholder') }}"
                               class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold mb-1" style="color: #F3E9CF;">{{ __('admin.profile_confirm_password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               placeholder="{{ __('admin.profile_confirm_password_placeholder') }}"
                               class="w-full rounded-lg px-3 py-2 text-sm border-0 focus:ring-2" style="background-color: #0D211D; color: #F3E9CF;">
                    </div>
                </div>
                <p class="text-xs" style="color: rgba(243,233,207,0.7);">{{ __('admin.profile_password_hint') }}</p>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #0D211D; background-color: #F3E9CF;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ __('admin.profile_save') }}
                </button>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium rounded-lg px-6 py-2.5 transition-colors" style="color: #F3E9CF; background-color: #0D211D; border: 1px solid #3E8E7E;">
                    {{ __('admin.common_cancel') }}
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
