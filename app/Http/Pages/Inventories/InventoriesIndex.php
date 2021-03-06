<?php

namespace App\Http\Pages\Inventories;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\TempPurchaseDetail;
use Illuminate\Support\Facades\DB;

class InventoriesIndex extends Autocomplete
{
    public $suppliers;
    public $supplier;

    public $invoice_date;
    public $supplier_id;
    public $supplier_name;
    public $invoice_number;

    public $purchase;

    public $bill;
    public $payment;
    public $fund;

    public $products = [];

    protected $listeners = ['valueSelected'];

    public function render()
    {
        return view('pages.inventories.inventories-index');
    }

    public function save()
    {
        /*
         * 1. Insert into Purchase table select from tempPurchase
         * 2. Insert Into Details Purchase select from tempPurchaseDetails
         * 3. Insert Into ProductStock every Purchase Details
         * 4. Increment Warehouse stock in product table
         * 5. Insert into PurchaseHistory
         * 6. Delete tempPurchase and TempPurchaseDetails
         */
        $this->validate([
            'payment'                   => ['nullable', 'numeric', 'lte:bill'],
            'products'                  => ['required', 'array', 'min:1', 'max:4000000000'],
            'products.*.quantity'       => ['required', 'numeric', 'min:1', 'max:4000000000'],
            'products.*.buying_price'   => ['required', 'numeric', 'min:1', 'max:4000000000'],
        ]);

        DB::beginTransaction();
        try {
            // Insert into Purchase table select from tempPurchase
            $purchase_transaction = Purchase::query()
                ->create([
                    'user_id'           => $this->purchase->user_id,
                    'supplier_id'       => $this->purchase->supplier_id,
                    'supplier_name'     => $this->purchase->supplier_name,
                    'invoice_number'    => $this->purchase->invoice_number,
                    'invoice_date'      => $this->purchase->invoice_date,
                    'status'            => $this->fund ? 'BELUM LUNAS' : "LUNAS",
                ]);
            foreach ($this->purchase->details as $detail) {
                // Insert Into Details Purchase select from tempPurchaseDetails

                $purchase_transaction->details()->create([
                    'product_id'                => $detail->product_id,
                    'product_price_id'          => $detail->product_price_id,
                    'product_name'              => $detail->product_name,
                    'quantity'                  => $detail->quantity,
                    'product_price_quantity'    => $detail->product_price_quantity,
                    'buying_price'              => $detail->buying_price,
                    'total'                     => $detail->total,
                ]);

                $purchase_transaction->with('price');
                // Insert Into ProductStock every Purchase Details

//                dd($this->purchase->details);
                $purchase_transaction->stocks()->create([
                    'product_id'        => $detail->product_id,
                    'supplier_id'       => $this->purchase->supplier_id,
                    'first_stock'       => $detail->product_price_quantity,
                    'available_stock'   => $detail->product_price_quantity,
                    'buying_price'      => $detail->total / $detail->product_price_quantity,
                    'description'       => 'PENAMBAHAN',
                ]);

//                Debugbar::info('Create Stock');
                // Increment Warehouse stock in product table
                // get store stock before increment warehouse stock
                $store_stock = $detail->product->store_stock;

//                DB::rollBack();
//                dd('Stck ' .$detail->product_price_quantity);
                // increment warehouse stock reduce store stock

                $detail->product()->increment('warehouse_stock', $detail->product_price_quantity);
            }

            // Insert into purchase history
            $purchase_transaction->histories()->create([
                'pay_date'      => $this->purchase->invoice_date,           // Tanggal pembayaran
                'bill'          => $this->bill,                             // total tagihan
                'payment'       => $this->payment,                          // total pembayaran
                'fund'          => $this->fund                              // sisa pembayaran
            ]);
            // Delete tempPurchase and TempPurchaseDetails
            //

            $this->purchase->details()->delete();
            $this->purchase->delete();

            DB::commit();


        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with(['status' => 'error', 'message' => $exception->getMessage()]);
        }

        $this->cancelPurchase();


    }

    public function updateProduct($key)
    {
        $t_details = collect($this->products[$key]);
        $p_prices = collect($t_details['product']['prices'])->where('id', $this->products[$key]['product_price_id'])->first();
        $buying_price = $this->products[$key]['buying_price'];
        $this->purchase
            ->details()
            ->where('id', $t_details['id'])
            ->update([
                'product_price_id'          => $this->products[$key]['product_price_id'],
                'quantity'                  => $this->products[$key]['quantity'],
                'product_price_quantity'    => $this->products[$key]['quantity'] * $p_prices['quantity'],
                'buying_price'              => $buying_price,
                'total'                     => $buying_price * $this->products[$key]['quantity'],
            ]);

        $this->loadTemp();
    }

