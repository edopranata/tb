<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashes', function (Blueprint $table) {
            $table->id();
            $table->string('tx_date')->unique();
            $table->unsignedBigInteger('to_user')->nullable();
            $table->unsignedBigInteger('from_user')->nullable();
            $table->foreign('to_user')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('from_user')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('to_name')->nullable();
            $table->string('from_name')->nullable();
            $table->double('cash')->default(0);
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
        Schema::dropIfExists('cashes');
    }
}
