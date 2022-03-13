<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Supplier::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('supplier_name')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->string('invoice_number')->nullable();
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
        Schema::dropIfExists('temp_purchases');
    }
}
