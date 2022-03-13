<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Kategori Barang</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.categories.index') }}">Kategori Produk</a></li>
                            <li class="breadcrumb-item active">Edit Kategori {{ $name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.categories.index') }}" :btn="'light'">Kembali Kedaftar Kategori</x-card.action-link>
        <x-card.action-button onclick="confirm('Hapus Kategori ini?') || event.stopImmediatePropagation()" wire:click="delete()" :btn="'danger'">Hapus Data</x-card.action-button>
        <x-card.action-button wire:click="update()">Simpan Data</x-card.action-button>
    </x-card.action>
    <div class="row">
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Edit kategori <strong>{{ $name }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Kategori produk</label>
                        <input wire:model.defer="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Kategori produk">
                        @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
