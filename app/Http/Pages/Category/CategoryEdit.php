<?php

namespace App\Http\Pages\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CategoryEdit extends Component
{
    public $category;

    public $name;

    public function render()
    {
        return view('pages.category.category-edit');
    }

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
    }

    public function update(){

        $this->validate([
            'name'      => ['required', 'string', 'min:2', 'max:20', 'unique:categories,name,' . $this->category->id]
        ]);
        DB::transaction(function () {
            $this->category->update([
                'name' => $this->name
            ]);
        });
        return redirect()->route('pages.categories.index')->with(['status' => 'success', 'message' => 'Kategori produk <strong>' . $this->name . '</strong> berhasil di ubah']);
    }

    public function delete()
    {
        $this->category->delete();

        return redirect()->route('pages.categories.index')->with(['status' => 'warning', 'message' => 'Kategori produk <strong>' . $this->name . '</strong> dihapus']);
    }

}
