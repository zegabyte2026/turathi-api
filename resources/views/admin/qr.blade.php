@extends('admin.layout')

@section('title', __('admin.qr_title'))

@section('content')
<div class="min-h-screen bg-[#F3E9CF] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-[#0D211D]">{{ __('admin.qr_title') }}</h1>
        </div>

        @if(session('success'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium bg-[#3E8E7E] text-white">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rounded-lg px-4 py-3 text-sm font-medium bg-[#B85C38] text-white">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-bold mb-2 text-[#0D211D]">{{ __('admin.qr_generate_section') }}</h2>
            <p class="text-xs mb-4 text-gray-500">{{ __('admin.qr_generate_hint') }}</p>
            <form action="{{ route('admin.qr.generate') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                @csrf
                <div class="flex-1">
                    <label for="site_id" class="block text-xs font-semibold mb-1 text-gray-600">{{ __('admin.qr_site_label') }}</label>
                    <select name="site_id" id="site_id" required class="w-full rounded-lg px-3 py-2 text-sm text-[#0D211D] border border-gray-200 focus:ring-2 focus:ring-[#3E8E7E] focus:border-[#3E8E7E]">
                        <option value="" disabled selected>{{ __('admin.qr_select_site') }}</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name['fr'] ?? $site->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 text-sm font-semibold rounded-lg px-6 py-2.5 bg-[#0D211D] text-white hover:bg-[#132E28] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    {{ __('admin.qr_generate_button') }}
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.qr_type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.qr_site_label') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.endroits_title') }} / Objet</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">QR ID</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.common_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($qrCodes as $qr)
                        @php
                            $site = $qr->site;
                            $endroit = $qr->endroit;
                            $objet = $qr->objet;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm">
                                @if($qr->type === 'site')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#3E8E7E]/10 text-[#3E8E7E]">Site</span>
                                @elseif($qr->type === 'objet')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#C89B3C]/10 text-[#C89B3C]">Objet</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-[#B85C38]/10 text-[#B85C38]">Endroit</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#0D211D]">
                                @if($site)
                                    <a href="{{ route('admin.sites.show', $site) }}" class="hover:underline">{{ $site->name['fr'] ?? '—' }}</a>
                                @elseif($qr->type === 'objet' && $objet && $objet->endroit && $objet->endroit->site)
                                    <a href="{{ route('admin.sites.show', $objet->endroit->site) }}" class="hover:underline">{{ $objet->endroit->site->name['fr'] ?? '—' }}</a>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#0D211D]">
                                @if($qr->type === 'objet' && $objet)
                                    <span class="font-semibold">{{ $objet->title['fr'] ?? '—' }}</span>
                                    @if($objet->endroit)
                                        <br><span class="text-xs text-gray-400">→ {{ $objet->endroit->title['fr'] ?? '' }}</span>
                                    @endif
                                @elseif($qr->type === 'endroit' && $endroit)
                                    <span class="font-semibold">{{ $endroit->title['fr'] ?? '—' }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-xs font-mono text-gray-400">{{ $qr->qr_code_id }}</td>
                            <td class="px-6 py-4 text-right">
                                @if($site || ($qr->type === 'objet' && $objet && $objet->endroit && $objet->endroit->site))
                                <a href="{{ route('admin.qr.export', $qr->site_id ?? ($qr->type === 'objet' && $objet && $objet->endroit ? $objet->endroit->site_id : null)) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-lg px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors" target="_blank">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    {{ __('admin.qr_print') }}
                                </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">{{ __('admin.qr_empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $qrCodes->links() }}</div>
    </div>
</div>
@endsection
