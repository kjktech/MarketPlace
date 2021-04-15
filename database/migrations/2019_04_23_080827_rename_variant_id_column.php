<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameVariantIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('category_variant_category', function(Blueprint $table) {
            $table->renameColumn('variant_id', 'variant_category_id');
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
        Schema::table('category_variant_category', function(Blueprint $table) {
            $table->renameColumn('variant_category_id', 'variant_id');
        });
    }
}
