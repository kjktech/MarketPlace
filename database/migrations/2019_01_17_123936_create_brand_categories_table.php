<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->unique(['category_id', 'brand_id']);
            $table->timestamps();
        });

        Schema::table('listings', function (Blueprint $table) {
            //
            $table->integer('brand_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_categories');
        Schema::table('listings', function (Blueprint $table) {
            //
            $table->dropColumn('brand_id');
        });
    }
}
