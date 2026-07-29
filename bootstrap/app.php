<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO);
        $middleware->validateCsrfTokens(except: [
            'admin/login',
        ]);
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'super_admin' => \App\Http\Middleware\SuperAdminOnly::class,
            'auth.web' => \App\Http\Middleware\AuthenticateWeb::class,
            'super_admin.web' => \App\Http\Middleware\SuperAdminWeb::class,
            'set.locale' => \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            return response('<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Fichier trop volumineux</title><style>body{font-family:sans-serif;padding:40px;background:#F3E9CF;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0}.card{background:#fff;padding:32px;border-radius:16px;max-width:480px;box-shadow:0 4px 24px rgba(0,0,0,.1);text-align:center}h2{color:#B85C38;margin:0 0 12px}p{color:#555;margin:0 0 24px;line-height:1.6}a{color:#3E8E7E;text-decoration:none;font-weight:600}a:hover{text-decoration:underline}</style></head><body><div class="card"><h2>Fichier trop volumineux</h2><p>Le fichier que vous essayez d\'envoyer dépasse la taille maximale autorisée de 64 Mo.<br>Veuillez réduire la taille du fichier et réessayer.</p><a href="' . e($request->headers->get('referer', '/admin')) . '">← Retour au formulaire</a></div></body></html>', 413);
        });
    })->create();
