<?php

namespace App\Http\Pages\Inventories;

use Livewire\Component;

class InventoriesTransferCreate extends Component
{
    public $transfer_to;

    protected $transfer = ['store', 'warehouse'];

    public function render()
    {
        return view('pages.inventories.inventories-transfer-create');
    }

    public function mount($transfer)
    {

        if (in_array($transfer, $this->transfer )) {
            $this->transfer_to = $transfer;
        }else{
            abort(404);
        }
    }
}
