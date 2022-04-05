<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pengaturan Aplikasi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Pengaturan Aplikasi</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
{{--        <x-card.action-button wire:click="syncPermissions()"><i class="fas fa-refresh"></i> Update Permissions</x-card.action-button>--}}
    </x-card.action>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permission List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">

                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>

    </script>
@endpush
