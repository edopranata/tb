<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth Route :

Route::controller(\App\Http\Controllers\Auth\AuthenticatedSessionController::class)->middleware('guest')->group(function (){
    Route::get('',  'create')->name('login');
    Route::post('',  'store');
});
Route::controller(\App\Http\Controllers\Auth\PasswordResetLinkController::class)->middleware('guest')->group(function (){
    Route::get('forgot-password', 'create')->name('password.request');
    Route::post('forgot-password','store')->name('password.email');
});
Route::middleware(['auth'])->group(function (){
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function (){
        Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('index');
    });
});



//require __DIR__.'/auth.php';
