<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Purchase::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Product::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\ProductPrice::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('product_name');
            $table->string('quantity'); // Jumlah per id Product Price
            $table->string('product_price_quantity'); // Pengali ke satuan terkecil
            $table->string('buying_price'); // Harga beli Per ID Satuan
            $table->string('total'); //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
