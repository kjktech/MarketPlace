<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingBookedDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_booked_dates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('listing_id')->nullable();
      			$table->date('booked_date')->nullable();
      			$table->integer('quantity')->nullable()->default(0);
      			$table->integer('available_units')->nullable();
      			$table->boolean('is_available')->nullable();
      			$table->decimal('price', 11, 2)->nullable();
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
        Schema::dropIfExists('listing_booked_dates');
    }
}
