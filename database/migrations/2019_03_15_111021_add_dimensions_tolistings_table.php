<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDimensionsTolistingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('listings', function (Blueprint $table) {
            //
            $table->decimal('weight', 11, 4)->nullable();
            $table->decimal('length', 11, 4)->nullable();
            $table->decimal('width', 11, 4)->nullable();
            $table->decimal('height', 11, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('listings', function (Blueprint $table) {
            //
            $table->dropColumn('weight');
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
