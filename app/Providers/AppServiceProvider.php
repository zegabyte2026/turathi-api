<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $renderUrl = getenv('RENDER_EXTERNAL_URL');
        if ($renderUrl) {
            $this->app['config']->set('app.url', $renderUrl);
            $this->app['config']->set('filesystems.disks.public.url', $renderUrl . '/storage');
            URL::forceScheme('https');
            URL::forceRootUrl($renderUrl);
        } elseif (app()->environment('production')) {
            $baseUrl = 'https://' . request()->getHost();
            $this->app['config']->set('app.url', $baseUrl);
            $this->app['config']->set('filesystems.disks.public.url', $baseUrl . '/storage');
            URL::forceScheme('https');
            URL::forceRootUrl($baseUrl);
        }

        Request::setTrustedProxies(
            ['*'],
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_PROTO
        );
    }
}
