<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

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
        Route::group(['prefix' => 'management', 'as' => 'management.'], function (){
            Route::group(['prefix' => 'users', 'as' => 'users.'], function (){
                Route::get('/', \App\Http\Pages\Management\User\ManagementUserIndex::class)->name('index');
                Route::get('{user}/edit', \App\Http\Pages\Management\User\ManagementUserEdit::class)->name('edit');
                Route::get('create', \App\Http\Pages\Management\User\ManagementUserCreate::class)->name('create');
            });
            Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function (){
                Route::get('/', \App\Http\Pages\Management\Permission\ManagementPermissionIndex::class)->name('index');
            });
            Route::group(['prefix' => 'roles', 'as' => 'roles.'], function (){
                Route::get('/', \App\Http\Pages\Management\Role\ManagementRoleIndex::class)->name('index');
            });
        });

        Route::group(['prefix' => 'units', 'as' => 'units.'], function (){
            Route::get('/', \App\Http\Pages\Unit\UnitIndex::class)->name('index');
            Route::get('create', \App\Http\Pages\Unit\UnitCreate::class)->name('create');
            Route::get('{unit}/edit', \App\Http\Pages\Unit\UnitEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function (){
            Route::get('/', \App\Http\Pages\Category\CategoryIndex::class)->name('index');
            Route::get('create', \App\Http\Pages\Category\CategoryCreate::class)->name('create');
            Route::get('{category}/edit', \App\Http\Pages\Category\CategoryEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function (){
            Route::get('/', \App\Http\Pages\Suppliers\SuppliersIndex::class)->name('index');
            Route::get('create', \App\Http\Pages\Suppliers\SuppliersCreate::class)->name('create');
            Route::get('{supplier}/edit', \App\Http\Pages\Suppliers\SuppliersEdit::class)->name('edit');
        });

        Route::group(['prefix' => 'customers', 'as' => 'customers.'], function (){
            Route::get('/', \App\Http\Pages\Customer\CustomerIndex::class)->name('index');
            Route::get('create', \App\Http\Pages\Customer\CustomerCreate::class)->name('create');
            Route::get('{customer}', \App\Http\Pages\Customer\CustomerEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'products', 'as' => 'products.'], function (){
            Route::get('/', \App\Http\Pages\Products\ProductsIndex::class)->name('index');
            Route::get('upload', \App\Http\Pages\Products\ProductsImport::class)->name('import');
            Route::get('create', \App\Http\Pages\Products\ProductsCreate::class)->name('create');
            Route::get('{product}', \App\Http\Pages\Products\ProductsEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'prices', 'as' => 'prices.'], function (){
            Route::get('/', \App\Http\Pages\ProductPrices\ProductPricesIndex::class)->name('index');
            Route::get('{product}', \App\Http\Pages\ProductPrices\ProductPricesEdit::class)->name('edit');
        });
        Route::group(['prefix' => 'inventories', 'as' => 'inventories.'], function (){
            Route::get('/', \App\Http\Pages\Inventories\InventoriesIndex::class)->name('index');
        });
        Route::group(['prefix' => 'stock', 'as' => 'stock.'], function (){
            Route::get('/', \App\Http\Pages\Inventories\InventoriesTransfer::class)->name('index');
            Route::group(['prefix' => 'transfer', 'as' => 'transfer.'], function (){
                Route::get('store/create', \App\Http\Pages\Inventories\InventoriesTransferCreate::class)->name('store');
                Route::get('warehouse/create', \App\Http\Pages\Inventories\InventoriesTransferCreate::class)->name('warehouse');
            });
        });

        Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function (){
            Route::get('/', \App\Http\Pages\Transaction\TransactionSell::class)->name('index');
            Route::get('return', \App\Http\Pages\Transaction\TransactionReturn::class)->name('return');
            Route::get('{sell}/print', \App\Http\Pages\Transaction\TransactionPrint::class)->name('print');
        });

        Route::group(['prefix' => 'reporting', 'as' => 'reporting.'], function (){
            Route::group(['prefix' => 'reprint', 'as' => 'reprint.'], function (){
                Route::get('/', \App\Http\Pages\Reporting\Reprint\Transaction::class)->name('index');
            });
            Route::group(['prefix' => 'stock', 'as' => 'stock.'], function (){
                Route::get('/', App\Http\Pages\Reporting\ReportTransfer::class)->name('index');
                Route::get('{transfer}', App\Http\Pages\Reporting\ReportTransferView::class)->name('view');
            });
            Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function (){
                Route::get('/', App\Http\Pages\Reporting\ReportPurchase::class)->name('index');
                Route::get('{purchase}', App\Http\Pages\Reporting\ReportPurchaseView::class)->name('view');
            });
            Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function (){
                Route::get('/', App\Http\Pages\Reporting\Transaction\ReportTransaction::class)->name('index');
            });
        });
    });
});
