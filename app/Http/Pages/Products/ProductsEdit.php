<?php

namespace App\Http\Pages\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductsEdit extends Component
{


    public $barcode;
    public $product_name;
    public $description;
    public $category_id;
    public $min_stock;
    public $unit_name;
    public $warehouse_stock;
    public $store_stock;

    public $categories;
    public $product;

    public function render()
    {
        return view('pages.products.products-edit');
    }

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->product->load(['unit','prices']);
        $this->categories = Category::all();
        $this->setValues();
    }

    public function setValues()
    {
//        dd($this->product->prices->where('default', '1')->count() ? $this->product->prices : null);
        $this->barcode          = $this->product->barcode;
        $this->product_name     = $this->product->name;
        $this->description      = $this->product->description;
        $this->unit_name        = $this->product->unit ? $this->product->unit->name : null;
        $this->category_id      = $this->product->category_id;
        $this->min_stock        = $this->product->min_stock;
        $this->warehouse_stock  = $this->product->warehouse_stock;
        $this->store_stock      = $this->product->store_stock;
    }

    public function update()
    {
        $this->validate([
            'barcode'       => ['required', 'string', 'min:2', 'max:20', 'unique:products,barcode, ' . $this->product->id],
            'product_name'  => ['required', 'string', 'min:2', 'max:255'],
            'description'   => ['nullable', 'string', 'min:2', 'max:255'],
            'category_id'   => ['required', 'exists:categories,id'],
            'min_stock'     => ['required', 'numeric', 'min:1'],
        ]);
        DB::beginTransaction();
        try {
            $this->product->update([
                'barcode'       => $this->barcode,
                'product_name'  => $this->product_name,
                'description'   => $this->description,
                'category_id'   => $this->category_id,
                'min_stock'     => $this->min_stock,
            ]);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with(['status' => 'error', 'message' => $exception->getMessage()]);
        }

        return redirect()->route('pages.products.index')->with(['status' => 'success', 'message' => 'data produk <strong>' . $this->product_name . '</strong> berhasil dibuat']);

    }
}
