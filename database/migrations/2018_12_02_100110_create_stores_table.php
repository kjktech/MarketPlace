<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id')->nullable();
          $table->integer('store_category_id')->nullable();
          $table->string('name')->nullable();
          $table->string('slug', 191);
          $table->string('blurb', 191)->nullable();
          $table->string('photo')->nullable();
          $table->text('description')->nullable();
          $table->string('phone')->nullable();
          $table->string('email', 191)->nullable();
          $table->boolean('staff_pick')->nullable();
          $table->integer('views_count')->nullable();
          $table->string('city')->nullable();
          $table->string('country')->nullable();
          $table->dateTime('is_admin_verified')->nullable();
          $table->dateTime('is_disabled')->nullable();
          $table->text('tags')->nullable();
          $table->text('tags_string', 65535)->nullable();
          $table->text('photos', 65535)->nullable();
          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
