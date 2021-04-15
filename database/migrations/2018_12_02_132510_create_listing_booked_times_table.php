<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingBookedTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_booked_times', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('listing_id')->nullable();
            $table->dateTime('booked_date')->nullable();
            $table->time('start_time')->nullable();
              $table->integer('duration')->nullable()->default(0);
            $table->integer('quantity')->nullable()->default(0);
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
        Schema::dropIfExists('listing_booked_times');
    }
}
