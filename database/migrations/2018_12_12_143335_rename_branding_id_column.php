<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBrandingIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brand_comments', function (Blueprint $table) {
            //
            $table->renameColumn('branding_id', 'directory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brand_comments', function (Blueprint $table) {
            //
            $table->renameColumn('directory_id', 'branding_id');
        });
    }
}
