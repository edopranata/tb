<div x-data="transferPage()">
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
        @if($sells)<button x-on:keydown.window.prevent.ctrl.enter="$wire.transactionSave()" class="btn btn-primary btn-flat" type="button" x-on:click="submit()" @if($errors->any()) disabled @endif">Simpan Data (ctrl + enter)</button>@endif
    </x-card.action>
    <div class="row d-none d-print-block">
        @if($sell)
            @php
                $total = $sell->details->sum('total');
                $sub_total = $total - $sell->discount;
                $bond = $sell->histories()->sum('payment') - $sub_total
            @endphp

            <div class="card card-body mt-0 tw-text-[45px] tw-font-mono" >
                <div class="tw-p-0 tw-m-0 tw-flex tw-justify-center">
                    <img src="{{ asset('dist/img/sbr-logo.png') }}">
                </div>
                <div class="tw-p-0 tw-m-0 text-center">Toko Bangunan SBR</div>
                <div class="tw-p-0 tw-m-0 text-center">Building Material</div>
                <div class="tw-p-0 tw-m-0 text-center">Pasaman Barat</div>
                <hr class="bg-black tw-border-black tw-my-1">
                <div class="row tw-text-[36px]">
                    <div class="col-6">
                        ID #{{ $sell->id }}
                    </div>
                    <div class="col-6">
                        INV #{{ $sell->invoice_number }}
                    </div>

                    <div class="col-6">
                        USER #{{ $sell->user->username }}
                    </div>
                    <div class="col-6">
                        DATE #{{ $sell->invoice_date->format('d-m-Y') }}
                    </div>

                    <div class="col-6">
                        @if($sell->customer_id)
                            TO : {{ $sell->customer_name }}
                        @endif
                    </div>
                    <div class="col-6">
                        TIME #{{ $sell->invoice_date->format('H:i:s') }}
                    </div>
                </div>
                <hr class="bg-black tw-border-black tw-my-1">
                <div class="divide-y divide-slate-200">
                    @foreach($sell->details as $detail)
                        <div class="tw-flex">
                            <div class="tw-grow">
                                {{ $detail->product_name }}<br>
                                {{ $detail->quantity . ' ' . $detail->price->unit->name }} @ Rp. {{ number_format($detail->sell_price) . ' ' }}
                                @if($detail->discount > 0) Disc Rp. {{ number_format($detail->discount) }} @endif
                            </div>
                            <div class="tw-flex-none tw-w-auto text-right">
                                Rp. {{ number_format($detail->total) }}
                            </div>
                        </div>
                        <hr class="tw-bg-slate-500 tw-border-slate-500 tw-my-1">
                    @endforeach
                </div>
                <div class="divide-y divide-slate-200">
                    <div class="tw-flex tw-font-bold">
                        <div class="tw-grow text-right">Total</div>
                        <div class="tw-flex-none tw-w-[20rem] text-right">Rp. {{ number_format($total) }}</div>
                    </div>
                    <div class="tw-flex tw-font-bold">
                        <div class="tw-grow text-right">Disc</div>
                        <div class="tw-flex-none tw-w-[20rem] text-right">Rp. {{ number_format($sell->discount) }}</div>
                    </div>
                    <div class="tw-flex tw-font-bold tw-text-[24px]">
                        <div class="tw-grow text-right">Subtotal</div>
                        <div class="tw-flex-none tw-w-[20rem] text-right border-bottom border-top">Rp. {{ number_format($sub_total) }}</div>
                    </div>
                    <div class="tw-flex tw-font-bold">
                        <div class="tw-grow text-right">Bayar</div>
                        <div class="tw-flex-none tw-w-[20rem] text-right">{{ number_format($sell->payment) }}</div>
                    </div>
                    @if($sell->payment >= $sub_total)
                        <div class="tw-flex tw-font-bold">
                            <div class="tw-grow text-right">Kembali</div>
                            <div class="tw-flex-none tw-w-[20rem] text-right">{{ number_format( $sell->payment - $sub_total) }}</div>
                        </div>
                    @else
                        <div class="tw-flex tw-font-bold">
                            <div class="tw-grow text-right">Bond</div>
                            <div class="tw-flex-none tw-w-[20rem] text-right">{{ number_format($bond) }}</div>
                        </div>
                    @endif
                </div>
                <hr>
                <div class="tw-flex tw-flex-col tw-items-center">
                    <div>*** Terima Kasih ***</div><br>
                    <div><i class="fab fa-facebook"></i></i> SBRPASAMAN BARAT</div>
                    <div><i class="fab fa-whatsapp-square"></i> 0822-1193-5100</div>
                    <div><i class="fab fa-instagram-square"></i> SBRPASAMAN BARAT</div>
                </div>
            </div>
        @endif
    </div>
    <div class="row no-print">
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
                            </div>
                            @error('customer_id') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
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
                                <button type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>
                            @else
                                <button
                                    x-ref="btnSave"
                                    x-on:keydown.window.prevent.ctrl.enter="$wire.transactionBegin()"
                                    wire:click="transactionBegin()" type="button" class="btn btn-dark btn-flat">Buat Transaksi (ctrl + enter)</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($sells)
        <div class="row no-print">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group" x-on:keydown.window.prevent.alt.slash="inputBarcode = true">
                                    <label>Quick Add Barcode (ALT + /)</label>
                                    <input placeholder="ALT + /" x-trap="inputBarcode" x-on:focusout="inputBarcode = false" wire:model.lazy="barcode"wire:keydown.enter="selectBarcode()" type="text" class="form-control-lg form-control rounded-0">
                                </div>
                            </div>
                            <div class="col-md-9" x-on:keydown.window.prevent.ctrl.slash="inputSearch = true">
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
                                        <label>Cari Produk berdasarkan nama/barcode (CTRL + /)</label>
                                        <input placeholder="CTRL + /" x-ref="query" x-trap="inputSearch" x-on:focusout="inputSearch = false" class="form-control-lg form-control rounded-0"
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
            <div class="row no-print">
                <div class="col-md-8">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 10px">#</th>
                                    <th style="min-width: 200px;">Nama Produk</th>
                                    <th style="min-width: 250px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                Jumlah Beli
                                            </div>
                                            <div class="col-md-6">
                                                Satuan
                                            </div>
                                        </div>
                                    </th>

