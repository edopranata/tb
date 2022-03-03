<?php

namespace App\Http\Pages\Suppliers;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SuppliersIndex extends Component
{

    public $search;
    public $order;

    use WithPagination;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.suppliers.suppliers-index', [
            'suppliers' => Supplier::query()
                ->with('user')
                ->filter($this->search)
                ->paginate(10)
                ->withQueryString()
                ->through(function ($suppliers) {
                    return [
                        'id' => $suppliers->id,
                        'name' => $suppliers->name,
                        'description' => $suppliers->description,
                        'phone' => $suppliers->phone,
                        'address' => $suppliers->address,
                        'created_by' => $suppliers->user ? $suppliers->user->name : null,
                        'created_at' => $suppliers->created_at,
                    ];
                }),
        ]);
    }

    public function editId(Supplier $suppliers)
    {
        return redirect()->route('pages.suppliers.edit', $suppliers->id);
    }

    public function addNew() {
        return redirect()->route('pages.suppliers.create');
    }
}
