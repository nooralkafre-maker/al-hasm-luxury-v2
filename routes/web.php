<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDataController;

Route::get('/admin/login', [AdminAuthController::class, 'show'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:8,1')->name('admin.login');
Route::middleware('auth')->group(function () {
    Route::get('/admin', function () { abort_unless(auth()->user()?->is_admin, 403); return view('admin'); })->name('admin.dashboard');
    Route::post('/admin/logout', [AdminAuthController::class,'logout'])->name('admin.logout');
    // Session-authenticated admin APIs live in web middleware, so csrf + session guard work.
    Route::prefix('/api/admin')->group(function () {
        Route::get('/bootstrap', [AdminDataController::class, 'bootstrap']);
        Route::put('/settings', [AdminDataController::class, 'settings']);
        Route::post('/uploads', [AdminDataController::class, 'upload']);
        Route::post('/{type}', [AdminDataController::class, 'store']);
        Route::put('/{type}/{id}', [AdminDataController::class, 'update']);
        Route::delete('/{type}/{id}', [AdminDataController::class, 'destroy']);
    });
});
Route::get('/sitemap.xml', function () {
    $base = rtrim(config('app.url'), '/');
    $urls = ['', '/properties', '/about', '/services', '/contact', '/insights', '/valuation', '/mortgage'];
    foreach (\App\Models\Property::where('status', 'Published')->pluck('id') as $id) $urls[] = '/property/'.$id;
    foreach (\App\Models\Post::where('status', 'Published')->pluck('id') as $id) $urls[] = '/insights/'.$id;
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) $xml .= '<url><loc>'.htmlspecialchars($base.$url, ENT_XML1).'</loc></url>';
    return response($xml.'</urlset>', 200)->header('Content-Type', 'application/xml; charset=utf-8');
});
Route::get('/', fn () => view('site'));
Route::get('/property/{id}', fn () => view('site'))->whereNumber('id');
Route::get('/insights/{id}', fn () => view('site'))->whereNumber('id');
Route::get('/{page}', fn () => view('site'))->whereIn('page', ['home','properties','favorites','about','services','contact','valuation','insights','mortgage']);
