<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempSellDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_sell_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\TempSell::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Product::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\ProductPrice::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('product_name');
            $table->string('quantity'); // Jumlah per id Product Price
            $table->string('product_price_quantity'); // Total dari ke satuan terkecil
            $table->double('sell_price');
            $table->double('sell_price_quantity');
            $table->string('price_category')->default('ECERAN');
            $table->double('discount')->default(0);
            $table->double('total');
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
        Schema::dropIfExists('temp_sell_details');
    }
}
