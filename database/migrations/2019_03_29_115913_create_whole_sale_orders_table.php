<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWholeSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whole_sale_orders', function (Blueprint $table) {
            $table->increments('id');
      			$table->integer('seller_id')->nullable();
      			$table->integer('user_id')->nullable();
            $table->string('email', 191)->unique();
            $table->string('first_name', 191)->unique();
            $table->string('last_name', 191)->unique();
      			$table->string('status')->nullable()->default('open');
      			$table->decimal('amount', 11, 2)->nullable();
      			$table->string('currency')->nullable();
      			$table->string('reference')->nullable();
      			$table->dateTime('accepted_at')->nullable();
      			$table->dateTime('declined_at')->nullable();
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
        Schema::dropIfExists('whole_sale_orders');
    }
}
