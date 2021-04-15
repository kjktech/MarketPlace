<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('bank_id')->nullable();
            $table->string('identity')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('bank_account_name')->nullable();
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
        Schema::dropIfExists('store_setups');
    }
}
