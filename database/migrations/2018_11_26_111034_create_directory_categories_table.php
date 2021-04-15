<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_categories', function (Blueprint $table) {
            $table->increments('id');
      			$table->integer('parent_id')->unsigned()->nullable()->index('categories_parent_id_foreign');
      			$table->integer('order')->nullable()->default(1);
      			$table->string('name', 191);
      			$table->string('slug', 191);
      			$table->string('hash')->nullable();
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
        Schema::dropIfExists('directory_categories');
    }
}
