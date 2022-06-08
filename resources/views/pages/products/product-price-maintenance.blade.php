<div x-data="inventoryPage()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Maintenance Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Maintenance</a></li>
                            <li class="breadcrumb-item active">Product</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-button wire:click="save()">Simpan Data</x-card.action-button>
    </x-card.action>
</div>
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
<div class="row">
    <div class="col-md-4">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">Maintenance Harga Produk</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Barcode / Kode Produk</label>
                    <input disabled value="{{ $product->barcode }}" type="text" class="form-control" placeholder="Barcode / Kode Produk">
                </div>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input disabled value="{{ $product->name }}" type="text" class="form-control" placeholder="Nama Produk">
                </div>
                <div class="form-group">
                    <label>Deskripsi / Keterangan Produk</label>
                    <input disabled value="{{ $product->description }}" type="text" class="form-control" placeholder="Deskripsi / keterangan produk">
                </div>

                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select disabled class="form-control">
                        <option value="{{ $product->category_id }}" selected>{{ $product->category->name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Stok Minimal (@ {{ $product->unit->name }})</label>
                    <input disabled type="number" class="form-control" value="{{ $product->min_stock }}" placeholder="Stok Minimal">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card rounded-0">
            <div class="card-header">
                <h3 class="card-title">List Inventori Product</h3>
            </div>
            <div class="card-body p-0">
                @isset($product->stocks)
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Stock Awal</th>
                            <th>Sisa Stock</th>
                            <th class="text-right">Harga Modal</th>
                            <th class="text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($product->stocks as $key => $inventory)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $inventory->created_at->toDateString() }}</td>
                                <td>{{ $inventory->description }}</td>
                                <td>{{ $inventory->first_stock }} {{ ucfirst($product->unit->name) }}</td>
                                <td>{{ $inventory->available_stock }} {{ ucfirst($product->unit->name) }}</td>
                                <td class="text-right">{{ number_format($inventory->buying_price, 2) }}</td>
                                <td class="text-right">{{ number_format($inventory->total , 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            @php
                            $stock = collect($product->stocks)
                            @endphp
                            <th></th>
                            <th colspan="3">Total & rata-rata modal</th>
                            <th>{{ $stock->sum('available_stock') }} {{ ucfirst($product->unit->name) }}</th>
                            <th class="text-right">{{ number_format($stock->where('available_stock', '>', 0)->sum('total') / $stock->where('available_stock', '>', 0)->sum('available_stock'),2) }}</th>
                            <th class="text-right">{{ number_format($stock->where('available_stock', '>', 0)->sum('total'),2) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                @endisset
            </div>
        </div>
        <div class="card rounded-0">
            <div class="card-header border-top">
                <h3 class="card-title">Harga Penjualan</h3>
            </div>
            <div class="card-body p-0">
                @isset($product->prices)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Satuan</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-right">Harga Grosir</th>
                                <th class="text-right">Harga Pelanggan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product->prices as $key => $price)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>@ {{ $price->unit->name }}</td>
                                    <td class="text-right">{{ number_format($price->sell_price) }}</td>
                                    <td class="text-right">{{ number_format($price->wholesale_price) }}</td>
                                    <td class="text-right">{{ number_format($price->customer_price) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endisset
            </div>
        </div>
    </div>
</div>

<div class="row">
    @php
    $sells = collect($product->sells->toArray())

    @endphp
    <div class="col-md-12"></div>
</div>
@push('js')
    <script>

    </script>
@endpush
