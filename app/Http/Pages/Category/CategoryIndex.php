<?php

namespace App\Http\Pages\Category;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryIndex extends Component
{

    public $search;
    public $order;

    use WithPagination;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.category.category-index', [
            'categories' => Category::query()
                ->whereNotIn('name', ['Paket'])
                ->with('user')
                ->filter($this->search)
                ->paginate(10)
                ->withQueryString()
                ->through(function ($categoriess) {
                    return [
                        'id' => $categoriess->id,
                        'name' => $categoriess->name,
                        'created_by' => $categoriess->user ? $categoriess->user->name : null,
                        'created_at' => $categoriess->created_at,
                    ];
                }),
        ]);
    }

    public function editId(Category $category)
    {
        return redirect()->route('pages.categories.edit', $category->id);
    }

    public function addNew() {
        return redirect()->route('pages.categories.create');
    }
}
