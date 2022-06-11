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
            <button onclick="inventoriesPage.save()" type="button" class="btn btn-success btn-flat" id="btn-save">Simpan Data</button>
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
                                            <button id="btn-set-today" class="input-group-text" onclick="inventoriesPage.getToday()"><i class="fas fa-clock mr-2"></i> Hari ini </button>
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
                                <button id="btn-cancel-transaction" onclick="inventoriesPage.cancel()" type="button" class="btn btn-danger btn-flat">Batalkan Transaksi</button>
                                <button id="btn-save-draft" type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>

                                <button id="btn-begin-transaction" onclick="inventoriesPage.create()" type="button" class="btn btn-dark btn-flat" >Add Product</button>
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
            let inventoriesPage = function () {
                let btnSave = $('#btn-save');
                let btnCancel = $('#btn-cancel-transaction');
                let btnDraft = $('#btn-save-draft');
                let btnBeginTransaction = $('#btn-begin-transaction');
                let btnSetToday = $('#btn-set-today');
                let btnClearToday = $('#btn-clear-today');

                let inputInvoiceDate = $('#invoice_date');
                let inputSupplierId = $('#supplier_id');
                let inputInvoiceNumber = $('#invoice_number');
                let inputBill = $('#bill');
                let inputPayment = $('#payment');
                let inputFund = $('#fund');

                let labelInvoiceDate = $('#label-invoice-date');
                let labelSupplierID = $('#label-supplier-id');
                let labelInvoiceNumber = $('#label-invoice-number');
                let labelPayment = $('#label-payment');

                let contentSearch = $('#content-search');
                let searchBarcode = $('#search-barcode');
                let contentTransactionList = $('#content-transaction-list');

                let formTransaction = $('#form-transaction');
                let setupPage = function (){

                    $('#supplier_id').select2();

                    // Set tampilan awal
                    resetPages();

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

                    // select product
                    searchBarcode.on("select2:selecting", function(e) {
                        $('.input-ajax').attr('readonly', true);
                        let productID = e.params.args.data.id;
                        searchBarcode.val(null).trigger('change');
                        getProductID(productID);

                    });


                }

                let createTransaction = function (){
                    let params = {
                        'path': 'createTransaction',
                        'supplier_id': inputSupplierId.val(),
                        'invoice_number': inputInvoiceNumber.val(),
                        'invoice_date': inputInvoiceDate.val(),
                    }
                    sendRequest(params)
                }

                let cancelTransaction = function (){
                    let params = {
                        'path': 'cancelTransaction'
                    }
                    sendRequest(params);
                }

                let saveTransaction = function () {
                    let params = formTransaction.serialize()
                    sendRequest(params)
                }

                let getProductID = function (id){
                    let params = {
                        'path': 'getProductID',
                        'id': id
                    }
                    sendRequest(params)
                }

                let editProduct = function (id ){
                    let product_price_id = $('#product_price_id-'+id).val()
                    let quantity = $('#quantity-'+id).val()
                    let buying_price = $('#buying_price-'+id).val()
                    let params = {
                        'path': 'editProduct',
                        'id': id,
                        'product_price_id': product_price_id,
                        'quantity': quantity,
                        'buying_price': buying_price,
                    }
                    $('.input-ajax').attr('readonly', true)
                    sendRequest(params)
                }

                let removeProduct = function (id ){
                    let result = confirm("Delete product dari daftar?");
                    let params = {
                        'path': 'removeProduct',
                        'id': id,
                    }

                    if (result === true) {
                        sendRequest(params);
                    } else {
                        return false;
                    }

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

                // Load temporary transaction
                let loadTemp = function () {
                    $.ajax({
                        data : {'path': 'loadTemp'},
                        success:function(data){
                            if(data.purchase){
                                // tampilkan data pembelian
                                showProductList(data);
                            }else{
                                resetPages();
                            }
                        }
                    });
                }

                let showProductList = function (contents){
                    // set tombol
                    beginPages();

                    let data = contents.purchase;
                    let total = contents.products.reduce((partialSum, a) => partialSum + a.total, 0);

                    // tampilkan data
                    inputBill.val(contents.bill)
                    inputPayment.val(contents.payment)
                    inputFund.val(contents.fund)
                    inputInvoiceDate.val(data.invoice_date).attr('readonly', true)
                    inputInvoiceNumber.val(data.invoice_number).attr('readonly', true)
                    inputSupplierId.val(data.supplier_id).prop('disabled', true).trigger('change')

                    if(data.details.length){
                        contentTransactionList.show()
                        let tebleBody = '';
                        let opt = '';
                        let i = 0;
                        data.details.forEach(function (item, key){
                            opt = '';
                            item.product.prices.forEach(function (quantity){
                                if(item.product_price_id === quantity.id){
                                    opt += `<option value="${quantity.id}" selected>${quantity.unit.name}</option>`
                                }else{
                                    opt += `<option value="${quantity.id}">${quantity.unit.name}</option>`
                                }
                            })

                            tebleBody += `<tr>`
                            tebleBody += `<td>${key + 1}</td>`
                            tebleBody += `<td>${item.product_name}</td>`
                            tebleBody += `<td>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <input name="id[${i}]" class="form-control mr-sm-2" type="hidden" min="1"/>
                                                    <input id="quantity-${item.id}" value="${item.quantity}" name="quantity[${i}]" onfocusout="inventoriesPage.editProductID(${item.id})" class="form-control mr-sm-2 input-ajax" type="number" min="1"/>
                                                    <div class="text-sm text-muted text-red validation-array" id="label-quantity-${i}"></div>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <select id="product_price_id-${item.id}" onchange="inventoriesPage.editProductID(${item.id})" name="product_price_id[${i}]" class="form-control mr-sm-2 input-ajax">${opt}</select>
                                                </div>
                                            </div>
                                          </td>`
                            tebleBody += `<td class="">
                                                <div class="form-group col-md-12">
                                                    <div class="input-group">
                                                        <input value="${item.product_price_quantity}" name="product_price_quantity[${i}]" class="form-control" type="text" readonly/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">${item.product.unit.name}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                          </td>`
                            tebleBody += `<td>
                                                <div class="form-group col-md-12">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp. @ ${item.price.unit.name}</span>
                                                        </div>
                                                        <input onfocusout="inventoriesPage.editProductID(${item.id})" id="buying_price-${item.id}" value="${item.buying_price}" name="buying_price[${i}]" class="form-control rupiah mr-sm-2 text-right input-ajax" type="text" min="1"/>
                                                        <div class="text-sm text-muted text-red" id="label-buying_price-${i}"></div>
                                                    </div>
                                                </div>
                                          </td>`
                            tebleBody += `<td>
                                                <div class="form-group col-md-12">
                                                    <input value="${item.total}" name="total[${i}]" class="form-control rupiah mr-sm-2 text-right" type="text" readonly/>
                                                </div>
                                          </td>`
                            tebleBody += `<td>
                                            <button onclick="inventoriesPage.removeProductID(${item.id})" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                          </td>`

                            tebleBody += `</tr>`;

                            i++;
                        })
                        let tableHead = `<table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 10px">#</th>
                                        <th style="min-width: 300px;">Nama Produk</th>
                                        <th style="min-width: 300px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Jumlah Beli
                                                </div>
                                                <div class="col-md-6">
                                                    Satuan
                                                </div>
                                            </div>
                                        </th>
                                        <th style="min-width: 150px;" class="">Quantity</th>
                                        <th style="min-width: 300px;">Harga Modal</th>
                                        <th style="min-width: 300px;">Total</th>
                                        <th style="min-width: 130px">Hapus</th>
                                    </tr>
                                    </thead>`;

                        let tableFoot = `<tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <th>
                                            <div class="form-group col-md-12">
                                                <input value="${total}" class="form-control rupiah mr-sm-2 text-right" type="text" disabled/>
                                            </div>
                                        </th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>`

                        $('#transaction-list').html(tableHead + `<tbody>` + tebleBody + `</tbody>` + tableFoot)
                        $('.rupiah').number(true,2);
                        searchBarcode.val(null).trigger('change')
                    }else{
                        contentTransactionList.hide();
                    }
                }


                let resetPages = function (){
                    btnSetToday.attr('disabled', false)
                    btnClearToday.attr('disabled', false)
                    inputBill.val(null)
                    inputPayment.val(null)
                    inputFund.val(null)
                    inputInvoiceDate.val(null).removeAttr('readonly')
                    inputInvoiceNumber.val(null).removeAttr('readonly')
                    inputSupplierId.val(null).prop('disabled', false).trigger('change')
                    btnSave.hide();
                    btnCancel.hide();
                    btnDraft.hide();
                    btnBeginTransaction.show();
                    contentSearch.hide()
                    labelInvoiceDate.hide();
                    labelSupplierID.hide();
                    labelInvoiceNumber.hide();
                    labelPayment.hide();
                }

                let beginPages = function (){
                    btnSetToday.attr('disabled', true)
                    btnClearToday.attr('disabled', true)
                    btnSave.show();
                    btnDraft.show();
                    btnCancel.show();
                    btnBeginTransaction.hide();
                    labelInvoiceDate.hide();
                    labelSupplierID.hide();
                    labelInvoiceNumber.hide();
                    contentSearch.show()
                    labelPayment.hide();
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

                return {
                    init : function (){
                        loadTemp();
                        setupPage();
                    },
                    getToday: function (){
                        getCurrentDate();
                    },
                    create: function (){
                        createTransaction();
                    },
                    cancel: function (){
                        cancelTransaction();
                    },
                    save: function (){
                        saveTransaction();
                    },
                    editProductID: function (id){
                        editProduct(id);
                    },
                    removeProductID: function (id){
                        removeProduct(id);
                    }

                };
            }();
            $(document).ready(function (){
                inventoriesPage.init();
            });
            $(document).ajaxStop(function() {
                $('.input-ajax').removeAttr('readonly')
            });
        </script>
    @endpush

</x-base-layout>
