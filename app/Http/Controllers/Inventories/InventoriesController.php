<?php

namespace App\Http\Controllers\Inventories;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Inventories\InventoryRepositories;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoriesController extends Controller
{


    public function index(Request $request, InventoryRepositories $inventoryRepositories)
    {

        if($request->ajax()){
            return $inventoryRepositories->navigate($request);
        }else{

            $suppliers = Supplier::query()->get()->toArray();
            return view('inventories.index', compact('suppliers'));
        }
    }
}
