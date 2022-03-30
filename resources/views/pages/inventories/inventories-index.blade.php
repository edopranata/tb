<div x-data="inventoryPage()">
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
    <div class="row"
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
                                <label>Tanggal Invoice</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button wire:click="setToday()" class="input-group-text" @if($purchase) disabled @endif><i
                                                    class="fas fa-clock mr-2"></i> Hari ini
                                            </button>
                                        </div>
                                        <input wire:model.defer="invoice_date" type="date"
                                               class="form-control" @if($purchase) disabled @endif>
                                        <div class="input-group-append">
                                            <button wire:click="clearToday()" class="input-group-text" @if($purchase) disabled @endif><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    @error('invoice_date') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" wire:ignore>
                                <label>Supplier</label>
                                <select id="supplier-select" class="form-control" @if($purchase) disabled @endif>
                                    <option value="">Pilih Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier['id'] }}" @if($supplier['id'] = $supplier_id) selected @endif>{{ $supplier['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No Invoice</label>
                                <input wire:model.defer="invoice_number" type="text" class="form-control" placeholder="No Invoice / Nota" @if($purchase) disabled @endif>
                                @error('invoice_number') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if($purchase)
                                <button wire:click="cancelPurchase()" type="button" class="btn btn-danger btn-flat">Batalkan Transaksi</button>
                                <button wire:click="saveDraft()" type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>
                            @else
                                <button wire:click="beginPurchase()" type="button" class="btn btn-dark btn-flat">Add Product</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($purchase)
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Pembayaran</label>
                                    <input wire:model.defer="bill" type="text" class="form-control rupiah" placeholder="Total Pembayaran" disabled>
                                    @error('bill') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah Pembayaran</label>
                                    <input wire:change="loadTemp()" wire:model.defer="payment" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" class="form-control rupiah" placeholder="Jumlah Pembayaran">
                                    @error('payment') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sisa Tagihan</label>
                                    <input wire:model.defer="fund" type="text" class="form-control rupiah" placeholder="Sisa Tagihan" disabled>
                                    @error('fund') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                                        <div class="tw-absolute tw-w-full pr-5" x-show="open" x-on:click.away="open = false">
                                            <ul class="dropdown-menu show tw-w-full mr-3" x-ref="results">
                                                @isset($results)
                                                    @forelse($results as $index => $result)
                                                        <li class="dropdown-item hover:tw-bg-slate-300 tw-cursor-pointer"
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
                                                        <li class="dropdown-item hover:tw-bg-slate-300 tw-cursor-pointer">Produk tidak ditemukan</li>
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
            </div>
        </div>
        @if($purchase->details->count())
            <div class="row">
                <div class="col-lg-12">
                    @if(session('status'))
                        <div class="col-12">
                            <div class="alert alert-{{ session('status') }} rounded-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {!! session('message') !!}
                            </div>
                        </div>
                    @endif
                    <div class="card tw-z-50">
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
                                                    Jumlah Beli
                                                </div>
                                                <div class="col-md-6">
                                                    Satuan
                                                </div>
                                            </div>
                                        </th>
                                        <th style="min-width: 150px;">Total</th>
                                        <th style="min-width: 300px;">Harga Modal</th>
                                        <th style="min-width: 300px;">Total Harga Modal</th>
                                        <th style="min-width: 130px">Label</th>
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
                                                            <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.id" class="form-control mr-sm-2" type="hidden" min="1"/>
                                                            <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.quantity" class="form-control mr-sm-2" type="number" min="1"/>
                                                            @error('products.' . $key . '.quantity') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <select wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.product_price_id" class="form-control mr-sm-2">
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
                                                    <input wire:model.defer="products.{{ $key }}.product_price_quantity" class="form-control mr-sm-2" type="text" readonly/>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="form-group col-md-12">
                                                    <input wire:change="updateProduct({{ $key }})" wire:model.defer="products.{{ $key }}.buying_price" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" class="form-control rupiah mr-sm-2 text-right" type="text" min="1"/>
                                                    @error('products.' . $key . '.buying_price') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group col-md-12">
                                                    <input wire:model.defer="products.{{ $key }}.total" class="form-control rupiah mr-sm-2 text-right" type="text" readonly/>
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
                                        <th colspan="4" class="text-right">Total</th>
                                        <th>
                                            <div class="form-group col-md-12">
                                                <input value="{{ $purchase->details->sum('buying_price') }}" class="form-control rupiah mr-sm-2 text-right" type="text" disabled/>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="form-group col-md-12">
                                                <input value="{{ $purchase->details->sum('total') }}" class="form-control rupiah mr-sm-2 text-right" type="text" disabled/>
                                            </div>
                                        </th>
                                        <th>Label</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

@push('js')
    <script>
        window.addEventListener('purchaseBegin', () => {
            $("#supplier-select").select2({
                disabled: true,
            });
        })
        window.addEventListener('purchaseCancel', () => {
            $("#supplier-select").val('').select2({
                disabled: false,
            }).trigger('change');
        })

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

        function inventoryPage() {
            return {

                purchases: @entangle('purchase'),
                selectedItem:'',
                searchProducts() {
                    setTimeout(() => {
                        this.$refs.query.focus()
                    }, 100)
                },
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

                    $('#supplier-select').select2();
                    $('#supplier-select').on('change', function (e) {
                        @this.set('supplier_id', $('#supplier-select').select2("val"));
                    });
                }
            }
        }

        // window.init = inventoryPage();
    </script>
@endpush
