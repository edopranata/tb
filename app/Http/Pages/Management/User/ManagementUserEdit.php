<?php

namespace App\Http\Pages\Management\User;

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class ManagementUserEdit extends Component
{
    public $full_name;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;

    public $roles;
    public $users;

    public function render()
    {
        return view('pages.management.user.management-user-edit');
    }

    public function mount(User $user)
    {
        $this->users        = $user;
        $this->full_name    = $user->name;
        $this->username     = $user->username;
        $this->email        = $user->email;
        $this->role         = $user->roles()->first()->name;

        $this->roles = Role::query()
            ->select(['name'])
            ->whereNotIn('name',['Administrator', $this->role])
            ->get();


        $this->dispatchBrowserEvent('reloadPage');
    }

    public function save()
    {
        $this->validate([
            'full_name'     => ['required', 'string', 'min:2', 'max:255'],
            'username'      => ['required', 'string', 'min:2', 'max:20', 'unique:users,username,' . $this->users->id],
            'email'         => ['required', 'string', 'min:2', 'max:255', 'unique:users,email,'. $this->users->id],
            'role'          => ['required', 'exists:roles,name'],
        ]);

        DB::beginTransaction();
        try {
            Debugbar::info('Begin Transaction');
            $this->users->update([
                'name'      => $this->full_name,
                'username'  => $this->username,
                'email'     => $this->email,
            ]);

            Debugbar::info('Update Success');

            $this->users->syncRoles($this->role);

            Debugbar::info('Sync Role');

            $this->dispatchBrowserEvent('reloadPage');

            DB::commit();

            Debugbar::info('Commit');
            return redirect()->route('pages.management.users.index')->with(['status' => 'success', 'message' => 'Data pengguna <strong>' . $this->full_name . '</strong> berhasil ubah']);

        }catch (\Exception $exception){
            DB::rollBack();
            session()->flash('error', $exception->getMessage());
        }
    }

    public function changePassword()
    {
        $this->validate([
            'password'      => ['required', 'confirmed', Password::defaults()],
        ]);

        $this->users->update([
            'password'      => Hash::make($this->password),
        ]);

        return redirect()->route('pages.management.users.index')->with(['status' => 'success', 'message' => 'Password user ' . $this->full_name . ' berhasil di ubah']);

    }
}
