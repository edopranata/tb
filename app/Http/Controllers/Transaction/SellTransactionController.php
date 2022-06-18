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
        if($request->ajax()){
            return $transactionRepositories->navigate($request);
        }else{
            $customers = Customer::all();
            return view('transaction.index', compact('customers'));
        }
    }
}
