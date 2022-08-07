<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Transaction\SellTransactionRepositories;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\SellDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellTransactionController extends Controller
{

    public function index(Request $request, SellTransactionRepositories $transactionRepositories)
    {
        if($request->ajax()){
            return $transactionRepositories->navigate($request);
        }else{
            $customers = Customer::all();
            return view('transaction.index', compact('customers'));
        }
    }

    public function payloads()
    {
        $sell_details = SellDetail::query()->chunk(100, function ($details){
            DB::beginTransaction();
            try {
                foreach ($details as $detail) {

                    $payloads = $detail->payload;
                    foreach ($payloads as $payload) {
                        $detail->payloads()->create($payload);
                    }
                }
                DB::commit();
            } catch (\Exception $exception){
                DB::rollBack();
                return $exception->getMessage();
            }
        });
    }

    public function customer()
    {
        $purchases = Purchase::query()
            ->with(['supplier'])
            ->get();
        DB::beginTransaction();
        try {
            foreach ($purchases as $purchase) {
                $name = $purchase->supplier->name;

                $purchase->update([
                    'supplier_name' => $name
                ]);
            }
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
