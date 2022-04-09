<?php

namespace App\Http\Pages\Products;

use App\Models\Category;
use App\Models\Unit;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class ProductsCreate extends Component
{
    public $new_product = true;

    public $supplier;

    public $barcode;
    public $product_name;
    public $description;
    public $unit_id;
    public $category_id;
    public $min_stock;
    public $first_stock;
    public $available_stock;
    public $buying_price;
    public $expired_at;
    public $sell_price;
    public $wholesale_price;
    public $customer_price;

    public $units;
    public $categories;

    public function render()
    {
        return view('pages.products.products-create');
    }

    public function mount()
    {
        $this->units = Unit::all();
        $this->categories = Category::all();
    }

    public function generateBarcode()
    {
        $this->barcode = Str::upper(Str::random(2)) . now()->getPreciseTimestamp(3);
    }

    public function switchOption()
    {
        $this->new_product ? true : false;
        $this->reset(['first_stock','available_stock', 'buying_price']);
        $this->dispatchBrowserEvent('pageReload');

    }

    public function save()
    {
        Debugbar::info($this->buying_price);
        Debugbar::info($this->sell_price);
        Debugbar::info($this->wholesale_price);
        Debugbar::info($this->customer_price);
        $this->validate([
            'barcode'       => ['required', 'string', 'min:2', 'max:20', 'unique:products,barcode'],
            'product_name'  => ['required', 'string', 'min:2', 'max:255'],
            'description'   => ['nullable', 'string', 'min:2', 'max:255'],
            'min_stock'     => ['required', 'numeric', 'min:1'],
            'unit_id'       => ['required', 'exists:units,id'],
            'category_id'   => ['required', 'exists:categories,id'],

            'first_stock'   => ['nullable', 'numeric', 'min:1'],
            'buying_price'  => ['nullable', 'numeric', 'min:1'],
            'expired_at'    => ['nullable', 'date', 'after:now'],
            'sell_price'        => ['required', 'numeric', 'min:1'],
            'wholesale_price'   => ['required', 'numeric', 'min:1'],
            'customer_price'    => ['required', 'numeric', 'min:1'],
        ]);
        $this->available_stock = $this->first_stock;
        DB::beginTransaction();
        try {
            $product = Auth::user()->products()->create([
                'barcode'       => $this->barcode,
                'name'          => $this->product_name,
                'description'   => $this->description,
                'unit_id'       => $this->unit_id,
                'category_id'   => $this->category_id,
                'min_stock'     => $this->min_stock,
            ]);
            if($this->new_product === false){

                $product->update([
                    'warehouse_stock'   => $this->available_stock,
                    'store_stock'       => 0,
                ]);

                $product->stocks()->create([
                    'first_stock'       => $this->first_stock,
                    'available_stock'   => $this->available_stock,
                    'buying_price'      => $this->buying_price,
                    'expired_at'        => $this->expired_at,
                    'description'       => 'PERSEDIAAN AWAL',
                ]);
            }

            $product->prices()->create([
                'unit_id'   => $this->unit_id,
                'sell_price'   => $this->sell_price,
                'wholesale_price'   => $this->wholesale_price,
                'customer_price'   => $this->customer_price,
                'default'   => '1',
            ]);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with(['status' => 'error', 'message' => $exception->getMessage()]);
        }

        $this->reset();
        $this->mount();
        return redirect()->back()->with(['status' => 'success', 'message' => 'data produk <strong>' . $this->product_name . '</strong> berhasil dibuat']);

    }
}
