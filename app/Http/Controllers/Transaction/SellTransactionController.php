<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Transaction\SellTransactionRepositories;
use App\Models\Customer;
use App\Models\SellDetail;
use Illuminate\Http\Request;

class SellTransactionController extends Controller
{

    public function index(Request $request, SellTransactionRepositories $transactionRepositories)
    {

        $datas = SellDetail::query()
            ->where('payload', 'like', '%'.'product_sock_id'.'%')
            ->get();

        foreach ($datas as $data){
            $pattern = '/product_sock_id/i';
            $payload = preg_replace($pattern, 'product_stock_id', $data->payload);
            $data->update([
                'payload'   => $payload
            ]);
        }

        return 'Done';
    }
}
