<?php

namespace App\Http\Pages\Reporting\Transaction;

use App\Models\Sell;
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

        $this->sells = Sell::query()
            ->when($this->user_id, function ($query, $user_id){
                $query->where('user_id', $user_id);
            })
            ->when($this->transaction_date, function ($query, $transaction_date){
                $query->whereDate('invoice_date', Carbon::createFromFormat('Y-m-d', $transaction_date));
            })
            ->with(['user'])
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

                ];
            });
    }

    public function exportExcel()
    {
        $this->transaction_date = $this->transaction_date ?: now()->format('Y-m-d');

        Debugbar::info($this->user_id);
        Debugbar::info($this->transaction_date);
    }
}
