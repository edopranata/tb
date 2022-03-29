<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Sell::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->dateTime('due_date')->nullable();
            $table->double('bill');
            $table->double('payment');
            $table->double('bond');
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
        Schema::dropIfExists('sell_histories');
    }
}
