<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Data produk</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data produk</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input wire:model.debounce.500ms="search" type="search" class="form-control float-right"
                                   placeholder="Search">
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    @if(session('status'))
                        <div class="alert alert-{{ session('status') }} rounded-0 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {!! session('message') !!}
                        </div>
                    @endif
                    <button wire:click="addNew()" class="btn btn-flat tw-bg-slate-900 tw-text-slate-300 hover:tw-bg-slate-700 hover:tw-text-slate-100 tw-font-bold mx-2 my-2">Tambah produk baru </button>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="tw-w-[8rem]">Barcode / Kode</th>
                            <th class="tw-w-[10rem]">Nama Produk</th>
                            <th>Deskripsi / Keterangan</th>
                            <th class="tw-w-[10rem]">Kategori</th>
                            <th class="tw-w-[10rem]">Satuan</th>
                            <th class="tw-w-[10rem]">Stock</th>
                            <th class="tw-w-[10rem]">Dibuat Oleh</th>
                            <th class="tw-w-[10rem]">Dibuat pada</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($products)
                            @foreach($products as $key => $product)
                                <tr class="tw-cursor-pointer hover:tw-bg-slate-200" wire:click="editId({{$product['id']}})">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product['barcode'] }}</td>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['description'] }}</td>
                                    <td>{{ $product['category'] }}</td>
                                    <td>{{ $product['unit'] }}</td>
                                    <td>
                                        <div>Gudang : {{ $product['stock']['warehouse'] ?? 0 }}</div>
                                        <div>Toko : {{ $product['stock']['store'] ?? 0 }}</div>
                                    </td>
                                    <td>{{ $product['created_by'] }}</td>
                                    <td>{{ $product['created_at'] }}</td>
                                    <td class="text-right"><i class="fas fa-chevron-right"></i> &nbsp;</td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $products->links('vendor.livewire.tailwind') }}
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
