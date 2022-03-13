<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\TempPurchase::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Product::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\ProductPrice::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('product_name');
            $table->string('quantity'); // Jumlah per id Satuan
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
        Schema::dropIfExists('temp_purchase_details');
    }
}
