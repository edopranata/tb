<?php

namespace App\Http\Pages\Inventories;

use App\Models\ProductTransfer;
use Livewire\Component;
use Livewire\WithPagination;

class InventoriesTransfer extends Component
{


    public $search;
    public $order;

    use WithPagination;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.inventories.inventories-transfer', [
            'transfers' => ProductTransfer::query()
                ->with(['details.product', 'user'])
                ->paginate(10)
                ->withQueryString()
                ->through(function ($transfer) {
                    return [
                        'id' => $transfer->id,
                        'transfer_date' => $transfer->transfer_date,
                        'created_by' => $transfer->user ? $transfer->user->name : null,
                        'created_at' => $transfer->created_at,
                        'count_products' => $transfer->details->count()
                    ];
                }),
        ]);
    }


    public function mount()
    {

    }
}
