<?php

namespace App\Http\Pages\Management\Permission;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class ManagementPermissionIndex extends Component
{
    use WithPagination;

    public $search;
    public $order;

    protected $queryString = ['search', 'order'];

    public function render()
    {
        return view('pages.management.permission.management-permission-index', [
            'permissions' => Permission::query()
                ->with(['roles'])
                ->paginate(10)
                ->withQueryString()
                ->through(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                        'payload' => $permission->payload,
                        'created_at' => $permission->created_at,
                    ];
                }),
        ]);
    }

    public function syncPermissions()
    {
        $permissions = collect(Route::getRoutes())->whereNotNull('action.as')->map(function ($route){
            $action = collect($route->action)->toArray();
            $method = collect($route->methods)->first();

            if( Str::lower(substr( $action['as'], 0, 5)) === 'pages') {
                return [
                    'method'    => $method,
                    'name' => $action['as'],
                    'description' => Str::replace('app ', '', Str::replace('.', ' ', $action['as'])),
                    'action' => $action
                ];
            }
        })->filter(function ($value) { return !is_null($value); });
        DB::beginTransaction();
        try {
            foreach ($permissions as $permission) {
                Permission::query()->updateOrCreate([
                    'name'  => $permission['description'],
                ],[
                    'payload'   => json_encode($permission)
                ]);
            }
            DB::commit();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Synchronize permission route complete. Total  <strong>' . $permissions->count() . '</strong> record']);

        }catch (\Exception $exception){
            DB::rollBack();
        }

    }
}
