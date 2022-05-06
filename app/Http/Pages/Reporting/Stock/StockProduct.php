<?php

namespace App\Http\Pages\Reporting\Stock;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class StockProduct extends Component
{
    public $category_id;
    public $product_name;
    public $categories;
    public $per_pages = 10;

    use WithPagination;

    public function render()
    {
        return view('pages.reporting.stock.stock-product', [
            'products' => Product::query()
                ->with(['unit', 'stocks', 'prices.unit'])
                ->when($this->product_name, function ($product, $name){
                    $product->where('name', 'like', '%'.$name.'%')
                        ->orWhere('barcode', 'like', '%'.$name.'%');
                })
                ->when($this->category_id, function ($category, $id){
                    $category->where('category_id', $id);
                })->paginate($this->per_pages)
        ]);
    }

    public function mount()
    {
        $this->categories = Category::all();
    }





}
