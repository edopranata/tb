<?php

namespace App\Http\Pages\Transaction;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sell;
use App\Models\TempSellDetail;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\ErrorHandler\Debug;

class TransactionSell extends Autocomplete
{
    public $prefix = "TB";

    public $transaction_date;
    public $customer_id;
    public $customer_name = 'Guest';
    public $invoice_number;
    public $sell_discount = 0;

    public $total;
    public $payment = 0;
    public $payment_format = 0;
    public $refund;
    public $due_date;

    public $price_type = 'sell';

    public $sells;
    public $products = [];
    public $barcode;


    public $customers;
    public $customer;
    protected $listeners = ['valueSelected'];

    public $show_discount = false;

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

    public function fixedPayment()
    {
        $this->updatePayment($this->total);
//        $this->loadTemp();
    }

    public function updatePayment($pay = 0)
    {
        $bill = $this->sells->details->sum('total');
        $discount = $this->sell_discount;
        $payment = ($pay ?: $this->payment) - $discount;
        $this->sells->update([
            'bill'      => $bill,
            'discount'  => $discount,
            'payment'   => $payment,
            'status'    => (($payment - $bill) >= 0) ? "LUNAS" : "BELUM LUNAS",
        ]);

        $this->loadTemp();
    }

    public function resetPayment()
    {
        $this->payment = 0;
        $this->updatePayment();
    }

    public function updatedCustomerId()
    {
        $this->selectCustomer($this->customer_id);
    }

