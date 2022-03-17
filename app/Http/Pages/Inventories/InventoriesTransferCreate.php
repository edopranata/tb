<?php

namespace App\Http\Pages\Inventories;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\TempProductTransferDetails;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InventoriesTransferCreate extends Autocomplete
{
    public $transfer_to;
    public $transfer_date;
    public $transfer_from;

    public $transfer;
    public $products = [];

    protected $transfers = ['store', 'warehouse'];
    protected $listeners = ['valueSelected'];


    protected $validationAttributes = [
        'products.*.quantity'   => 'product quantity',
        'products'              => 'product',
        'products.*'            => 'product ke ',
        'products.*.product_price_quantity'     => 'total stock transfer',


    ];

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
                    $total_q = $value * $p_prices['quantity'];
                    $current_stock = $t_details['product'][Str::lower($this->transfer_from).'_stock'];
                    $total = $current_stock - $total_q;
                    if ($total < 0) {
//                        $fail(
//                            'Current : ' . $current_stock .
//                            ':::Value :' . $value .
//                            ':::PP Quantity :' . $p_prices['quantity'] .
//                            ':::Total Q:' . $total_q .
//                            ':::Total :' . $total
//                        );
                        $fail('Out of stock ' . $total);
                    }
                }
            ]
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
        $this->search = null;
    }

    public function loadTemp()
    {
        // kosongkan variable transfer load table temporary beserta relasi
        $this->transfer = [];
        $this->transfer = \auth()->user()->tempTransfer()->where('transfer_to', $this->transfer_to)->with(['details.product.prices.unit','details.product.unit', 'details.price.unit'])->first();

        if($this->transfer){
            if($this->transfer->details->count()){
                // kosongkan variable products
                $this->products = [];
                foreach ($this->transfer->details as $detail) {
                    array_push($this->products, $detail->toArray());
                }
            }
            $this->transfer_date = $this->transfer['transfer_date']->format('Y-m-d');
        }
    }

    public function save()
    {

        /*
                 * 1. Insert into productTransfer table select from tempProductTransfer
                 * 2. Insert Into productTransferDetails select from tempProductTransferDetails
                 * 3. Increment Warehouse or Store stock in product table
                 * 4. Decrement Warehouse or Store stock in product table
                 * 5. Delete tempPurchase and TempPurchaseDetails
                 */

        Debugbar::info('Save');
        $this->validate([
            'products'                  => ['required', 'array', 'min:1', 'max:4000000000'],
            'products.*.quantity'       => ['required', 'numeric', 'min:1', 'max:4000000000'],
        ]);

        Debugbar::info('Validation pass');

//        dd($errors = $this->getErrorBag()->toArray());
        DB::beginTransaction();
        try {

            Debugbar::info('Create into table product transfer');
            // Insert into Purchase table select from tempPurchase
            $transfer_transaction = ProductTransfer::query()
                ->create([
                    'user_id'       => $this->transfer->user_id,
                    'transfer_date' => $this->transfer->transfer_date,
                    'transfer_from' => $this->transfer->transfer_from,
                    'transfer_to'   => $this->transfer->transfer_to,
                ]);

            Debugbar::info('Create into table product transfer success' . collect($this->getErrorBag()->toArray())->count());
//            dd(collect($this->getErrorBag()->toArray())->count());
            if($this->transfer->details->count() === 0){
                DB::rollBack();
                Debugbar::warning('Failed: empty products');
//                    return \Illuminate\Validation\ValidationException::withMessages([
//                        'products' => ['Validation Message #1'],
//                    ]);
                return back()->with(['error' => 'Empty products']);
            }
            foreach ($this->transfer->details as $detail) {
                Debugbar::info('Foreach ' . $detail['product'][Str::lower($this->transfer_from) .'_stock']);
                // Insert Into Details Purchase select from tempPurchaseDetails
                if($this->getErrorBag()->toArray()){
                    DB::rollBack();
                    Debugbar::warning('Failed');
//                    return \Illuminate\Validation\ValidationException::withMessages([
//                        'products' => ['Validation Message #1'],
//                    ]);
                    session()->flash('error', 'Out of stock for ' . $detail->product_name);
                    return back();
                }

                Debugbar::ingo('Validation insert temporary Pass');
                $transfer_transaction->details()->create([
                    'product_id'                => $detail->product_id,
                    'product_price_id'          => $detail->product_price_id,
                    'product_name'              => $detail->product_name,
                    'quantity'                  => $detail->quantity,
                    'product_price_quantity'    => $detail->product_price_quantity,
                ]);

                $transfer_transaction->with('price');

                // Increment Warehouse stock in product table
                // get store stock before increment warehouse stock
                $store_stock = $detail->product->store_stock;
                // increment warehouse stock reduce store stock

                $detail->product->increment($this->transfer_to . '_stock', $detail->product_price_quantity);
                $detail->product->decrement($this->transfer_from . '_stock', $detail->product_price_quantity);
            }

            // Delete tempPurchase and TempPurchaseDetails
            $this->transfer->details()->delete();
            $this->transfer->delete();

            DB::commit();


        }catch (\Exception $exception){
            DB::rollBack();
            return $exception->getMessage();
        }

        $this->cancelTransfer();

    }

    public function removeItem(TempProductTransferDetails $details)
    {
        $details->delete();
        $this->loadTemp();
    }

    public function cancelTransfer()
    {
        $this->transfer->details()->delete();
        $this->transfer->delete();
        $this->reset(['transfer_date']);
        $this->loadTemp();
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
        // panggil fungsi loadTemp (Load table transaksi temporari transfer)
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
        $price = $product->prices->sortByDesc('quantity')->first();

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
        // panggil fungsi loadTemp (Load table transaksi temporari transfer)
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
