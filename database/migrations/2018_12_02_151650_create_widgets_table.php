<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title')->nullable();
      			$table->string('alignment')->nullable();
      			$table->string('type')->nullable();
      			$table->string('locale')->nullable();
      			$table->text('metadata')->nullable();
      			$table->string('background')->nullable();
      			$table->integer('position')->nullable();
      			$table->text('style', 65535)->nullable();
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
        Schema::dropIfExists('widgets');
    }
}
