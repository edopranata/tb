<div x-data="productPriceEdit()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Laporan Product Stock</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Laporan</a></li>
                            <li class="breadcrumb-item active">Product Stock</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-button onclick="window.print()" type="button" :btn="'light'">
            Print
        </x-card.action-button>
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
    </div>
    <div class="col-lg-12">
        <div class="card">

            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kategori Produk</label>
                            <select wire:model="category_id" class="form-control">
                                <option value="">Semua</option>
                                @if($categories)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ \Illuminate\Support\Str::upper($category->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jumlah ditampilkan</label>
                            <select wire:model="per_pages" class="form-control">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50" selected>50</option>
                                <option value="75">75</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cari (Barcode / Nama Produk)</label>
                            <input type="text" wire:model.debounce.600ms="product_name" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                @isset($products)
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Barcode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Satuan</th>
                            <th>Harga Grosir</th>
                            <th>Harga Pelanggan</th>
                            <th>Harga Modal</th>
                        </tr>
                        <tbody>
                        @foreach($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ strtoupper($product->barcode) }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ \Illuminate\Support\Str::upper($product->category->name) }}</td>
                                <td>
                                    @foreach($product->prices->sortByDesc('quantity') as $price)
                                        <div><strong>{{ number_format($price->sell_price) }}</strong> <span class="text-muted">1 {{ $price->unit->name }}</span></div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->prices->sortByDesc('quantity') as $price)
                                        <div><strong>{{ number_format($price->wholesale_price) }}</strong> <span class="text-muted">1 {{ $price->unit->name }}</span></div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->prices->sortByDesc('quantity') as $price)
                                        <div><strong>{{ number_format($price->customer_price) }}</strong> <span class="text-muted">1 {{ $price->unit->name }}</span></div>
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                    $stocks = collect($product->stocks)->where('available_stock', '>', 0)

                                    @endphp
                                    @foreach($stocks as $stock)
                                        <div><strong>{{ number_format($stock->buying_price) }}</strong> <span class="text-muted">@ {{ $product->unit->name }}</span></div>
                                    @endforeach
{{--                                    {{ dd($stocks) }}--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                @endisset
            </div>
            <div class="card-footer">
                {{ $products->links('vendor.livewire.tailwind') }}
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>

    </script>
@endpush