    public function valueSelected(Product $product)
    {
        $this->emitUp('userSelected', $product);

        // Load relasi produk
        $product->load(['unit', 'prices.unit', 'stocks']);

        // panggil fungsi addProduct
        $this->addProduct($product);

        $this->reset(['search']);
    }

    public function addProduct($product)
    {
        // set satuan default adalah yang terakhir (Satuan terbesar)
        $price = $product->prices->last();
        // cek harga modal terakhir beli (jika ada)
        $stock = $product->stocks->last();

        // Tambahkan produk ke table temp (tabel sementara)
        $this
            ->purchase
            ->details()
            ->create([
                'product_id'                => $product->id,
                'product_price_id'          => $price->id,
                'product_name'              => $product->name,
                'quantity'                  => 1,
                'product_price_quantity'    => $price->quantity,
                'buying_price'              => $stock ? $stock->buying_price * $price->quantity : 0,
                'total'                     => $stock ? $stock->buying_price * $price->quantity : 0,

            ]);
        // panggil fungsi loadTemp (Load table transaksi temporari pembeian)
        $this->loadTemp();
    }

    public function mount()
    {
        // list Supplier
        $this->suppliers = Supplier::query()
            ->get()->toArray();


        // panggil fungsi loadTemp (Load table transaksi temporari pembeian)
        $this->loadTemp();

        $this->selectSupplier($this->purchase ? $this->purchase->supplier_id : null);

        $this->invoice_date = $this->purchase ? $this->purchase->invoice_date : null;
        $this->invoice_number = $this->purchase ? $this->purchase->invoice_number : null;

    }

    // buat transaksi pembeian (insert ke tabel purchase)
    public function beginPurchase()
    {
        $this->validate([
            'supplier_id'       => ['nullable', 'exists:suppliers,id'],
            'invoice_number'    => ['required'],
            'invoice_date'      => ['required', 'date', 'before_or_equal:now'],
        ]);

        \auth()->user()->tempPurchase()
            ->create([
                'supplier_id'       => $this->supplier_id,
                'supplier_name'     => $this->supplier_name,
                'invoice_number'    => $this->invoice_number,
                'invoice_date'      => $this->invoice_date,
            ]);
        // panggil fungsi loadTemp (Load table transaksi temporari pembeian)
        $this->loadTemp();
    }

    public function loadTemp()
    {
        // load table temporary beserta relasi
        $this->purchase = [];
        $this->purchase = \auth()->user()->tempPurchase()->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit'])->first();
        if($this->purchase){
            if($this->purchase->details->count()){
                $this->products = [];
                foreach ($this->purchase->details as $detail) {
                    array_push($this->products, $detail->toArray());
                }
            }
//            dd($this->products);
            $this->bill = $this->purchase->details->count() ? $this->purchase->details->sum('total') : 0;
            $this->payment = $this->payment ?: 0;
            $this->fund = $this->bill - $this->payment;
        }

        $this->dispatchBrowserEvent('pageReload');

    }

    // fungsi hapus produk dari table temporari
    public function removeItem(TempPurchaseDetail $tempPurchaseDetail)
    {
        $tempPurchaseDetail->delete();
        $this->loadTemp();
    }

    public function cancelPurchase()
    {
        $this->purchase->details()->delete();
        $this->purchase->delete();
        $this->loadTemp();
        $this->reset([
            'invoice_date',
            'supplier_id',
            'supplier_name',
            'invoice_number',
            'purchase',
            'products',
        ]);

        $this->dispatchBrowserEvent('purchaseCancel');
    }

    public function saveDraft()
    {
        // ke halaman index (transaksi pembelian terakhir tetap tersimpan berdasarkan User ID)
        return redirect()->route('dashboard.index');
    }

    // trigger perubahan pada supplier_id (jika supplier di select)
    public function updatedSupplierId()
    {
        $this->selectSupplier($this->supplier_id);
    }

    public function selectSupplier($id = null)
    {
        // select supplier
        $supplier = Supplier::query()
            ->where('id', $id)
            ->first();
        $this->supplier = $supplier;
        $this->supplier_name = $supplier ? $supplier->name : null;
        $this->supplier_id = $supplier ? $supplier->id : null;
    }


    // fungsi set tanggal hari ini
    public function setToday()
    {
        $this->invoice_date = now()->format('Y-m-d');
    }

    // fungsi reset tanggal
    public function clearToday()
    {
        $this->reset('invoice_date');
    }

    // query untuk search produk berdasarkan barcode dan nama produk
    public function query() {
        return Product::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('barcode', 'like', '%'.$this->search.'%')
            ->orderBy('name')->take(10);
    }
}
