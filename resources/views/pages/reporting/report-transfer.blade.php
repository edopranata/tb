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
        <x-card.action-button type="button" :btn="'light'">
            Print
        </x-card.action-button>
    </x-card.action>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table text-nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Dibuat Oleh</th>
                            <th>Dibuat pada</th>
                            <th>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        @isset($categories)--}}
{{--                            @foreach($categories as $key => $category)--}}
{{--                                <tr class="tw-cursor-pointer hover:tw-bg-slate-200" wire:click="editId({{$category['id']}})">--}}
{{--                                    <td>{{ $key + 1 }}</td>--}}
{{--                                    <td>{{ $category['name'] }}</td>--}}
{{--                                    <td>{{ $category['created_by'] }}</td>--}}
{{--                                    <td>{{ $category['created_at'] }}</td>--}}
{{--                                    <td class="text-right"><i class="fas fa-chevron-right"></i> &nbsp;</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @endisset--}}
                        </tbody>
                    </table>
                </div>
{{--                <div class="card-footer">--}}
{{--                    {{ $categories->links('vendor.livewire.tailwind') }}--}}
{{--                </div>--}}
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
