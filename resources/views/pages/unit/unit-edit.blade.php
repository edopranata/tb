<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Satuan Barang</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.units.index') }}">Satuan Produk</a></li>
                            <li class="breadcrumb-item active">Edit satuan {{ $name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.units.index') }}" :btn="'light'">Kembali Kedaftar Satuan</x-card.action-link>
        <x-card.action-button onclick="confirm('Hapus satuan ini?') || event.stopImmediatePropagation()" wire:click="delete()" :btn="'danger'">Hapus Data</x-card.action-button>
        <x-card.action-button wire:click="update()">Simpan Data</x-card.action-button>
    </x-card.action>
    <div class="row">
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Edit satuan <strong>{{ $name }}</strong></h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Satuan produk</label>
                        <input wire:model.defer="name" type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1" placeholder="Satuan produk">
                        @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
