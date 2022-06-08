<?php

namespace App\Http\Pages\Products;

use App\Models\Product;
use Livewire\Component;

class ProductPriceMaintenance extends Component
{
    public $product;

    public function render()
    {
        return view('pages.products.product-price-maintenance');
    }

    public function mount(Product $product)
    {
        $this->product = $product->load(['category', 'unit', 'stocks', 'prices.unit', 'sells']);

    }
    public function save()
    {

    }
}
