<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedApiController::class, 'store']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->apiResource('users', App\Http\Controllers\Api\UserController::class);
Route::middleware(['auth:sanctum'])->apiResource('roles', App\Http\Controllers\Api\RoleController::class);
Route::middleware(['auth:sanctum'])->apiResource('pages', App\Http\Controllers\Api\PageController::class);
Route::middleware(['auth:sanctum'])->apiResource('posts', App\Http\Controllers\Api\PostController::class);
Route::middleware(['auth:sanctum'])->apiResource('categories', App\Http\Controllers\Api\CategoryController::class);
Route::middleware(['auth:sanctum'])->apiResource('media', App\Http\Controllers\Api\MediaController::class);
Route::middleware(['auth:sanctum'])->apiResource('settings', App\Http\Controllers\Api\SettingController::class);
Route::middleware(['auth:sanctum'])->apiResource('menus', App\Http\Controllers\Api\MenuController::class);

// Menu items routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/menus/{menu}/items', [App\Http\Controllers\Api\MenuController::class, 'storeItem']);
    Route::put('/menus/{menu}/items/{item}', [App\Http\Controllers\Api\MenuController::class, 'updateItem']);
    Route::delete('/menus/{menu}/items/{item}', [App\Http\Controllers\Api\MenuController::class, 'destroyItem']);
});

// Settings bulk update
Route::middleware(['auth:sanctum'])->post('/settings/bulk-update', [App\Http\Controllers\Api\SettingController::class, 'updateMultiple']);

// Analytics and Reports routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/analytics', [App\Http\Controllers\Api\AnalyticsController::class, 'index']);
    Route::get('/reports', [App\Http\Controllers\Api\ReportController::class, 'index']);
});
