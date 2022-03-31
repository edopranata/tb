<?php

namespace App\Http\Pages\Management\User;

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class ManagementUserCreate extends Component
{
    public $full_name;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;

    public $roles;
    public function render()
    {
        Debugbar::info($this->role);
        return view('pages.management.user.management-user-create');
    }

    public function mount()
    {
        $this->roles = Role::query()
            ->select(['name'])
            ->where('name', '<>', 'Administrator')
            ->get();

    }

    public function save()
    {
        $this->validate([
            'full_name'     => ['required', 'string', 'min:2', 'max:255'],
            'username'      => ['required', 'string', 'min:2', 'max:20', 'unique:users,username'],
            'email'         => ['required', 'string', 'min:2', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'confirmed', Password::defaults()],
            'role'          => ['required', 'exists:roles,name'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::query()->create([
                'name'      => $this->full_name,
                'username'  => $this->username,
                'email'     => $this->email,
                'password'  => Hash::make($this->password),
            ]);

            $user->assignRole($this->role);

            $this->dispatchBrowserEvent('reloadPage');

            DB::commit();
            return redirect()->route('pages.management.users.index')->with(['status' => 'success', 'message' => 'data pengguna <strong>' . $this->full_name . '</strong> berhasil dibuat']);

        }catch (\Exception $exception){
            DB::rollBack();
            session()->flash('error', $exception->getMessage());
        }



    }
}
