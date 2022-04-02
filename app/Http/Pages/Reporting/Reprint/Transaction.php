<?php

namespace App\Http\Pages\Reporting\Reprint;

use App\Models\Sell;
use Livewire\Component;

class Transaction extends Component
{
    public $sell;

    public $invoice;

    public function render()
    {
        return view('pages.reporting.reprint.transaction');
    }

    public function printInvoice()
    {
        $this->validate([
            'invoice' =>    ['required', 'exists:sells,invoice_number']
        ]);

        $this->sell = Sell::query()
            ->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit', 'user'])
            ->where('invoice_number', $this->invoice)->first();

        $this->dispatchBrowserEvent('pagePrint');
    }

    public function lastTransaction()
    {
        $this->sell = Sell::query()
            ->with(['details.product.prices.unit', 'details.product.stocks', 'details.price.unit', 'user'])
            ->where('user_id', auth()->id())
            ->latest()->first();

        $this->dispatchBrowserEvent('pagePrint');

    }



}
