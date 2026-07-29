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
        $renderUrl = getenv('RENDER_EXTERNAL_URL') ?: (app()->environment('production') ? 'https://' . request()->getHost() : null);
        if ($renderUrl) {
            $this->app['config']->set('app.url', $renderUrl);
            $this->app['config']->set('filesystems.disks.public.url', $renderUrl . '/storage');
            URL::forceScheme('https');
            URL::forceRootUrl($renderUrl);
        }
    }
}
