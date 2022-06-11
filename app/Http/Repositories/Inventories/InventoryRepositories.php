<?php

namespace App\Http\Repositories\Inventories;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\TempPurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class InventoryRepositories
{

    public function navigate(Request $request)
    {
        switch ($request->path){
            case 'loadTemp':
                return $this->loadTemp();
                break;
            case 'searchProduct':
                return $this->searchProduct($request);
                break;
            case 'getProductID':
                return $this->getProductID($request);
                break;
            case 'createTransaction':
                return $this->createTransaction($request);
                break;
            case 'cancelTransaction':
                return $this->cancelTransaction($request);
                break;
            case 'saveTransaction':
                return $this->saveTransaction($request);
                break;
            case 'getToday':
                return $this->getToday();
                break;
            case 'editProduct':
                return $this->editProduct($request);
                break;
            case 'removeProduct':
                return $this->removeProduct($request);
                break;

            default:
                return response()->json(['errors' => 'Invalid path'], 401);
                break;
        }
    }

    public function cancelTransaction(Request $request)
    {
        DB::beginTransaction();
        try {
            $purchase = \auth()->user()->tempPurchase()->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit'])->first();
            $purchase->details()->delete();
            $purchase->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaction canceled'], 201);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Cancel transaction failed ' . $exception->getMessage()], 401);
        }
    }

    public function saveTransaction(Request $request)
    {

        $request->validate([
            'payment'          => ['nullable', 'numeric', 'lte:bill'],
            'id'               => ['required', 'array', 'min:1', 'max:4000000000'],
            'quantity.*'       => ['required', 'numeric', 'min:1', 'max:4000000000'],
            'buying_price.*'   => ['required', 'numeric', 'min:1', 'max:4000000000'],
        ]);

        DB::beginTransaction();


        try {
            $purchase = \auth()->user()->tempPurchase()->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit'])->first();

            $purchase_transaction = Purchase::query()
                ->create([
                    'user_id'           => $purchase->user_id,
                    'supplier_id'       => $purchase->supplier_id,
                    'supplier_name'     => $purchase->supplier_name,
                    'invoice_number'    => $purchase->invoice_number,
                    'invoice_date'      => $purchase->invoice_date,
                    'status'            => ($request->bill < $request->fund) ? 'BELUM LUNAS' : "LUNAS",
                ]);
            foreach ($purchase->details as $detail) {
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


                $purchase_transaction->stocks()->create([
                    'product_id'        => $detail->product_id,
                    'supplier_id'       => $purchase->supplier_id,
                    'first_stock'       => $detail->product_price_quantity,
                    'available_stock'   => $detail->product_price_quantity,
                    'buying_price'      => $detail->total / $detail->product_price_quantity,
                    'description'       => 'PENAMBAHAN',
                ]);

                // increment warehouse stock reduce store stock
                $detail->product()->increment('warehouse_stock', $detail->product_price_quantity);
            }

            // Insert into purchase history
            $purchase_transaction->histories()->create([
                'pay_date'      => $purchase->invoice_date,           // Tanggal pembayaran
                'bill'          => $request->bill,                    // total tagihan
                'payment'       => $request->payment,                 // total pembayaran
                'fund'          => $request->fund                     // sisa pembayaran
            ]);
            // Delete tempPurchase and TempPurchaseDetails


            $purchase->details()->delete();
            $purchase->delete();


            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaction saved'], 201);
        }catch (Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Create transaction save failed ' . $exception->getMessage()], 401);
        }
        return response()->json(['success' => false, 'message' => 'Validation success'], 201);

    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'supplier_id'       => ['nullable', 'exists:suppliers,id'],
            'invoice_number'    => ['required'],
            'invoice_date'      => ['required', 'date', 'before_or_equal:now'],
        ]);

        DB::beginTransaction();
        try {
            $supplier_name = Supplier::query()
                ->find($request->supplier_id);

            \auth()->user()->tempPurchase()
                ->create([
                    'supplier_id'       => $request->supplier_id,
                    'supplier_name'     => $supplier_name,
                    'invoice_number'    => $request->invoice_number,
                    'invoice_date'      => $request->invoice_date,
                ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaction created'], 201);
        }catch (Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Create transaction failed ' . $exception->getMessage()], 401);
        }

    }

    public function getToday()
    {
        return now()->toDateString();
    }

    public function removeProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            TempPurchaseDetail::query()
                ->where('id', $request->id)->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Delete product success'], 201);
        }catch (Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Delete product failed. ' . $exception->getMessage()], 401);

        }
    }
    public function editProduct(Request $request)
    {
        $temp_purchase = TempPurchaseDetail::query()
            ->where('id', $request->id)
            ->with(['product.prices.unit', 'product.stocks', 'price.unit'])->first();

        $p_prices = $temp_purchase->product->prices->where('id', $request->product_price_id)->first();

        $temp_purchase->update([
            'product_price_id'          => $request->product_price_id,
            'quantity'                  => $request->quantity,
            'product_price_quantity'    => $request->quantity * $p_prices->quantity,
            'buying_price'              => $request->buying_price,
            'total'                     => $request->buying_price * $request->quantity,
        ]);
        return $temp_purchase;
    }

    public function getProductID(Request $request)
    {
        DB::beginTransaction();
        try {

            $product = Product::query()
                ->where('id', $request->id)
                ->first();

            $purchase = \auth()->user()->tempPurchase()->with(['details.product.prices.unit', 'details.product.stocks', 'details.product.unit', 'details.price.unit'])->first();
            // set satuan default adalah yang terakhir (Satuan terbesar)
            $price = $product->prices->last();
            // cek harga modal terakhir beli (jika ada)
            $stock = $product->stocks->last();

            // Tambahkan produk ke table temp (tabel sementara)
            $purchase->details()
                ->create([
                    'product_id' => $product->id,
                    'product_price_id' => $price->id,
                    'product_name' => $product->name,
                    'quantity' => 1,
                    'product_price_quantity' => $price->quantity,
                    'buying_price' => 0,
                    'total' => 0,
                ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Add product success'], 201);
        }catch (Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Add product failed ' . $exception->getMessage()], 401);
        }
    }

    public function searchProduct(Request $request)
    {
        if($request->q){
            return Product::query()
                ->where('barcode', 'like', '%' . $request->q . '%')
                ->orWhere('name', 'like', '%' . $request->q . '%')
                ->get()
                ->take(10)
                ->map(function ($data){
                    return [
                        'id'     => $data->id,
                        'text'      => $data->barcode . ' ' . $data->name
                    ];
                });
        }else{
            return response()->json(['error' => 'Invalid parameters'], 401);
        }
    }

    public function loadTemp()
    {
        $data['products'] = [];
        $data['payment'] = 0;
        $data['fund'] = 0;
        $data['purchase'] = \auth()->user()->tempPurchase()->with(['details.product.prices.unit', 'details.product.stocks', 'details.product.unit', 'details.price.unit'])->first();
        if($data['purchase']){
            if($data['purchase']->details->count()){
                foreach ($data['purchase']->details as $detail) {
                    array_push($data['products'], $detail->toArray());
                }
            }

            $data['bill'] = $data['purchase']->details->count() ? $data['purchase']->details->sum('total') : 0;
            $data['payment'] = $data['payment'] ?: 0;
            $data['fund'] = $data['bill'] - $data['payment'];
        }

        return $data;
    }
}
