<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transaksi Penjualan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Transaksi penjualan</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.stock.index') }}" :btn="'light'">Kembali halaman utama</x-card.action-link>
        @if($sells)<x-card.action-button wire:click="save()" :disabled="$errors->any()">Simpan Data</x-card.action-button>@endif
    </x-card.action>

    <div class="row" x-data="transferPage()">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Transaksi</label>
                                <input wire:model="transaction_date" type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" wire:ignore>
                                <label>Pelanggan</label>
                                <select id="customer-select" class="form-control">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer['id'] }}" @if($customer['id'] == $customer_id) selected @endif>{{ $customer['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Pelanggan</label>
                                <input wire:model="customer_name" type="text" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No Nota</label>
                                <input wire:model="invoice_number" type="text" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            @if($sells)
                                <button wire:click="cancelTransaction()" type="button" class="btn btn-danger btn-flat">Batalkan Transaksi</button>
                                <button wire:click="saveDraft()" type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>
                            @else
                                <button wire:click="transactionBegin()" type="button" class="btn btn-dark btn-flat">Tambah produk</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($sells)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Quick Add (Barcode)</label>
                                    <input wire:model.defer="barcode"wire:keydown.enter="selectBarcode()" type="text" class="form-control-lg form-control rounded-0">
                                </div>
                            </div>
                            <div class="col-md-9">
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
                                               {{--                                       wire:keydown.enter="selectBarcode()"--}}
                                               x-on:keydown.arrow-down.stop.prevent="highlightNext()"
                                               x-on:keydown.arrow-up.stop.prevent="highlightPrevious()"
                                               x-on:keydown.enter.stop.prevent="$dispatch('value-selected', {
                                            id: $refs.results.children[highlightedIndex].getAttribute('data-result-id'),
                                            name: $refs.results.children[highlightedIndex].getAttribute('data-result-name')
                                      })"
                                        >
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
                                                            data-result-barcode="{{ $result->barcode }}"
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
                    </div>
                </div>

                @if (session()->has('error'))
                    <div class="card-footer alert-danger">
                        {{session('error')}}
                    </div>
                @endif
                @if (session()->has('warning'))
                    <div class="card-footer alert-warning">
                        {{session('warning')}}
                    </div>
                @endif
            </div>
        </div>
        @if($sells->details->count())
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 10px">#</th>
                                    <th style="min-width: 200px;">Nama Produk</th>
                                    <th style="min-width: 300px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                Jumlah Beli
                                            </div>
                                            <div class="col-md-6">
                                                Satuan
                                            </div>
                                        </div>
                                    </th>
                                    <th style="min-width: 120px;">Total</th>
                                    <th style="min-width: 250px;">Harga</th>
                                    <th style="min-width: 250px;">Disc</th>
                                    <th style="min-width: 250px;">Total Harga</th>
                                    <th style="min-width: 130px">Act</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sells->details as $key => $item)
                                    <tr class="tw-cursor-pointer">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>
                                            <div class="form-row">
                                                @if($item->product->prices->count())
                                                    <div class="form-group col-md-6">
                                                        <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.id" class="form-control mb-2 mr-sm-2" type="hidden" min="1"/>
                                                        <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.quantity" class="form-control mb-2 mr-sm-2" type="number" min="1"/>
                                                        @error('products.' . $key . '.quantity') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
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
                                        <td>
                                            <div class="form-group col-md-12">
                                                <input wire:model.defer="products.{{ $key }}.product_price_quantity" class="form-control mb-2 mr-sm-2" type="text" readonly/>
                                            </div>

                                        </td>
                                        <td>
                                            <div class="input-group">
                                                @php

                                                @endphp
                                                <div class="input-group-prepend" wire:click="setPrice('{{ $key }}','{{ $customer_id ? 'customer' : 'sell' }}')">
                                                    <span class="input-group-text {{ \Illuminate\Support\Str::lower($products[$key]['price_category']) != 'wholesale' ? 'text-bold tw-bg-slate-700 tw-text-slate-100' : ''}}">{{ $customer_id ? 'C' : 'S'}}</span>
                                                </div>
                                                <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.sell_price" class="form-control text-right" type="text"/>
                                                <div class="input-group-append" wire:click="setPrice('{{ $key }}','wholesale')">
                                                    <div class="input-group-text {{ \Illuminate\Support\Str::lower($products[$key]['price_category']) === 'wholesale' ? 'text-bold tw-bg-slate-700 tw-text-slate-100' : ''}}">G</div>
                                                </div>
                                                @error('products.' . $key . '.sell_price') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                            </div>

                                        </td>
                                        <td>
                                            <div class="form-group col-md-12">
                                                <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.discount" class="form-control text-right" type="text"/>
                                                @error('products.' . $key . '.discount') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group col-md-12">
                                                <input wire:model.defer="products.{{ $key }}.total" class="form-control mb-2 mr-sm-2 text-right" type="text" readonly/>
                                            </div>
                                        </td>
                                        <td>
                                            <button wire:click="removeItem({{ $item->id }})" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right pb-4">Total</th>
                                    <th>
                                        <div class="form-group col-md-12">
                                            <input value="{{ $sells->details->sum('sell_price') }}" class="form-control mb-2 mr-sm-2 text-right" type="text" disabled/>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="form-group col-md-12">
                                            <input value="{{ $sells->details->sum('discount') }}" class="form-control mb-2 mr-sm-2 text-right" type="text" disabled/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <input wire:model.debounce.500ms="sell_discount" class="form-control mb-2 mr-sm-2 text-right" type="text"/>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="form-group col-md-12">
                                            <input value="{{ $sells->details->sum('total') - $sell_discount }}" class="form-control mb-2 mr-sm-2 text-right" type="text" disabled/>
                                        </div>
                                    </th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@push('js')
    <script>

        window.addEventListener('transactionBegin', () => {
            $("#customer-select").select2({
                disabled: true,
            });
        })
        window.addEventListener('transactionCancel', () => {
            $("#customer-select").val('').select2({
                disabled: false,
            }).trigger('change');
        })

        function transferPage() {
            return {
                init: function(){
                    $('#customer-select').select2();
                    $('#customer-select').on('change', function (e) {
                        @this.set('customer_id', $('#customer-select').select2("val"));
                    });
                }
            }
        }
    </script>
@endpush
