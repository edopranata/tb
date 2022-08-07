<x-base-layout>
    <x-slot name="breadcrumb">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product Price Maintenance</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Product Price Maintenance</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </x-slot>
    <div class="row">
        @if(session('status'))
            <div class="col-12">
                <div class="alert alert-{{ session('status') }} rounded-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! session('message') !!}
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Harga Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Barcode</label>
                                <input disabled value="{{ $product->barcode }}" type="text" class="form-control" placeholder="Barcode / Kode Produk">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input disabled value="{{ $product->name }}" type="text" class="form-control" placeholder="Nama Produk">
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>Deskripsi / Keterangan Produk</label>
                        <textarea disabled class="form-control" placeholder="Deskripsi / keterangan produk">{{ $product->description }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Kategori Produk</label>
                                <select disabled class="form-control">
                                    <option value="{{ $product->category_id }}" selected>{{ $product->category->name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Stok Minimal (@ {{ $product->unit->name }})</label>
                                <input disabled type="number" class="form-control" value="{{ $product->min_stock }}" placeholder="Stok Minimal">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">List Inventori Product</h3>
                </div>
                <div class="card-body p-0">
                    @isset($product->stocks)
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Stock Awal</th>
                                    <th>Sisa Stock</th>
                                    <th class="text-right">Harga Modal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($stocks as $key => $inventory)
                                    <tr>
                                        <td><button class="btn btn-flat btn-sm btn-dark input-ajax" onclick="maintenancePage.editID({{ $inventory->product_id }}, {{ $inventory->id }})">Edit</button> </td>
                                        <td>{{ $inventory->created_at->toDateString() }}</td>
                                        <td>{{ $inventory->description }}</td>
                                        <td>{{ $inventory->first_stock }} {{ ucfirst($product->unit->name) }}</td>
                                        <td>{{ $inventory->available_stock }} {{ ucfirst($product->unit->name) }}</td>
                                        <td class="text-right">{{ number_format($inventory->buying_price, 2) }}</td>
                                        <td class="text-right">{{ number_format($inventory->first_total , 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mx-3">{{ $stocks->links('vendor.pagination.bootstrap-4') }}</div>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Harga Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga modal saat ini</label>
                                <input value="{{ $product->id }}" type="hidden" id="product_id">
                                <input type="hidden" id="product_stock_id">
                                <input id="old_buying_price" disabled type="text" class="form-control rupiah text-right" placeholder="Harga modal lama">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga modal baru</label>
                                <input id="buying_price" type="text" class="form-control rupiah text-right" placeholder="Harga modal baru">
                                <div class="text-sm text-muted text-red" id="label-buying_price"></div>
                                <div class="text-sm text-muted text-red" id="label-product_stock_id"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button id="btn_update_price" onclick="maintenancePage.updatePrice(); return false;" type="button" class="btn-success btn-sm btn-flat">Ubah Harga Modal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="card-header border-top">
                    <h3 class="card-title">Harga Penjualan</h3>
                </div>
                <div class="card-body p-0">
                    @isset($product->prices)
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Satuan</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-right">Harga Grosir</th>
                                    <th class="text-right">Harga Pelanggan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product->prices as $key => $price)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>@ {{ $price->unit->name }}</td>
                                        <td class="text-right">{{ number_format($price->sell_price, 2) }}</td>
                                        <td class="text-right">{{ number_format($price->wholesale_price, 2) }}</td>
                                        <td class="text-right">{{ number_format($price->customer_price, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card rounded-0 card-gray-dark">
                <div class="card-header border-top">
                    <h3 class="card-title">List harga modal pada transaksi penjualan yang akan di ubah</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th class="text-right">Harga Modal</th>
                                <th style="max-width: 10rem" class="text-right">Total Modal</th>
                                <th class="text-right">Harga Jual</th>
                                <th class="text-right">Total Jual</th>
                            </tr>
                            </thead>
                            <tbody id="table-body">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @push('js')
        <script>
            let maintenancePage = function () {
                let btn_update_price = $('#btn_update_price')
                let content_table = $('#table-body');
                let product_stock_id = $('#product_stock_id')
                let product_id = $('#product_id')
                let old_buying_price = $('#old_buying_price')
                let buying_price = $('#buying_price')
                let label_buying_price = $('#label-buying_price')
                let label_product_stock_id = $('#label-product_stock_id')

                let setupPage = function () {
                    $('.rupiah').number(true,2);
                }

                let resetValidation = function (){
                    label_buying_price.hide();
                    label_product_stock_id.hide();
                    btn_update_price.attr('disabled', false)
                }

                let editPrice = function (product_id, product_stock_id){
                    let params = {
                        'path': 'tableSells',
                        'product_id': product_id,
                        'product_stock_id': product_stock_id,
                    }
                    sendRequest(params)
                    resetValidation()
                }

                function updatePrice() {
                    btn_update_price.attr('disabled', true)
                    let params = {
                        'path': 'updatePrice',
                        'product_stock_id': product_stock_id.val(),
                        'product_id': product_id.val(),
                        'buying_price': buying_price.val(),
                    }
                    $.ajax({
                        data : params,
                        success:function(data){
                            resetValidation()
                            editPrice(product_id.val(), product_stock_id.val())
                        },
                        error: function (err){
                            setMessage(err);
                        }
                    });
                }

                let sendRequest = function (params){
                    $('.input-ajax').attr('readonly', true);
                    $.ajax({
                        data : params,
                        success:function(data){
                            loadTable(data)
                        },
                        error: function (err){
                            setMessage(err);
                        }
                    });
                }

                let loadTable = function (data){
                    if(data.stock){
                        product_stock_id.val(data.stock.id)
                        old_buying_price.val(data.stock.buying_price)
                    }

                    if(data.details.length) {
                        let tableBody = '';
                        let i = 0;
                        let stock_id = data.stock_id;
                        data.details.forEach(function (item, key){
                            let show_list = false;
                            let payloads = item.payloads
                            let data_payload = '';
                            let classes = (item.sell_price < item.buying_price) ? 'bg-warning' : '';
                            let data_sell = '';
                            data_sell = `<div>Rp.${$.number( parseFloat(item.sell_price), 2 )} x ${item.quantity} = Rp. ${$.number(parseFloat(item.total), 2)}</div>`
                            payloads.forEach(function (payload, key){
                                if(payload.quantity > 0){
                                    data_payload += `<div>Rp. ${$.number(parseFloat(payload.buying_price), 2)} x ${payload.quantity} = Rp. ${$.number(parseFloat(payload.total), 2)}</div>`
                                    if(parseInt(payload.product_stock_id) === parseInt(stock_id)){
                                        show_list = true;
                                    }
                                }
                            })

                            if(show_list){
                                tableBody += `<tr class="${classes}">`
                                tableBody += `<td>${i + 1}</td>`
                                tableBody += `<td>${item.invoice_number}</td>`
                                tableBody += `<td class="text-right">${$.number(parseFloat(item.buying_price), 2)}</td>`
                                tableBody += `<td class="text-right">${data_payload}</td>`
                                tableBody += `<td class="text-right">${$.number(parseFloat(item.sell_price), 2)}</td>`
                                tableBody += `<td class="text-right">${data_sell}</td>`
                                tableBody += `</tr>`
                                i++
                            }
                        });

                        content_table.html(tableBody);

                    }
                }


                let setMessage = function (res){
                    let data = res.responseJSON
                    if("errors" in data){
                        let err = data.errors
                        if("buying_price" in err){
                            label_buying_price.text(err.buying_price[0]).show()
                        }
                        if("product_stock_id" in err){
                            label_product_stock_id.text(err.product_stock_id[0]).show()
                        }
                    }

                }

                return {
                    init: function () {
                        setupPage();
                    },
                    editID: function (id, stock_id){
                        editPrice(id, stock_id);
                    },
                    updatePrice: function (){
                        updatePrice();
                    }
                }
            }();
            $(document).ready(function (){
                maintenancePage.init();
            });

        </script>
    @endpush

</x-base-layout>
