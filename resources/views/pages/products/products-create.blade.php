<div x-data="productPage()">
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
                            <li class="breadcrumb-item active">Buat produk baru {{ $product_name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.products.index') }}" :btn="'light'">Kembali Ke daftar produk</x-card.action-link>
        <x-card.action-button wire:click="save()">Simpan Data</x-card.action-button>
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
                        <div class="input-group">
                            <input wire:model.defer="barcode" {{ old('barcode') }} type="text" class="form-control @error('barcode') is-invalid @enderror" placeholder="Barcode / Kode Produk">
                            <div class="input-group-append">
                                <button type="button" wire:click="generateBarcode()" class="input-group-text">Generate new barcode</button>
                            </div>
                        </div>
                        @error('barcode')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input wire:model.defer="product_name" {{ old('product_name') }} type="text" class="form-control @error('product_name') is-invalid @enderror" placeholder="Nama Produk">
                        @error('product_name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Deskripsi / Keterangan Produk</label>
                        <input wire:model.defer="description" value="{{ old('description') }}" type="text" class="form-control @error('description') is-invalid @enderror" placeholder="Deskripsi / keterangan produk">
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
                        <input wire:model.defer="min_stock" type="number" class="form-control @error('min_stock') is-invalid @enderror" placeholder="Stok Minimal">
                        @error('min_stock')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="card-header">
                    <label class="card-title">Produk baru / stok awal?
                        <input type="checkbox" wire:model="new_product" wire:change="switchOption()" class="form-checkbox rounded text-pink-500 ml-5 mr-2" />
                        <strong class="bg-indigo px-2 py-1">(<span> {{$new_product ? 'Produk Baru' : 'Stok Awal'}}</span>)</strong>
                    </label>

                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Stok Awal</label>
                        <input wire:model.defer="first_stock" type="text" @if($new_product) disabled @endif class="form-control @error('first_stock') is-invalid @enderror" placeholder="Stok Awal">
                        @error('first_stock')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Harga Modal (harga modal untuk satuan terkecil)</label>
                        <input wire:model.defer="buying_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" @if($new_product) disabled @endif class="form-control rupiah @error('buying_price') is-invalid @enderror" placeholder="Harga modal">
                        @error('buying_price')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Tanggal Expire Produk</label>
                        <input wire:model.defer="expired_at" type="date" @if($new_product) disabled @endif class="form-control @error('expired_at') is-invalid @enderror" placeholder="Tanggal Expired">
                        @error('expired_at')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div class="alert alert-info rounded-0 alert-dismissible tw-px-2 tw-py-1">
                        Harga jual (harga jual untuk satuan terkecil)
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Satuan</label>
                                <input wire:model.defer="sell_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" step="0.01" class="form-control rupiah @error('sell_price') is-invalid @enderror" placeholder="Harga satuan">
                                @error('sell_price')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga grosir</label>
                                <input wire:model.defer="wholesale_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" step="0.01" class="form-control rupiah @error('wholesale_price') is-invalid @enderror" placeholder="Harga grosir">
                                @error('wholesale_price')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga member</label>
                                <input wire:model.defer="customer_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" step="0.01" class="form-control rupiah @error('customer_price') is-invalid @enderror" placeholder="Harga member">
                                @error('customer_price')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        window.addEventListener('pageReload', () => {
            $('.rupiah').unmask();
            $('.rupiah').mask('#,##0', {
                reverse: true,
                translation: {
                    '#': {
                        pattern: /-|\d/,
                        recursive: true
                    }
                },
                onChange: function(value, e) {
                    var target = e.target,
                        position = target.selectionStart; // Capture initial position

                    target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');

                    target.selectionEnd = position; // Set the cursor back to the initial position.
                }
            });
        })

        function productPage() {
            return {
                init: function () {
                    $('.rupiah').mask('#,##0', {
                        reverse: true,
                        translation: {
                            '#': {
                                pattern: /-|\d/,
                                recursive: true
                            }
                        },
                        onChange: function (value, e) {
                            var target = e.target,
                                position = target.selectionStart; // Capture initial position

                            target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');

                            target.selectionEnd = position; // Set the cursor back to the initial position.
                        }
                    });
                }
            }
        }
    </script>
@endpush
