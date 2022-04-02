<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\Supplier;
use App\Models\Unit;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class NewProductImport implements ToCollection
{
    /**
    * @param Collection $collection
    */

    public $transfer;

    public function  __construct($transfer)
    {
        $this->transfer = $transfer;
    }

    public function collection(Collection $collection)
    {

        $user_id = auth()->id();

        if($this->transfer){
            $product_transfer = ProductTransfer::query()
                ->create([
                    'user_id'       => $user_id,
                    'transfer_date' => now(),
                    'transfer_from' => 'WAREHOUSE',
                    'transfer_to'   => 'STORE'
                ]);
        }
        foreach ($collection as $key => $row) {

            /**
             * 1. Collect data from excel ✔
             * 2. Create Or Update units table ✔
             * 3. Create Or Update categories table ✔
             * 4. Create Or Update suppliers table ✔
             * 5. Insert into product table
             *    Create custom barcode (unique)
             *    Set warehouse_stock
             * 6. Insert into product_stock as STOCK AWAL
             * 7. Insert into product_price as default
             * 8. Add product transfer
             */

            if($key == 3) {
                $excel_header = [
                    0 => "BARCODE",
                    1 => "BARCODE",
                    2 => "NAMA PRODUK",
                    3 => "STOK MINIMAL",
                    4 => "STOK",
                    5 => "KATEGORI",
                    6 => "SATUAN",
                    7 => "HARGA MODAL",
                    8 => "HARGA SATUAN",
                    9 => "HARGA GROSIR",
                    10 => "HARGA CUSTOMER"
                ];

            }elseif($key >= 1) {
                if ($row[0] != '') {
                    /**
                     * firstOrCreate categories
                     */
                    $category = Category::query()
                        ->firstOrCreate([
                            'name'  => $row[5], // SATUAN
                        ], [
                            'name'  => $row[5],
                            'user_id'   => $user_id
                        ]);


                    /**
                     * firstOrCreate units
                     */
                    $unit = Unit::query()
                        ->firstOrCreate([
                            'name'      => $row[6] // SATUAN
                        ], [
                            'name'      => $row[6],
                            'user_id'   => $user_id
                        ]);


                    /**
                     * firstOrCreate suppliers
                     */
                    $supplier = Supplier::query()
                        ->firstOrCreate([
                            'name'      => $row[0],
                            'user_id'   => $user_id
                        ]);



                    /**
                     * Create products
                     */
                    $barcode = Str::upper(Str::random(4)) . now()->format('YmdHis');

                    $product = Product::query()
                        ->create([
                            'barcode'           => $row[1] ?: $barcode,
                            'name'              => $row[2],
                            'min_stock'         => $row[3],
                            'warehouse_stock'   => $row[4],
                            'store_stock'       => 0,
                            'category_id'       => $category->id,
                            'unit_id'           => $unit->id,
                            'user_id'           => $user_id
                        ]);

                    /**
                     * Create product_stocks
                     */
                    $product->stocks()->create([
                        'supplier_id'       => $supplier->id,
                        'first_stock'       => $row[4],
                        'available_stock'   => $row[4],
                        'buying_price'      => $row[7],
                        'description'       => 'STOCK AWAL',
                    ]);

                    /**
                     * Create product_prices
                     */
                    $price = $product->prices()->create([
                        'unit_id'           => $unit->id,
                        'quantity'          => 1,
                        'sell_price'        => $row[8],
                        'wholesale_price'   => $row[9],
                        'customer_price'    => $row[10],
                        'default'           => '1'
                    ]);

                    if ($this->transfer){
                        /**
                         * Create product_transfer_details
                         */
                        $product_transfer->details()->create([
                            'product_id'                => $product->id,
                            'product_price_id'          => $price->id,
                            'product_name'              => $product->name,
                            'quantity'                  => $row[4],
                            'product_price_quantity'    => $row[4],
                        ]);

                        /**
                         * increment store_stock and decrement warehouse_stock
                         */

                        $product->increment('store_stock', $row[4]);
                        $product->decrement('warehouse_stock', $row[4]);
                    }

                }
            }
        }
    }
}
