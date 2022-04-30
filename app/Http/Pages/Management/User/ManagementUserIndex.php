<?php

namespace App\Http\Pages\Management\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManagementUserIndex extends Component
{
    use WithPagination;

    public $search;
    public $order;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.management.user.management-user-index', [
            'users' => User::query()
                ->with(['roles'])
                ->whereHas('roles', function($q){
                    $q->where('name', '<>','Administrator');
                })
                ->filter($this->search)
                ->paginate(10)
                ->withQueryString()
                ->through(function ($user) {
                    return [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'username' => $user->username,
                        'role' => $user->roles->first()->name,
                        'status' => $user->status,
                        'created_at' => $user->created_at,
                    ];
                }),
        ]);
    }

    public function mount()
    {

    }
}
