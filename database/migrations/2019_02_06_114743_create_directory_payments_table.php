<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoryPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('directory_payments', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('directory_id')->nullable();
             $table->integer('user_id')->nullable();
             $table->text('paystack_reference')->nullable();
             $table->string('status')->nullable()->default('open');
             $table->decimal('amount', 11, 2)->nullable();
             $table->boolean('verified')->nullable()->default(false);
             $table->integer('payment_type');
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
         Schema::dropIfExists('directory_payments');
     }
}
