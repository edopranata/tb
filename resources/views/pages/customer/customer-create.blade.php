<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tambah Member</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.customers.index') }}">Data Member</a></li>
                            <li class="breadcrumb-item active">Tambah Member {{ $name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.customers.index') }}" :btn="'light'">Kembali Ke daftar Member</x-card.action-link>
        <x-card.action-button wire:click="save()">Simpan Data</x-card.action-button>
    </x-card.action>
    <div class="row">
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Tambah Member</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input wire:model.defer="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Supplier / Pemasok">
                        @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>No Telp</label>
                        <input wire:model.defer="phone" type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="No Telp">
                        @error('phone')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input wire:model.defer="address" type="text" class="form-control @error('address') is-invalid @enderror" placeholder="Alamat Pemasok">
                        @error('address')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
