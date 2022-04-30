<div x-data="pageCreate()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tambah Pengguna</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.customers.index') }}">Data Pengguna</a></li>
                            <li class="breadcrumb-item active">Tambah Pengguna</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.customers.index') }}" :btn="'light'">Kembali Ke daftar Pengguna</x-card.action-link>
    </x-card.action>
    <div class="row">
        <div class="col-12">
            @if (session()->has('error'))
                <div class="card-footer alert-danger">
                    {{session('error')}}
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pengguna</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input wire:model.defer="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" placeholder="Nama Lengkap">
                        @error('full_name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input wire:model.defer="email" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                        @error('email')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input wire:model.defer="username" type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Username">
                        @error('username')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <div class="form-group" wire:ignore>
                            <label>Role User</label>
                            <select id="role-select" class="form-control">
                                <option value="{{ $role }}">{{ $role }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" @if($role->name == $role) selected @endif>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" wire:click="save" class="btn btn-sm btn-primary">Update Data</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-body">
                    <div class="form-group">
                        <label>Password</label>
                        <input wire:model.defer="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                        @error('password')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Ulangi Password</label>
                        <input wire:model.defer="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ulangi Password">
                        @error('password_confirmation')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" wire:click="changePassword" class="btn btn-sm btn-primary">Ubah Password</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        function pageCreate() {
            window.addEventListener('reloadPage', () => {
                $("#role-select").val('').select2().trigger('change');
            })
            return {
                init: function (){
                    $('#role-select').select2();
                    $('#role-select').on('change', function (e) {
                    @this.set('role', $('#role-select').select2("val"));
                    });
                }
            }
        }
    </script>
@endpush
