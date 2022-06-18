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
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="label-customer_id" class="text-sm text-muted text-red"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No Nota</label>
                                    <input id="invoice_number" name="invoice_number" type="text" class="form-control" readonly>
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

        <div class="row">
            <div class="col-lg-12" id="content-search">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cari Barcode </label>
                                    <input id="barcode" style="width: 100%" name="search-barcode" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Cari Produk (Barcode / Nama Produk)</label>
                                    <select id="search-barcode" style="width: 100%" name="search" class="form-control"></select>
                                    {{--                                <input type="text" class="form-control-lg form-control rounded-0"/>--}}
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

                let inputCustomerID = $('#customer-select')
                let inputInvoiceDate = $('#transaction_date');
                let inputInvoiceNumber = $('#invoice_number');

                let inputSearchBarcode = $('#search-barcode');
                let inputBarcode = $('#barcode');

                let contentSearch = $('#content-search');
                let contentTransactionList = $('#content-transaction-list');

                let formTransaction = $('#form-transaction');

                let resetPages = function (){
                    btnSetToday.attr('disabled', false)
                    btnClearToday.attr('disabled', false)
                    btnSave.hide();
                    btnCancel.hide();
                    btnDraft.hide();
                    btnBeginTransaction.show();
                    inputInvoiceDate.val(null).attr('disabled', false);
                    inputCustomerID.val(null).trigger('change');
                    inputInvoiceNumber.val(null);
                    contentSearch.hide();
                }

                let beginPages = function (){
                    btnSetToday.attr('disabled', true)
                    btnClearToday.attr('disabled', true)
                    btnSave.show();
                    btnDraft.show();
                    btnCancel.show();
                    btnBeginTransaction.hide();
                    inputInvoiceDate.attr('disabled', true);
                    contentSearch.show();
                }

                let getCurrentDate = function (){
                    $.ajax({
                        url: '',
                        data: {'path': 'getToday'},
                        success:function (data){
                            inputInvoiceDate.val(data)
                        }
                    })
                }

                let setupPage = function () {
                    resetPages();

                    formTransaction.submit(function () {
                        return false;
                    })

                    // Select 2
                    inputCustomerID.select2();

                    inputBarcode.keypress(function (e){
                        if(e.keyCode == 13){
                            getProductID('barcode', inputBarcode.val());
                        }
                    })

                    // Search Barcode
                    inputSearchBarcode.select2({
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

                    // select product
                    inputSearchBarcode.on("select2:selecting", function(e) {
                        $('.input-ajax').attr('readonly', true);
                        let productID = e.params.args.data.id;
                        getProductID('id', productID);

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

                let getProductID = function (field, id){
                    let price_type = (inputCustomerID.val() === '') ? 'sell' : 'customer'
                    let params = {
                        'path': 'getProductID',
                        'field': field,
                        'price_type': price_type,
                        'id': id
                    }
                    sendRequest(params)
                }

                let createTransaction = function () {
                    let params = {
                        path: 'createTransaction',
                        invoice_date: inputInvoiceDate.val(),
                        customer_id: inputCustomerID.val(),
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
                            if(data.sells){
                                // tampilkan data pembelian
                                showTransactionList(data);
                            }else{
                                resetPages();
                            }
                            inputSearchBarcode.val(null).trigger('change');
                            inputBarcode.val(null);
                        }
                    });
                }

                let showTransactionList = function (contents){
                    console.info(contents)
                    inputInvoiceDate.val(contents.sells.invoice_date)
                    inputInvoiceNumber.val(contents.sells.invoice_number)
                    beginPages();
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
                        loadTemp();
                        setupPage();
                    },
                    getToday: function (){
                        getCurrentDate();
                    },
                    create: function (){
                        createTransaction()
                    },
                    cancel: function (){
                        cancelTransaction();
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
