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
        if (app()->environment('production')) {
            $baseUrl = 'https://' . request()->getHost();
            $this->app['config']->set('app.url', $baseUrl);
            $this->app['config']->set('filesystems.disks.public.url', $baseUrl . '/storage');
            $this->app['config']->set('session.driver', 'cookie');
            $this->app['config']->set('session.secure', true);
            $this->app['config']->set('session.same_site', 'lax');

            URL::forceScheme('https');
            URL::forceRootUrl('https://' . request()->getHost());

            Request::setTrustedProxies(
                ['*'],
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
            );
        }
    }
}
