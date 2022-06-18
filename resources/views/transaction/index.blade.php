<x-base-layout>
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
    <form id="form-transaction">
        <input type="hidden" name="path" value="saveTransaction">
        <x-card.action>
            <x-card.action-link href="{{ route('pages.stock.index') }}" :btn="'light'">Kembali halaman utama</x-card.action-link>
            <button onclick="transactionPage.save()" type="button" class="btn btn-success btn-flat" id="btn-save">Simpan Data</button>
        </x-card.action>
        <!--
        # Content Print
        # Invoice print content here
        -->

        <!-- Default box -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tanggal Transaksi</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button id="btn-set-today" class="input-group-text" onclick="transactionPage.getToday()"><i class="fas fa-clock mr-2"></i> Hari ini </button>
                                    </div>
                                    <input id="transaction_date" name="transaction_date" type="date" class="form-control">
                                    <div class="input-group-append">
                                        <button id="btn-clear-today" class="input-group-text" onclick="$('#transaction_date').val(null)"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="text-sm text-muted text-red" id="label-invoice-date"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pelanggan</label>
                                    <select name="customer_id" id="customer-select" class="form-control">
                                        <option value="">Pilih Pelanggan</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer['id'] }}" @if($customer['id'] == $customer_id) selected @endif>{{ $customer['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="label-customer_id" class="text-sm text-muted text-red"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No Nota</label>
                                    <input name="invoice_number" type="text" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button id="btn-cancel-transaction" onclick="transactionPage.cancel()" type="button" class="btn btn-danger btn-flat">Batalkan Transaksi</button>
                                <button id="btn-save-draft" type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>

                                <button id="btn-begin-transaction" onclick="transactionPage.create()" type="button" class="btn btn-dark btn-flat" >Buat Transaksi</button>

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

                let inputCustomerID = $('#customer-select')
                let inputInvoiceDate = $('#transaction_date');
                let searchBarcode = $('#search-barcode');

                let formTransaction = $('#form-transaction');

                let resetPages = function (){
                    btnSetToday.attr('disabled', false)
                    btnClearToday.attr('disabled', false)
                    btnSave.hide();
                    btnCancel.hide();
                    btnDraft.hide();
                    btnBeginTransaction.show();
                }

                let beginPages = function (){
                    btnSetToday.attr('disabled', true)
                    btnClearToday.attr('disabled', true)
                    btnSave.show();
                    btnDraft.show();
                    btnCancel.show();
                    btnBeginTransaction.hide();
                }

                let setupPage = function () {
                    resetPages();

                    formTransaction.submit(function () {
                        return false;
                    })

                    // Select 2
                    inputCustomerID.select2();

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
                                return query;
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            }
                        }
                    });
                }

                let sendRequest = function (params){
                    $.ajax({
                        data : params,
                        success:function(data){
                            loadTemp();
                        },
                        error: function (err){
                            setMessage(err);
                        }
                    });
                }

                let createTransaction = function () {
                    let params = {
                        path: 'createTransaction',
                        invoice_date: inputInvoiceDate.val(),
                    }
                    sendRequest(params);
                }

                let cancelTransaction = function (){
                    let params = {
                        'path': 'cancelTransaction'
                    }
                    sendRequest(params);
                }

                let loadTemp = function (){
                    $.ajax({
                        data : {'path': 'loadTemp'},
                        success:function(data){
                            if(data){
                                // tampilkan data pembelian
                                // showProductList(data);
                            }else{
                                resetPages();
                            }
                        }
                    });
                }

                let setMessage = function (res){
                    let data = res.responseJSON
                    if("errors" in data){
                        let err = data.errors
                        console.info(err);
                        // if("invoice_date" in err){
                        //     labelInvoiceDate.text(err.invoice_date[0]).show()
                        // }
                        // if("supplier_id" in err){
                        //     labelSupplierID.text(err.supplier_id[0]).show()
                        // }
                        // if("invoice_number" in err){
                        //     labelInvoiceNumber.text(err.invoice_number[0]).show()
                        // }
                        //
                        // if("payment" in err){
                        //     labelPayment.text(err.payment[0]).show()
                        // }
                        //
                        // let productList = $('.validation-array').toArray()
                        // productList.forEach(function (item, i){
                        //     if(err.hasOwnProperty("buying_price." + i)){
                        //         $('#label-quantity-' + i).text(err['quantity.' + i]).show()
                        //         $('#label-buying_price-' + i).text(err['buying_price.' + i]).show()
                        //     }
                        // })
                    }

                }

                return {
                    init : function (){
                        setupPage();
                    },
                    getToday: function (){

                    },
                    create: function (){
                        createTransaction()
                    },
                    cancel: function (){

                    }
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
