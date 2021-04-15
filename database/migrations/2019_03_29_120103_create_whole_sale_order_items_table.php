<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWholeSaleOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whole_sale_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('whole_sale_order_id')->nullable();
            $table->integer('listing_id')->nullable();
      			$table->integer('seller_id')->nullable();
      			$table->decimal('price', 11, 2)->nullable();
            $table->integer('quantity')->nullable();
      			$table->string('currency')->nullable();
      			$table->text('choices')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('whole_sale_order_items');
    }
}
