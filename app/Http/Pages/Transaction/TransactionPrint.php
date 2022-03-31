<?php

namespace App\Http\Pages\Transaction;

use App\Models\Sell;
use Livewire\Component;

class TransactionPrint extends Component
{
    public $sell;

    public function render()
    {
        return view('pages.transaction.transaction-print');
    }

    public function mount(Sell $sell)
    {

        $this->sell = $sell->load(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit', 'user']);
    }
}
