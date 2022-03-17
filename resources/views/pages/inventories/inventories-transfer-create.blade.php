<div x-data="transferPage()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transfer stock {{ ($transfer_to === "store") ? "Toko ke " : "Gudang ke " }} {{ ($transfer_to === "store") ? "Gudang" : "Toko" }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.stock.index') }}">Stok Transfer</a></li>
                            <li class="breadcrumb-item active">Transfer stock {{ ($transfer_to === "store") ? "Toko ke " : "Gudang ke " }} {{ ($transfer_to === "store") ? "Gudang" : "Toko" }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.stock.index') }}" :btn="'light'">Kembali daftar transfer stock</x-card.action-link>
        @if($transfer)<x-card.action-button wire:click="save()" :disabled="$errors->any()">Simpan Data</x-card.action-button>@endif
    </x-card.action>
    @php
        $storage_store = [
            'Store'     => 'Toko',
            'Warehouse' => 'Gudang'
        ];
    @endphp
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal transfer</label>
                                <input wire:model.defer="transfer_date" required type="date" class="form-control form-control-lg" @if($transfer) disabled @endif>
                                @error('transfer_date') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Transfer dari</label>
                                <input wire:model.defer="transfer_from" type="text" class="form-control form-control-lg" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Transfer ke</label>
                                <input wire:model.defer="transfer_to" type="text" class="form-control form-control-lg" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if($transfer)
                                <button wire:click="cancelTransfer()" type="button" class="btn btn-danger btn-flat">Batalkan Transaksi</button>
                                <a href="{{ route('dashboard.index') }}" class="btn btn-warning btn-flat">Simpan sebagai draft kembali ke halaman utama</a>
                            @else
                                <button wire:click="beginTransfer()" class="btn btn-flat btn-dark">Add Product</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($transfer)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group"
                             x-data="
                                {
                                    open: @entangle('showDropdown'),
                                    search: @entangle('search'),
                                    selected: @entangle('selected'),
                                    highlightedIndex: 0,
                                    highlightPrevious() {
                                        if (this.highlightedIndex > 0) {
                                          this.highlightedIndex = this.highlightedIndex - 1;
                                          this.scrollIntoView();
                                        }
                                    },
                                    highlightNext() {
                                        if (this.highlightedIndex < this.$refs.results.children.length - 1) {
                                          this.highlightedIndex = this.highlightedIndex + 1;
                                          this.scrollIntoView();
                                        }
                                    },
                                    scrollIntoView() {
                                        this.$refs.results.children[this.highlightedIndex].scrollIntoView({
                                          block: 'nearest',
                                          behavior: 'smooth'
                                        });
                                    },
                                    updateSelected(id, name) {
                                        this.selected = id;
                                        this.search = name;
                                        this.open = false;
                                        this.highlightedIndex = 0;
                                        },
                                    }"
                        >
                            <div x-on:value-selected="updateSelected($event.detail.id, $event.detail.name)" class="tw-w-full">
                                <label>Cari Produk (Barcode / Nama Produk)</label>
                                <input x-ref="query" class="form-control-lg form-control rounded-0"
                                       wire:model.debounce.600ms="search"
                                       x-on:keydown.arrow-down.stop.prevent="highlightNext()"
                                       x-on:keydown.arrow-up.stop.prevent="highlightPrevious()"
                                       x-on:keydown.enter.stop.prevent="$dispatch('value-selected', {
                                            id: $refs.results.children[highlightedIndex].getAttribute('data-result-id'),
                                            name: $refs.results.children[highlightedIndex].getAttribute('data-result-name')
                                      })">
                                <div class="tw-absolute tw-w-full tw-pr-10" x-show="open" x-on:click.away="open = false">
                                    <ul class="tw-relative dropdown-menu show tw-w-full" x-ref="results">
                                        @isset($results)
                                            @forelse($results as $index => $result)
                                                <li class="dropdown-item tw-cursor-pointer hover:tw-bg-slate-300"
                                                    wire:key="{{ $index }}"
                                                    x-on:click.stop="$dispatch('value-selected', {
                                                        id: {{ $result->id }},
                                                        name: '{{ $result->name }}'
                                                    })"
                                                    :class="{
                                                        'tw-bg-slate-400': {{ $index }} === highlightedIndex
                                                    }"
                                                    data-result-id="{{ $result->id }}"
                                                    data-result-name="{{ $result->name }}">
                                                    <span>
                                                      {{ $result->barcode . ' - ' . $result->name }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="dropdown-item tw-cursor-pointer hover:tw-bg-slate-300">Produk tidak ditemukan</li>
                                            @endforelse
                                        @endisset
                                    </ul>
                                </div>
                            </div>
                            @error('products') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                @if (session()->has('error'))
                    <div class="card-footer alert-danger">
                        {{session('error')}}
                    </div>
                @endif
            </div>
        </div>
        @if($transfer->details->count())
            <div class="row">
                <div class="col-lg-12">
                <div class="card tw-z-50">
                    <div class="card-header alert-default-info">
                        <h5>Transfer stok dari {{  \Illuminate\Support\Str::ucfirst($storage_store[$transfer_from]) }} ke {{  \Illuminate\Support\Str::ucfirst($storage_store[$transfer_to]) }}</h5>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 10px">#</th>
                                    <th style="min-width: 300px;">Nama Produk</th>
                                    <th style="min-width: 300px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                Jumlah Transfer
                                            </div>
                                            <div class="col-md-6">
                                                Satuan
                                            </div>
                                        </div>
                                    </th>
                                    <th style="min-width: 150px;">Total Transfer</th>
                                    <th style="min-width: 180px;">Stok Toko</th>
                                    <th style="min-width: 180px;">Stok Gudang</th>
                                    <th style="min-width: 180px">Total {{ \Illuminate\Support\Str::ucfirst($storage_store[$transfer_to])  }}</th>
                                    <th style="min-width: 180px">Total {{ \Illuminate\Support\Str::ucfirst($storage_store[$transfer_from])  }}</th>
                                    <th style="min-width: 100px">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($transfer->details as $key => $item)
                                    <tr class="tw-cursor-pointer">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>
                                            <div class="form-row">
                                                @if($item->product->prices->count())
                                                    <div class="form-group col-md-6">
                                                        <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.id" class="form-control mb-2 mr-sm-2" type="hidden" min="1"/>
                                                        <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.quantity" class="form-control mb-2 mr-sm-2" type="number" min="1"/>
                                                        @error('products.' . $key . '.quantity') <div class="text-sm text-muted text-red">{{ $message }}</div><hr> @enderror
                                                        @error('products.' . $key . '.product_price_quantity') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <select wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.product_price_id" class="form-control mb-2 mr-sm-2">
                                                            @foreach($item->product->prices as $product_item)
                                                                <option value="{{ $product_item->id }}">
                                                                    {{ $product_item->unit->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="tw-items-center">
                                            <strong>{{ $products[$key]['product_price_quantity'] ?: 0  }}</strong> <span class="text-muted">{{ $products[$key]['product']['unit']['name'] }}</span>
{{--                                            <div class="input-group mb-3">--}}
{{--                                                <input wire:model.defer="products.{{ $key }}.product_price_quantity" type="text" class="form-control" readonly>--}}
{{--                                                <div class="input-group-append">--}}
{{--                                                    <span class="input-group-text">{{ $products[$key]['product']['unit']['name'] }}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </td>
                                        <td class="tw-items-center">
                                            <div class="tw-text-green-900"><strong>{{ $products[$key]['product']['store_stock'] ?: 0  }}</strong> <span class="tw-text-sm">{{ $products[$key]['product']['unit']['name'] }}</span></div>
                                            <hr>
                                            @php
                                                $current_stock = $products[$key]['product']['store_stock'] ?: 0;
                                                $prices = collect($products[$key]['product']['prices'])->sortByDesc('quantity');
                                            @endphp


                                            @foreach($prices as $price)
                                                @php

                                                    $stock = $current_stock / $price['quantity'];
                                                @endphp
                                                <div><strong>{{ (int) $stock }}</strong> <span class="text-muted">{{ $price['unit']['name'] }}</span></div>
                                                @php
                                                    $current_stock = $current_stock - ( (int) $stock * $price['quantity'])
                                                @endphp

                                            @endforeach

{{--                                            <div class="input-group mb-3">--}}
{{--                                                <input wire:model.defer="products.{{ $key }}.product.store_stock" type="text" class="form-control" readonly>--}}
{{--                                                <div class="input-group-append">--}}
{{--                                                    <span class="input-group-text">{{ $products[$key]['product']['unit']['name'] }}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </td>
                                        <td class="tw-items-center">
                                            <div class="tw-text-green-900"><strong>{{ $products[$key]['product']['warehouse_stock'] ?: 0 }}</strong> <span class="tw-text-sm">{{ $products[$key]['product']['unit']['name'] }}</span></div>
                                            <hr>
                                            @php
                                            $current_stock = $products[$key]['product']['warehouse_stock'] ?: 0;
                                            $prices = collect($products[$key]['product']['prices'])->sortByDesc('quantity');
                                            @endphp


                                            @foreach($prices as $price)
                                                @php
                                                    $stock = $current_stock / $price['quantity'];
                                                @endphp
                                                    <div><strong>{{ (int) $stock }}</strong> <span class="text-muted">{{ $price['unit']['name'] }}</span></div>
                                                @php
                                                    $current_stock = $current_stock - ( (int) $stock * $price['quantity'])
                                                @endphp

                                            @endforeach
                                            {{--                                            <div class="input-group mb-3">--}}
{{--                                                <input wire:model.defer="products.{{ $key }}.product.warehouse_stock" type="text" class="form-control" readonly>--}}
{{--                                                <div class="input-group-append">--}}
{{--                                                    <span class="input-group-text">{{ $products[$key]['product']['unit']['name'] }}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </td>
                                        <td class="tw-items-center">
                                            <div class="tw-text-green-900"><strong>{{ $products[$key]['product'][\Illuminate\Support\Str::lower($transfer_to) . '_stock'] + $products[$key]['product_price_quantity'] }}</strong> <span class="tw-text-sm">{{ $products[$key]['product']['unit']['name'] }}</span></div>
                                            <hr>
                                            @php
                                                $current_stock = $products[$key]['product'][\Illuminate\Support\Str::lower($transfer_to) . '_stock'] + $products[$key]['product_price_quantity'];
                                                $prices = collect($products[$key]['product']['prices'])->sortByDesc('quantity');
                                            @endphp


                                            @foreach($prices as $price)
                                                @php

                                                    $stock = $current_stock / $price['quantity'];
                                                @endphp
                                                <div><strong>{{ (int) $stock }}</strong> <span class="text-muted">{{ $price['unit']['name'] }}</span></div>
                                                @php
                                                    $current_stock = $current_stock - ( (int) $stock * $price['quantity'])
                                                @endphp

                                            @endforeach


{{--                                            <div class="input-group mb-3">--}}
{{--                                                <input wire:model.defer="products.{{ $key }}.product.store_stock" type="text" class="form-control" readonly>--}}
{{--                                                <div class="input-group-append">--}}
{{--                                                    <span class="input-group-text">{{ $products[$key]['product']['unit']['name'] }}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </td>
                                        <td class="tw-items-center">
                                            <div class="tw-text-green-900"><strong>{{ $products[$key]['product'][\Illuminate\Support\Str::lower($transfer_from) . '_stock'] - $products[$key]['product_price_quantity'] }}</strong> <span class="tw-text-sm">{{ $products[$key]['product']['unit']['name'] }}</span></div>
                                            <hr>
                                            @php
                                                $current_stock = $products[$key]['product'][\Illuminate\Support\Str::lower($transfer_from) . '_stock'] - $products[$key]['product_price_quantity'];
                                                $prices = collect($products[$key]['product']['prices'])->sortByDesc('quantity');
                                            @endphp


                                            @foreach($prices as $price)
                                                @php

                                                    $stock = $current_stock / $price['quantity'];
                                                @endphp
                                                <div><strong>{{ (int) $stock }}</strong> <span class="text-muted">{{ $price['unit']['name'] }}</span></div>
                                                @php
                                                    $current_stock = $current_stock - ( (int) $stock * $price['quantity'])
                                                @endphp

                                            @endforeach
{{--                                            <div class="input-group mb-3">--}}
{{--                                                <input wire:model.defer="products.{{ $key }}.product.warehouse_stock" type="text" class="form-control" readonly>--}}
{{--                                                <div class="input-group-append">--}}
{{--                                                    <span class="input-group-text">{{ $products[$key]['product']['unit']['name'] }}</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </td>
                                        <td><button wire:click="removeItem({{ $item->id }})" class="btn btn-flat btn-sm btn-danger" type="button"><i class="fas fa-trash"></i> Hapus </button> </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
@push('script')
    <script>
        function transferPage() {
            return {
                init: function(){

                }
            }
        }
    </script>
@endpush
