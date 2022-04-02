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
                <div class="card-body">
                    <form wire:submit.prevent="upload()">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input wire:model.defer="transfer" type="checkbox" class="custom-control-input" id="transfer">
                                <label class="custom-control-label" for="transfer">Transfer stock to store</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Upload Excel File</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input wire:model="file" type="file" class="custom-file-input">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text">Upload</button>
                                </div>
                            </div>
                            @error('file') <span class="tw-text-sm text-danger">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>

    </script>
@endpush
