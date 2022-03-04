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

    Route::group(['prefix' => 'pages', 'as' => 'pages.'], function (){
        Route::group(['prefix' => 'units', 'as' => 'units.'], function (){
            Route::get('/', \App\Http\Pages\Unit\UnitIndex::class)->name('index');
            Route::get('/create', \App\Http\Pages\Unit\UnitCreate::class)->name('create');
            Route::get('/{unit}', \App\Http\Pages\Unit\UnitEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function (){
            Route::get('/', \App\Http\Pages\Category\CategoryIndex::class)->name('index');
            Route::get('/create', \App\Http\Pages\Category\CategoryCreate::class)->name('create');
            Route::get('/{category}', \App\Http\Pages\Category\CategoryEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function (){
            Route::get('/', \App\Http\Pages\Suppliers\SuppliersIndex::class)->name('index');
            Route::get('/create', \App\Http\Pages\Suppliers\SuppliersCreate::class)->name('create');
            Route::get('/{supplier}', \App\Http\Pages\Suppliers\SuppliersEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'products', 'as' => 'products.'], function (){
            Route::get('/', \App\Http\Pages\Products\ProductsIndex::class)->name('index');
            Route::get('/create', \App\Http\Pages\Products\ProductsCreate::class)->name('create');
            Route::get('/{supplier}', \App\Http\Pages\Products\ProductsEdit::class)->name('edit');
        });
    });
});



//require __DIR__.'/auth.php';
