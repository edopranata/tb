<?php

namespace App\Http\Pages\ProductPrices;

use App\Models\Product;
use App\Models\Unit;
use Livewire\Component;

class ProductPricesEdit extends Component
{
    public $price_id;
    public $unit_id;
    public $quantity;
    public $sell_price;
    public $wholesale_price;
    public $customer_price;

    public $product;
    public $units;
    public $price;
    public function render()
    {
        return view('pages.product-prices.product-prices-edit');
    }

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->updateData();
    }

    public function updateData()
    {
        $this->product->load(['prices.unit', 'unit']);

        $this->units = Unit::query()
            ->when($this->product->prices->count(), function ($query){
                $query->whereNotIn('id', $this->product->prices()->pluck('unit_id'));
            })->get();
    }

    public function submitPrice() {

        $this->validate([
            'unit_id'           => ['required', 'exists:units,id'],
            'quantity'          => ['required', 'numeric', 'gt:1'],
            'sell_price'        => ['required', 'numeric', 'min:1', 'gte:1'],
            'wholesale_price'   => ['required', 'numeric', 'min:1', 'lte:' . $this->sell_price],
            'customer_price'    => ['required', 'numeric', 'min:1', 'lte:' . $this->sell_price],
        ]);

        $this->product->prices()->create([
            'unit_id'           => $this->unit_id,
            'quantity'          => $this->quantity,
            'sell_price'        => $this->sell_price,
            'wholesale_price'   => $this->wholesale_price,
            'customer_price'    => $this->customer_price,
        ]);

        $this->resetForm();
    }

    public function submitEdit() {
        $this->validate([
            'unit_id'           => ['required', 'exists:units,id'],
            'quantity'          => ['required', 'numeric', $this->price->default ? 'gt:0' : 'gt:1' ],
            'sell_price'        => ['required', 'numeric', 'min:1', 'gte:1'],
            'wholesale_price'   => ['required', 'numeric', 'min:1', 'lte:' . $this->sell_price],
            'customer_price'    => ['required', 'numeric', 'min:1', 'lte:' . $this->sell_price],
        ]);

        $this->price->update([
            'unit_id'           => $this->unit_id,
            'quantity'          => $this->quantity,
            'sell_price'        => $this->sell_price,
            'wholesale_price'   => $this->wholesale_price,
            'customer_price'    => $this->customer_price,
        ]);

        $this->resetForm();
    }

    public function editPrice($id)
    {
        $price = $this->product->prices()->where('id', $id)->first();

        $price->load('unit');

        $this->price            = $price;
        $this->price_id         = $price->id;
        $this->unit_id          = $price->unit_id;
        $this->quantity         = $price->quantity;
        $this->sell_price       = $price->sell_price;
        $this->wholesale_price  = $price->wholesale_price;
        $this->customer_price   = $price->customer_price;

        $this->resetErrorBag();

        $this->dispatchBrowserEvent('pageReload');
    }

    public function resetForm()
    {
        $this->reset([
            'price_id',
            'unit_id',
            'quantity',
            'sell_price',
            'wholesale_price',
            'customer_price',
            'price',
        ]);
        $this->resetErrorBag();
        $this->updateData();
    }
}
