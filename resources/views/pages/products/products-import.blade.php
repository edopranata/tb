<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Upload Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Upload Product</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <form wire:submit.prevent="upload()">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input wire:model.defer="transfer" type="checkbox" class="custom-control-input" id="transfer">
                                <label class="custom-control-label" for="transfer">Transfer stock to store</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Upload Excel File</label>
                            <input wire:model="file" type="file" class="form-control">
                            @error('file') <span class="tw-text-sm text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button wire:loading.attr="disabled" type="submit" class="input-group-text"><i wire:loading class="fas fa-fan fa-spin mr-2"></i> Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>

    </script>
@endpush
