<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_comments', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('branding_id')->nullable()->index('comments_commentable_id_commentable_type_index');
          $table->integer('owner_id')->nullable();
          $table->integer('commenter_id')->nullable()->index('comments_commented_id_commented_type_index');
          $table->text('comment');
          $table->boolean('approved')->default(1);
          $table->float('rate', 15, 8)->nullable();
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
        Schema::dropIfExists('brand_comments');
    }
}
