<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to admin panel
Route::get('/', function () {
    return redirect('/admin');
});

// Admin panel routes - serve Vue SPA
Route::get('/admin/{any?}', function () {
    return view('admin');
})->where('any', '.*');

// Fallback route for any other web routes
Route::fallback(function () {
    return redirect('/admin');
});

require __DIR__.'/auth.php';