    public function selectCustomer($id = null)
    {

        $customer = Customer::query()
            ->where('id', $id)
            ->first();

        if($this->sells){
            $this->sells->update([
                'customer_id'   => $id ?: null,
                'customer_name' => $id ? $customer->name : 'Guest',
            ]);

            $this->customer = $customer;
            $this->customer_name = $id ? $customer->name :  "Guest";
            $this->customer_id = $id ? $customer->id : null;

        }else{
            $this->customer = $customer;
            $this->customer_name = $id ? $customer->name :  "Guest";
            $this->customer_id = $id ? $customer->id : null;
        }

        $this->price_type = ($id != null) ? 'customer' : 'sell';

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

    public function cancelTransaction()
    {
        $this->sells->details()->delete();
        $this->sells->delete();
        $this->loadTemp();
        $this->reset([
            'transaction_date',
            'customer_id',
            'customer_name',
            'invoice_number',
            'sell_discount',
            'price_type',
            'products',
            'due_date',
        ]);

        $this->transaction_date = now()->format('Y-m-d');

        $this->dispatchBrowserEvent('transactionCancel');
    }

    public function saveDraft()
    {
        // ke halaman index (transaksi pembelian terakhir tetap tersimpan berdasarkan User ID)
        return redirect()->route('dashboard.index');
    }

    public function loadTemp()
    {
        $this->sells = [];
        $this->sells = \auth()->user()->tempSells()->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit'])->first();
        if($this->sells){
            $this->products = [];
            if($this->sells->details->count()){
                foreach ($this->sells->details as $detail) {
                    array_push($this->products, $detail->toArray());
                }
            }

            $this->invoice_number   = $this->sells->invoice_number;
            $this->sell_discount    = $this->sells->discount;
            $this->transaction_date = $this->sells->invoice_date->format('Y-m-d');
            $this->customer_id      = $this->sells->customer_id;
            $this->customer_name    = $this->sells->customer_name;
            $this->price_type       = ($this->customer_id != null) ? 'customer' : 'sell';

            $this->total            = $this->sells->details->sum('total');
            $this->sell_discount    = $this->sells->discount;
            $this->payment          = $this->sells->payment;
            $this->payment_format   = $this->sells->payment;
            $this->refund           = $this->payment - $this->total;

//            $this->updatePayment();
        }

        $this->dispatchBrowserEvent('pageReload');

    }

    public function transactionSave()
    {
        /**
         * 1. Validasi sebelum simpan data
         * 2. Cek ketersediaan stock
         * 3. Ambil harga modal dari tiap stock di table product stok
         * 4. Simpan data dari temp ke transaksi penjualan beserta detail transasksi dengan status hutang atau lunas
         * 5. Update stok di table product stock dan table produk
         * 6. simpan ke table sell history
         **/

//        dd($this->products);
        $group_products = collect($this->products)->groupBy('product_id');
        $products = $group_products->map(function ($group){
            return [
                'product_id'    => $group->first()['product_id'],
                'product_name'  => $group->first()['product_name'],
                'quantity'      => $group->sum('product_price_quantity')
            ];
        });

        $product_id = $products->pluck('product_id');


        $tb_product = Product::query()
            ->whereIn('id', $product_id)->get();


        $this->validate([
            'products'                  => ['required', 'array', 'min:1', 'max:4000000000'],
            'products.*.quantity'       => ['required', 'numeric', 'min:1', 'max:4000000000'],
            'due_date'                  => [Rule::requiredIf($this->refund < 0)]
        ]);

        DB::beginTransaction();
        try {
            /**
             * Check every product stock
             */
            foreach ($products as $product){
                $selected_product = $tb_product->where('id', $product['product_id'])->first();
                if($product['quantity'] > $selected_product->store_stock){
                    return back()->with(['error' => 'Invalid quantity for product ' . $selected_product->barcode . ' ' . $selected_product->name . ' store stock : ' . $selected_product->store_stock ]);
                    DB::rollBack();
                }
            }

            /**
             * Insert into sell table
             */
            $sells_transaction = Sell::query()
                ->create([
                    'user_id'           => $this->sells->user_id,
                    'customer_id'       => $this->sells->customer_id,
                    'customer_name'     => $this->sells->customer_name,
                    'invoice_number'    => $this->sells->invoice_number,
                    'invoice_date'      => now(),
                    'bill'              => $this->sells->bill,
                    'discount'          => $this->sells->discount,
                    'payment'           => $this->sells->payment,
                    'status'            => $this->sells->status,
                    'due_date'          => $this->due_date ?: null,
                ]);

            foreach ($this->sells->details as $detail) {
                $fifo_product = collect($detail->product);
                dd($fifo_product);

//                $sells_transaction->details()->create([
//                    'product_id'                => $detail->product_id,
//                    'product_price_id'          => $detail->product_price_id,
//                    'product_name'              => $detail->product_name,
//                    'quantity'                  => $detail->quantity,
//                    'product_price_quantity'    => $detail->product_price_quantity,
//                    'buying_price'              => $detail->buying_price,
//                    'total'                     => $detail->total,
//                ]);

            }

            DB::rollBack();
            return back()->with(['error' => 'Testing' ]);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            return back()->with(['error' => $exception->getMessage() ]);
        }
    }

    public function updateProduct($key)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $t_details = collect($this->products[$key]);
        $p_prices = collect($t_details['product']['prices'])->where('id', $this->products[$key]['product_price_id'])->first();

        $temp_category = $this->customer_id ? 'customer' : 'sell';
        $price_category = (Str::lower($this->products[$key]['price_category']) == 'wholesale') ? 'wholesale' : $temp_category;

        TempSellDetail::query()
            ->where('id', $t_details['id'])
            ->update([
                'product_price_id'          => $this->products[$key]['product_price_id'],
                'quantity'                  => $this->products[$key]['quantity'],
                'product_price_quantity'    => $this->products[$key]['quantity'] * $p_prices['quantity'],

                'sell_price'                => $p_prices[Str::lower($price_category) . '_price' ?: $this->price_type . '_price'],
                'discount'                  => $this->products[$key]['discount'],
                'sell_price_quantity'       => 1,
                'price_category'            => Str::upper($price_category),
                'total'                     => ($p_prices[Str::lower($price_category) . '_price'] * $this->products[$key]['quantity']) - $this->products[$key]['discount'] ,
            ]);
        $this->resetPayment();
        $this->loadTemp();
    }

    public function valueSelected(Product $product)
    {
        $this->emitUp('userSelected', $product);
        $product->load(['unit', 'prices.unit']);
        $this->addProduct($product);
    }

    public function removeItem(TempSellDetail $details)
    {
        $details->delete();
        $this->resetPayment();
        $this->loadTemp();
    }

    public function setPrice($index, $type = 'sell')
    {
        Debugbar::info($type);
        $this->products[$index]['price_category'] = $type;
        $this->updateProduct($index);

    }

    public function addProduct($product)
    {
        if($product->store_stock >= 1){
            $price = collect($product->prices->where('default', '1')->first());
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
                    'total'                     => $price[$this->price_type . '_price'] * $price['quantity'],

                ]);

            $this->resetPayment();
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
