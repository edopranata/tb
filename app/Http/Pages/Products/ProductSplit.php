<?php

namespace App\Http\Pages\Products;

use App\Models\Product;
use Livewire\Component;

class ProductSplit extends Component
{
    public function render()
    {
        return view('pages.products.product-split');
    }

    public function mount(Product $product)
    {
        dd($product);
    }
}
