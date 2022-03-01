<div>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Satuan Barang</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pages.units.index') }}">Satuan Produk</a></li>
                            <li class="breadcrumb-item active">Edit satuan {{ $name }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit satuan <strong>{{ $name }}</strong></h3>
                    <div class="card-tools">
                        <button onclick="confirm('Hapus satuan ini?') || event.stopImmediatePropagation()" wire:click="delete()" type="button" class="btn btn-sm tw-bg-red-900 tw-text-red-200 hover:tw-text-red-100 hover:tw-bg-red-700 tw-transition">Hapus Satuan {{ $name }}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Satuan produk</label>
                        <input wire:model.defer="name" type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputEmail1" placeholder="Satuan produk">
                        @error('name')<span class="text-danger text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
                    <!-- /.card-body -->

                <div class="card-footer">
                    <button wire:click="update()" type="button" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
