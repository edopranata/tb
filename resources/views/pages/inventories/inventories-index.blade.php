<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Inventori Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Inventori Produk</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-button wire:click="save()">Simpan Data</x-card.action-button>
    </x-card.action>
    <div class="row" x-data="inventoryPage()"
         x-on:keydown.window.slash.prevent="searchProducts()"
         x-on:keydown.window.ctrl.k.prevent="searchProducts()" >
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Produk Masuk (Inventori)</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Invoice (YYYY-MM-DD)</label>
                                @if($purchase)
                                    <input type="text" class="form-control" disabled value="{{ $purchase->invoice_date->format('Y-m-d') }}">
                                @else
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button wire:click="setToday()" class="input-group-text"><i
                                                    class="fas fa-clock mr-2"></i> Hari ini
                                            </button>
                                        </div>
                                        <input wire:model.defer="invoice_date" type="date"
                                               class="form-control">
                                        <div class="input-group-append">
                                            <button wire:click="clearToday()" class="input-group-text"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    @error('invoice_date') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" wire:ignore>
                                <label>Supplier</label>
                                @if($purchase)
                                    <input type="text" class="form-control" disabled value="{{ $purchase->supplier_name }}">
                                @else
                                    <select id="supplier-select" class="form-control" @if($purchase) disabled @endif>
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No Invoice</label>
                                @if($purchase)
                                    <input type="text" class="form-control" disabled value="{{ $purchase->invoice_number }}">
                                @else
                                    <input wire:model.defer="invoice_number" type="text" class="form-control">
                                    @error('invoice_number') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if($purchase)
                                <button wire:click="cancelPurchase()" type="button" class="btn btn-primary btn-flat">Batalkan Transaksi</button>
                                <button wire:click="saveDraft()" type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>
                            @else
                                <button wire:click="beginPurchase()" type="button" class="btn btn-dark btn-flat">Add Product</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($purchase)
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12">
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
                                <div x-on:value-selected="updateSelected($event.detail.id, $event.detail.name)">
                                    <label>Cari Produk (Barcode / Nama Produk)</label>
                                    <input x-ref="query" class="form-control-lg form-control rounded-0"
                                           wire:model.debounce.600ms="search"
                                           x-on:keydown.arrow-down.stop.prevent="highlightNext()"
                                           x-on:keydown.arrow-up.stop.prevent="highlightPrevious()"
                                           x-on:keydown.enter.stop.prevent="$dispatch('value-selected', {
                                            id: $refs.results.children[highlightedIndex].getAttribute('data-result-id'),
                                            name: $refs.results.children[highlightedIndex].getAttribute('data-result-name')
                                      })">
                                    <div class="tw-absolute tw-w-full pr-3" x-show="open" x-on:click.away="open = false">
                                        <ul class="nav nav-pills flex-column tw-bg-slate-100 tw-rounded-b-xl tw-bordered tw-border-indigo-700 tw-text-slate-700" x-ref="results">
                                            @isset($results)
                                            @forelse($results as $index => $result)
                                                <li class="py-2 px-4"
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
                                                <li class="py-2 px-4 tw-bg-slate-400">Produk tidak ditemukan</li>
                                            @endforelse
                                            @endisset
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($purchase->details->count())
                <div class="card">
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nama Produk</th>
                                    <th style="width: 300px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                Jumlah Beli
                                            </div>
                                            <div class="col-md-6">
                                                Satuan
                                            </div>
                                        </div>
                                    </th>
                                    <th style="width: 150px;">Total</th>
                                    <th style="width: 300px;">Harga Modal</th>
                                    <th style="width: 300px;">Total Harga Modal</th>
                                    <th style="width: 130px">Label</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchase->details as $key => $item)
                                    <tr class="tw-cursor-pointer">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>
                                            <div class="form-row">
                                                @if($item->product->prices->count())
                                                    <div class="form-group col-md-6">
                                                        <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.id" class="form-control mb-2 mr-sm-2" type="hidden" min="1"/>
                                                        <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.quantity" class="form-control mb-2 mr-sm-2" type="number" min="1"/>
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
                                            <div class="form-group col-md-12">
                                                <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.buying_price" class="form-control mb-2 mr-sm-2" type="text" min="1"/>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group col-md-12">
                                                <input wire:model.defer="products.{{ $key }}.total" class="form-control mb-2 mr-sm-2" type="text" readonly/>
                                            </div>
                                        </td>
                                        <td>
                                            <button wire:click="removeItem({{ $item->id }})" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
        </div>
        @endif
    </div>
</div>

@push('js')
    <script>
        window.addEventListener('purchaseBegin', () => {
            $("#supplier-select").select2({
                disabled: true,
            });
        })
        function inventoryPage() {
            return {

                pruchases: @entangle('purchase'),
                selectedItem:'',
                searchProducts() {
                    setTimeout(() => {
                        this.$refs.query.focus()
                    }, 100)
                },
                init: function () {


                    $('#supplier-select').select2();
                    $('#supplier-select').on('change', function (e) {
                        @this.set('supplier_id', $('#supplier-select').select2("val"))
                    console.log($('#supplier-select').select2("val"))
                    });
                }
            }
        }

        // window.init = inventoryPage();
    </script>
@endpush
