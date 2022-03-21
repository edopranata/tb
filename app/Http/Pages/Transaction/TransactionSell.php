<?php

namespace App\Http\Pages\Transaction;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Customer;
use App\Models\Product;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Str;
use Symfony\Component\ErrorHandler\Debug;

class TransactionSell extends Autocomplete
{
    public $prefix = "TB";

    public $transaction_date;
    public $customer_id;
    public $customer_name;
    public $invoice_number;

    public $price_type;

    public $sells;
    public $products = [];

    public $customers;
    public $customer;
    protected $listeners = ['valueSelected'];

    public function render()
    {
        return view('pages.transaction.transaction-sell');
    }

    public function mount()
    {
        $this->customers = Customer::query()
            ->select(['id', 'name'])
            ->get()
            ->toArray();
        $this->transaction_date = now()->format('Y-m-d');
        $this->customer_name = "Guest";

        $this->loadTemp();

        $this->price_type = 'sell';

    }

    public function updatedCustomerId()
    {
        Debugbar::info($this->customer_id);
        $this->selectCustomer($this->customer_id);

    }

    public function selectCustomer($id = null)
    {
        Debugbar::info($id);
        $customer = Customer::query()
            ->where('id', $id)
            ->first();

        Debugbar::info($customer);
        $this->customer = $customer;
        $this->customer_name = $customer ? $customer->name :  "Guest";
        $this->customer_id = $customer ? $customer->id : null;
        Debugbar::info($this->customer_id);
        Debugbar::info($this->customer_name);

        $this->price_type = $id ? 'customer' : 'sell';

    }



    public function transactionBegin()
    {
        $this->invoice_number = $this->prefix . now()->format('Ymdhis');

        auth()->user()->tempSells()->create([
            'customer_id'   => $this->customer_id ?: null,
            'customer_name' => $this->customer_name ?: null,
            'invoice_date'  => $this->transaction_date,
            'invoice_number'=> $this->invoice_number,
        ]);

        $this->loadTemp();
    }

    public function loadTemp()
    {
        // load table temporary beserta relasi
        $this->sells = [];
        $this->sells = \auth()->user()->tempSells()->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit'])->first();
        if($this->sells){
            if($this->sells->details->count()){
                $this->products = [];
                foreach ($this->sells->details as $detail) {
                    array_push($this->products, $detail->toArray());
                }
            }
//            dd($this->products);
//            $this->bill = $this->sells->details->count() ? $this->sells->details->sum('total') : 0;
//            $this->payment = $this->payment ?: 0;
//            $this->fund = $this->bill - $this->payment;
        }

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
        $price = collect($product->prices->where('default', '1')->first());
        // cek harga modal terakhir beli (jika ada)
//        $stock = $product->stocks->last();

        // Tambahkan produk ke table temp (tabel sementara)
        $this
            ->sells
            ->details()
            ->create([
                'product_id'                => $product->id,
                'product_price_id'          => $price['id'],
                'product_name'              => $product->name,
                'quantity'                  => 1,
                'product_price_quantity'    => $price['quantity'],
                'sell_price'                => $price[$this->price_type . '_price'],
                'sell_price_quantity'       => 1,
                'price_category'            => Str::upper($this->price_type),
//                'buying_price'              => $stock ? $stock->buying_price * $price->quantity : 0,
                'total'                     => $price[$this->price_type . '_price'] * $price['quantity'],

            ]);
        // panggil fungsi loadTemp (Load table transaksi temporari pembeian)
        $this->loadTemp();
    }

    public function query() {
        return Product::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('barcode', 'like', '%'.$this->search.'%')
            ->orderBy('name')->take(10);
    }
}
