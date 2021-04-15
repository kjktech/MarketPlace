<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWholesaleToListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            //
            $table->decimal('whole_sale_price', 11,2)->nullable();
            $table->integer('whole_sale_min_quantity')->nullable();
            $table->boolean('is_whole_sale')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            //
            $table->dropColumn('whole_sale_price');
            $table->dropColumn('whole_sale_min_quantity');
            $table->dropColumn('is_whole_sale');
        });
    }
}
