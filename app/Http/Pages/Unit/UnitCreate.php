<?php

namespace App\Http\Pages\Unit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UnitCreate extends Component
{
    public $name;


    public function render()
    {
        return view('pages.unit.unit-create');
    }

    public function save()
    {
        $this->validate([
            'name'      => ['required', 'string', 'min:2', 'max:20', 'unique:units,name']
        ]);

        DB::transaction(function (){
            Auth::user()->units()->create([
                'name' => $this->name
            ]);
        });

        return redirect()->route('pages.units.index')->with(['status' => 'success', 'message' => 'Satuan produk <strong>' . $this->name . '</strong> berhasil dibuat']);

    }
}
