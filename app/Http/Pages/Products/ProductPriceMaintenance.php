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
            ]
        );
    }

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'unit', 'stocks', 'prices.unit', 'sells.sell']);

    }
    public function save()
    {

    }
}
