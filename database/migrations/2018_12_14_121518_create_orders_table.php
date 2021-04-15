<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
      			$table->integer('listing_id')->nullable();
      			$table->integer('seller_id')->nullable();
      			$table->integer('user_id')->nullable();
            $table->string('email', 191)->unique();
            $table->string('last_name', 191)->unique();
      			$table->string('status')->nullable()->default('open');
      			$table->decimal('amount', 11, 2)->nullable();
      			$table->string('currency')->nullable();
      			$table->string('reference')->nullable();
      			$table->text('listing_options')->nullable();
      			$table->text('choices')->nullable();
      			$table->text('customer_details')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
