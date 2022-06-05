<?php

namespace App\Http\Pages\Maintenance\Product;

use App\Models\Product;
use Livewire\Component;

class ProductPrice extends Component
{

    public function render()
    {
        return view('pages.maintenance.product.product-price');
    }

    public function mount(Product $product)
    {

    }

    public function save()
    {

    }
}