{{--                                    <th style="min-width: 120px;">Total</th>--}}
                                    <th style="min-width: 200px;">Harga</th>
                                    @if($show_discount)
                                    <th style="min-width: 200px;">Disc</th>
                                    @endif
                                    <th style="min-width: 200px;">Total Harga</th>
                                    <th style="min-width: 80px">Act</th>
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
                                                        <input wire:change="updateProduct({{ $key }})" wire:model.lazy="products.{{ $key }}.quantity" class="form-control mr-sm-2 rupiah" type="text" min="1"/>
                                                        @error('products.' . $key . '.quantity') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <select wire:change="updateProduct({{ $key }})" wire:model.lazy="products.{{ $key }}.product_price_id" class="form-control mr-sm-2">
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
{{--                                        <td>--}}
{{--                                            <div class="form-group col-md-12">--}}
{{--                                                <input wire:model.lazy="products.{{ $key }}.product_price_quantity" class="form-control mr-sm-2" type="text" readonly/>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend" wire:click="setPrice('{{ $key }}','{{ $customer_id ? 'customer' : 'sell' }}')">
                                                    <span class="input-group-text {{ \Illuminate\Support\Str::lower($products[$key]['price_category']) != 'wholesale' ? 'text-bold tw-bg-slate-700 tw-text-slate-100' : ''}}">{{ $customer_id ? 'C' : 'S'}}</span>
                                                </div>
                                                <input wire:model.lazy="products.{{ $key }}.sell_price" wire:change="setPrice('{{ $key }}','sell', 'current')" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" class="form-control text-right rupiah" type="text"/>
                                                <div class="input-group-append" wire:click="setPrice('{{ $key }}','wholesale')">
                                                    <div class="input-group-text {{ \Illuminate\Support\Str::lower($products[$key]['price_category']) === 'wholesale' ? 'text-bold tw-bg-slate-700 tw-text-slate-100' : ''}}">G</div>
                                                </div>
                                                @error('products.' . $key . '.sell_price') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                            </div>

                                        </td>
                                        @if($show_discount)
                                        <td>
                                            <div class="form-group col-md-12">
                                                <input wire:change="updateProduct({{ $key }})" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" wire:model.lazy="products.{{ $key }}.discount" class="form-control text-right rupiah" type="text"/>
                                                @error('products.' . $key . '.discount') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                                            </div>
                                        </td>
                                        @endif
                                        <td>
                                            <div class="form-group col-md-12">
                                                <input wire:model.lazy="products.{{ $key }}.total" class="form-control mr-sm-2 text-right rupiah" type="text" readonly/>
                                            </div>
                                        </td>
                                        <td>
                                            <button wire:click="removeItem({{ $item->id }})" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total tagihan</h5>
                            <div class="input-group input-group-lg mb-3">
                                <div class="input-group-prepend rounded-0">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input wire:model="total" type="text" class="form-control form-control-lg rounded-0 rupiah" readonly>
                            </div>
                            @if($show_discount)
                                <h5>Potongan</h5>
                                <div class="input-group input-group-lg mb-3">
                                    <div class="input-group-prepend rounded-0">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input wire:model="sell_discount" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" class="form-control form-control-lg rounded-0 rupiah">
                                </div>
                            @endif

                            <h5>Total pembayaran</h5>
                            <div class="input-group input-group-lg mb-3">
                                <div class="input-group-prepend rounded-0">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input wire:change="updatePayment()" wire:model.lazy="payment" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" class="form-control form-control-lg rounded-0 rupiah">
                                <div class="input-group-append rounded-0">
                                    <button wire:click="fixedPayment()" class="btn btn-info btn-flat" type="button">Pas</button>
                                </div>
                            </div>

                            <h5>Uang kembali</h5>
                            <div class="input-group input-group-lg mb-3">
                                <div class="input-group-prepend rounded-0">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input wire:model="refund" type="text" class="form-control form-control-lg rounded-0 rupiah" readonly>
                            </div>
                            <h5>Tanggal Jatuh Tempo</h5>
                            <div class="input-group input-group-lg mb-3">
                                <div class="input-group-prepend rounded-0">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                </div>
                                <input wire:model="due_date" type="date" class="form-control form-control-lg rounded-0">
                            </div>
                            @error('due_date') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
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

        window.addEventListener('pagePrint', () => {
            window.print();
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

        function transferPage() {
            return {
                inputBarcode: false,
                inputSearch:false,

                submit(){
                    // $('.rupiah').unmask();
                    this.$wire.transactionSave()
                },

                updatePayment() {
                    $('.rupiah').unmask();
                    this.$wire.updatePayment()
                },
                init: function(){
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
                    $('#customer-select').select2();
                    $('#customer-select').on('change', function (e) {
                        @this.set('customer_id', $('#customer-select').select2("val"));
                    });
                }
            }
        }
    </script>
@endpush
