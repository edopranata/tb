<div x-data="productPriceEdit()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Multi Harga {{ $product->name }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.products.index') }}">Daftar Produk</a>
                            </li>
                            <li class="breadcrumb-item active">Multi Harga {{ $product->name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.products.index') }}" :btn="'light'">Kembali Ke Daftar Produk
        </x-card.action-link>
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
                    <h3 class="card-title">Detail Produk <strong>{{ $product->name }}</strong></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Barcode / Kode Produk</label>
                        <input value="{{ $product->barcode }}" type="text" class="form-control"
                               placeholder="Barcode / Kode Produk" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input value="{{ $product->name }}" type="text" class="form-control" placeholder="Nama Produk"
                               disabled>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi / Keterangan Produk</label>
                        <input value="{{ $product->description }}" type="text" class="form-control"
                               placeholder="Deskripsi / keterangan produk" disabled>
                    </div>

                    <div class="form-group">
                        <label>Kategori Produk</label>
                        <input value="{{ $product->category->name }}" type="text" class="form-control"
                               placeholder="Kategori produk" disabled>

                    </div>
                    <div class="form-group">
                        <label>Stok Minimal</label>
                        <input value="{{ $product->min_stock }}" type="number" class="form-control"
                               placeholder="Stok Minimal" disabled>
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
                                <input class="form-control" value="{{ $product->unit->name ?: 0 }}" type="text"
                                       disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok Gudang</label>
                                <input class="form-control" value="{{ $product->warehouse_stock ?: 0 }}" type="text"
                                       disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok Toko</label>
                                <input class="form-control" value="{{ $product->store_stock ?: 0 }}" type="text"
                                       disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-0">
                <div class="card-body @if($price_id) tw-bg-amber-200 @endif">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan produk</label>
                                <select wire:model.defer="unit_id"
                                        class="form-control @error('unit_id') is-invalid @enderror">
                                    <option value="0">Pilih satuan</option>
                                    @if($price_id)
                                        <option value="{{ $price->unit->id }}">{{ $price->unit->name }}</option>
                                    @endif
                                    @if(isset($units))
                                        @if($price)
                                            @if(!$price->default)
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}"
                                                            @if(old('unit_id') === $unit->id) selected @endif>{{ $unit->name }}</option>
                                                @endforeach
                                            @endif
                                        @else
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                        @if(old('unit_id') === $unit->id) selected @endif>{{ $unit->name }}</option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                                @error('unit_id')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Qty dr satuan terkecil</label>
                                <input wire:model.defer="quantity"
                                       class="form-control @error('quantity') is-invalid @enderror" type="text"
                                       placeholder="Qty dr satuan terkecil" @if($price) @if($price->default) readonly @endif @endif>
                                @error('quantity')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga satuan</label>
                                <input wire:model.defer="sell_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})"
                                       class="form-control rupiah @error('sell_price') is-invalid @enderror" type="text"
                                       placeholder="Harga Satuan">
                                @error('sell_price')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Grosir</label>
                                <input wire:model.defer="wholesale_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})"
                                       class="form-control rupiah @error('wholesale_price') is-invalid @enderror" type="text"
                                       placeholder="Harga Grosir">
                                @error('wholesale_price')<span
                                    class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Member</label>
                                <input wire:model.defer="customer_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})"
                                       class="form-control rupiah @error('customer_price') is-invalid @enderror" type="text"
                                       placeholder="Harga Member">
                                @error('customer_price')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer @if($price_id) tw-bg-amber-300 @endif">
                    @if($price_id)
                        <div class="row">
                            <div class="col-6">
                                <button wire:click="resetForm()" class="btn btn-block btn-lg btn-flat btn-danger">
                                    Batalkan Perubahan
                                </button>
                            </div>
                            <div class="col-6">
                                <button wire:click="submitEdit()" class="btn btn-block btn-lg btn-flat btn-info">Simpan
                                    Perubahan
                                </button>
                            </div>
                        </div>
                    @else
                        <button wire:click="submitPrice()" class="btn btn-block btn-lg btn-flat btn-success">Tambah
                            Harga Untuk Produk Ini
                        </button>
                    @endif
                </div>
            </div>
            @if(isset($product->prices))
                <div class="card rounded-0">
                    <div class="card-body">
                        @foreach($product->prices as $price)
                            <div wire:click="editPrice({{ $price->id }})"
                                 class="row hover:tw-bg-gray-200 -tw-mx-2 tw-cursor-pointer">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Harga
                                            1 {{ $price->unit ? $price->unit->name : 'Satuan tidak ditemukan' }}</label>
                                        <input class="form-control" value="{{ number_format($price->sell_price) }}" type="text"
                                               @if($price->default) disabled @else readonly @endif>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Harga Grosir
                                            1 {{ $price->unit ? $price->unit->name : 'Satuan tidak ditemukan' }}</label>
                                        <input class="form-control" value="{{ number_format($price->wholesale_price) }}" type="text"
                                               @if($price->default) disabled @else readonly @endif>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Harga Member
                                            1 {{ $price->unit ? $price->unit->name : 'Satuan tidak ditemukan' }}</label>
                                        <input class="form-control" value="{{ number_format($price->customer_price) }}" type="text"
                                               @if($price->default) disabled @else readonly @endif>
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

        function productPriceEdit() {
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
