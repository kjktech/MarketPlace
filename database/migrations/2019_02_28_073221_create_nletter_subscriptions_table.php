<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNletterSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nletter_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191)->nullable();
            $table->string('email')->unique();
            $table->boolean('subscribed')->default(0)->nullable();
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
        Schema::dropIfExists('nletter_subscriptions');
    }
}
