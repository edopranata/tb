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
            </div>
        </div>
        <div class="row no-print">
            <div class="col-md-9">
                <div class="card">
                    <div class="table-responsive" id="content-transaction-list">

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" id="content-transaction-summaries">

                </div>
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        <h5>Total tagihan</h5>--}}
{{--                        <div class="input-group input-group-lg mb-3">--}}
{{--                            <div class="input-group-prepend rounded-0">--}}
{{--                                <span class="input-group-text">Rp.</span>--}}
{{--                            </div>--}}
{{--                            <input wire:model="total" type="text" class="form-control form-control-lg rounded-0 rupiah" readonly>--}}
{{--                        </div>--}}
{{--                        @if($show_discount)--}}
{{--                            <h5>Potongan</h5>--}}
{{--                            <div class="input-group input-group-lg mb-3">--}}
{{--                                <div class="input-group-prepend rounded-0">--}}
{{--                                    <span class="input-group-text">Rp.</span>--}}
{{--                                </div>--}}
{{--                                <input wire:model="sell_discount" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" class="form-control form-control-lg rounded-0 rupiah">--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <h5>Total pembayaran</h5>--}}
{{--                        <div class="input-group input-group-lg mb-3">--}}
{{--                            <div class="input-group-prepend rounded-0">--}}
{{--                                <span class="input-group-text">Rp.</span>--}}
{{--                            </div>--}}
{{--                            <input wire:change="updatePayment()" wire:model.lazy="payment" onfocus="$(this).unmask()" onfocusout="$(this).mask('#,##0', {reverse: true})" type="text" class="form-control form-control-lg rounded-0 rupiah">--}}
{{--                            <div class="input-group-append rounded-0">--}}
{{--                                <button wire:click="fixedPayment()" class="btn btn-info btn-flat" type="button">Pas</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <h5>Uang kembali</h5>--}}
{{--                        <div class="input-group input-group-lg mb-3">--}}
{{--                            <div class="input-group-prepend rounded-0">--}}
{{--                                <span class="input-group-text">Rp.</span>--}}
{{--                            </div>--}}
{{--                            <input wire:model="refund" type="text" class="form-control form-control-lg rounded-0 rupiah" readonly>--}}
{{--                        </div>--}}
{{--                        <h5>Tanggal Jatuh Tempo</h5>--}}
{{--                        <div class="input-group input-group-lg mb-3">--}}
{{--                            <div class="input-group-prepend rounded-0">--}}
{{--                                <span class="input-group-text"><i class="fas fa-clock"></i></span>--}}
{{--                            </div>--}}
{{--                            <input wire:model="due_date" type="date" class="form-control form-control-lg rounded-0">--}}
{{--                        </div>--}}
{{--                        @error('due_date') <div class="text-sm text-muted text-red">{{ $message }}</div> @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}
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
                let contentTransactionSummaries = $('#content-transaction-summaries');

                let formTransaction = $('#form-transaction');

                let resetPages = function (){
                    btnSetToday.attr('disabled', false)
                    btnClearToday.attr('disabled', false)
                    btnSave.hide();
                    btnCancel.hide();
                    btnDraft.hide();
                    btnBeginTransaction.show();
                    inputInvoiceDate.val(null).attr('readonly', false);
                    inputCustomerID.val(null).trigger('change');
                    inputInvoiceNumber.val(null);
                    contentSearch.hide();
                    contentTransactionList.html('')
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

                    inputCustomerID.on("select2:selecting", function(e) {
                        $('.input-ajax').attr('readonly', true);
                        let customer_id = e.params.args.data.id;
                        changeCustomer(customer_id);
                    });
                }

                let sendRequest = function (params){
                    $('.input-ajax').attr('readonly', true);
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

                let updateProductList = function (id){
                    let product_price_id = $('#product_price_id-'+id).val()
                    let quantity = $('#quantity-'+id).val()
                    let sell_price = $('#sell_price-'+id).val()
                    let discount = $('#discount-'+id).val()
                    let params = {
                        'path': 'updateProductList',
                        'id': id,
                        'product_price_id': product_price_id,
                        'quantity': quantity,
                        'sell_price': sell_price,
                        'discount': discount,
                    }
                    sendRequest(params)
                }

                let changeCustomer = function (id){
                    let params = {
                        'path': 'changeCustomer',
                        'id': id
                    }
                    sendRequest(params)
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

                let setPrice = function (id, type = null, value = null){
                    let params = {
                        path: 'setPrice',
                        id: id,
                        price_type: type,
                        value: value
                    }

                    sendRequest(params)
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
                    // console.log(contents);
                    beginPages();
                    let data = contents.sells;

                    inputCustomerID.val(contents.customer_id).trigger('change')
                    inputInvoiceDate.val(contents.sells.invoice_date)
                    inputInvoiceNumber.val(contents.sells.invoice_number)

                    if(data.details.length) {
                        contentTransactionList.show();

                        let tableBody = '';
                        let opt = '';
                        let lClasses = '';
                        let rClasses = '';


                        data.details.forEach(function (item, key){
                            opt = '';
                            let i = 0;
                            lClasses = (item.price_category.toUpperCase() == 'CUSTOMER' || item.price_category.toUpperCase() == 'SELL') ? 'text-bold tw-bg-slate-700 tw-text-slate-100' : '';
                            rClasses = (item.price_category.toUpperCase() == 'WHOLESALE') ? 'text-bold tw-bg-slate-700 tw-text-slate-100' : '';
                            item.product.prices.forEach(function (quantity){
                                if(item.product_price_id === quantity.id){
                                    opt += `<option value="${quantity.id}" selected>${quantity.unit.name}</option>`
                                }else{
                                    opt += `<option value="${quantity.id}">${quantity.unit.name}</option>`
                                }
                            })

                            tableBody += `<tr>`
                            tableBody += `<td>${key + 1}</td>`
                            tableBody += `<td>${item.product_name}</td>`
                            tableBody += `<td>
                                              <div class="form-row">
                                                  <div class="form-group col-md-6">
                                                       <input onfocusout="transactionPage.updateProductList(${item.id})" type="text" id="quantity-${item.id}" name="quantity[${i}]" value="${item.quantity}" class="form-control mr-sm-2 input-ajax">
                                                  </div>
                                                  <div class="form-group col-md-6">
                                                       <select id="product_price_id-${item.id}" name="product_price_id[${i}]" class="form-control mr-sm-2 input-ajax">
                                                            ${opt}
                                                       </select>
                                                  </div>
                                              </div>
                                          </td>`

                            tableBody += `<td>
                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span onclick="transactionPage.setPrice(${item.id}, '${contents.customer_id ? 'CUSTOMER' : 'SELL'}')" class="input-group-text tw-cursor-pointer ${lClasses}">${contents.customer_id ? 'C' : 'S'}</span>
                                                  </div>
                                                  <input value="${item.sell_price}" id="sell_price-${item.id}" name="sell_price[${i}]" class="form-control text-right rupiah input-ajax" type="text"/>
                                                  <div class="input-group-append">
                                                      <span onclick="transactionPage.setPrice(${item.id}, 'WHOLESALE')" class="input-group-text tw-cursor-pointer ${rClasses}">G</span>
                                                  </div>
                                              </div>
                                          </td>`;

                            tableBody += `<td>
                                            <div class="form-group col-md-12">
                                                <input value="${item.discount}" name="discount[${i}]" class="form-control text-right rupiah input-ajax" type="text"/>
                                            </div>
                                          </td>`

                            tableBody += `<td>
                                            <div class="form-group col-md-12">
                                                <input readonly value="${item.total}" name="total[${i}]" class="form-control text-right rupiah" type="text"/>
                                            </div>
                                          </td>`

                            tableBody += `<td>
                                              <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                          </td>`
                            tableBody += `</tr>`;
                            i++;
                        })

                        let tableHead = `<table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th style="min-width: 10px">#</th>
                                            <th style="min-width: 200px;">Nama Produk</th>
                                            <th style="min-width: 250px;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        Jumlah Beli
                                                    </div>
                                                    <div class="col-md-6">
                                                        Satuan
                                                    </div>
                                                </div>
                                            </th>
                                            <th style="min-width: 200px;">Harga</th>
                                            <th style="min-width: 200px;">Disc</th>
                                            <th style="min-width: 200px;">Total Harga</th>
                                            <th style="min-width: 80px">Act</th>
                                        </tr>
                                        </thead>`;

                        let tableFoot = `</table>`
                        contentTransactionList.html(tableHead + '<tbody>' + tableBody + '</tbody>' + tableFoot)
                        $('.rupiah').number(true,2);
                    }
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
                    },
                    updateProductList: function (id){
                        updateProductList(id)

                    },
                    setPrice: function (id, type = null, value = null){
                        return setPrice(id, type, value)
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
