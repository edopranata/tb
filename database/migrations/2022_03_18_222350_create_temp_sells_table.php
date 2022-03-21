<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_sells', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Customer::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('customer_name');
            $table->dateTime('invoice_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->double('bill')->default(0); //total tagihan
            $table->double('discount')->default(0); // discount
            $table->double('payment')->default(0); // total uang diterima
            $table->string('status')->default('BELUM LUNAS'); // LUNAS / BELUM LUNAS
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('temp_sells');
    }
}
