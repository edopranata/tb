<?php

namespace App\Http\Pages\Suppliers;

use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SuppliersEdit extends Component
{
    public $suppliers;

    public $name;
    public $description;
    public $phone;
    public $address;

    public function render()
    {
        return view('pages.suppliers.suppliers-edit');
    }

    public function mount(Supplier $supplier)
    {
        $this->suppliers = $supplier;
        $this->name = $supplier->name;
        $this->description = $supplier->description;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;
    }

    public function update(){

        $this->validate([
            'name'          => ['required', 'string', 'min:2', 'max:20', 'unique:units,name,' . $this->suppliers->id],
            'description'   => ['nullable', 'string', 'min:2', 'max:255'],
            'phone'         => ['nullable', 'string', 'min:2', 'max:255'],
            'address'       => ['nullable', 'string', 'min:2', 'max:100'],
        ]);
        DB::transaction(function () {
            $this->suppliers->update([
                'name'          => $this->name,
                'description'   => $this->description,
                'phone'         => $this->phone,
                'address'       => $this->address,
            ]);
        });
        return redirect()->route('pages.suppliers.index')->with(['status' => 'success', 'message' => 'Supplier / pemasok <strong>' . $this->name . '</strong> berhasil di ubah']);
    }

    public function delete()
    {
        $this->suppliers->delete();

        return redirect()->route('pages.units.index')->with(['status' => 'warning', 'message' => 'Supplier / pemasok <strong>' . $this->name . '</strong> dihapus']);
    }
}
