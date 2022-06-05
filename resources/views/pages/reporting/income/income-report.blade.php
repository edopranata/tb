<div x-data="incomeReport()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Laporan Product Stock</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Laporan</a></li>
                            <li class="breadcrumb-item active">Product Stock</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-button onclick="window.print()" type="button" :btn="'light'">
            Print
        </x-card.action-button>
    </x-card.action>
    <div class="row">
        @if(session('status'))
            <div class="col-12">
                <div class="alert alert-{{ session('status') }} rounded-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! session('message') !!}
                </div>
            </div>
        @endif
    </div>
    <div class="col-lg-12">
        <div class="card no-print">

            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select wire:model="report_type" class="form-control">
                                <option value="daily">Harian</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                        @if($report_type == 'daily')
                            <input wire:model="report_day" placeholder="Tanggal laporan" type="date" class="form-control"/>
                        @elseif($report_type == 'monthly')
                            <div class="row">
                                <div class="col-6">
                                    <select wire:model="report_month"  class="form-control">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input wire:model="report_year" placeholder="Tahun" type="number" min="2022" class="form-control"/>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-4">
                                    <input wire:model="report_year" placeholder="Tahun" type="number" min="2022" class="form-control"/>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-dark btn-flat" wire:click="viewReport">Lihat Laporan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-bold">Pendapatan Penjualan</h5>
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Modal</th>
                                <th>Penjualan</th>
                                <th>Pendapatan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($sells)
                                @forelse($sells as $key => $sell)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if($report_type == 'daily')
                                                {{ \Carbon\Carbon::create($sell->invoice_date)->toDateString() }}
                                            @elseif($report_type == 'monthly')
                                                {{ $sell->months }}
                                            @else
                                                {{ $sell->years }}
                                            @endif
                                        </td>
                                        <td>Rp. {{ number_format($sell->buying_price) }}</td>
                                        <td>Rp. {{ number_format($sell->sell_price) }}</td>
                                        <td class="bg-green text-success">Rp. {{ number_format($sell->sell_price - $sell->buying_price) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">Tidak ada penjualan</td>
                                    </tr>
                                @endforelse
                            @endisset
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 px-3">
                        <h5 class="text-bold">Pengeluaran (Inventori Produk)</h5>
                        <table class="table table-hover mx-3">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Pengeluaran</th>
                            </tr>
                            </thead>
                            @isset($inventories)
                                @forelse($inventories as $key => $inventory)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            @if($report_type == 'daily')
                                                {{ \Carbon\Carbon::create($inventory->invoice_date)->toDateString() }}
                                            @elseif($report_type == 'monthly')
                                                {{ $inventory->months }}
                                            @else
                                                {{ $inventory->years }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($inventory->total)  }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">Tidak ada pengeluaran</td>
                                    </tr>
                                @endforelse
                            @endisset
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <div class="col-md-6">
                                <h5 class="text-bold">List Transaksi Penjualan</h5>
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Invoice Number</th>
                                        <th>Modal</th>
                                        <th>Penjualan</th>
                                        <th>Pendapatan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @isset($reports)
                                        @forelse($reports as $key => $report)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if($report_type == 'daily')
                                                        {{ $report_day }}
                                                    @elseif($report_type == 'monthly')
                                                        {{ \Carbon\Carbon::create($report->invoice_date)->toDateString() }}
                                                    @else
                                                        {{ $report->months }}
                                                    @endif
                                                </td>
                                                <td>{{ $report->invoice_number }}</td>
                                                <td>Rp. {{ number_format($report->buying_price) }}</td>
                                                <td>Rp. {{ number_format($report->sell_price) }}</td>
                                                <td class="@if($report->buying_price > $report->sell_price)  text-warning bg-warning @else bg-success text-success @endif">Rp. {{ number_format($report->sell_price - $report->buying_price) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Tidak ada transaksi</td>
                                            </tr>
                                        @endforelse
                                    @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        function incomeReport() {

        }
    </script>
@endpush
