<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingAdditionalOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_additional_options', function (Blueprint $table) {
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
        Schema::dropIfExists('listing_additional_options');
    }
}
