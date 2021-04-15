<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoyaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->text('value')->nullable();
            $table->string('operator', 191)->nullable();
            $table->boolean('status')->default(false)->nullable();
            $table->integer('points')->nullable();
            $table->dateTime('duration')->nullable();
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
        Schema::dropIfExists('loyalties');
    }
}
