<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unique();
            $table->integer('owner_id')->nullable();
            $table->integer('officer_id')->nullable()->comment('This is the id of an afiaanyi staff.');
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
        Schema::dropIfExists('store_ledgers');
    }
}
