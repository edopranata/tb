<?php

namespace App\Http\Pages\Unit;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UnitEdit extends Component
{
    public $unit;

    public $name;
    public function render()
    {
        return view('pages.unit.unit-edit');
    }

    public function mount(Unit $unit)
    {
        $this->unit = $unit;
        $this->name = $unit->name;
    }

    public function update(){

        $this->validate([
            'name'      => ['required', 'string', 'min:2', 'max:20', 'unique:units,name,' . $this->unit->id]
        ]);
        DB::transaction(function () {
            $this->unit->update([
                'name' => $this->name
            ]);
        });
        return redirect()->route('pages.units.index')->with(['status' => 'success', 'message' => 'Satuan produk <strong>' . $this->name . '</strong> berhasil di ubah']);
    }

    public function delete()
    {
        $this->unit->delete();

        return redirect()->route('pages.units.index')->with(['status' => 'warning', 'message' => 'Satuan produk <strong>' . $this->name . '</strong> dihapus']);
    }

}
