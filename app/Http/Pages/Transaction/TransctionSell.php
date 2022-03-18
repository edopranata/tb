<?php

namespace App\Http\Pages\Transaction;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Product;

class TransctionSell extends Autocomplete
{

    public $transaction;

    protected $listeners = ['valueSelected'];

    public function render()
    {
        return view('pages.transaction.transction-sell');
    }

    public function valueSelected(Product $product)
    {
        $this->emitUp('userSelected', $product);
        $product->load(['unit', 'prices.unit']);
        //$this->addProduct($product);
    }

    public function query() {
        return Product::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('barcode', 'like', '%'.$this->search.'%')
            ->orderBy('name')->take(10);
    }
}
