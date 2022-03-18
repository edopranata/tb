<div x-data="transferPage()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transaksi Penjualan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Transaksi penjualan</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <x-card.action-link href="{{ route('pages.stock.index') }}" :btn="'light'">Kembali halaman utama</x-card.action-link>
        @if($transaction)<x-card.action-button wire:click="save()" :disabled="$errors->any()">Simpan Data</x-card.action-button>@endif
    </x-card.action>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
    @if($transaction)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group"
                             x-data="
                                {
                                    open: @entangle('showDropdown'),
                                    search: @entangle('search'),
                                    selected: @entangle('selected'),
                                    highlightedIndex: 0,
                                    highlightPrevious() {
                                        if (this.highlightedIndex > 0) {
                                          this.highlightedIndex = this.highlightedIndex - 1;
                                          this.scrollIntoView();
                                        }
                                    },
                                    highlightNext() {
                                        if (this.highlightedIndex < this.$refs.results.children.length - 1) {
                                          this.highlightedIndex = this.highlightedIndex + 1;
                                          this.scrollIntoView();
                                        }
                                    },
                                    scrollIntoView() {
                                        this.$refs.results.children[this.highlightedIndex].scrollIntoView({
                                          block: 'nearest',
                                          behavior: 'smooth'
                                        });
                                    },
                                    updateSelected(id, name) {
                                        this.selected = id;
                                        this.search = name;
                                        this.open = false;
                                        this.highlightedIndex = 0;
                                        },
                                    }"
                        >
                            <div x-on:value-selected="updateSelected($event.detail.id, $event.detail.name)" class="tw-w-full">
                                <label>Cari Produk (Barcode / Nama Produk)</label>
                                <input x-ref="query" class="form-control-lg form-control rounded-0"
                                       wire:model.debounce.600ms="search"
                                       x-on:keydown.arrow-down.stop.prevent="highlightNext()"
                                       x-on:keydown.arrow-up.stop.prevent="highlightPrevious()"
                                       x-on:keydown.enter.stop.prevent="$dispatch('value-selected', {
                                            id: $refs.results.children[highlightedIndex].getAttribute('data-result-id'),
                                            name: $refs.results.children[highlightedIndex].getAttribute('data-result-name')
                                      })">
                                <div class="tw-absolute tw-w-full tw-pr-10" x-show="open" x-on:click.away="open = false">
                                    <ul class="tw-relative dropdown-menu show tw-w-full" x-ref="results">
                                        @isset($results)
                                            @forelse($results as $index => $result)
                                                <li class="dropdown-item tw-cursor-pointer hover:tw-bg-slate-300"
                                                    wire:key="{{ $index }}"
                                                    x-on:click.stop="$dispatch('value-selected', {
                                                        id: {{ $result->id }},
                                                        name: '{{ $result->name }}'
                                                    })"
                                                    :class="{
                                                        'tw-bg-slate-400': {{ $index }} === highlightedIndex
                                                    }"
                                                    data-result-id="{{ $result->id }}"
                                                    data-result-name="{{ $result->name }}">
                                                    <span>
                                                      {{ $result->barcode . ' - ' . $result->name }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="dropdown-item tw-cursor-pointer hover:tw-bg-slate-300">Produk tidak ditemukan</li>
                                            @endforelse
                                        @endisset
                                    </ul>
                                </div>
                            </div>
                            @error('products') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                @if (session()->has('error'))
                    <div class="card-footer alert-danger">
                        {{session('error')}}
                    </div>
                @endif
            </div>
        </div>
        @if($transaction->details->count())
            <div class="row">
                <div class="col-lg-12">
                    <div class="card tw-z-50">
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@push('script')
    <script>
        function transferPage() {
            return {
                init: function(){

                }
            }
        }
    </script>
@endpush
