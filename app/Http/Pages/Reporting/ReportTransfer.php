<?php

namespace App\Http\Pages\Reporting;

use App\Models\ProductTransfer;
use App\Models\Purchase;
use Livewire\Component;

class ReportTransfer extends Component
{

    public $user_id;
    public $transfer_date;
    public $transfer_to;
    public function render()
    {

        return view('pages.reporting.report-transfer', [
            'transfers' => ProductTransfer::query()
                ->with(['user', 'details.product.unit', 'details.price'])
                ->withCount('details')
                ->when($this->user_id, function ($user, $id){
                    $user->where('user_id', $id);
                })
                ->when($this->transfer_date,function ($transfer, $date){
                    $transfer->where('transfer_date', $date);
                })
                ->when($this->transfer_to, function ($transfer, $to){
                    $transfer->where('transfer_to', $to);
                })->get()
        ]);

    }

    public function mount()
    {

    }
}
