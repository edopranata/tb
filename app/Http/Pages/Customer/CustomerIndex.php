<?php

namespace App\Http\Pages\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    public $search;
    public $order;

    use WithPagination;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.customer.customer-index', [
            'customers' => Customer::query()
                ->with('user')
                ->filter($this->search)
                ->paginate(10)
                ->withQueryString()
                ->through(function ($suppliers) {
                    return [
                        'id' => $suppliers->id,
                        'name' => $suppliers->name,
                        'phone' => $suppliers->phone,
                        'address' => $suppliers->address,
                        'created_by' => $suppliers->user ? $suppliers->user->name : null,
                        'created_at' => $suppliers->created_at,
                    ];
                }),
        ]);
    }

    public function editId(Customer $customer)
    {
        return redirect()->route('pages.customers.edit', $customer->id);
    }

    public function addNew() {
        return redirect()->route('pages.customers.create');
    }
}
