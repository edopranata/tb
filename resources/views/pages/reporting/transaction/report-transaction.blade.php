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
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm">
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
                        @empty

                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right">Rp. {{ number_format($sells->sum('bill')) }}</th>
                            <th class="text-right">Rp. {{ number_format($sells->sum('discount')) }}</th>
                            <th class="text-right">Rp. {{ number_format($sells->sum('total')) }}</th>
                            <th class="text-right">Rp. {{ number_format($sells->sum('payment')) }}</th>
                            <th class="text-right">Rp. {{ number_format($sells->sum('refund')) }}</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
