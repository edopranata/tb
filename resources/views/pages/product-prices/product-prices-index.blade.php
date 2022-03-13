<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Multi Harga</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Cari Produk</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <div class="row mt-3">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-lg-3 pb-2">
                            <div class="form-group">
                                <select wire:model.defer="cat_id"
                                        class="custom-select custom-select-lg form-control-border">
                                    <option value="">Pilih Kategori</option>
                                    @if(isset($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    @if($cat_id === $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 pb-2">
                            <div class="form-group">
                                <select wire:model.defer="per_page"
                                        class="custom-select custom-select-lg form-control-border">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="10">20</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-3 pb-2">
                            <div class="form-group">
                                <select wire:model.defer="search_field"
                                        class="custom-select custom-select-lg form-control-border">
                                    <option value="barcode">Berdasarkan Barcode</option>
                                    <option value="name">Berdasarkan Nama</option>
                                    <option value="description">Berdasarkan Deskripsi</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 pb-2">
                            <div class="input-group">
                                <input wire:model.debounce.600ms="search" type="search"
                                       class="form-control form-control-lg rounded-0"
                                       placeholder="Cari produk yang akan di buatkan multi harga">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-default rounded-0">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($search)
        @if(isset($products))
            <div class="row mt-3">
                <div class="col-md-10 offset-md-1">
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h3>Hasil Pencarian <strong>{{ $search }}</strong></h3>
                            @if($products->count())
                                <p class="mb-0">Ditemukan <strong>{{ $products->count() }} produk</strong></p>
                            @else
                                <p class="mb-0">Tidak ada produk dengan kata kunci <strong>{{ $search }}</strong></p>
                            @endif
                        </div>
                    </div>
                    @if($products->count())
                    <div class="card rounded-0">
                        <div class="card-body p-0 tw-bg-gray-200/80">
                            @foreach($products as $product)
                                <div wire:click="addPrices({{ $product->id }})" class="callout callout-info rounded-0 tw-cursor-pointer tw-group hover:tw-border-l-amber-900">
                                    <h4 class="tw-font-bold tw-block"><span class="tw-font-mono tw-text-sky-700 tw-drop-shadow">{{ $product->barcode }}</span> {{ $product->name }} <span
                                            class="tw-text-sm tw-font-medium tw-text-gray-700 tw-line-clamp-1">{{ $product->description }}</span>
                                    </h4>

                                    <div class="tw-flex tw-flex-col md:tw-flex-row">
                                        @if(isset($product->prices))
                                            <div class="table-responsive tw-w-full md:tw-w-1/2">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-left">Harga Satuan</th>
                                                        <th class="text-left">Harga Grosir</th>
                                                        <th class="text-left">Harga Member</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($product->prices as $price)
                                                        <tr>
                                                            <td class="p-2">1 {{ $price->unit->name }}
                                                                Rp. {{ $price->sell_price }}</td>
                                                            <td class="p-2">1 {{ $price->unit->name }}
                                                                Rp. {{ $price->wholesale_price }}</td>
                                                            <td class="p-2">1 {{ $price->unit->name }}
                                                                Rp. {{ $price->customer_price }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        @endif
                                        <div class="table-responsive tw-w-full md:tw-w-1/2">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th class="text-left">Stock Gudang</th>
                                                    <th class="text-left">Stock Toko</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="p-2">{{ $product->warehouse_stock ?? 0 }}</td>
                                                    <td class="p-2">{{ $product->store_stock ?? 0 }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
