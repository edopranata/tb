<?php

namespace App\Http\Pages\Transaction;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Customer;
use App\Models\Product;
use App\Models\TempSellDetail;
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
    public $sell_discount;

    public $price_type;

    public $sells;
    public $products = [];
    public $barcode;


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
        $this->price_type = 'sell';
        $this->customer_name = 'Guest';
        $this->loadTemp();
    }

    public function selectBarcode()
    {
        $product = Product::query()
            ->where('barcode', $this->barcode)
            ->first();
        if(empty($product)) {
            session()->flash('error', 'Barcode ' . $this->barcode . ' not found');
            return back();
        }else{
            $this->addProduct($product);
            $this->barcode = '';
        }
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

        $this->sells->update([
            'customer_id'   => $id,
            'customer_name' => $id ? $customer->name : 'Guest',
        ]);
        Debugbar::info($customer->name);

        $this->customer = $customer;
        $this->customer_name = $customer ? $customer->name :  "Guest";
        $this->customer_id = $customer ? $customer->id : null;

        $this->price_type = $id ? 'customer' : 'sell';

        foreach ($this->products as $key => $product) {
            $this->updateProduct($key);
        }
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
            $this->sell_discount    = $this->sells->discount;
            $this->transaction_date = $this->sells->invoice_date->format('Y-m-d');
            $this->customer_id      = $this->sells->customer_id;
            $this->customer_name    = $this->sells->customer_name;
            $this->price_type       = 'customer';
//            $this->customer         = $this->sells->customer->toArray();
        }

//        dd($this->products);

    }

    public function updateProduct($key)
    {
        Debugbar::info('Begin update ' . $key);
        $t_details = collect($this->products[$key]);
        $p_prices = collect($t_details['product']['prices'])->where('id', $this->products[$key]['product_price_id'])->first();
//        $p_stock = collect($t_details['product']['stocks'])->last();
//        $sell_price = $p_stock['buying_price'] * $p_prices['quantity']; //$p_stock ? $p_stock['buying_price'] * $p_prices['quantity'] : 0;
//        dd($p_prices);
        $this->sells
            ->details()
            ->where('id', $t_details['id'])
            ->update([
                'product_price_id'          => $this->products[$key]['product_price_id'],
                'quantity'                  => $this->products[$key]['quantity'],
                'product_price_quantity'    => $this->products[$key]['quantity'] * $p_prices['quantity'],

                'sell_price'                => $p_prices[Str::lower($this->products[$key]['price_category']) . '_price' ?: $this->price_type . '_price'],
                'discount'                  => $this->products[$key]['discount'],
                'sell_price_quantity'       => 1,
                'price_category'            => $this->products[$key]['price_category'] ?: Str::upper($this->price_type),
                'total'                     => ($p_prices[Str::lower($this->products[$key]['price_category']) . '_price' ?: $this->price_type . '_price'] * $this->products[$key]['quantity']) - $this->products[$key]['discount'] ,
            ]);

        Debugbar::info('quantity ' . $this->products[$key]['quantity']);
        Debugbar::info('product_price_quantity ' . $p_prices['quantity']);
        $this->loadTemp();
    }

    public function valueSelected(Product $product)
    {
        $this->emitUp('userSelected', $product);
        $product->load(['unit', 'prices.unit']);
        $this->addProduct($product);
//        dd($product);
//        if($product->store_stock >= 1){
//
//        }else{
//            session()->flash('error', 'Out of stock for ' . $product->product_name . ' store stock ' . $product->store_stock . ' warehouse stock' . $product->warehouse_stock);
//            return back();
//        }
    }

    public function removeItem(TempSellDetail $details)
    {
        $details->delete();
        $this->loadTemp();
    }

    public function addProduct($product)
    {
        if($product->store_stock >= 1){
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
        }else{
            session()->flash('error', 'Out of stock for ' . $product->name . ' store stock ' . $product->store_stock . ' warehouse stock ' . $product->warehouse_stock);
            return back();
        }

    }

    public function query() {
        return Product::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('barcode', 'like', '%'.$this->search.'%')
            ->orderBy('name')->take(10);
    }
}
