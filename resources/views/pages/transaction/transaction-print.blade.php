<div x-data="pagePrint">
    @if($sell)
        <div class="card card-body mt-0 tw-text-[45px] tw-font-mono" >
            <div class="tw-p-0 tw-m-0 tw-flex tw-justify-center">
                <img src="{{ asset('dist/img/sbr-logo.png') }}">
            </div>
            <div class="tw-p-0 tw-m-0 text-center">Toko Bangunan SBR</div>
            <div class="tw-p-0 tw-m-0 text-center">Building Material</div>
            <div class="tw-p-0 tw-m-0 text-center">Pasaman Barat</div>
            <hr class="bg-black tw-border-black tw-my-1">
            <table class="tw-table-fixed tw-border-collapse">
                <tbody>
                <tr class="tw-py-0">
                    <td class="tw-py-0">TRX-ID</td>
                    <td class="tw-py-0">: #{{ $sell->id }}</td>
                </tr>
                <tr class="tw-py-0">
                    <td class="tw-py-0">TRX-USR</td>
                    <td class="tw-py-0">: {{ $sell->user->username }}</td>
                </tr>
                <tr class="tw-py-0">
                    <td class="tw-py-0">TRX-INV</td>
                    <td class="tw-py-0">: {{ $sell->invoice_number }}</td>
                </tr>
                </tbody>
            </table>
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
@push('js')
    <script>
        function pagePrint() {
            return {

                init: function () {

                }
            }
        }
    </script>

@endpush
