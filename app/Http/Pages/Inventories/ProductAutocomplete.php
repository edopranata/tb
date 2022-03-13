<?php

namespace App\Http\Pages\Inventories;

use App\Models\Product;
use Livewire\Component;

class ProductAutocomplete extends Component
{
    protected $listeners = ['valueSelected'];

    public function valueSelected(Product $product)
    {
        $this->emitUp('userSelected', $product);
    }

    public function query() {
        return Product::where('name', 'like', '%'.$this->search.'%')->orderBy('name');
    }
}
