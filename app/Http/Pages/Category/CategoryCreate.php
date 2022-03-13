<?php

namespace App\Http\Pages\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CategoryCreate extends Component
{
    public $name;

    public function render()
    {
        return view('pages.category.category-create');
    }

    public function save()
    {
        $this->validate([
            'name'          => ['required', 'string', 'min:2', 'max:20', 'unique:categories,name'],
        ]);

        DB::transaction(function (){
            Auth::user()->categories()->create([
                'name' => $this->name
            ]);
        });

        return redirect()->route('pages.categories.index')->with(['status' => 'success', 'message' => 'Kategori produk <strong>' . $this->name . '</strong> berhasil dibuat']);

    }
}
