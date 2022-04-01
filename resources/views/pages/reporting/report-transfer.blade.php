<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Laporan transfer stock</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Laporan transfer stock</li>
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
        <div class="col-lg-12">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table text-nowrap">
                        <thead>
                        <tr class="no-print">
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>
                                <div class="form-group">
                                    <select wire:model="transfer_to" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="warehouse">Gudang</option>
                                        <option value="store">Toko</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div class="form-group">
                                    <select wire:model="user_id" class="form-control">
                                        <option value="">Semua</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div class="form-group">
                                    <input wire:model="transfer_date" type="date" class="form-control">
                                </div>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($transfers)
                            @foreach($transfers as $key => $transfer)
                                <tr>
                                    <th>{{ $key + 1 }}</th>
                                    <th>{{ $transfer->transfer_from }}</th>
                                    <th>{{ $transfer->transfer_to }}</th>
                                    <th>{{ $transfer->user->username }}</th>
                                    <th class="tw-flex tw-justify-between">
                                        <div>{{ $transfer->transfer_date }}</div>
                                        <div class="text-right no-print"><i class="fas fa-chevron-right"></i> &nbsp;</div>
                                    </th>
                                    <th>{{ $transfer->details_count }}</th>
                                </tr>
                                @if(isset($transfer->details))
                                    <tr>
                                        <td colspan="6" class="p-0">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Barcode</th>
                                                    <th>Nama Produk</th>
                                                    <th>Kategori</th>
                                                    <th>Jumlah transfer</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($transfer->details as $keys => $detail)
                                                    <tr>
                                                        <td>{{ $keys + 1 }}</td>
                                                        <td>{{ $detail->product->barcode }}</td>
                                                        <td>{{ $detail->product->name }}</td>
                                                        <td>{{ $detail->product->category->name }}</td>
                                                        <td>{{ $detail->quantity }} {{ $detail->price->unit->name }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endisset
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
