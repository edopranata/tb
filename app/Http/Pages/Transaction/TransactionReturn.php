<?php

namespace App\Http\Pages\Transaction;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Sell;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransactionReturn extends Component
{

    /**
     * To Do
     * 1. Ambil No Invoice Transaksi Penjualan ✔
     * 2. Tampilkan detail barang yang dibeli ✔
     * 3. Input quantity barang yang akan di retur ✔
     * 4. Validasi untuk tiap barang yang akan di retur ✔
     * 5. Kembalikan stock ✔
     *    Pada table product ✔
     *    Pada table product_stock (fifo stock) sesuai payload dari transaksi ✔
     * 6. Simpan retur pada table sell_returns ✔
     */

    public $invoice_number;
    public $sell;
    public $return = [];
    public function render()
    {
        return view('pages.transaction.transaction-return');
    }

    public function getInvoice()
    {
        $this->validate([
            'invoice_number'    => ['required', 'string', 'exists:sells,invoice_number']
        ]);
        $this->sell = Sell::query()
            ->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit', 'returns', 'user'])
            ->where('invoice_number', $this->invoice_number)->first();


    }

    public function submitReturn()
    {

        $data_return = [];
        /**
         * Collect detail dari invoice
         */
        $details = collect($this->sell->details);
        $returns = collect($this->sell->returns);
        /**
         * Cek form product yang akan di retur
         */
        if(!$this->return){
            return back()->with(['error' => 'invalid return quantity']);
        }

        /**
         * buat fungsi database transaction
         */
        DB::beginTransaction();
        try {
            /**
             * Looping data produk yang akan di retur
             */
            foreach ($this->return as $key => $item) {
                /**
                 * Ambil sell_detail sesuai dengan product yang akan di retur
                 */
                $detail = $details->where('id', $key)->first();

                /**
                 * Ambil sell_returns sesuai dengan product yang akan di retur
                 */
                $stock_return = $returns->where('sell_detail_id', $detail['id'])->first();

                /**
                 * Quantity yang bisa di return
                 */

                $available_return = $detail['quantity'] - ($stock_return ? $stock_return['quantity'] : 0);

                /**
                 * Quantity return harus lebih kecil atau sama dengan quantity yang sudah di beli berdasarkan invoice
                 */
                if($item['quantity'] > $available_return){
                    return back()->with(['error' => 'Kesalahan pada quantity untuk produk ' . $detail['product_name'] . ', sisa yang bisa di retur adalah ' . $available_return]);
                    DB::rollBack();
                }

                /**
                 * Collect payload from sell_details
                 */
                $stocks = collect(json_decode($detail['payload'], true))->toArray();

                /**
                 * set $current_quantity to return quantity
                 */
                $current_quantity = $detail['product_price_quantity'];


                /**
                 * Increment store_stock to return quantity from products table
                 */
                $product = Product::query()
                    ->where('id', $detail['product_id'])->first();
                $product->increment('store_stock', $current_quantity);

                /**
                 * Looping payload data from sell_details
                 */
                $payload_stock = [];
                foreach ($stocks as $stock) {
                    $stock_id = $stock['product_stock_id'];
//                    dd($stock_id);
                    /**
                     * Set payload for sell_returns table
                     */
                    if($current_quantity >= $stock['quantity']){
                        $payload_stock[] = [
                            'product_stock_id' => $stock_id,
                            'quantity' => $stock['quantity'],
                            'buying_price' => $stock['buying_price'],
                            'total' => $stock['total'],
                        ];

                        /**
                         * decrement $current_quantity
                         */
                        $current_quantity = $current_quantity - $stock['quantity'];

                        /**
                         * Restore or increment available_stock in product_stocks table
                         */
                        $product_stock = ProductStock::query()
                            ->where('id', $stock['product_stock_id'])->first();

                        $product_stock->increment('available_stock', $stock['quantity']);

                    }else{

                        $payload_stock[] = [
                            'product_stock_id' => $stock['product_stock_id'],
                            'quantity' => $current_quantity,
                            'buying_price' => $stock['buying_price'],
                            'total' => $stock['total'],
                        ];

                        /**
                         * Restore or increment available_stock in product_stocks table
                         */

                        $product_stock = ProductStock::query()
                            ->where('id', $stock['product_stock_id'])->first();

                        $product_stock->increment('available_stock', $current_quantity);

                        $current_quantity = 0;
                    }
                }

                /**
                 * Insert into sell_returns table
                 */
                $this->sell->returns()
                    ->create([
                        'user_id'               => auth()->id(),
                        'product_id'            => $detail['product_id'],
                        'product_price_id'      => $detail['product_price_id'],
                        'sell_detail_id'        => $detail['id'],
                        'product_name'          => $detail['product_name'],
                        'quantity'              => $item['quantity'],
                        'product_price_quantity'=> $detail['product_price_quantity'],
                        'buying_price'          => $detail['buying_price'],
                        'payload'               => json_encode($payload_stock),
                        'sell_price'            => $detail['sell_price'],
                    ]);
            }

            /**
             * Commit database transaction
             * reset form and variable
             * return success message
             */
            DB::commit();
            $invoice = $this->invoice_number;
            $this->reset();
            return back()->with(['success' => 'Retur untuk invoice ' . $invoice . ' berhasil disimpan, stock produk bertambah']);
        }catch (\Exception $exception) {
            DB::rollBack();
            return back()->with(['error' => $exception->getMessage() . ' on line ' . $exception->getLine()]);
        }
    }
}
