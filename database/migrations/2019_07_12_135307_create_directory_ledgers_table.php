<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoryLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('directory_id')->unique();
            $table->integer('officer_id')->nullable()->comment('This is the id of an afiaanyi staff.');
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
        Schema::dropIfExists('directory_ledgers');
    }
}
