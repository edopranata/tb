<div x-data="transferPage()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transaksi Return Penjualan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Transaksi Return Penjualan</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.stock.index') }}" :btn="'light'">Kembali halaman utama</x-card.action-link>
    </x-card.action>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Invoice Number</label>
                            <div class="input-group">
                                <input wire:keydown.enter="getInvoice()" wire:model.defer="invoice_number" type="text" class="form-control form-control-lg">
                                <div class="input-group-append">
                                    <button wire:click="getInvoice()" type="button" class="input-group-text">Cari</button>
                                </div>
                            </div>
                            @error('invoice_number')
                            <span class="tw-text-red-800 tw-text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success rounded-0">
            {{session('success')}}
        </div>
    @endif
    @if($sell)
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-invoice"></i>
                            Transaction Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Invoice Number</dt>
                            <dd class="col-sm-8">{{ $sell->invoice_number }}</dd>
                            <dt class="col-sm-4">Tanggal Invoice </dt>
                            <dd class="col-sm-8">{{ $sell->invoice_date }}</dd>
                            <dt class="col-sm-4">Pelanggan</dt>
                            <dd class="col-sm-8">{{ $sell->customer_name }}</dd>
                            <dt class="col-sm-4">Tagihan</dt>
                            <dd class="col-sm-8">{{ number_format($sell->bill) }}</dd>
                            <dt class="col-sm-4">Diskon</dt>
                            <dd class="col-sm-8">{{ number_format($sell->discount) }}</dd>
                            <dt class="col-sm-4">Pembayaran</dt>
                            <dd class="col-sm-8">{{ number_format($sell->payment) }}</dd>
                            <dt class="col-sm-4">Uang Kembali</dt>
                            <dd class="col-sm-8">{{ number_format($sell->payment - ($sell->bill - $sell->discount)) }}</dd>
                            <dt class="col-sm-4">Payment Status</dt>
                            <dd class="col-sm-8"><span class="badge @if($sell->status === "LUNAS") badge-success @else badge-danger @endif">{{ $sell->status }}</span></dd>
                            <dt class="col-sm-4">Cashier</dt>
                            <dd class="col-sm-8">{{ $sell->user->username }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Transaction Product List</h3>
                    </div>

                    <div class="card-body p-0">
                        @if (session()->has('error'))
                            <div class="alert alert-danger rounded-0">
                                {{session('error')}}
                            </div>
                        @endif
                        @if (session()->has('warning'))
                            <div class="alert alert-warning rounded-0">
                                {{session('warning')}}
                            </div>
                        @endif
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th style="min-width: 10px">#</th>
                                <th style="min-width: 200px;">Nama Produk</th>
                                <th style="min-width: 150px;">Jumlah Beli</th>
                                <th style="min-width: 150px;">Harga</th>
                                <th style="min-width: 150px;">Disc</th>
                                <th style="min-width: 150px;">Total Harga</th>
                                <th style="min-width: 80px">Retur</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sell->details as $key => $item)
                                <tr class="tw-cursor-pointer">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }} {{ $item->price->unit->name }}</td>
                                    <td>{{ number_format($item->sell_price) }}</td>
                                    <td>{{ number_format($item->discount) }}</td>
                                    <td>{{ number_format($item->total) }}</td>
                                    <td>
                                        <input wire:model="return.{{ $item->id }}.quantity" style="max-width: 80px;" type="number" min="0" max="{{ $item->quantity }}" class="form-control form-control-sm">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button wire:click="submitReturn()" type="button" class="btn btn-success btn-flat float-right">Proses Retur</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@push('js')
    <script>

    </script>
@endpush
