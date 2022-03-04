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
                            <li class="breadcrumb-item"><a href="{{ route('pages.products.index') }}">Data Produk</a></li>
                            <li class="breadcrumb-item active">Buat produk baru {{ $name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <div class="row" x-data="pageProduct()">
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Buat produk baru <strong>{{ $name }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Barcode / Kode Produk</label>
                        <input wire:model.defer="barcode" {{ old('barcode') }} type="text" class="form-control @error('barcode') is-invalid @enderror" placeholder="Barcode / Kode Produk">
                        @error('barcode')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input wire:model.defer="name" {{ old('name') }} type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Produk">
                        @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Deskripsi / Keterangan Produk</label>
                        <input wire:model.defer="description" value="{{ old('description') }}" type="text" class="form-control @error('description') is-invalid @enderror" placeholder="Deskripsi / keterangan pemasok">
                        @error('description')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Satuan Produk (satuan terkecil penjualan)</label>
                        <select wire:model.defer="unit_id" class="form-control @error('unit_id') is-invalid @enderror">
                            <option value="0">Pilih satuan</option>
                            @if(isset($units))
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" @if(old('unit_id') === $unit->id) selected @endif>{{ $unit->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('unit_id')<span class="text-danger text-sm">{{ $message }}</span>@enderror
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
                        <input wire:model.defer="min_stock" type="text" class="form-control @error('min_stock') is-invalid @enderror" placeholder="Alamat Pemasok">
                        @error('min_stock')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button wire:click="save()" type="button" class="btn btn-primary btn-flat">Submit</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="card-header">
                    <label class="card-title">Produk baru / stok awal?
                        <input type="checkbox" x-on:change="new_product = ! new_product" class="form-checkbox rounded text-pink-500 ml-5 mr-2" />
                        <strong class="bg-indigo px-2 py-1">(<span x-text="new_product ? 'Produk Baru' : 'Stok Awal'"></span>)</strong>
                    </label>

                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input wire:model.defer="first_stock" {{ old('first_stock') }} type="text" :disabled="new_product" class="form-control @error('first_stock') is-invalid @enderror" placeholder="Stok Awal">
                        @error('first_stock')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Harga Modal (harga modal untuk satuan terkecil)</label>
                        <input wire:model.defer="buying_price" {{ old('buying_price') }} type="text" :disabled="new_product" class="form-control @error('buying_price') is-invalid @enderror" placeholder="Harga modal">
                        @error('buying_price')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        function pageProduct(){
            return {
                new_product: true,
            }
        }
    </script>
@endpush
