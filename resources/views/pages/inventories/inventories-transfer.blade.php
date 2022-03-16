<div x-data="inventoryPage()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transfer stok</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Transfer stok</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.stock.index') }}" :btn="'light'">Kembali list transfer produk</x-card.action-link>
        <x-card.action-link href="{{ route('pages.stock.transfer.create', 'store') }}" :btn="'primary'">Transfer stock gudang ke <strong class="text-dark">toko</strong></x-card.action-link>
        <x-card.action-link href="{{ route('pages.stock.transfer.create', 'warehouse') }}" :btn="'primary'">Transfer stock toko ke <strong class="text-dark">gudang</strong></x-card.action-link>
    </x-card.action>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List produk terakhir ditambahkan</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input wire:model.debounce.500ms="search" type="search" class="form-control float-right"
                                   placeholder="Search">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Transfer</th>
                                <th>Jumlah Product</th>
                                <th>User</th>
                                <th>Dibuat Tanggal</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transfers as $key => $transfer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $transfer['transfer_date'] }}</td>
                                    <td>{{ $transfer['count_products'] }}</td>
                                    <td>{{ $transfer['create_by'] }}</td>
                                    <td>{{ $transfer['created_at'] }}</td>
                                    <td><button class="btn btn-flat btn-sm btn-primary"><i class="fas fa-glass"></i> View </button> </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>

    </script>
@endpush
