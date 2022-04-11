<?php

namespace App\Http\Pages\Reporting\Transaction;

use App\Models\Sell;
use App\Models\SellReturn;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Livewire\Component;

class ReportTransaction extends Component
{
    public $user_id;
    public $users;
    public $transaction_date;

    public $sells;
    public $returns;


    public function render()
    {
        return view('pages.reporting.transaction.report-transaction');
    }

    public function mount()
    {
        $this->users = User::query()->get();
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function viewReport()
    {
        $this->transaction_date = $this->transaction_date ?: now()->format('Y-m-d');
        $this->returns = SellReturn::query()
            ->with(['user', 'price.unit', 'sell'])
            ->whereHas('sell', function ($sell){
                $sell->whereDate('invoice_date', '<>', $this->transaction_date);
            })
            ->when($this->user_id, function ($query, $user_id){
                $query->where('user_id', $user_id);
            })
            ->when($this->transaction_date, function ($query, $transaction_date){
                $query->whereDate('created_at', Carbon::createFromFormat('Y-m-d', $transaction_date));
            })
            ->get()->map(function ($data){
                return [
                    'invoice_number'    => $data->sell->invoice_number,
                    'invoice_date'      => $data->sell->invoice_date->format('d-m-Y'),
                    'user'              => $data->user->username,
                    'product_name'      => $data->product_name,
                    'quantity'          => $data->quantity . ' ' .$data->price->unit->name,
                    'sell_price'        => $data->sell_price,
                    'total'             => $data->quantity * $data->sell_price,
                ];
            });

//        dd($this->returns);

        $this->sells = Sell::query()
            ->when($this->user_id, function ($query, $user_id){
                $query->where('user_id', $user_id);
            })
            ->when($this->transaction_date, function ($query, $transaction_date){
                $query->whereDate('invoice_date', Carbon::createFromFormat('Y-m-d', $transaction_date));
            })
            ->with(['user', 'returns' => function($query){
                $query->when($this->transaction_date, function ($query_date, $date){
                    $query_date->whereDate('created_at', Carbon::createFromFormat('Y-m-d', $date));
                });
            }])
            ->withSum('details', 'total')
            ->withSum('details', 'discount')
            ->get()->map(function ($data) {
                return [
                    'user'              => $data->user->username,
                    'invoice_number'    => $data->invoice_number,
                    'invoice_date'      => $data->invoice_date->format('d-m-Y'),
                    'customer'          => $data->customer_name,
                    'bill'              => $data->bill + $data->discount,
                    'discount'          => $data->discount,
                    'total'             => $data->bill,
                    'payment'           => $data->payment,
                    'refund'            => $data->payment - $data->bill,
                    'status'            => $data->status,
                    'sum_return'        => $data->returns->sum(function($return){
                        return $return['quantity'] * $return['sell_price'];
                    }),
                    'returns'           => $data->returns,

                ];
            });
//        dd($this->sells);
    }

    public function exportExcel()
    {
        $this->transaction_date = $this->transaction_date ?: now()->format('Y-m-d');

        Debugbar::info($this->user_id);
        Debugbar::info($this->transaction_date);
    }
}
