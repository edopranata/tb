<x-base-layout>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Inventori Produk</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Beranda</a></li>
                            <li class="breadcrumb-item active">Inventori Produk</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <form id="form-transaction">
        <input type="hidden" name="path" value="saveTransaction">
        <x-card.action>
            <button onclick="transactionPage.save()" type="button" class="btn btn-success btn-flat" id="btn-save">Simpan Data</button>
        </x-card.action>
        <!-- Default box -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Produk Masuk (Inventori)</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Invoice</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button id="btn-set-today" class="input-group-text" onclick="transactionPage.getToday()"><i class="fas fa-clock mr-2"></i> Hari ini </button>
                                        </div>
                                        <input id="invoice_date" name="invoice_date" type="date" class="form-control">
                                        <div class="input-group-append">
                                            <button id="btn-clear-today" class="input-group-text" onclick="$('#invoice_date').val(null)"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="text-sm text-muted text-red" id="label-invoice-date"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select id="supplier_id" name="supplier_id" class="form-control">
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-sm text-muted text-red" id="label-supplier-id"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No Invoice</label>
                                    <input id="invoice_number" name="invoice_number" type="text" class="form-control" placeholder="No Invoice / Nota">
                                    <div class="text-sm text-muted text-red" id="label-invoice-number"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button id="btn-cancel-transaction" onclick="transactionPage.cancel()" type="button" class="btn btn-danger btn-flat">Batalkan Transaksi</button>
                                <button id="btn-save-draft" type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>

                                <button id="btn-begin-transaction" onclick="transactionPage.create()" type="button" class="btn btn-dark btn-flat" >Add Product</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="content-search">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cari Produk (Barcode / Nama Produk)</label>
                                    <select id="search-barcode" style="width: 100%" name="search" class="form-control"></select>
                                    {{--                                <input type="text" class="form-control-lg form-control rounded-0"/>--}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Pembayaran</label>
                                    <input name="bill" id="bill" type="text" class="form-control rupiah" placeholder="Total Pembayaran" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah Pembayaran</label>
                                    <input name="payment" id="payment"  type="text" class="form-control rupiah input-ajax" placeholder="Jumlah Pembayaran">
                                    <div class="text-sm text-muted text-red" id="label-payment"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sisa Tagihan</label>
                                    <input name="fund" id="fund" type="text" class="form-control rupiah" placeholder="Sisa Tagihan" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" id="content-transaction-list">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card tw-z-50">
                                    <div class="card-body px-0">
                                        <div class="table-responsive" id="transaction-list">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('js')
        <script>
            let transactionPage = function () {
                let btnSave = $('#btn-save');
                let btnCancel = $('#btn-cancel-transaction');
                let btnDraft = $('#btn-save-draft');
                let btnBeginTransaction = $('#btn-begin-transaction');
                let btnSetToday = $('#btn-set-today');
                let btnClearToday = $('#btn-clear-today');


                let labelInvoiceDate = $('#label-invoice-date');
                let labelSupplierID = $('#label-supplier-id');
                let labelInvoiceNumber = $('#label-invoice-number');
                let labelPayment = $('#label-payment');

                let searchBarcode = $('#search-barcode');

                let formTransaction = $('#form-transaction');
                let setupPage = function (){

                    formTransaction.submit(function (){
                        return false;
                    })
                    // Search Barcode
                    searchBarcode.select2({
                        placeholder: 'Cari Produk (Barcode / Nama Produk)',
                        minimumInputLength: 2,
                        ajax: {
                            url: '',
                            delay: 500,
                            data: function (params) {
                                let query = {
                                    q: params.term,
                                    path: 'searchProduct'
                                }
                                // Query parameters will be ?search=[term]&type=public
                                return query;
                            },
                            processResults: function (data) {
                                // Transforms the top-level key of the response object from 'items' to 'results'
                                return {
                                    results: data
                                };
                            }
                        }
                    });


                let sendRequest = function (params){
                    $.ajax({
                        data : params,
                        success:function(data){
                            console.log(data);

                        },
                        error: function (err){
                            setMessage(err);
                        }
                    });
                }

                let setMessage = function (res){
                    let data = res.responseJSON
                    if("errors" in data){
                        let err = data.errors
                        if("invoice_date" in err){
                            labelInvoiceDate.text(err.invoice_date[0]).show()
                        }
                        if("supplier_id" in err){
                            labelSupplierID.text(err.supplier_id[0]).show()
                        }
                        if("invoice_number" in err){
                            labelInvoiceNumber.text(err.invoice_number[0]).show()
                        }

                        if("payment" in err){
                            labelPayment.text(err.payment[0]).show()
                        }

                        let productList = $('.validation-array').toArray()
                        productList.forEach(function (item, i){
                            if(err.hasOwnProperty("buying_price." + i)){
                                $('#label-quantity-' + i).text(err['quantity.' + i]).show()
                                $('#label-buying_price-' + i).text(err['buying_price.' + i]).show()
                            }
                        })
                    }

                }

                return {
                    init : function (){
                        setupPage();
                    },
                };
            }();
            $(document).ready(function (){
                transactionPage.init();
            });
            $(document).ajaxStop(function() {
                $('.input-ajax').removeAttr('readonly')
            });
        </script>
    @endpush

</x-base-layout>
