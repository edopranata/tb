<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Daftar Satuan Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Daftar Satuan Produk</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.units.create') }}" :btn="'light'">
            Buat Satuan Baru
        </x-card.action-link>
    </x-card.action>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Satuan Produk</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input wire:model.debounce.500ms="search" type="search" class="form-control float-right"
                                   placeholder="Search">
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    @if(session('status'))
                        <div class="alert alert-{{ session('status') }} rounded-0 alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {!! session('message') !!}
                        </div>
                    @endif
                    <table class="table text-nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Satuan</th>
                            <th>Dibuat Oleh</th>
                            <th>Dibuat pada</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($units)
                            @foreach($units as $key => $unit)
                                <tr class="tw-cursor-pointer hover:tw-bg-slate-200" wire:click="editId({{$unit['id']}})">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $unit['name'] }}</td>
                                    <td>{{ $unit['created_by'] }}</td>
                                    <td>{{ $unit['created_at'] }}</td>
                                    <td class="text-right"><i class="fas fa-chevron-right"></i> &nbsp;</td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $units->links('vendor.livewire.tailwind') }}
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
