@extends('admin.layout')

@section('title', __('admin.users_new'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] p-6">
    <div class="max-w-2xl mx-auto">

        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-[#3E8E7E] hover:underline font-semibold mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('admin.users_back') }}
        </a>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-[#0D211D] mb-6">{{ __('admin.users_new') }}</h1>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-[#0D211D] mb-1">{{ __('admin.users_nom') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3E8E7E] focus:border-[#3E8E7E] outline-none transition">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#0D211D] mb-1">{{ __('admin.field_email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3E8E7E] focus:border-[#3E8E7E] outline-none transition">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-[#0D211D] mb-1">{{ __('admin.field_password') }}</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3E8E7E] focus:border-[#3E8E7E] outline-none transition">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#0D211D] mb-1">{{ __('admin.field_password_confirm') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3E8E7E] focus:border-[#3E8E7E] outline-none transition">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-[#0D211D] mb-1">{{ __('admin.field_role') }}</label>
                        <select name="role" id="role" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3E8E7E] focus:border-[#3E8E7E] outline-none transition bg-white">
                            <option value="local_admin" {{ old('role') === 'local_admin' ? 'selected' : '' }}>{{ __('admin.users_local_admin') }}</option>
                            <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>{{ __('admin.users_super_admin') }}</option>
                        </select>
                    </div>

                    <div id="site-field" class="{{ old('role', 'local_admin') === 'super_admin' ? 'hidden' : '' }}">
                        <label for="site_id" class="block text-sm font-semibold text-[#0D211D] mb-1">{{ __('admin.users_site_assigned') }}</label>
                        <select name="site_id" id="site_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3E8E7E] focus:border-[#3E8E7E] outline-none transition bg-white">
                            <option value="">{{ __('admin.users_select_site') }}</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                    {{ $site->name['fr'] ?? '—' }} — {{ $site->wilaya->name['fr'] ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.users_local_admin_hint') }}</p>
                    </div>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="submit"
                            class="bg-[#3E8E7E] text-white px-6 py-2.5 rounded-lg hover:bg-[#2d7a6a] transition font-semibold shadow-md text-sm">
                        {{ __('admin.common_create') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-6 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-[#0D211D] hover:bg-gray-50 transition">
                        {{ __('admin.common_cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        document.getElementById('site-field').classList.toggle('hidden', this.value === 'super_admin');
    });
</script>
@endsection
