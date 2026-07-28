<?php

use App\Http\Controllers\Api\Admin\EndroitController;
use App\Http\Controllers\Api\Admin\ObjetController;
use App\Http\Controllers\Api\Admin\PackController;
use App\Http\Controllers\Api\Admin\QrController;
use App\Http\Controllers\Api\Admin\SiteController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Public\MobileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Mobile API (publique)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::get('/wilayas', [MobileController::class, 'wilayas']);
    Route::get('/sites', [MobileController::class, 'sites']);
    Route::get('/wilayas/{id}/sites', [MobileController::class, 'wilayaSites']);
    Route::get('/sites/{id}', [MobileController::class, 'site']);
    Route::get('/sites/{id}/endroits', [MobileController::class, 'siteEndroits']);
    Route::get('/endroits/{id}', [MobileController::class, 'endroit']);
    Route::get('/endroits/{id}/objets', [MobileController::class, 'endroitObjets']);
    Route::get('/objets/{id}', [MobileController::class, 'objet']);
    Route::get('/qr/{qr_code_id}/resolve', [MobileController::class, 'resolveQr']);
    Route::get('/sites/{id}/pack/version', [MobileController::class, 'packVersion']);
    Route::get('/sites/{id}/pack', [MobileController::class, 'packDownload']);
});

/*
|--------------------------------------------------------------------------
| Admin API (authentifiée)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    // Logout
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Sites
    Route::get('/sites', [SiteController::class, 'index']);
    Route::get('/sites/{id}', [SiteController::class, 'show']);
    Route::put('/sites/{id}', [SiteController::class, 'update']);

    // Endroits
    Route::get('/sites/{siteId}/endroits', [EndroitController::class, 'index']);
    Route::post('/endroits', [EndroitController::class, 'store']);
    Route::get('/endroits/{id}', [EndroitController::class, 'show']);
    Route::put('/endroits/{id}', [EndroitController::class, 'update']);
    Route::delete('/endroits/{id}', [EndroitController::class, 'destroy']);
    Route::post('/endroits/{id}/media', [EndroitController::class, 'uploadMedia']);

    // Objets
    Route::get('/endroits/{endroitId}/objets', [ObjetController::class, 'index']);
    Route::post('/objets', [ObjetController::class, 'store']);
    Route::get('/objets/{id}', [ObjetController::class, 'show']);
    Route::put('/objets/{id}', [ObjetController::class, 'update']);
    Route::delete('/objets/{id}', [ObjetController::class, 'destroy']);
    Route::post('/objets/{id}/media', [ObjetController::class, 'uploadMedia']);

    // QR Codes
    Route::post('/qr/generate', [QrController::class, 'generate']);
    Route::post('/qr/{qr_code_id}/regenerate', [QrController::class, 'regenerate']);
    Route::get('/qr/{site_id}/export-pdf', [QrController::class, 'exportPdf']);

    // Packs Offline
    Route::post('/sites/{siteId}/compile-pack', [PackController::class, 'compile']);
    Route::get('/sites/{siteId}/compile-status', [PackController::class, 'status']);

    // Super Admin only
    Route::middleware('super_admin')->group(function () {
        Route::post('/sites', [SiteController::class, 'store']);
        Route::delete('/sites/{id}', [SiteController::class, 'destroy']);

        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}/sites', [UserController::class, 'updateSites']);
        Route::patch('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);
    });
});
