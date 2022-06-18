<?php

namespace App\Http\Repositories\Transaction;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;

class SellTransactionRepositories
{
    public $prefix = 'SBR';
    public $price_type = 'sell';

    public function navigate(Request $request)
    {
        switch ($request->path){
            case 'loadTemp':
                return $this->loadTemp();
                break;
            default:
                return response()->json(['errors' => 'Invalid path'], 401);
                break;
            case 'getToday':
                return $this->getToday();
                break;
            case 'createTransaction':
                return $this->createTransaction($request);
                break;
            case 'searchProduct':
                return $this->searchProduct($request);
                break;
            case 'getProductID':
                return $this->getProductID($request);
                break;
            case 'cancelTransaction':
                return $this->cancelTransaction($request);
                break;
//            case 'saveTransaction':
//                return $this->saveTransaction($request);
//                break;
//            case 'editProduct':
//                return $this->editProduct($request);
//                break;
//            case 'removeProduct':
//                return $this->removeProduct($request);
//                break;

        }
    }

    public function getToday()
    {
        return now()->toDateString();
    }

    public function generateInvoiceNumber($transaction_date)
    {
        $date = str_replace('-', '', $transaction_date);
        $sells = Sell::query()
            ->whereDate('invoice_date', $date)->get()->count();

        $number = $sells ? $sells + 1 : 1;
        $suffix = sprintf('%03d', $number);

        return $this->prefix . $date . $suffix;
    }

    public function getProductID(Request $request)
    {

        switch ($request->field){
            case 'id':
                $product = Product::query()
                    ->where('id', $request->id)
                    ->first();
                return $this->getProduct($request, $product);
                break;
            case 'barcode':
                $product = Product::query()
                    ->where('barcode', $request->id)
                    ->first();
                return $this->getProduct($request, $product);
                break;

        }

    }

    public function getProduct(Request $request, $product)
    {
        $this->price_type = $request->price_type;
        if($product->store_stock >= 1){
            $price = collect($product->prices->where('default', '1')->first());
            DB::beginTransaction();
            try {

                $sell = \auth()->user()->tempSells()->with(['details'])->first();
                $sell
                    ->details()
                    ->create([
                        'product_id' => $product->id,
                        'product_price_id' => $price['id'],
                        'product_name' => $product->name,
                        'quantity' => 1,
                        'product_price_quantity' => $price['quantity'],
                        'sell_price' => $price[$this->price_type . '_price'],
                        'sell_price_quantity' => 1,
                        'price_category' => Str::upper($this->price_type),
                        'total' => $price[$this->price_type . '_price'] * $price['quantity'],

                    ]);

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Product added'], 201);
            }catch (\Exception $exception){
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Product added failed ' . $exception->getMessage()], 401);
            }
        }else{
            return response()->json(['success' => false, 'message' => 'Out of stock for ' . $product->name . ' store stock ' . $product->store_stock . ' warehouse stock ' . $product->warehouse_stock], 401);

        }
    }

    public function cancelTransaction(Request $request)
    {
        DB::beginTransaction();
        try {
            $sell = \auth()->user()->tempSells()->with(['details'])->first();
            $sell->details()->delete();
            $sell->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaction canceled'], 201);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Cancel transaction failed ' . $exception->getMessage()], 401);
        }
    }

    public function createTransaction(Request $request)
    {
        $invoice_date = $request->invoice_date ?: now()->format('Y-m-d');
        $invoice_number = $this->generateInvoiceNumber($invoice_date);
        $customer = Customer::find($request->customer_id);

        DB::beginTransaction();
        try {
            auth()->user()->tempSells()->create([
                'customer_id' => $customer ? $customer->id : null,
                'customer_name' => $customer ? $customer->name : 'Guest',
                'invoice_date' => $invoice_date,
                'invoice_number' => $invoice_number,
            ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaction begin'], 201);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Transaction begin failed ' . $exception->getMessage()], 401);
        }
    }

    public function loadTemp()
    {
        $data['products'] = [];
        $data['sells'] = \auth()->user()->tempSells()->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit', 'user'])->first();
        if ($data['sells']) {
            $data['products'] = [];
            if ($data['sells']->details->count()) {
                foreach ($data['sells']->details as $detail) {
                    $data['products'][] = $detail->toArray();
                }
            }

            $data['invoice_number'] = $data['sells']->invoice_number;
            $data['sell_discount'] = $data['sells']->discount;
            $data['transaction_date'] = $data['sells']->invoice_date->format('Y-m-d');
            $data['customer_id'] = $data['sells']->customer_id;
            $data['customer_name'] = $data['sells']->customer_name;
            $data['price_type'] = ($data['customer_id'] != null) ? 'customer' : 'sell';

            $data['total'] = $data['sells']->details->sum('total');
            $data['sell_discount'] = $data['sells']->discount;
            $data['payment'] = $data['sells']->payment;
            $data['payment_format'] = $data['sells']->payment;
            $data['due_date'] = $data['sells']->due_date;
            $data['refund'] = $data['payment'] - ($data['total'] - $data['sell_discount']);

        }
        return $data;
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
}
