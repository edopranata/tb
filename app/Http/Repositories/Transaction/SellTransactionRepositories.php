<?php

namespace App\Http\Repositories\Transaction;

use Illuminate\Http\Request;

class SellTransactionRepositories
{

    public function navigate(Request $request)
    {
        switch ($request->path){
            case 'loadTemp':
                return '$this->loadTemp()';
                break;
//            case 'searchProduct':
//                return $this->searchProduct($request);
//                break;
//            case 'getProductID':
//                return $this->getProductID($request);
//                break;
            case 'createTransaction':
                return $this->createTransaction($request);
                break;
//            case 'cancelTransaction':
//                return $this->cancelTransaction($request);
//                break;
//            case 'saveTransaction':
//                return $this->saveTransaction($request);
//                break;
//            case 'getToday':
//                return $this->getToday();
//                break;
//            case 'editProduct':
//                return $this->editProduct($request);
//                break;
//            case 'removeProduct':
//                return $this->removeProduct($request);
//                break;

            default:
                return response()->json(['errors' => 'Invalid path'], 401);
                break;
        }
    }

    public function createTransaction(Request $request)
    {
        $this->transaction_date = $this->transaction_date ?: now()->format('Y-m-d');
        auth()->user()->tempSells()->create([
            'customer_id'   => $this->customer_id ?: null,
            'customer_name' => $this->customer_name ?: null,
            'invoice_date'  => $this->transaction_date,
            'invoice_number'=> $this->invoice_number,
        ]);
    }
}
