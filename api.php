<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicDataController;
Route::get('/site-data', [PublicDataController::class, 'index']);
Route::get('/properties', [PublicDataController::class, 'properties']);
Route::get('/properties/{id}', [PublicDataController::class, 'property'])->whereNumber('id');
Route::post('/inquiries', [PublicDataController::class, 'inquiry'])->middleware('throttle:10,1');
