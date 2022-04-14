<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Laporan transaksi penjualan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Laporan transaksi penjualan</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <form wire:submit.prevent="viewReport()">
        <x-card.action>
            <div class="col-lg-8">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group m-0 p-0">
                            <select wire:model.defer="user_id" class="form-control form-control-lg rounded-0">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-0 p-0">
                            <div class="input-group">
                                <input wire:model.defer="transaction_date" type="date" class="form-control form-control-lg rounded-0">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text rounded-0 tw-bg-slate-800 hover:tw-bg-slate-900 tw-text-slate-100">Lihat</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button wire:click="exportExcel()" type="button" class="btn btn-flat btn-lg btn-primary">Export Excel</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 tw-flex tw-justify-end">
                <x-card.action-button onclick="window.print()" type="button" :btn="'light'">
                    Print
                </x-card.action-button>
            </div>
        </x-card.action>
    </form>
    <div class="row">
        <div class="col-lg-12">
            @if($sells)
            <div class="card">
                <div class="card-header">
                    List transaksi {{ $transaction_date }}
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm border-0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kasir</th>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th class="text-right">Tagihan</th>
                            <th class="text-right">Diskon</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Pembayaran</th>
                            <th class="text-right">Uang Kembali</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sells as $key => $sell)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $sell['user'] }}</td>
                            <td>{{ $sell['invoice_date'] }}</td>
                            <td>{{ $sell['invoice_number'] }}</td>
                            <td class="text-right">Rp. {{ number_format($sell['bill']) }}</td>
                            <td class="text-right">Rp. {{ number_format($sell['discount']) }}</td>
                            <td class="text-right">Rp. {{ number_format($sell['total']) }}</td>
                            <td class="text-right">Rp. {{ number_format($sell['payment']) }}</td>
                            <td class="text-right">Rp. {{ number_format($sell['refund']) }}</td>
                            <td>{{ $sell['status'] }}</td>
                        </tr>
                            @if($sell['returns'])
                                @foreach($sell['returns'] as $index => $return)
                                    <tr class="border-0">
                                        <td colspan="3"></td>
                                        <td>{{ $return['product_name'] }}</td>
                                        <td class="text-right">{{ $return['quantity'] }}</td>
                                        <td class="text-right">Rp. {{ number_format($return['sell_price']) }}</td>
                                        <td class="text-right">Rp. {{ number_format($return['quantity'] * $return['sell_price']) }}</td>
                                        <td colspan="3">Retur</td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty

                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total Penjualan</th>
                            <th class="text-right">Rp. {{ number_format($sells ? $sells->sum('bill') : 0) }}</th>
                            <th class="text-right">Rp. {{ number_format($sells ? $sells->sum('discount') : 0) }}</th>
                            <th class="text-right">Rp. {{ number_format($sells ? $sells->sum('total') : 0) }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Total retur</th>
                            <th colspan="3" class="text-right">Rp. {{ number_format($sells ? $sells->sum('sum_return') : 0) }}</th>
                            <th colspan="3"></th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Total Keseluruhan</th>
                            <th colspan="3" class="text-right">Rp. {{ number_format(($sells ? $sells->sum('total') : 0) - ($sells ? $sells->sum('sum_return') : 0)) }}</th>
                            <th colspan="3"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-12">
            @if($returns)
                <div class="card">
                    <div class="card-header">
                        Transaksi Retur {{ $transaction_date }}
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-sm border-0">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kasir</th>
                                <th>Tanggal Invoice</th>
                                <th>Invoice</th>
                                <th>Produk Retur</th>
                                <th class="text-right">Quantity Retur</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Total</th>
                                <th>Keterangan</th>
                            </tr>
                            <tbody>
                            @foreach($returns as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item['user'] }}</td>
                                    <td>{{ $item['invoice_date'] }}</td>
                                    <td>{{ $item['invoice_number'] }}</td>
                                    <td>{{ $item['product_name'] }}</td>
                                    <td class="text-right">{{ $item['quantity'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($item['sell_price']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($item['total']) }}</td>
                                    <td>Retur</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="7" class="text-right">Total Retur</th>
                                <th class="text-right">Rp. {{ number_format($returns ? $returns->sum('total') : 0) }}</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if($sells)
        <div class="row">
        <div class="col-md-6">

        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice"></i>
                        Transaction Details
                    </h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Tanggal Transaksi </dt>
                        <dd class="col-sm-8">{{ $transaction_date }}</dd>
                        <dt class="col-sm-4">Kasir</dt>
                        <dd class="col-sm-8">{{ \auth()->user()->username }}</dd>
                        <dt class="col-sm-4">Total Penjualan</dt>
                        <dd class="col-sm-8 text-bold">Rp. {{ number_format($sells ? $sells->sum('total') - $sells->sum('sum_return') : 0) }}</dd>
                        <dt class="col-sm-4">Total Retur Barang</dt>
                        <dd class="col-sm-8 text-bold">Rp. {{ number_format($returns ? $returns->sum('total') : 0) }}</dd>
                        <dt class="col-sm-4">Subtotal</dt>
                        <dd class="col-sm-8 text-bold">Rp. {{ number_format(($sells ? $sells->sum('total') - $sells->sum('sum_return') : 0) - ($returns ? $returns->sum('total') : 0)) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
