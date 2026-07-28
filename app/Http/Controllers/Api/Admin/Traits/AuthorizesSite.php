<?php

namespace App\Http\Controllers\Api\Admin\Traits;

use App\Http\Controllers\Api\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

trait AuthorizesSite
{
    private function authorizeSite(Request $request, Site $site): void
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && !$user->sites()->where('sites.id', $site->id)->exists()) {
            abort(403, 'Accès refusé à ce site.');
        }
    }
}
