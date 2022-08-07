<?php

namespace App\Http\Repositories\Products;

use App\Models\ProductStock;
use App\Models\SellDetail;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductMaintenanceRepositories
{
    public function navigate(Request $request)
    {
        switch ($request->path){
            case 'tableSells':
                return $this->tableSells($request);
                break;
            case 'tableSellDetails':
                return $this->tableSellDetails($request);
                break;
            case 'updatePrice':
                return $this->updatePrice($request);
                break;
            default:
                return response()->json(['errors' => 'Invalid path'], 401);
                break;
        }
    }

    public function updatePrice(Request $request)
    {
        $request->validate([
            'product_stock_id'  => ['required', 'exists:product_stocks,id'],
            'buying_price'      => ['required', 'numeric'],
        ]);

        $product_stock = ProductStock::query()
            ->with('payloads')
            ->where('id', $request->product_stock_id)->first();

        DB::beginTransaction();
        try {
            /**
             * 1. Update harga modal di table product_stocks
             * 2. Update harga modal di table price_payloads berdasarkan harga sesuai product_stock_id
             * 3. Update harga modal pada table sell_details sesuai dengan sell_detail_id pada price_payloads
             */

            $payloads = [];
            foreach ($product_stock->payloads as $payload) {
                $payload->update([
                    'buying_price'  => $request->buying_price,
                    'total'         => $payload->quantity * $request->buying_price
                ]);
                if($payload->quantity > 0){
                    $payloads[] = $payload;
                }
            }

            $product_stock->update([
                'buying_price'  => $request->buying_price
            ]);

            $sell_detail_id = collect($payloads)->pluck('sell_detail_id');
            foreach ($sell_detail_id as $id) {
                $sell_detail = SellDetail::query()
                    ->with('payloads')
                    ->where('id', $id)->first();

                $sell_detail->update([
                    'payload'       => collect($sell_detail->payloads),
                    'buying_price'  => $sell_detail->payloads()->sum('total') / $sell_detail->payloads()->sum('quantity')
                ]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Seluruh transaksi telah di update'], 201);
        }catch (Exception $exception){
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Transaction failed ' . $exception->getMessage()], 401);
        }
    }

    public function tableSells(Request $request)
    {
        return [
            'stock_id'  => $request->product_stock_id,
            'details'   => SellDetail::query()
                ->with(['sell', 'price', 'product', 'payloads'])
                ->where('product_id', $request->product_id)
                ->get()
                ->map(function ($sells){
                    return [
                        'id'                => $sells->id,
                        'invoice_number'    => $sells->sell->invoice_number,
                        'buying_price'      => $sells->buying_price,
                        'sell_price'        => $sells->sell_price,
                        'quantity'          => $sells->quantity,
                        'payloads'          => $sells->payloads,
                        'total'             => $sells->total
                    ];
                }),
            'stock' => ProductStock::query()->where('id', $request->product_stock_id)->first()->only(['id','purchase_id', 'buying_price'])
        ];
    }

    public function tableSellDetails(Request $request)
    {
        return $request->all();
    }
}
