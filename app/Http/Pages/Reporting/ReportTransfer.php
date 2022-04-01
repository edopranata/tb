<?php

namespace App\Http\Pages\Reporting;

use App\Models\ProductTransfer;
use App\Models\Purchase;
use App\Models\User;
use Livewire\Component;

class ReportTransfer extends Component
{

    public $user_id;
    public $transfer_date;
    public $transfer_to;

    public $users;

    public function render()
    {

        return view('pages.reporting.report-transfer', [
            'transfers' => ProductTransfer::query()
                ->with(['user', 'details.product.category', 'details.price.unit'])
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
        $this->users = User::query()->select(['id', 'name'])->get();
//        $this->transfer_date = now()->format('Y-m-d');

    }
}
