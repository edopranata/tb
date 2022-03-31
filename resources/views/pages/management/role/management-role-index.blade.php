<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Role List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Role List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Role List</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input wire:model.debounce.500ms="search" type="search" class="form-control float-right"
                                   placeholder="Search">
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    @if(session('status'))
                        <div class="alert alert-{{ session('status') }} rounded-0 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {!! session('message') !!}
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th>Guard</th>
                            <th>Count User</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($roles)
                            @foreach($roles as $key => $role)
                                <tr class="tw-cursor-pointer hover:tw-bg-slate-200">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $role['name'] }}</td>
                                    <td>{{ $role['guard_name'] }}</td>
                                    <td>{{ $role['users_count'] }}</td>
                                    <td>{{ $role['created_at'] }}</td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $roles->links('vendor.livewire.tailwind') }}
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>

    </script>
@endpush
