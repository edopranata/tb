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
    <x-card.action>
        <x-card.action-link href="{{ route('pages.products.create') }}" :btn="'light'">
            Tambah Produk Baru
        </x-card.action-link>
        <x-card.action-link href="{{ route('pages.products.import') }}" :btn="'warning'">
            Upload Product Baru
        </x-card.action-link>
    </x-card.action>
    <div class="row" x-data="{ 'isDialogOpen': false }">
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
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="tw-w-[10rem"]>&nbsp;</th>
                            <th>#</th>
                            <th class="tw-w-[8rem]">Barcode / Kode</th>
                            <th class="tw-w-[10rem]">Nama Produk</th>
                            <th>Deskripsi / Keterangan</th>
                            <th class="tw-w-[10rem]">Kategori</th>
                            <th class="tw-w-[10rem]">Satuan</th>
                            <th class="tw-w-[10rem]">Stock</th>
                            <th class="tw-w-[10rem]">Dibuat Oleh</th>
                            <th class="tw-w-[10rem]">Dibuat pada</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($products)
                            @foreach($products as $key => $product)
                                <tr class="tw-cursor-pointer hover:tw-bg-slate-200">
                                    <td class="text-right" x-data="{ 'isHamburgerOpen': false }">
                                        <div
                                            title="Open the actions menu"
                                            class="tw-font-mono tw-text-2xl tw-px-2"
                                            @click="isHamburgerOpen = true"
                                            :class="{ 'tw-bg-gray-100': isHamburgerOpen }"
                                        >
                                            &ctdot;
                                        </div>

                                        <ul
                                            x-show="isHamburgerOpen"
                                            x-cloak
                                            @click.away="isHamburgerOpen = false"
                                            class="tw-absolute tw-list-none pl-0 tw-border tw-bg-white tw-shadow-md tw-text-left tw-mt-0"
                                        >
                                            <li class="tw-p-2 hover:tw-bg-gray-200" wire:click="editId({{ $product['id'] }})">✏ Ubah Produk</li>
                                            <li class="tw-p-2 hover:tw-bg-gray-200" wire:click="toProductPrice({{ $product['id'] }})">💸 Multi Harga</li>
                                        </ul>
                                    </td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product['barcode'] }}</td>
                                    <td><div class="tw-line-clamp-2">{{ $product['name'] }}</div></td>
                                    <td><div class="tw-line-clamp-2">{{ $product['description'] }}</div></td>
                                    <td>{{ $product['category'] }}</td>
                                    <td>{{ $product['unit'] }}</td>
                                    <td>
                                        <div>Gudang : {{ $product['stock']['warehouse'] ?? 0 }}</div>
                                        <div>Toko : {{ $product['stock']['store'] ?? 0 }}</div>
                                    </td>
                                    <td>{{ $product['created_by'] }}</td>
                                    <td>{{ $product['created_at'] }}</td>
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

@push('js')
<script>

</script>
@endpush
