<?php

namespace App\Http\Pages\ProductPrices;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductPricesIndex extends Component
{
    public $product_id;
    public $products;
    public $search;
    public $cat_id;
    public $search_field = 'name';
    public $per_page = 5;

    public $categories;

    protected $queryString = ['product_id', 'search', 'cat_id', 'search_field', 'per_page'];

    public function render()
    {
        $this->searchProducts();
        return view('pages.product-prices.product-prices-index');
    }

    public function mount($id = null)
    {
        $this->categories = Category::all();
    }

    public function searchProducts()
    {
        if ($this->search || $this->cat_id){
            $this->products = Product::query()
                ->with(['stocks', 'prices.unit'])
                ->when($this->cat_id, function ($query){
                    $query->where('category_id', $this->cat_id);
                })
                ->when($this->search, function ($query){
                    $query->where($this->search_field, 'like', '%' . $this->search . '%');
                })
                ->get()
                ->take($this->per_page);
        }
    }

    public function addPrices($id)
    {
        return redirect()->route('pages.prices.edit', $id);
    }


}
