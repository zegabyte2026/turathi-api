<?php

use App\Http\Controllers\Web\AdminController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

// Language switch
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['fr', 'ar', 'en'])) {
        session()->put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('admin.lang');

// All admin routes (with locale detection)
Route::prefix('admin')->middleware('set.locale')->group(function () {

    // Admin Login (public)
    Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post')->middleware('throttle:5,1');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Admin Panel (authenticated)
    Route::middleware('auth.web')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Profile (all admins)
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile', [AdminController::class, 'profileUpdate'])->name('admin.profile.update');

    // Sites
    Route::get('/sites', [AdminController::class, 'sites'])->name('admin.sites.index');
    Route::get('/sites/create', [AdminController::class, 'siteCreate'])->name('admin.sites.create');
    Route::post('/sites', [AdminController::class, 'siteStore'])->name('admin.sites.store');
    Route::get('/sites/{site}', [AdminController::class, 'siteShow'])->name('admin.sites.show');
    Route::get('/sites/{site}/edit', [AdminController::class, 'siteEdit'])->name('admin.sites.edit');
    Route::put('/sites/{site}', [AdminController::class, 'siteUpdate'])->name('admin.sites.update');
    Route::delete('/sites/{site}', [AdminController::class, 'siteDestroy'])->name('admin.sites.destroy');

    // Endroits
    Route::get('/endroits', [AdminController::class, 'endroits'])->name('admin.endroits.index');
    Route::get('/endroits/create', [AdminController::class, 'endroitCreate'])->name('admin.endroits.create');
    Route::post('/endroits', [AdminController::class, 'endroitStore'])->name('admin.endroits.store');
    Route::get('/endroits/{endroit}', [AdminController::class, 'endroitShow'])->name('admin.endroits.show');
    Route::get('/endroits/{endroit}/edit', [AdminController::class, 'endroitEdit'])->name('admin.endroits.edit');
    Route::put('/endroits/{endroit}', [AdminController::class, 'endroitUpdate'])->name('admin.endroits.update');
    Route::delete('/endroits/{endroit}', [AdminController::class, 'endroitDestroy'])->name('admin.endroits.destroy');

    // Objets
    Route::get('/objets', [AdminController::class, 'objets'])->name('admin.objets.index');
    Route::get('/objets/create', [AdminController::class, 'objetCreate'])->name('admin.objets.create');
    Route::post('/objets', [AdminController::class, 'objetStore'])->name('admin.objets.store');
    Route::get('/objets/{objet}', [AdminController::class, 'objetShow'])->name('admin.objets.show');
    Route::get('/objets/{objet}/edit', [AdminController::class, 'objetEdit'])->name('admin.objets.edit');
    Route::put('/objets/{objet}', [AdminController::class, 'objetUpdate'])->name('admin.objets.update');
    Route::delete('/objets/{objet}', [AdminController::class, 'objetDestroy'])->name('admin.objets.destroy');

    // QR Codes
    Route::get('/qr', [AdminController::class, 'qrCodes'])->name('admin.qr.index');
    Route::post('/qr/generate', [AdminController::class, 'qrGenerate'])->name('admin.qr.generate');
    Route::get('/qr/{site}/export', [AdminController::class, 'qrExport'])->name('admin.qr.export');

    // Visitors
    Route::get('/visitors', [AdminController::class, 'visitors'])->name('admin.visitors.index');
    Route::get('/visitors/{visitor}', [AdminController::class, 'visitorShow'])->name('admin.visitors.show');
    Route::post('/visitors/{visitor}/toggle-block', [AdminController::class, 'visitorBlock'])->name('admin.visitors.block');
    Route::delete('/visitors/{visitor}', [AdminController::class, 'visitorDelete'])->name('admin.visitors.delete');

    // Super admin only
    Route::middleware('super_admin.web')->group(function () {
        // Wilayas
        Route::get('/wilayas', [AdminController::class, 'wilayas'])->name('admin.wilayas.index');
        Route::get('/wilayas/create', [AdminController::class, 'wilayaCreate'])->name('admin.wilayas.create');
        Route::post('/wilayas', [AdminController::class, 'wilayaStore'])->name('admin.wilayas.store');
        Route::get('/wilayas/{wilaya}', [AdminController::class, 'wilayaShow'])->name('admin.wilayas.show');
        Route::get('/wilayas/{wilaya}/edit', [AdminController::class, 'wilayaEdit'])->name('admin.wilayas.edit');
        Route::put('/wilayas/{wilaya}', [AdminController::class, 'wilayaUpdate'])->name('admin.wilayas.update');
        Route::delete('/wilayas/{wilaya}', [AdminController::class, 'wilayaDestroy'])->name('admin.wilayas.destroy');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/users/create', [AdminController::class, 'userCreate'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'userStore'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'userEdit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'userUpdate'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'userDestroy'])->name('admin.users.destroy');
        Route::patch('/users/{user}/toggle', [AdminController::class, 'userToggle'])->name('admin.users.toggle');
    });
    });
});
