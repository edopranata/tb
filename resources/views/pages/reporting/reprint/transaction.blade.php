<div x-data="pagePrint()">
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Reprint Struk Transaksi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Reprint struk transaksi</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <x-card.action>
        <div class="form-group mb-0">
            <div class="input-group input-group-lg">
                <input wire:model.defer="invoice" type="text" class="form-control form-control-lg" placeholder="Invoice Number">
                <div class="input-group-append">
                    <button type="button" wire:click="printInvoice()" class="input-group-text">Print Struk</button>
                </div>
            </div>
        </div>
        <div class="form-group mb-0">
            <button type="button" class="btn btn-light btn-lg" wire:click="lastTransaction()">Print Struk Terakhir</button>
        </div>
    </x-card.action>
    <div class="row no-print">
        @error('invoice')
        <div class="col-12">
            <div class="card card-header alert-danger rounded-0">
                {{ $message }}
            </div>
        </div>
        @enderror

        <div class="col-lg-4">
            @if($sell)
                <div class="card card-body m-0 p-0 tw-text-[22px] tw-font-mono" >
                    @error('invoice')
                        <div class="card-header alert-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="tw-p-0 tw-m-0 tw-flex tw-justify-center">
                        <img class="tw-h-48" src="{{ asset('dist/img/sbr-logo.png') }}">
                    </div>
                    <div class="tw-p-0 tw-m-0 text-center">Toko Bangunan SBR</div>
                    <div class="tw-p-0 tw-m-0 text-center">Building Material</div>
                    <div class="tw-p-0 tw-m-0 text-center">Pasaman Barat</div>
                    <hr class="bg-black tw-border-black tw-my-1">
                    <div class="row tw-text-[18px]">
                        <div class="col-6">
                            ID# {{ $sell->id }}
                        </div>
                        <div class="col-6">
                            INV# {{ $sell->invoice_number }}
                        </div>

                        <div class="col-6">
                            USER# {{ $sell->user->username }}
                        </div>
                        <div class="col-6">
                            DATE# {{ $sell->invoice_date->format('d-m-Y H:i:s') }}
                        </div>
                    </div>

                    <hr class="bg-black tw-border-black tw-my-1">
                    <div class="divide-y divide-slate-200">
                        @foreach($sell->details as $detail)
                            <div class="tw-flex">
                                <div class="tw-grow">
                                    {{ $detail->product_name }}<br>
                                    {{ $detail->quantity . ' ' . $detail->price->unit->name }} @ Rp. {{ number_format($detail->buying_price) . ' ' }}
                                    @if($detail->discount > 0) Discount Rp. {{ number_format($detail->discount) }} @endif
                                </div>
                                <div class="tw-flex-none tw-w-auto text-right">
                                    Rp. {{ number_format($detail->total) }}
                                </div>
                            </div>
                            <hr class="tw-bg-slate-500 tw-border-slate-500 tw-my-1">
                        @endforeach
                    </div>
                    <div class="divide-y divide-slate-200">

                        <div class="tw-flex tw-font-bold">
                            <div class="tw-grow text-right">Total</div>
                            <div class="tw-flex-none tw-w-[15rem] text-right">Rp. {{ number_format($sell->details->sum('total') - $sell->details->sum('discount')) }}</div>
                        </div>
                        <div class="tw-flex tw-font-bold">
                            <div class="tw-grow text-right">Disc</div>
                            <div class="tw-flex-none tw-w-[15rem] text-right border-bottom">Rp. {{ number_format($sell->discount) }}</div>
                        </div>
                        <div class="tw-flex tw-font-bold tw-text-[24px]">
                            <div class="tw-grow text-right">Subtotal</div>
                            <div class="tw-flex-none tw-w-[15rem] text-right">Rp. {{ number_format(($sell->details->sum('total') - $sell->details->sum('discount')) - $sell->discount) }}</div>
                        </div>

                    </div>
                    <hr>
                    <div class="tw-flex tw-flex-col tw-items-center">
                        <div>*** Terima Kasih ***</div><br>
                        <div><i class="fab fa-facebook"></i></i> SBRPASAMAN BARAT</div>
                        <div><i class="fab fa-whatsapp-square"></i> SBRPASAMAN BARAT</div>
                        <div><i class="fab fa-instagram-square"></i> SBRPASAMAN BARAT</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="row d-none d-print-block">
        <div class="col-lg-12">
            @if($sell)
                <div class="card card-body mt-0 tw-text-[45px] tw-font-mono" >
                    <div class="tw-p-0 tw-m-0 tw-flex tw-justify-center">
                        <img src="{{ asset('dist/img/sbr-logo.png') }}">
                    </div>
                    <div class="tw-p-0 tw-m-0 text-center">Toko Bangunan SBR</div>
                    <div class="tw-p-0 tw-m-0 text-center">Building Material</div>
                    <div class="tw-p-0 tw-m-0 text-center">Pasaman Barat</div>
                    <hr class="bg-black tw-border-black tw-my-1">
                    <div class="row tw-text-[36px]">
                        <div class="col-6">
                            ID #{{ $sell->id }}
                        </div>
                        <div class="col-6">
                            INV #{{ $sell->invoice_number }}
                        </div>

                        <div class="col-6">
                            USER #{{ $sell->user->username }}
                        </div>
                        <div class="col-6">
                            DATE #{{ $sell->invoice_date->format('d-m-Y') }}
                        </div>

                        <div class="col-6">
                            &nbsp;
                        </div>
                        <div class="col-6">
                            TIME #{{ $sell->invoice_date->format('H:i:s') }}
                        </div>
                    </div>
                    <hr class="bg-black tw-border-black tw-my-1">
                    <div class="divide-y divide-slate-200">
                        @foreach($sell->details as $detail)
                            <div class="tw-flex">
                                <div class="tw-grow">
                                    {{ $detail->product_name }}<br>
                                    {{ $detail->quantity . ' ' . $detail->price->unit->name }} @ Rp. {{ number_format($detail->buying_price) . ' ' }}
                                    @if($detail->discount > 0) Discount Rp. {{ number_format($detail->discount) }} @endif
                                </div>
                                <div class="tw-flex-none tw-w-auto text-right">
                                    Rp. {{ number_format($detail->total) }}
                                </div>
                            </div>
                            <hr class="tw-bg-slate-500 tw-border-slate-500 tw-my-1">
                        @endforeach
                    </div>
                    <div class="divide-y divide-slate-200">

                        <div class="tw-flex tw-font-bold">
                            <div class="tw-grow text-right">Total</div>
                            <div class="tw-flex-none tw-w-[20rem] text-right">{{ number_format($sell->details->sum('total') - $sell->details->sum('discount')) }}</div>
                        </div>
                        <div class="tw-flex tw-font-bold">
                            <div class="tw-grow text-right">Disc</div>
                            <div class="tw-flex-none tw-w-[20rem] text-right border-bottom">{{ number_format($sell->discount) }}</div>
                        </div>
                        <div class="tw-flex tw-font-bold tw-text-[48px]">
                            <div class="tw-grow text-right">Subtotal</div>
                            <div class="tw-flex-none tw-w-[20rem] text-right">{{ number_format(($sell->details->sum('total') - $sell->details->sum('discount')) - $sell->discount) }}</div>
                        </div>

                    </div>
                    <hr>
                    <div class="tw-flex tw-flex-col tw-items-center">
                        <div>*** Terima Kasih ***</div><br>
                        <div><i class="fab fa-facebook"></i></i> SBRPASAMAN BARAT</div>
                        <div><i class="fab fa-whatsapp-square"></i> SBRPASAMAN BARAT</div>
                        <div><i class="fab fa-instagram-square"></i> SBRPASAMAN BARAT</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('js')
    <script>
        window.addEventListener('pagePrint', () => {
            window.print();
        })

        function pagePrint() {
            return {
                init:function (){

                }
            }
        }
    </script>
@endpush
