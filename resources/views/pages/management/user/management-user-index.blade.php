<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Pengguna</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Data Pengguna</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.management.users.create') }}" :btn="'light'">
            Tambah Pengguna Baru
        </x-card.action-link>
    </x-card.action>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Pengguna</h3>

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
                            <th>&nbsp;</th>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Dibuat pada</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($users)
                            @foreach($users as $key => $user)
                                <tr class="tw-cursor-pointer hover:tw-bg-slate-200">
                                    <td>
                                        <a href="{{ route('pages.management.users.edit', $user['id']) }}" class="btn btn-sm btn-light">Edit</a>
                                    </td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user['name'] }}</td>
                                    <td><div class="tw-line-clamp-2">{{ $user['email'] }}</div></td>
                                    <td><div class="tw-line-clamp-2">{{ $user['username'] }}</div></td>
                                    <td>{{ $user['role'] }}</td>
                                    <td>{{ $user['created_at'] }}</td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $users->links('vendor.livewire.tailwind') }}
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
