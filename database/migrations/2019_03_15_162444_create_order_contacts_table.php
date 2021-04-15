<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 191)->nullable();
            $table->string('first_name', 191);
            $table->string('last_name', 191);
            $table->string('phone', 191)->nullable();
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
        Schema::dropIfExists('order_contacts');
    }
}
