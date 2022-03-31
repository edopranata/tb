<?php

namespace App\Http\Pages\Management\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ManagementRoleIndex extends Component
{
    use WithPagination;

    public $search;
    public $order;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.management.role.management-role-index', [
            'roles' => Role::query()
                ->with(['users'])
                ->when($this->search, function ($query, $search){
                    $query->where('name', 'like', '%'.$search.'%');
                })
                ->paginate(10)
                ->withQueryString()
                ->through(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'guard_name' => $role->guard_name,
                        'users_count' => $role->users->count(),
                        'created_at' => $role->created_at,
                    ];
                }),
        ]);
    }
}
