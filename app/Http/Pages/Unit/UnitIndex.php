<?php

namespace App\Http\Pages\Unit;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class UnitIndex extends Component
{
    public $search;
    public $order;

    use WithPagination;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.unit.unit-index', [
            'units' => Unit::query()
                ->with('user')
                ->filter($this->search)
                ->paginate(10)
                ->withQueryString()
                ->through(function ($units) {
                    return [
                        'id' => $units->id,
                        'name' => $units->name,
                        'created_by' => $units->user ? $units->user->name : null,
                        'created_at' => $units->created_at,
                    ];
                }),
        ]);
    }

    public function editId(Unit $unit)
    {
        return redirect()->route('pages.units.edit', $unit->id);
    }

    public function addNew() {
        return redirect()->route('pages.units.create');
    }


}
