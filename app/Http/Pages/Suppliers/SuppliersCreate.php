<?php

namespace App\Http\Pages\Suppliers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SuppliersCreate extends Component
{
    public $supplier;

    public $name;
    public $description;
    public $phone;
    public $address;

    public function render()
    {
        return view('pages.suppliers.suppliers-create');
    }

    public function save()
    {
        $this->validate([
            'name'      => ['required', 'string', 'min:2', 'max:20', 'unique:suppliers,name']
        ]);

        DB::transaction(function (){
            Auth::user()->suppliers()->create([
                'name' => $this->name
            ]);
        });

        return redirect()->route('pages.suppliers.index')->with(['status' => 'success', 'message' => 'data suplier / pemasok <strong>' . $this->name . '</strong> berhasil dibuat']);

    }
}
