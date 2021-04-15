<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingShippingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_shipping_options', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('listing_id')->nullable();
      			$table->decimal('price', 11, 2)->nullable();
      			$table->string('name')->nullable();
      			$table->integer('position')->nullable()->default(0);
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
        Schema::dropIfExists('listing_shipping_options');
    }
}
