<?php

namespace App\Http\Pages\Customer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CustomerCreate extends Component
{
    public $supplier;

    public $name;
    public $phone;
    public $address;

    public function render()
    {
        return view('pages.customer.customer-create');
    }

    public function save()
    {
        $this->validate([
            'name'          => ['required', 'string', 'min:2', 'max:20', 'unique:customers,name'],
            'phone'         => ['nullable', 'string', 'min:2', 'max:255'],
            'address'       => ['nullable', 'string', 'min:2', 'max:100'],
        ]);

        DB::transaction(function (){
            Auth::user()->customers()->create([
                'name'          => $this->name,
                'phone'         => $this->phone,
                'address'       => $this->address,
            ]);
        });

        return redirect()->route('pages.customers.index')->with(['status' => 'success', 'message' => 'data member <strong>' . $this->name . '</strong> berhasil dibuat']);

    }
}
