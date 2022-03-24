<?php

namespace App\Http\Pages\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CustomerEdit extends Component
{
    public $customer;

    public $name;
    public $phone;
    public $address;


    public function render()
    {
        return view('pages.customer.customer-edit');
    }

    public function mount(Customer $customer)
    {
        $this->customer= $customer;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
    }

    public function update(){

        $this->validate([
            'name'          => ['required', 'string', 'min:2', 'max:50', 'unique:customers,name,' . $this->customer->id],
            'phone'         => ['nullable', 'string', 'min:2', 'max:255'],
            'address'       => ['nullable', 'string', 'min:2', 'max:100'],
        ]);
        DB::transaction(function () {
            $this->customer->update([
                'name'          => $this->name,
                'phone'         => $this->phone,
                'address'       => $this->address,
            ]);
        });
        return redirect()->route('pages.customers.index')->with(['status' => 'success', 'message' => 'Member <strong>' . $this->name . '</strong> berhasil di ubah']);
    }

    public function delete()
    {
        $this->customer->delete();

        return redirect()->route('pages.customers.index')->with(['status' => 'warning', 'message' => 'Member <strong>' . $this->name . '</strong> dihapus']);
    }
}
