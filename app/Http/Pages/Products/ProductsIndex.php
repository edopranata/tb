<?php

namespace App\Http\Pages\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsIndex extends Component
{
    public $search;
    public $order;

    use WithPagination;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.products.products-index', [
            'products' => Product::query()
                ->with(['user', 'category', 'unit'])
                ->filter($this->search)
                ->paginate(10)
                ->withQueryString()
                ->through(function ($products) {
                    return [
                        'id' => $products->id,
                        'barcode' => $products->barcode,
                        'name' => $products->name,
                        'description' => $products->description,
                        'stock' => [
                            'warehouse' => $products->warehouse_stock,
                            'store'     => $products->store_stock
                            ],
                        'unit' => $products->unit ? $products->unit->name : null,
                        'category' => $products->category ? $products->category->name : null,
                        'created_by' => $products->user ? $products->user->name : null,
                        'created_at' => $products->created_at,
                    ];
                }),
        ]);
    }

    public function editId(Product $products)
    {
        return redirect()->route('pages.products.edit', $products->id);
    }

    public function toProductPrice(Product $product)
    {
        return redirect()->route('pages.products.price', $product->id);
    }

    public function toProductSplit(Product $product)
    {
        return redirect()->route('pages.products.split', $product->id);
    }

    public function toRoute($route_name, $params = null)
    {
        return redirect()->route($route_name);
    }

    public function addNew() {
        return redirect()->route('pages.products.create');
    }
}
