<?php

namespace App\Http\Pages\Products;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductsCreate extends Component
{
    public $supplier;

    public $barcode;
    public $name;
    public $description;
    public $unit_id;
    public $category_id;
    public $min_stock;
    public $first_stock;
    public $available_stock;
    public $price;

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

    public function save()
    {
        $this->validate([
            'barcode'       => ['required', 'string', 'min:2', 'max:20', 'unique:products,barcode'],
            'name'          => ['required', 'string', 'min:2', 'max:20'],
            'description'   => ['nullable', 'required', 'string', 'min:2', 'max:255'],
            'unit_id'       => ['nullable', 'required', 'exists:units,id'],
            'category_id'   => ['nullable', 'required', 'exists:categories,id'],
        ]);

        DB::beginTransaction();
        try {
            Auth::user()->products()->create([
                'barcode'       => $this->barcode,
                'name'          => $this->name,
                'description'   => $this->description,
                'unit_id'       => $this->unit_id,
                'category_id'   => $this->category_id,
                'min_stock'     => $this->min_stock,
            ]);


            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
        }


        return redirect()->route('pages.suppliers.index')->with(['status' => 'success', 'message' => 'data produk <strong>' . $this->name . '</strong> berhasil dibuat']);

    }
}
