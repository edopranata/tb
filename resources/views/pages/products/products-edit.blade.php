<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Buat produk baru</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.products.index') }}">Data Produk</a></li>
                            <li class="breadcrumb-item active">Buat produk baru {{ $product_name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.products.index') }}" :btn="'light'">Kembali Kedaftar Produk</x-card.action-link>
        <x-card.action-button onclick="confirm('Hapus Produk ini?') || event.stopImmediatePropagation()" wire:click="delete()" :btn="'danger'">Hapus Data</x-card.action-button>
        <x-card.action-button wire:click="update()">Simpan Data</x-card.action-button>
    </x-card.action>
    <div class="row">
        @if(session('status'))
            <div class="col-12">
                <div class="alert alert-{{ session('status') }} rounded-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! session('message') !!}
                </div>
            </div>
        @endif
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Buat produk baru <strong>{{ $product_name }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Barcode / Kode Produk</label>
                        <input wire:model.defer="barcode" value="{{ old('barcode') }}" type="text" class="form-control @error('barcode') is-invalid @enderror" placeholder="Barcode / Kode Produk">
                        @error('barcode')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input wire:model.defer="product_name" value="{{ old('product_name') }}" type="text" class="form-control @error('product_name') is-invalid @enderror" placeholder="Nama Produk">
                        @error('product_name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Deskripsi / Keterangan Produk</label>
                        <input wire:model.defer="description" value="{{ old('description') }}" type="text" class="form-control @error('description') is-invalid @enderror" placeholder="Deskripsi / keterangan produk">
                        @error('description')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Kategori Produk</label>
                        <select wire:model.defer="category_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="0">Pilih satuan</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if(old('category_id') === $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('category_id')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Stok Minimal</label>
                        <input wire:model.defer="min_stock" type="number" class="form-control @error('min_stock') is-invalid @enderror" placeholder="Stok Minimal">
                        @error('min_stock')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Detail produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Satuan penjualan terkecil</label>
                                <input class="form-control" value="{{ $unit_name }}" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok Gudang</label>
                                <input class="form-control" value="{{ $warehouse_stock }}" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok Toko</label>
                                <input class="form-control" value="{{ $store_stock }}" type="text" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($product->prices))
            <div class="card rounded-0">
                <div class="card-body">
                    @foreach($product->prices as $price)
                        <div class="row">
                            <div class="col-12">
                                <div class="tw-font-bold bg-dark text-white px-2 py-1">Harga 1 {{ $price->unit ? $price->unit->name : 'Satuan tidak ditemukan' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Harga satuan</label>
                                    <input class="form-control" value="{{ number_format($price->sell_price) }}" type="text" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Harga Grosir</label>
                                    <input class="form-control" value="{{ number_format($price->wholesale_price) }}" type="text" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Harga Member</label>
                                    <input class="form-control" value="{{ number_format($price->customer_price) }}" type="text" disabled>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@push('js')
    <script>

    </script>
@endpush
