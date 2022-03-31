<div x-data="pagePrint">
    @if($sell)
        <div class="card card-body mt-2" style="width: 300px !important;">

            <h5 class="tw-p-0 tw-m-0 text-center">Toko Bangunan</h5>
            <h5 class="tw-p-0 tw-m-0 text-center">Jl. Raya Pekanbaru</h5>
            <hr class="bg-black tw-border-black tw-my-1">
            <table class="tw-table-fixed tw-border-collapse tw-text-xs">
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
            <div class="divide-y divide-slate-200 tw-text-xs">
                @foreach($sell->details as $detail)
                <div class="tw-flex">
                    <div class="tw-grow">
                        {{ $detail->product_name }}<br>
                        {{ $detail->quantity . ' ' . $detail->price->unit->name }} @ {{ $detail->buying_price . ' ' }}
                        @if($detail->discount > 0) Discount {{ $detail->discount }} @endif
                    </div>
                    <div class="tw-flex-none tw-w-20 text-right">
                        Rp. {{ number_format($detail->total) }}
                    </div>
                </div>
                <hr class="tw-bg-slate-500 tw-border-slate-500 tw-my-1">
                @endforeach
            </div>
            <div class="divide-y divide-slate-200 tw-text-xs">

                <div class="tw-flex tw-font-bold">
                    <div class="tw-grow text-right">Total</div>
                    <div class="tw-flex-none tw-w-20 text-right">{{ number_format($sell->details->sum('total') - $sell->details->sum('discount')) }}</div>
                </div>
                <div class="tw-flex tw-font-bold">
                    <div class="tw-grow text-right">Disc</div>
                    <div class="tw-flex-none tw-w-20 text-right">{{ number_format($sell->discount) }}</div>
                </div>
                <div class="tw-flex tw-font-bold tw-text-sm">
                    <div class="tw-grow text-right">Subtotal</div>
                    <div class="tw-flex-none tw-w-20 text-right">{{ number_format(($sell->details->sum('total') - $sell->details->sum('discount')) - $sell->discount) }}</div>
                </div>

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
