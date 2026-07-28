<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWeb
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $user = \App\Models\User::find(session('admin_id'));
        if (!$user || !$user->is_active) {
            session()->forget('admin_id');
            return redirect()->route('admin.login');
        }

        view()->share('admin', $user);
        return $next($request);
    }
}
