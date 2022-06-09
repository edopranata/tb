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

    <x-card.action>
        <x-card.action-button id="btn-save">Simpan Data</x-card.action-button>
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
                                        <button id="btn-set-today" class="input-group-text"><i class="fas fa-clock mr-2"></i> Hari ini </button>
                                    </div>
                                    <input id="invoice_date" name="invoice_date" type="date" class="form-control">
                                    <div class="input-group-append">
                                        <button class="input-group-text"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
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
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No Invoice</label>
                                <input id="invoice_number" name="invoice_number" type="text" class="form-control" placeholder="No Invoice / Nota">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                                <button id="btn-cancel-transaction" type="button" class="btn btn-danger btn-flat">Batalkan Transaksi</button>
                                <button id="btn-save-draft" type="button" class="btn btn-warning btn-flat">Simpan sebagai draf kembali ke halaman utama</button>

                                <button id="btn-begin-transaction" type="button" class="btn btn-dark btn-flat" >Add Product</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Cari Produk (Barcode / Nama Produk)</label>
                                <select id="search-barcode" name="search" class="form-control"></select>
{{--                                <input type="text" class="form-control-lg form-control rounded-0"/>--}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total Pembayaran</label>
                                <input name="bill" id="bill" type="text" class="form-control rupiah" placeholder="Total Pembayaran" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Pembayaran</label>
                                <input name="payment" id="payment"  type="text" class="form-control rupiah" placeholder="Jumlah Pembayaran">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sisa Tagihan</label>
                                <input name="fund" id="fund" type="text" class="form-control rupiah" placeholder="Sisa Tagihan" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
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

    @push('js')
        <script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@master/dist/latest/bootstrap-autocomplete.min.js"></script>
        <script>
            let inventoriesPage = function () {
                let btnSave = $('#btn-save');
                let btnCancel = $('#btn-cancel-transaction');
                let btnDraft = $('#btn-save-draft');
                let btnBeginTransaction = $('#btn-begin-transaction');

                let inputInvoiceDate = $('#invoice_date');
                let inputSupplierId = $('#supplier_id');
                let inputInvoiceNumber = $('#invoice_number');
                let inputBill = $('#bill');
                let inputPayment = $('#payment');
                let inputFund = $('#fund');

                let setupPage = function (){
                    $('#supplier_id').select2();

                    // Set tampilan awal
                    resetPages();

                    // Search Barcode
                    $('#search-barcode').select2({
                        placeholder: 'Cari Produk (Barcode / Nama Produk)',
                        minimumInputLength: 2,
                        ajax: {
                            url: '',
                            delay: 500,
                            data: function (params) {
                                var query = {
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

                    $('#search-barcode').on("select2:selecting", function(e) {
                        let productID = e.params.args.data.id;
                        getProductID(productID);
                    });
                }
                let getProductID = function (id){
                    $.ajax({
                        data : {'path': 'getProductID', 'id': id},
                        success:function(){
                            loadTemp();
                        }
                    });
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
                    // tampilkan data
                    inputBill.val(contents.bill)
                    inputPayment.val(contents.payment)
                    inputFund.val(contents.fund)
                    inputInvoiceDate.val(data.invoice_date).attr('readonly', true)
                    inputInvoiceNumber.val(data.invoice_number).attr('readonly', true)
                    inputSupplierId.val(data.supplier_id).prop('disabled', true).trigger('change')

                    if(data.details){
                        var tebleBody = '';
                        var opt = '';
                        data.details.forEach(function (item, key){
                            console.log(item)
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
                                                    <input name="products[]['id']" class="form-control mr-sm-2" type="hidden" min="1"/>
                                                    <input value="${item.quantity}" name="products[]['quantity']" class="form-control mr-sm-2" type="number" min="1"/>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <select name="products[]['product_price_id']" class="form-control mr-sm-2">${opt}</select>
                                                </div>
                                            </div>
                                         </td>`
                            tebleBody += `<td>
                                                <div class="form-group col-md-12">
                                                    <div class="input-group">
                                                        <input value="${item.product_price_quantity}" name="products[]['product_price_quantity']" class="form-control" type="text" readonly/>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">${item.product.unit.name}</span>
                                                        </div>
                                                    </div>
                                                </div>
                            </td>`
                            tebleBody += `<td>
                                                <div class="form-group col-md-12">
                                                    <input value="${item.buying_price}" name="products[]['buying_price']" class="form-control rupiah mr-sm-2 text-right" type="text" min="1"/>
                                                </div>
</td>`
                            tebleBody += `<td>${item.product_name}</td>`
                            tebleBody += `<td>${item.product_name}</td>`

                            tebleBody += `</tr>`
                        })
                        var tableHead = `<table class="table table-hover">
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
                                        <th style="min-width: 150px;">Total</th>
                                        <th style="min-width: 300px;">Harga Modal</th>
                                        <th style="min-width: 300px;">Total Harga Modal</th>
                                        <th style="min-width: 130px">Label</th>
                                    </tr>
                                    </thead>`;

                        var tableFoot = `<tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total</th>
                                        <th>
                                            <div class="form-group col-md-12">
                                                <input value="" class="form-control rupiah mr-sm-2 text-right" type="text" disabled/>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="form-group col-md-12">
                                                <input value="" class="form-control rupiah mr-sm-2 text-right" type="text" disabled/>
                                            </div>
                                        </th>
                                        <th>Label</th>
                                    </tr>
                                    </tfoot>
                                </table>`

                        $('#transaction-list').html(tableHead + `<tbody>` + tebleBody + `</tbody>` . tableFoot)
                    }


                }

                let resetPages = function (){
                    btnSave.hide();
                    btnCancel.hide();
                    btnDraft.hide();
                    btnBeginTransaction.show();
                }

                let beginPages = function (){
                    btnSave.show();
                    btnDraft.show();
                    btnCancel.show();
                    btnBeginTransaction.hide();
                }

                return {
                    init : function (){
                        loadTemp();
                        setupPage();
                    }
                };
            }();
            $(document).ready(function (){
                inventoriesPage.init();
            });
        </script>
    @endpush

</x-base-layout>
