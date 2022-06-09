<?php

namespace App\Http\Repositories\Inventories;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryRepositories
{

    public function navigate(Request $request)
    {
        switch ($request->path){
            case 'loadTemp':
                return $this->loadTemp();
                break;

            case 'searchProduct':
                return $this->searchProduct($request);
                break;
            default:
                return response()->json(['error' => 'Invalid path'], 401);
        }
    }

    public function getProductID()
    {
        /**
         * 1. Get Product
         */
    }

    public function getProductBarcode()
    {

    }

    public function searchProduct(Request $request)
    {
        if($request->q){
            return Product::query()
                ->where('barcode', 'like', '%' . $request->q . '%')
                ->orWhere('name', 'like', '%' . $request->q . '%')
                ->get()
                ->take(10)
                ->map(function ($data){
                    return [
                        'id'     => $data->id,
                        'text'      => $data->barcode . ' ' . $data->name
                    ];
                });
        }else{
            return response()->json(['error' => 'Invalid parameters'], 401);
        }
    }

    public function loadTemp()
    {
        $data['products'] = [];
        $data['payment'] = 0;
        $data['fund'] = 0;
        $data['purchase'] = \auth()->user()->tempPurchase()->with(['details.product.prices.unit', 'details.product.stocks', 'details.product.unit', 'details.price.unit'])->first();
        if($data['purchase']){
            if($data['purchase']->details->count()){
                foreach ($data['purchase']->details as $detail) {
                    array_push($data['products'], $detail->toArray());
                }
            }

            $data['bill'] = $data['purchase']->details->count() ? $data['purchase']->details->sum('total') : 0;
            $data['payment'] = $data['payment'] ?: 0;
            $data['fund'] = $data['bill'] - $data['payment'];
        }

        return $data;
    }
}
