<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWholeSaleOrderContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whole_sale_order_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('whole_sale_order_id')->nullable();
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
        Schema::dropIfExists('whole_sale_order_contacts');
    }
}
