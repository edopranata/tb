<?php

namespace App\Http\Pages\Inventories;

use App\Http\Pages\Components\Autocomplete;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TempPurchaseDetail;

class InventoriesIndex extends Autocomplete
{
    public $suppliers;
    public $supplier;

    public $supplier_search;
    public $invoice_date;
    public $supplier_id;
    public $supplier_name;
    public $invoice_number;

    public $purchase;
    public $purchase_details;


    public $products = [];



    protected $listeners = ['valueSelected'];

    public function render()
    {
        return view('pages.inventories.inventories-index');
    }

    public function updateProduct($key)
    {

        $t_details = collect($this->products[$key]);
        $p_prices = collect($t_details['product']['prices'])->where('id', $this->products[$key]['product_price_id'])->first();
        $p_stock = collect($t_details['product']['stocks'])->last();
        $buying_price = $p_stock['buying_price'] * $p_prices['quantity'];
        $this
            ->purchase
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
        $this->render();
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
        $this->dispatchBrowserEvent('purchaseBegin');
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
        }
//        dd($this->purchase->toArray());
    }

    // fungsi hapus produk dari table temporari
    public function removeItem(TempPurchaseDetail $tempPurchaseDetail)
    {
        $tempPurchaseDetail->delete();
        $this->loadTemp();
    }

    public function cancelPurchase()
    {

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

    public function selectSupplier($id)
    {
        // select supplier
        $supplier = Supplier::query()
            ->where('id', $id)
            ->first();
        $this->supplier = $supplier;
        $this->supplier_name = $supplier->name;
        $this->supplier_id = $supplier->id;
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
