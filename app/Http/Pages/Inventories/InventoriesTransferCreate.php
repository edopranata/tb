<?php

namespace App\Http\Pages\Inventories;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Product;
use Illuminate\Support\Str;

class InventoriesTransferCreate extends Autocomplete
{
    public $transfer_to;
    public $transfer_date;
    public $transfer_from;

    public $transfer;
    public $products = [];

    protected $transfers = ['store', 'warehouse'];
    protected $listeners = ['valueSelected'];

    public function render()
    {
        return view('pages.inventories.inventories-transfer-create');
    }

    public function mount()
    {
        $r = collect(explode('/', request()->path()));
        $transfer = $r[$r->count() - 2];
        if (in_array($transfer, $this->transfers )) {
            $this->transfer_to = Str::ucfirst($transfer);
            $this->transfer_from = Str::ucfirst(($transfer === 'store') ? 'warehouse' : 'store');
        }else{
            abort(404);
        }
        $this->transfer_date = now()->format('Y-m-d');
        $this->loadTemp();
    }

    public function updateProduct($key)
    {

        $t_details = collect($this->products[$key]);
        $p_prices = collect($t_details['product']['prices'])->where('id', $this->products[$key]['product_price_id'])->first();

        $this->validate([
            'products.*.quantity'   => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($p_prices, $t_details) {
                    $total_q = $t_details['quantity'] * $p_prices['quantity'];
                    $warehouse_stock = $t_details['product']['warehouse_stock'];
                    $total = $warehouse_stock-$total_q;
                    if($total<0){
                        $fail('Out of stock ' . $total);
                    }
            }]
        ]);

        $this->transfer
            ->details()
            ->where('id', $t_details['id'])
            ->update([
                'product_price_id'          => $this->products[$key]['product_price_id'],
                'quantity'                  => $this->products[$key]['quantity'],
                'product_price_quantity'    => $this->products[$key]['quantity'] * $p_prices['quantity'],
            ]);
        $this->loadTemp();
//        dd($this->products);
    }

    public function loadTemp()
    {
        // load table temporary beserta relasi
        $this->transfer = [];
        $this->transfer = \auth()->user()->tempTransfer()->where('transfer_to', $this->transfer_to)->with(['details.product.prices.unit','details.product.unit', 'details.price.unit'])->first();
//        dd($this->transfer);
        if($this->transfer){
            if($this->transfer->details->count()){
                $this->products = [];
                foreach ($this->transfer->details as $detail) {
                    array_push($this->products, $detail->toArray());
                }
            }
            $this->transfer_date = $this->transfer['transfer_date']->format('Y-m-d');
        }
    }

    public function beginTransfer()
    {
        $this->validate([
            'transfer_date'     => ['required', 'date', 'before_or_equal:now'],
        ]);

        \auth()->user()->tempTransfer()
            ->create([
                'transfer_date'     => $this->transfer_date,
                'transfer_from'     => $this->transfer_from,
                'transfer_to'       => $this->transfer_to,
            ]);
        // panggil fungsi loadTemp (Load table transaksi temporari pembeian)
        $this->loadTemp();
        $this->render();
    }

    public function valueSelected(Product $product)
    {
        $this->emitUp('userSelected', $product);
        $product->load(['unit', 'prices.unit']);
        $this->addProduct($product);
    }

    public function addProduct($product)
    {
        // set satuan default adalah yang terakhir (Satuan terbesar)
        $price = $product->prices->last();

        // Tambahkan produk ke table temp (tabel sementara)
        $this
            ->transfer
            ->details()
            ->create([
                'product_id'                => $product->id,
                'product_price_id'          => $price->id,
                'product_name'              => $product->name,
                'quantity'                  => 0,
                'product_price_quantity'    => 0,
            ]);
        // panggil fungsi loadTemp (Load table transaksi temporari pembeian)
        $this->loadTemp();
    }

    // query untuk search produk berdasarkan barcode dan nama produk
    public function query() {
        return Product::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('barcode', 'like', '%'.$this->search.'%')
            ->orderBy('name')->take(10);
    }
}
