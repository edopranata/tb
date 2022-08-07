<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Products\ProductMaintenanceRepositories;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductMaintenance extends Controller
{

    public function index(Product $product, Request $request, ProductMaintenanceRepositories $maintenanceRepositories)
    {
        if($request->ajax()){
            return $maintenanceRepositories->navigate($request);
        }else{
            return view('product.maintenance', [
                'product' => $product->load(['category', 'unit', 'stocks', 'prices.unit', 'sells.sell']),
                'stocks'  => $product->stocks()->paginate(5),
            ]);
        }
    }
}
