<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('admin.brand') }} - {{ __('admin.login_title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=Reem+Kufi:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink950: '#0D211D',
                        ink900: '#132E28',
                        ink800: '#1A3D35',
                        sand: '#F3E9CF',
                        'sand-light': '#FAF6ED',
                        teal: '#3E8E7E',
                        'teal-dark': '#34796B',
                        'teal-light': '#4AA393',
                        clay: '#B85C38',
                        gold: '#C89B3C',
                    },
                    fontFamily: {
                        'kufi': ['"Reem Kufi"', 'sans-serif'],
                        'sans': ['"IBM Plex Sans"', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.key') }}"></script>
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-ink950 flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md">
        <div class="bg-ink900 rounded-2xl shadow-2xl border border-ink800 p-8 sm:p-10">

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal/10 mb-4">
                    <svg class="w-8 h-8 text-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <h1 class="font-kufi text-3xl font-bold text-sand tracking-wide">تراثي</h1>
                <p class="text-sand/40 text-sm mt-1 font-medium tracking-widest uppercase">{{ __('admin.login_title') }}</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-clay/10 border border-clay/30 rounded-lg px-4 py-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-clay shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <ul class="text-sm text-clay">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-sand/60 mb-1.5">{{ __('admin.login_email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-sand/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full bg-ink950/60 border border-sand/10 rounded-lg pl-11 pr-4 py-3 text-sand placeholder-sand/20 focus:outline-none focus:ring-2 focus:ring-teal/50 focus:border-teal transition-colors text-sm"
                            placeholder="admin@turathi.ma"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-clay">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-sand/60 mb-1.5">{{ __('admin.login_password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-sand/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="w-full bg-ink950/60 border border-sand/10 rounded-lg pl-11 pr-4 py-3 text-sand placeholder-sand/20 focus:outline-none focus:ring-2 focus:ring-teal/50 focus:border-teal transition-colors text-sm"
                            placeholder="{{ __('admin.login_password_placeholder') }}"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-clay">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-sand/15 bg-ink950/60 text-teal focus:ring-teal/50 focus:ring-offset-0">
                        <span class="text-sm text-sand/50">{{ __('admin.login_remember_me') }}</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-teal hover:bg-teal-dark text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal/50 focus:ring-offset-2 focus:ring-offset-ink900 active:scale-[0.98] text-sm"
                >
                    {{ __('admin.login_submit') }}
                </button>
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            </form>

        </div>

        <p class="text-center text-sand/20 text-xs mt-6">
            &copy; {{ date('Y') }} {{ __('admin.brand') }}. {{ __('admin.login_copyright') }}
        </p>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            var form = this;
            if (typeof grecaptcha !== 'undefined' && grecaptcha) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.key') }}', {action: 'login'}).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        form.submit();
                    });
                });
            }
        });
    </script>
</body>
</html>
