<?php

namespace App\Http\Pages\Products;

use App\Models\Product;
use App\Models\Sell;
use App\Models\SellDetail;
use Livewire\Component;
use Livewire\WithPagination;

class ProductPriceMaintenance extends Component
{
    public $product;


    public function render()
    {
        return view('pages.products.product-price-maintenance', [
            'sells' => SellDetail::query()
                ->with(['sell', 'price', 'product'])
                ->where('product_id', $this->product->id)
                ->get()
                ->map(function ($sells){
                    return [
                        'id'                => $sells->id,
                        'invoice_number'    => $sells->sell->invoice_number,
                        'buying_price'      => $sells->buying_price,
                        'sell_price'        => $sells->sell_price,
                        'quantity'          => $sells->quantity,
                        'payloads'          => $this->payloads(json_decode($sells->payload)),
                    ];
                })
            ]
        );
    }

    private function payloads($payload){
        $array = [];
        foreach ($payload as $item) {
            array_push($array,$item);
        }
        return $array;
    }

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'unit', 'stocks', 'prices.unit', 'sells.sell']);

    }
    public function save()
    {

    }
}
