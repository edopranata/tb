<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Permission List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Permission List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-button wire:click="syncPermissions()"><i class="fas fa-refresh"></i> Update Permissions</x-card.action-button>
    </x-card.action>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Permission List</h3>

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
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Permission Name</th>
                            <th>Guard</th>
{{--                            <th>Payload</th>--}}
                            <th>Created At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($permissions)
                            @foreach($permissions as $key => $permission)
                                <tr class="tw-cursor-pointer hover:tw-bg-slate-200">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $permission['name'] }}</td>
                                    <td>{{ $permission['guard_name'] }}</td>
{{--                                    <td>--}}
{{--                                        <div class="tw-mockup-code tw-max-w-2xl">--}}
{{--                                            <pre>--}}
{{--                                                <code>{{ $permission['payload'] }}</code>--}}
{{--                                            </pre>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                    <td>{{ $permission['created_at'] }}</td>
                                </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $permissions->links('vendor.livewire.tailwind') }}
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>

    </script>
@endpush
